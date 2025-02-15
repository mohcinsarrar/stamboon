<?php

namespace App\Http\Controllers\superuser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File as StorageFile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Str;
use ReflectionClass;
use Carbon\Carbon;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\HeadingRowImport;
use App\Imports\TreeImport;
use App\Exports\TreeExport;

use App\Models\User;
use App\Models\Fantree;
use App\Models\SettingFantree;
use App\Models\Note;
use App\Models\Product;

use Illuminate\Support\Facades\Http;
use Goutte\Client;

use App\Rules\GedcomFantreeFile;
use App\Rules\HexColor;
use Gedcom\Parser as GedcomParser;
use Gedcom\Writer as GedcomWriter;
use Gedcom\Record\Indi as Indi;
use Gedcom\Record\Indi\Name as IndiName;
use Gedcom\Record\Indi\Birt as IndiBirt;
use Gedcom\Record\Indi\Deat as IndiDeat;
use Gedcom\Record\Indi\Fams as IndiFams;
use Gedcom\Record\Indi\Famc as IndiFamc;
use Gedcom\Record\Indi\Adop as IndiAdop;
use Gedcom\Record\NoteRef as IndiNoteRef;
use Gedcom\Record\Fam as Fam;


use App\Services\GedcomService;
use ZipArchive;


use App\DataTables\FantreesDataTable;

class FantreeController extends Controller
{

    public function list(FantreesDataTable $dataTable){


        return $dataTable->render('superuser.fantree.list');
        
    }


    private array $months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];

     

    private function dateStr($date){
        $records = explode('-', $date);
        if(sizeof($records) == 1){
            $year = $records[0];
            return "$year";
        }
        $day = $records[1];
        $month = $records[1];
        $year = $records[0];
        $monthName = $this->months[$month-1];

        return "$day $monthName $year";
    }


    public function index(Request $request, $fantree_id){


        // get product features
        $has_payment = true;

        $print_types = ['png','pdf'];


        $selected_output_png = [
            '1' => '1344 x 839 px',
            '2' => '2688 x 1678 px',
            '3' => '4032 x 2517 px',
            '4' => '5376 x 3356 px',
            '5' => '6720 x 4195 px',
        ];

        $selected_output_pdf = [
            'a0' => 'A0',
            'a1' => 'A1',
            'a2' => 'A2',
            'a3' => 'A3',
            'a4' => 'A4',
        ];


        $fantree = Fantree::where('id',$fantree_id)->first();
        
        $user = User::where('id',$fantree->user_id)->first();
        $user_name = $user->firstname. ' ' .$user->lastname;

        return view('superuser.fantree.index', compact('fantree_id','user_name','print_types','selected_output_png','selected_output_pdf','has_payment'));
    }

    // import a new gedcom file
    public function importgedcom(Request $request,$fantree_id){

        $input = $request->all();

        Validator::validate($input, [
                'file' => [
                    'required',
                    'file',
                    new GedcomFantreeFile
                ],
            ]);
        
        $fantree = Fantree::where('id',$fantree_id)->first();

        // delete file if exist
        if($fantree->gedcom_file != null){
            if (Storage::exists($fantree->gedcom_file)) {
                Storage::delete($fantree->gedcom_file);
              }
        }

        $file = $request->file('file');
        $destinationPath = 'fantree_gedcoms';
        $uniqueFilename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $gedcom_file = $file->storeAs($destinationPath, $uniqueFilename);

        
        $fantree->gedcom_file = $gedcom_file;
        $fantree->save();

        return response()->json(['error'=>false,'msg' => 'gedcom file imported']);
        
    }

    public function getTree(Request $request,$fantree_id){

        $fantree = Fantree::where('id',$fantree_id)->first();
        
        if($fantree->gedcom_file == null){
            return response(null, 200);
        }

        $this->filterFileLines($fantree->gedcom_file);

        $file = Storage::disk('local')->get($fantree->gedcom_file);

        // Return the file as a response
        return response($file, 200)
                  ->header('Content-Type', 'application/octet-stream')
                  ->header('Content-Disposition', 'attachment; filename="' . basename($fantree->gedcom_file) . '"');
    }

    private function filterFileLines($filePath)
    {
        // Check if the file exists
        if (!Storage::exists($filePath)) {
            return false;
        }
    
        // Open the file for reading
        $stream = Storage::readStream($filePath);

        if (!$stream) {
            return false;
        }

        $filteredLines = [];

        // Read the file line by line
        while (($line = fgets($stream)) !== false) {
            if (preg_match('/^\d/', $line)) {
                $filteredLines[] = trim($line);
            }
        }

        // Close the stream
        fclose($stream);

        // Write the filtered lines back to the file
        Storage::put($filePath, implode(PHP_EOL, $filteredLines));
        
        return true;
    }


    public function settings(Request $request,$fantree_id){
        
        
        // load settings
        if($request->isMethod('get')){

            $fantree = Fantree::where('id',$fantree_id)->first();
            if($fantree == null){
                return response()->json(['error'=>true,'msg' => 'error']);
            }

            $user = User::where('id',$fantree->user_id)->first();

            // if no settings created it
            if(SettingFantree::where('user_id',$user->id)->first() == null){
                SettingFantree::create(['user_id' => $user->id]);
            }
            
            $settings = SettingFantree::where('user_id',$user->id)->first()->toArray();
            
            $settings['max_nodes'] = 127;
            $settings['max_generation'] = 7;

            return response()->json(['error'=>false,'settings' => $settings]);
        }

        // update settings
        if($request->isMethod('post')){

            $inputs = $request->except(['_token']);

            Validator::make($inputs, [
                'male_color' => ['required',new HexColor],
                'female_color' => ['required',new HexColor],

                'text_color' => ['required',new HexColor],
                'band_color' => ['required',new HexColor],

                'father_link_color' => ['required',new HexColor],
                'mother_link_color' => ['required',new HexColor],

                'default_filter' => ['required',Rule::in(['none', 'grayscale', 'invert', 'sepia'])],
                
                'bg_template' => ['nullable',Rule::in(['0','1', '2', '3', '4'])],

                'note_type' => ['required',Rule::in(['1', '2', '3', '4'])],

                'note_text_color' => ['required',new HexColor],

                'default_date' => ['required',Rule::in(['MM-DD-YYYY', 'YYYY-MM-DD', 'DD-MM-YYYY'])]
            ])->validate();

            

            if($request->bg_template == null){
                $inputs['bg_template'] = '0';
            }

            $fantree = Fantree::where('id',$fantree_id)->first();
            if($fantree == null){
                return response()->json(['error'=>true,'msg' => 'error']);
            }

            $user = User::where('id',$fantree->user_id)->first();
            
            SettingFantree::where('user_id',$user->id)->update($inputs);

            return response()->json(['error'=>false,'message' => "settings edited with success"]);
        }
    }


    private function get_gedcom_file($fantree_id){

        $fantree = Fantree::where('id',$fantree_id)->first();
        if($fantree == null){
            return null;
        }

        if (!Storage::disk('local')->exists($fantree->gedcom_file)) {
            return null;
        } 
        
        $file = Storage::disk('local')->get($fantree->gedcom_file);

        return $fantree->gedcom_file;

    }

    private function get_gedcom($gedcom_file){

        $gedcom = $this->parse_gedcom('storage/'.$gedcom_file);

        return $gedcom;

    }

    private function get_person($gedcom, $person_id){
        $person = null;
        foreach ($gedcom->getIndi() as $individual) {
            $id = $individual->getId();
            if ($id == $person_id) {
                $person = $individual;
            }
        }

        return $person;
    }

    private function convertToDateFormat($dateString) {

        if($dateString == null){
            return null;
        }
        // Define the accepted formats
        $formats = [
            'Y-m-d', // YYYY-MM-DD
            'm-d-Y', // MM-DD-YYYY
            'd-m-Y'  // DD-MM-YYYY
        ];
    
        // Special case: Handle year-only input
        if (preg_match('/^\d{4}$/', $dateString)) {
            return $dateString; // Return the year as-is
        }
    
        foreach ($formats as $format) {
            $date = Carbon::createFromFormat($format, $dateString, null);
    
            // Check if the date is valid for the current format
            if ($date && $date->format($format) === $dateString) {
                // Convert to the desired format (DD-MM-YYYY)
                return $date->format('Y-m-d');
            }
        }
    
        // If no valid date format is matched, return null or throw an exception
        return null;
    }

    private function parse_gedcom($file){


        $parser = new GedcomParser();
        $gedcom = $parser->parse($file);

        return $gedcom;

    }

    private function extractNumber($item) {
        return intval(substr($item, 1));
    }

    private function create_indi($gedcom, $firstname, $lastname, $sex, $status, $birth_date, $death_date){
        // create person
        $person = new Indi();

        // generate id for new indi,
        $indis = array_keys($gedcom->getIndi());
        if($indis != []){
            $maxIndiId = max(array_map([$this, 'extractNumber'], $indis));
            $newIndiId = 'I' . ($maxIndiId + 1);
        }
        else{
            $newIndiId = 'I1';
        }
        
        // add id
        $person->setId($newIndiId);

        // add sex
        $person->setSex($sex);

        // add name
        $name = new IndiName();
        $name->setName($firstname."/".$lastname."/");
        $person->addName($name);

        // add Birt even
        $birt = new IndiBirt();
        $birt->setType('BIRT');
        $birt->setDate($birth_date);
        $person->addEven($birt);

        // add death even
        if($status == 'deceased') {
            $death = new IndiDeat();
            $death->setType('DEAT');
            $death->setDate($death_date);
            $person->addEven($death);
        }

        return $person;
    }

    // update person
    public function update(Request $request,$fantree_id){
        
        $inputs = $request->except(['_token']);
        $gedcomService = new GedcomService();

        Validator::make($inputs, [
            'person_id' => ['required','string'],
            'firstname' => ['required','string'],
            'lastname' => ['required','string'],
            'status' => ['required','string'],
        ])->validate();
        
        // get gedcom file
        $gedcom_file = $this->get_gedcom_file($fantree_id);
        if ($gedcom_file == null) {
            return redirect()->back()->with('error','cant load your family tree');
        } 

        // get gedcom object
        $gedcom = $this->get_gedcom($gedcom_file);


        // get person
        $person_id = str_replace('@','',$request->person_id);
        $person = $this->get_person($gedcom,$person_id);
        if($person == null){
            return redirect()->back()->with('error','cant find person');
        }

        // edit birt event
        $birth_date = $this->convertToDateFormat($request->birth_date);
        $gedcomService->edit_birth($person,$birth_date);

        // edit death event
        $death_date = $this->convertToDateFormat($request->death_date);
        $gedcomService->edit_death($person, $request->status,$death_date);
        
        

        // edit names
        $name = $request->firstname."/".$request->lastname."/";
        $gedcomService->edit_name($person,$name);

        // if has sex edit it
        if($request->sex != null){
            $person->setSex($request->sex);
        }


        // write modification to gedcom file
        $gedcomService->writer($gedcom,$gedcom_file);

        return response()->json(['error'=>false,'msg' => 'Person updated with success']);

        //return redirect()->back()->with('success','person updated with success');
    }


    public function addparents(Request $request,$fantree_id){
        
        $inputs = $request->except(['_token']);
        $gedcomService = new GedcomService();

        Validator::make($inputs, [
            'person_id' => ['required','string'],
            'parent_type' => ['required','in:1,2'],
            'firstname' => ['required','string'],
            'lastname' => ['required','string'],
            'status' => ['required','string'],
        ])->validate();
        // get gedcom file
        $gedcom_file = $this->get_gedcom_file($fantree_id);
        if ($gedcom_file == null) {
            return redirect()->back()->with('error','cant load your family tree');
        } 

        // get gedcom object
        $gedcom = $this->get_gedcom($gedcom_file);

        // get person
        $person_id = str_replace('@','',$request->person_id);
        $child = $this->get_person($gedcom,$person_id);
        if($child == null){
            return redirect()->back()->with('error','cant find person');
        }

        if($request->parent_type == 1){
            $sex = "M";
        }
        else{
            $sex = 'F';
        }


        // create parent
        $parent = $this->create_indi($gedcom, $request->firstname, $request->lastname, $sex, $request->status, $request->birth_date, $request->death_date);
        
        // add parent to gedcom
        $gedcom->addIndi($parent);

        // check if the child has already a family where he is a child, else create it
        $family = $this->get_family_for_parent($gedcom, $child, $parent, $sex);

        $gedcomService->writer($gedcom,$gedcom_file);
        
        return redirect()->back()->with('success','parent added with success');
    }

    private function get_family_for_parent($gedcom, $child, $parent, $sex){

        $family = $child->getFamc();
        
        // the child not have a family where is it child, create it
        if($family == null or $family == []){
            
            // create new family and add the child to it (it the person)
            $new_family = new Fam();
            $newFamId = $this->new_family_id($gedcom);
            $new_family->setId($newFamId);
            
            // add child
            $children = $new_family->getChil();
            array_push($children,$child->getId());
            $new_family->setChil($children);

            // add parent
            if($sex == 'M'){
                // father
                $new_family->setHusb($parent->getId());
            }
            else{
                // mother
                $new_family->setWife($parent->getId());
            }

            

            $gedcom->addFam($new_family);

            

            // add new_family as famc to child
            /// create FAMC for child
            $famc = new IndiFamc();
            $famc->setFamc($newFamId);
            /// add FAMC to child
            $child->addFamc($famc);

            
            // add new_family as fams to parent
            $fams = new IndiFams();
            $fams->setFams($newFamId);
            $parent->addFams($fams);

            

        }
        // child has family, add parent
        else{
            $exist_family_id = $family[0]->getFamc();
            $families = $gedcom->getFam();
            $exist_family = $families[$exist_family_id];

            // add parent
            if($sex == 'M'){
                // father
                $exist_family->setHusb($parent->getId());
            }
            else{
                // mother
                $exist_family->setWife($parent->getId());
            }

            // add exist_family as fams to parent
            $fams = new IndiFams();
            $fams->setFams($exist_family->getId());
            $parent->addFams($fams);

        }
    }

    private function new_family_id($gedcom){
        // generate new Family id
        $families = array_keys($gedcom->getFam());
        if($families != []){
            $maxFamId = max(array_map([$this, 'extractNumber'], $families));
            $newFamId = 'F' . ($maxFamId + 1);
        }
        else{
            $newFamId = 'F1';
        }

        return $newFamId;
    }


    public function delete(Request $request,$fantree_id){

        
        $inputs = $request->except(['_token']);
        $gedcomService = new GedcomService();

        Validator::make($inputs, [
            'person_id' => ['required','string'],
        ])->validate();

        // get gedcom file
        $gedcom_file = $this->get_gedcom_file($fantree_id);
        if ($gedcom_file == null) {
            return redirect()->back()->with('error','cant load your family tree');
        } 

        // get gedcom object
        $gedcom = $this->get_gedcom($gedcom_file);


        // get person
        $person_id = str_replace('@','',$request->person_id);
        $person = $this->get_person($gedcom,$person_id);
        if($person == null){
            return redirect()->back()->with('error','cant find person');
        }

        // get family where the person is a parent
        $person_family_id = $person->getFams()[0]->getFams();
        
        
        /// get person family
        $families = $gedcom->getFam(); 
        $person_family = $families[$person_family_id];
        /// get husb and wife
        $husb = $person_family->getHusb();
        $wife = $person_family->getWife();
        /// delete person if its a husb or wife
        if($wife != null && $wife == $person_id){
            $this->change_protected_field($person_family,'_wife',null);
        }
        if($husb != null && $husb == $person_id){
            $this->change_protected_field($person_family,'_husb',null);
        }

        
        // if family has no person delete it and delete famc from child
        /// fet husb and wife
        $husb = $person_family->getHusb();
        $wife = $person_family->getWife();

        
        if($wife == null && $husb == null){
            // get famc of child and delte it
            $child = $this->get_person($gedcom,$person_family->getChil()[0]);
            $this->change_protected_field($child,'famc',[]);
            
            // delete person family
            $families = $gedcom->getFam(); // get all families
            unset($families[$person_family_id]);
            $this->change_protected_field($gedcom,'fam',$families);
        }

        // delete person
        $indis = $gedcom->getIndi();
        unset($indis[$person_id]);
        $this->change_protected_field($gedcom,'indi',$indis);

        // write modification to gedcom file
        $gedcomService->writer($gedcom,$gedcom_file);

        return redirect()->back()->with('success','person deleted with success');
    }

    private function change_protected_field(&$object,$field,$value){
        $reflection = new ReflectionClass($object);
        $property = $reflection->getProperty($field);
        $property->setAccessible(true);
        $property->setValue($object, $value);
    }


    public function deleteimage(Request $request,$fantree_id){
        $gedcomService = new GedcomService();

        // get gedcom file
        $gedcom_file = $this->get_gedcom_file($fantree_id);
        if ($gedcom_file == null) {
            return redirect()->back()->with('error','cant load your family tree');
        } 

        // get gedcom object
        $gedcom = $this->get_gedcom($gedcom_file);

        
        // get person
        $person_id = str_replace('@','',$request->person_id);
        $person = $this->get_person($gedcom,$person_id);
        if($person == null){
            return redirect()->back()->with('error','cant find person');
        }
        
        // get all notes
        $notes = $person->getNote();

        // iterate notes and delete all photos
        if($notes != null && $notes != []){
            foreach($notes as $key => $note){
                if(str_contains($note->getNote(), '.png')){
                    // delete old image if exist
                    if (Storage::exists("portraits_fantree/".$note->getNote())) {
                        Storage::delete("portraits_fantree/".$note->getNote());
                      }

                }
            }
        }

        // empty the notes array of indi (person)
        $this->change_protected_field($person,'note',[]);

        // write modification to gedcom file
        $gedcomService->writer($gedcom,$gedcom_file);
        

        return response()->json(['error'=>false,'msg' => 'Photo deleted with success']);

    }


    public function saveimage(Request $request,$fantree_id){


        $gedcomService = new GedcomService();

        // get gedcom file
        $gedcom_file = $this->get_gedcom_file($fantree_id);
        if ($gedcom_file == null) {
            return redirect()->back()->with('error','cant load your family tree');
        } 

        // get gedcom object
        $gedcom = $this->get_gedcom($gedcom_file);

        
        // get person
        $person_id = str_replace('@','',$request->person_id);
        $person = $this->get_person($gedcom,$person_id);
        if($person == null){
            return redirect()->back()->with('error','cant find person');
        }


        // if is placeholder image
        if($request->checkedImage != null){

            $image = $request->checkedImage.'.jpg';
            $sourcePath = 'placeholder_portraits_fantree/'.$image;

            $imageName = Str::random(40).'.'.'png';
            $destinationPath = "portraits_fantree/".$imageName;

            Storage::copy($sourcePath, $destinationPath);
        }
        else{
            // save image
            $image = $request->imageBase64;
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = Str::random(40).'.'.'png';
            storage::put( "portraits_fantree/".$imageName, base64_decode($image));
        }

        

        // test if person has note
        $hasPhoto = false;
        $notes = $person->getNote();
        if($notes != null && $notes != []){
            foreach($notes as $key => $note){
                if(str_contains($note->getNote(), '.png')){
                    // delete old image if exist
                    if (Storage::exists("portraits_fantree/".$note->getNote())) {
                        Storage::delete("portraits_fantree/".$note->getNote());
                      }

                    $hasPhoto = true;
                    $note->setNote($imageName);
                }
            }
        }

        // if dont has photo create a note and add imagename and added it to person
        if($hasPhoto == false){
            $note = new IndiNoteRef();
            $note->setNote($imageName);
            $person->addNote($note);
        }
        
        $gedcomService->writer($gedcom,$gedcom_file);

        return response()->json(['error'=>false,'msg' => 'Image Added']);
        
        
    }


    public function addperson(Request $request,$fantree_id){

        $inputs = $request->except(['_token']);
        
        Validator::make($inputs, [
            'firstname' => ['required','string'],
            'lastname' => ['required','string'],
            'sex' => ['required','string'],
            'status' => ['required','string'],
        ])->validate();


        // Create a temporary file path
        $tempFilePath = storage_path('app/temp_file.txt');

        // Create an empty file
        StorageFile::put($tempFilePath, '');


        $todayDate = strtoupper(Carbon::now()->format('d M Y'));
        $todayTime = Carbon::now()->format('H:i:s');

        // Define the lines you want to add
        $lines = [
            "0 HEAD",
            "1 SOUR Stamboom",
            "2 VERS 10.1",
            "2 NAME Aldfaer",
            "2 CORP The Stamboom",
            "3 ADDR https://www.thestamboom.nl",
            "1 DATE ".$todayDate,
            "2 TIME ".$todayTime,
            "1 SUBM @SUBM1@",
            "1 GEDC",
            "2 VERS 5.5",
            "2 FORM Lineage-Linked",
            "1 CHAR UTF-8",
            "0 @I1@ INDI",
            "1 NAME ".$request->firstname."/".$request->lastname."/",
            "1 SEX ".$request->sex,
            "1 BIRT",
        ];


            if($request->birth_date != null){
                array_push($lines, "2 DATE ".$this->dateStr($request->birth_date));
            }
            if($request->death_date != null){
                array_push($lines, "1 DEAT");
                array_push($lines, "2 DATE ".$this->dateStr($request->death_date));
            }

            $lines = array_merge(
                $lines, 
                [
                    "1 _NEW",
                    "2 TYPE 1",
                    "2 DATE ".$todayDate,
                    "3 TIME ".$todayTime,
                    "1 CHAN",
                    "2 DATE ".$todayDate,
                    "3 TIME ".$todayTime,
                    "0 @SUBM1@ SUBM",
                    "1 NAME Unknown",
                    "0 TRLR",
                ]
                );
        

        // Add lines to the file
        foreach ($lines as $line) {
            StorageFile::append($tempFilePath, $line . PHP_EOL);
        }

        // Store the file in Laravel's storage
        $uniqueFilename = 'fantree_gedcoms/'.Str::uuid() . '.ged';
        Storage::put($uniqueFilename, StorageFile::get($tempFilePath));

        // store filename to pedigree
        $fantree = Fantree::where('id',$fantree_id)->first();
        $fantree->gedcom_file = $uniqueFilename;
        $fantree->save();

        // Optionally delete the temporary file
        StorageFile::delete($tempFilePath);

        return redirect()->back()->with('success','person added with success');
        
    }


    public function print(Request $request, $fantree_id){

        $fantree = Fantree::where('id',$fantree_id)->first();
        $user = User::where('id',$fantree->user_id)->first();

        return response()->json(['error'=>false,'msg' => 'limit unreached']);
        
    }

    public function download(Request $request,$fantree_id){
        

        // get gedcom file
        $gedcom_file = $this->get_gedcom_file($fantree_id);
        if($gedcom_file == null){
            return back()-with('error',"can't download your familytree, please try again");
        }

        // create tmp folder
        $tempDir = 'tmp_folder';
        if (!Storage::exists($tempDir)) {
            Storage::makeDirectory($tempDir, 0755, true);
        }

        
        // add gedcom file to tmp folder
        Storage::copy($gedcom_file, $tempDir . '/familytree.ged');

        // add photos to tmp folder
        $gedcom = $this->get_gedcom($gedcom_file);
        $indis = $gedcom->getIndi();
        foreach($indis as $indi){
            $note = $indi->getNote();
            $names = $indi->getName();
            $name = null;
            if($names != null and $names != []){
                $name = trim(str_replace("/"," ",$names[0]->getName()));
                $name = Str::slug($name, $separator = '_');
            }
            $photo = null;
            if($note != null and $note != []){
                $photo = $note[0]->getNote();
            }

            if($name != null and $photo != null){
                Storage::copy('portraits_fantree/'.$photo, $tempDir . '/' . $name . '.png');
            }
        }
        

        $zip = new ZipArchive();
        $zipFileName = 'pedigree.zip';
        $zipFilePath = Storage::path($zipFileName); // Save in default storage disk

        $zip = new ZipArchive();
        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $files = Storage::allFiles($tempDir);

            foreach ($files as $file) {
                $localPath = storage_path('app/' . $file); // Get the full path of the file
                $relativePath = substr($file, strlen($tempDir) + 1); // Relative path inside the zip
                $zip->addFile($localPath, $relativePath);
            }

            $zip->close();
        } else {
            return redirect()->back()->with('error', 'Could not download your familytree, please try again');
        }

        // Clean up the temp folder
        Storage::deleteDirectory($tempDir);
        
        return response()->download($zipFilePath)->deleteFileAfterSend(true);

        

    }


    public function editChartStatus(Request $request, $fantree_id){
        $chart_status = $request->input('chart_status');

        $fantree = Fantree::where('id',$fantree_id)->first();
        if($fantree == null){
            return response()->json(['error'=>true,'msg' => 'error']);
        }
        
        $fantree->chart_status = $chart_status;
        $fantree->save();
        return response()->json(['error'=>false,'msg' => 'error']);
    }

    public function getChartStatus(Request $request, $fantree_id){
        $fantree = Fantree::where('id',$fantree_id)->first();
        if($fantree == null){
            return response()->json(['error'=>true,'msg' => 'error']);
        }
        $chart_status = $fantree->chart_status;
        return response()->json(['error'=>false,'chart_status' => $chart_status]);
    }

    public function updatecount(Request $request, $fantree_id){
        $fantree = Fantree::where('id',$fantree_id)->first();
        if($fantree == null){
            return response()->json(['error'=>true,'msg' => 'error']);
        }

        $stats = $request->input('stats');
        $fantree->stats = $stats;

        $fantree->save();
        return response()->json(['error'=>false,'msg' => 'error']);


    }
}
