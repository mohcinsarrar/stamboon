<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\NodeResource;
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
use App\Models\Tree;
use App\Models\Node;
use App\Models\Pedigree;
use App\Models\Setting;
use App\Models\Note;
use App\Models\Product;

use Illuminate\Support\Facades\Http;
use Goutte\Client;

use App\Rules\GedcomFile;
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



class PedigreeController extends Controller
{


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

    public function addperson(Request $request){


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
        $uniqueFilename = 'gedcoms/'.Str::uuid() . '.ged';
        Storage::put($uniqueFilename, StorageFile::get($tempFilePath));

        // store filename to pedigree
        $pedigree = Pedigree::where('user_id',Auth::user()->id)->first();
        $pedigree->gedcom_file = $uniqueFilename;
        $pedigree->save();

        // Optionally delete the temporary file
        StorageFile::delete($tempFilePath);

        return redirect()->back()->with('success','person added with success');
        
    }

    public function getChartStatus(Request $request){
        $pedigree = Pedigree::where('user_id',Auth::user()->id)->first();
        if($pedigree == null){
            return response()->json(['error'=>true,'msg' => 'error']);
        }
        $chart_status = $pedigree->chart_status;
        return response()->json(['error'=>false,'chart_status' => $chart_status]);
    }

    public function print(Request $request){
        $user = Auth::user();
        $pedigree = Pedigree::where('user_id',$user->id)->first();
        $current_payment = $user->has_payment();
        if($current_payment == false){
            return redirect()->route('users.dashboard');
        }
        $product = Product::where('id',$current_payment->product_id)->first();
        
        // limit reached
        if($pedigree->print_number >= $product->print_number){
            return response()->json(['error'=>true,'msg' => 'limit reached']);
        }
        else{
            $pedigree->print_number = $pedigree->print_number + 1;
            $pedigree->save();
            return response()->json(['error'=>false,'msg' => 'limit unreached']);
        }
        
    }

    public function editChartStatus(Request $request){
        $chart_status = $request->input('chart_status');

        $pedigree = Pedigree::where('user_id',Auth::user()->id)->first();
        if($pedigree == null){
            return response()->json(['error'=>true,'msg' => 'error']);
        }
        
        $pedigree->chart_status = $chart_status;
        $pedigree->save();
        return response()->json(['error'=>false,'msg' => 'error']);
    }

    public function saveimage(Request $request){


        $gedcomService = new GedcomService();

        // get gedcom file
        $gedcom_file = $this->get_gedcom_file();
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
            $sourcePath = 'placeholder_portraits/'.$image;

            $imageName = Str::random(40).'.'.'png';
            $destinationPath = "portraits/".$imageName;

            Storage::copy($sourcePath, $destinationPath);
        }
        else{
            // save image
            $image = $request->imageBase64;
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = Str::random(40).'.'.'png';
            storage::put( "portraits/".$imageName, base64_decode($image));
        }

        

        // test if person has note
        $hasPhoto = false;
        $notes = $person->getNote();
        if($notes != null && $notes != []){
            foreach($notes as $key => $note){
                if(str_contains($note->getNote(), '.png')){
                    // delete old image if exist
                    if (Storage::exists("portraits/".$note->getNote())) {
                        Storage::delete("portraits/".$note->getNote());
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

    public function settings(Request $request){
        
        
        // load settings
        if($request->isMethod('get')){
            $user = Auth::user();
            $settings = Setting::where('user_id',$user->id)->first()->toArray();
            $pedigree = Pedigree::where('user_id',$user->id)->first();
            if($pedigree == null){
                return response()->json(['error'=>true,'msg' => 'error']);
            }
            // get product features
            $current_payment = $user->has_payment();
            if($current_payment == false){
                return redirect()->route('users.dashboard');
            }
            $product = Product::where('id',$current_payment->product_id)->first();
            $settings['max_nodes'] = $product->max_nodes;
            $settings['max_generation'] = $product->pedigree_max_generation;

            return response()->json(['error'=>false,'settings' => $settings]);
        }

        // update settings
        if($request->isMethod('post')){

            $inputs = $request->except(['_token']);
            
            Validator::make($inputs, [
                'box_color' => ['required',Rule::in(['gender', 'blood'])],
                'male_color' => ['required',new HexColor],
                'female_color' => ['required',new HexColor],
                'blood_color' => ['required',new HexColor],
                'notblood_color' => ['required',new HexColor],

                'text_color' => ['required',new HexColor],
                'band_color' => ['required',new HexColor],


                'spouse_link_color' => ['required',new HexColor],
                'bio_child_link_color' => ['required',new HexColor],
                'adop_child_link_color' => ['required',new HexColor],
                
                'node_template' => ['required',Rule::in(['1', '2', '3', '4'])],
                'bg_template' => ['required',Rule::in(['1', '2', '3', '4'])]
            ])->validate();

            
            Setting::where('user_id',Auth::user()->id)->update($inputs);

            return redirect()->back()->with('success','settings updated with success');
        }
    }


    public function index(Request $request){

        $user = Auth::user();
        $pedigree = Pedigree::where('user_id',$user->id)->first();
        $gedcom_file = $pedigree->gedcom_file;

        // get product features
        $current_payment = $user->has_payment();
        if($current_payment == false){
            return redirect()->route('users.dashboard');
        }
        $product = Product::where('id',$current_payment->product_id)->first();

        

        $pedigree_output_png = $product->pedigree_output_png;
        $pedigree_output_pdf = $product->pedigree_output_pdf;
        
        $print_types = [];
        if($pedigree_output_png == true){
            $print_types[] = 'png';
        }
        if($pedigree_output_pdf == true){
            $print_types[] = 'pdf';
        }

        $pedigree_max_output_png = $product->pedigree_max_output_png;
        $pedigree_max_output_pdf = $product->pedigree_max_output_pdf;

        $max_output_png = [
            '1' => '1344 x 839 px',
            '2' => '2688 x 1678 px',
            '3' => '4032 x 2517 px',
            '4' => '5376 x 3356 px',
            '5' => '6720 x 4195 px',
        ];
        $selected_output_png = array_slice($max_output_png, 0, $pedigree_max_output_png, true);

        $max_output_pdf = [
            'a0' => 'A0',
            'a1' => 'A1',
            'a2' => 'A2',
            'a3' => 'A3',
            'a4' => 'A4',
        ];
        $selected_output_pdf = array_slice($max_output_pdf, str_replace('a', '', $pedigree_max_output_pdf), 5,true);


        return view('users.pedigree.index',compact('gedcom_file','print_types','selected_output_png','selected_output_pdf'));
    }


    // import a new gedcom file
    public function importgedcom(Request $request){

        $input = $request->all();

            Validator::validate($input, [
                'file' => [
                    'required',
                    'file',
                    new GedcomFile
                ],
            ]);

        $pedigree = Pedigree::where('user_id',Auth::user()->id)->first();
        // delete file if exist
        if($pedigree->gedcom_file != null){
            if (Storage::exists($pedigree->gedcom_file)) {
                Storage::delete($pedigree->gedcom_file);
              }
        }
        

        $file = $request->file('file');
        $destinationPath = 'gedcoms';
        $uniqueFilename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $gedcom_file = $file->storeAs($destinationPath, $uniqueFilename);


        $pedigree->gedcom_file = $gedcom_file;
        $pedigree->save();
        
        // delete all notes
        $notes = Note::where('pedigree_id',$pedigree->id)->pluck('id')->toArray();
        Note::destroy($notes);

        return response()->json(['error'=>false,'msg' => 'gedcom file imported']);
        
    }

    // load the gedcom file for the current auth user
    public function getTree(Request $request){
        $pedigree = Pedigree::where('user_id',Auth::user()->id)->first();

        if($pedigree->gedcom_file == null){
            return response(null, 200);
        }

        $file = Storage::disk('local')->get($pedigree->gedcom_file);

        
        
        // Return the file as a response
        return response($file, 200)
                  ->header('Content-Type', 'application/octet-stream')
                  ->header('Content-Disposition', 'attachment; filename="' . basename($pedigree->gedcom_file) . '"');
    }

    // update person
    public function update(Request $request){
        $inputs = $request->except(['_token']);
        $gedcomService = new GedcomService();

        Validator::make($inputs, [
            'person_id' => ['required','string'],
            'firstname' => ['required','string'],
            'lastname' => ['required','string'],
            'status' => ['required','string'],
        ])->validate();
        
        // get gedcom file
        $gedcom_file = $this->get_gedcom_file();
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

        // edit death event
        $death_date = $request->death_date;
        $gedcomService->edit_death($person, $request->status,$death_date);

        // edit birt event
        $birth_date = $request->birth_date;
        $gedcomService->edit_birth($person,$birth_date);

        // edit names
        $name = $request->firstname."/".$request->lastname."/";
        $gedcomService->edit_name($person,$name);

        // write modification to gedcom file
        $gedcomService->writer($gedcom,$gedcom_file);

        return response()->json(['error'=>false,'msg' => 'Person updated with success']);

        //return redirect()->back()->with('success','person updated with success');
    }

    // add spouse
    public function addspouse(Request $request){
        $inputs = $request->except(['_token']);
        $gedcomService = new GedcomService();

        Validator::make($inputs, [
            'person_id' => ['required','string'],
            'firstname' => ['required','string'],
            'lastname' => ['required','string'],
            'status' => ['required','string'],
        ])->validate();

        // get gedcom file
        $gedcom_file = $this->get_gedcom_file();
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

        // create new indi as spouse
        $sex = $person->getSex() == 'M' ? 'F' : 'M';
        $spouse = $this->create_indi($gedcom, $request->firstname, $request->lastname, $sex, $request->status, $request->birth_date, $request->death_date);

        // generate new Family id
        $families = array_keys($gedcom->getFam());
        if($families != []){
            $maxFamId = max(array_map([$this, 'extractNumber'], $families));
            $newFamId = 'F' . ($maxFamId + 1);
        }
        else{
            $newFamId = 'F1';
        }
        

        // add FAMS to spouse and person
        $fams = new IndiFams();
        $fams->setFams($newFamId);

        $spouse->addFams($fams);
        $person->addFams($fams);

        // add spouse to gedcom
        $gedcom->addIndi($spouse);

        // create and add the fams in families
        $family = new Fam();
        $family->setId($newFamId);

        if($person->getSex() == 'M'){
            $family->setHusb($person->getId());
            $family->setWife($spouse->getId());
        }
        else{
            $family->setWife($person->getId());
            $family->setHusb($spouse->getId());
        }

        $gedcom->addFam($family);
        // write modification to gedcom file
        $gedcomService->writer($gedcom,$gedcom_file);

        return redirect()->back()->with('success','spouse added with success');
        
    }

    private function extractNumber($item) {
        return intval(substr($item, 1));
    }

    public function addChild(Request $request){

        $inputs = $request->except(['_token']);
        $gedcomService = new GedcomService();

        Validator::make($inputs, [
            'person_id' => ['required','string'],
            'person_type' => ['required','string'],
            'parents' => ['required','string'],
            'relationship' => ['required','string'],
            'firstname' => ['required','string'],
            'lastname' => ['required','string'],
            'sex' => ['required','string'],
            'status' => ['required','string'],
        ])->validate();

        // get gedcom file
        $gedcom_file = $this->get_gedcom_file();
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

        // create child
        $child = $this->create_indi($gedcom, $request->firstname, $request->lastname, $request->sex, $request->status, $request->birth_date, $request->death_date);

        // get family where parents exist
        $family = $this->get_family_for_child($gedcom, $request->parents);

        // create FAMC for child
        $famc = new IndiFamc();
        $famc->setFamc($family->getId());
        

        // add ADOPT event if child if adoptive
        if($request->relationship == 'adopt'){
            $adop = new IndiAdop();
            $adop->setType('ADOP');
            $adop->setAdop('BOTH');
            $adop->setFamc('@'.$famc->getFamc().'@');
            $child->addEven($adop);

            // add pedi to famc
            $famc->setPedi('adopted');
        }
        /*
        if($request->relationship == 'foster'){

            // add pedi to famc
            $famc->setPedi('foster');
        }
        */
        // add FAMC to child
        $child->addFamc($famc);

        // add child to gedcom
        $gedcom->addIndi($child);

        // add child to family children
        $children = $family->getChil();
        array_push($children,$child->getId());
        $family->setChil($children);
        
        $gedcomService->writer($gedcom,$gedcom_file);
        return redirect()->back()->with('success','child added with success');
    }

    public function delete(Request $request){

        $inputs = $request->except(['_token']);
        $gedcomService = new GedcomService();

        Validator::make($inputs, [
            'person_id' => ['required','string'],
        ])->validate();

        // get gedcom file
        $gedcom_file = $this->get_gedcom_file();
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

        // delete person from indi collection  in gedcom
        $indis = $gedcom->getIndi();
        unset($indis[$person_id]);

        $reflection = new ReflectionClass($gedcom);
        $property = $reflection->getProperty('indi');
        $property->setAccessible(true);
        $property->setValue($gedcom, $indis);


        // get family id if person has a fams and delete it
        $familiesId = [];
        $personFamilies = $person->getFams();
        
        foreach($personFamilies as $key => $family){
            array_push($familiesId, $family->getFams());
        }
        
        $families = $gedcom->getFam();
        foreach($familiesId as $id){
            unset($families[$id]);
        } 
        $reflection = new ReflectionClass($gedcom);
        $property = $reflection->getProperty('fam');
        $property->setAccessible(true);
        $property->setValue($gedcom, $families);


        // write modification to gedcom file
        $gedcomService->writer($gedcom,$gedcom_file);

        return redirect()->back()->with('success','person deleted with success');
    }


    private function get_gedcom_file(){

        $pedigree = Pedigree::where('user_id',Auth::user()->id)->first();
        if($pedigree == null){
            return null;
        }

        if (!Storage::disk('local')->exists($pedigree->gedcom_file)) {
            return null;
        } 
        
        $file = Storage::disk('local')->get($pedigree->gedcom_file);

        return $pedigree->gedcom_file;

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


    private function get_family_for_child($gedcom, $parents){
        $parents = explode("-", $parents);
        $person1 = str_replace('@','',$parents[0]);
        $person2 = str_replace('@','',$parents[1]);

        // get the family for the new child
        $families = $gedcom->getFam();
        foreach($families as $key => $family){
            if( 
                ($family->getHusb() == $person1 and $family->getWife() == $person2) 
                or 
                ($family->getHusb() == $person2 and $family->getWife() == $person1)
            ){
                return $family;
            }
        }
    }

    private function parse_gedcom($file){


        $parser = new GedcomParser();
        $gedcom = $parser->parse($file);

        return $gedcom;

    }



}