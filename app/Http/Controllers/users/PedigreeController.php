<?php

namespace App\Http\Controllers\users;

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
use ZipArchive;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\DataTables\users\PedigreesDataTable;

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


    private function get_pedigree($pedigree_id){


        try {
            $pedigree = Pedigree::findOrFail($pedigree_id);
            if(Auth::user()->id != $pedigree->user->id){
                abort(404, 'Pedigree not found');
            }
            return $pedigree;
        } catch (ModelNotFoundException $e) {
            abort(404, 'Pedigree not found');
        }


    }


    public function addperson(Request $request, $pedigree_id){


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
        $pedigree = $this->get_pedigree($pedigree_id);
        $pedigree->gedcom_file = $uniqueFilename;
        $pedigree->save();  

        // Optionally delete the temporary file
        StorageFile::delete($tempFilePath);

        return redirect()->back()->with('success','person added with success');
        
    }

    public function getChartStatus(Request $request, $pedigree_id){
        $pedigree = $this->get_pedigree($pedigree_id);
        if($pedigree == null){
            return response()->json(['error'=>true,'msg' => 'error']);
        }
        $chart_status = $pedigree->chart_status;
        return response()->json(['error'=>false,'chart_status' => $chart_status]);
    }

    public function print(Request $request, $pedigree_id){
        $user = Auth::user();
        $pedigree = $this->get_pedigree($pedigree_id);
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

    public function editChartStatus(Request $request, $pedigree_id){
        $chart_status = $request->input('chart_status');

        $pedigree = $this->get_pedigree($pedigree_id);
        if($pedigree == null){
            return response()->json(['error'=>true,'msg' => 'error']);
        }
        
        $pedigree->chart_status = $chart_status;
        $pedigree->save();
        return response()->json(['error'=>false,'msg' => 'error']);
    }

    public function saveimage(Request $request, $pedigree_id){


        $gedcomService = new GedcomService();

        // get gedcom file
        $gedcom_file = $this->get_gedcom_file($pedigree_id);
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

    // Helper function to recursively add files to the zip
    private function addFilesToZip($folder, $zip, $folderInZip = '')
    {
        $files = glob($folder . '/*');
        foreach ($files as $file) {
            if (is_dir($file)) {
                // Add sub-directory in zip
                $this->addFilesToZip($file, $zip, $folderInZip . basename($file) . '/');
            } else {
                $zip->addFile($file, $folderInZip . basename($file));
            }
        }
    }

    // Helper function to delete a directory
    private function deleteDirectory($dir)
    {
        $files = glob($dir . '/*');
        foreach ($files as $file) {
            if (is_dir($file)) {
                $this->deleteDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dir);
    }


    public function download(Request $request, $pedigree_id){
        

        // get gedcom file
        $gedcom_file = $this->get_gedcom_file($pedigree_id);
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
                Storage::copy('portraits/'.$photo, $tempDir . '/' . $name . '.png');
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

    public function orderspouses(Request $request, $pedigree_id){
        
        $gedcomService = new GedcomService();

        $gedcom_file = $this->get_gedcom_file($pedigree_id);
        if ($gedcom_file == null) {
            return response()->json(['error'=>true,'msg' => "cant order spouses, please try again !"]);
        }

        // get gedcom object
        $gedcom = $this->get_gedcom($gedcom_file);

        $families = array();
        foreach ($request->spouses as $spouse) {
            $person_id = str_replace('@','',$spouse);
            $person = $this->get_person($gedcom,$person_id);
            $fams = $person->getFams()[0]->getFams();
            $families[] = $fams;
        }

        $current_families = $gedcom->getFam();
        $new_fam = $this->reorderArrayByKeys($current_families, $families);

        $reflectionClass = new ReflectionClass($gedcom);
        $property = $reflectionClass->getProperty('fam');
        $property->setAccessible(true); // Make the protected property accessible
        $property->setValue($gedcom, $new_fam);
        
        
        $gedcomService->writer($gedcom,$gedcom_file);

        return response()->json(['error'=>false,'msg' => 'spouses order with succes']);
    }


    private function reorderArrayByKeys(array $inputArray, array $order): array {
        // Create a new array to store the reordered items
        $reorderedArray = [];
        
        // Add items from the input array to the reordered array based on the order array
        foreach ($order as $key) {
            if (array_key_exists($key, $inputArray)) {
                $reorderedArray[$key] = $inputArray[$key];
            }
        }
    
        // Append remaining items from the input array that were not in the order array
        foreach ($inputArray as $key => $value) {
            if (!array_key_exists($key, $reorderedArray)) {
                $reorderedArray[$key] = $value;
            }
        }
    
        return $reorderedArray;
    }

    public function getpersons(Request $request, $pedigree_id){

        // get gedcom file
        $gedcom_file = $this->get_gedcom_file($pedigree_id);
        if ($gedcom_file == null) {
            return response()->json(['error'=>true,'persons' => null]);
        } 

        // get gedcom object
        $gedcom = $this->get_gedcom($gedcom_file);

        $persons = array();
        foreach ($request->ids as $id) {
            $person_id = str_replace('@','',$id);
            $person = $this->get_person($gedcom,$person_id);
            $name = str_replace('/',' ',$person->getName()[0]->getName());
            $photo = null;
            if($person->getNote() != null && $person->getNote() != []){
                $photo = $person->getNote()[0]->getNote();
            }

            $sex = $person->getSex();
            $birth = null;
            if($person->getEven('BIRT') != null && $person->getEven('BIRT') != []){
                $birth = $person->getEven('BIRT')[0]->getDate();
            }

            $death = null;
            if($person->getEven('DEAT') != null && $person->getEven('DEAT') != []){
                $death = $person->getEven('DEAT')[0]->getDate();
            }
            
            $persons[] = [
                'personId' => "@".$person_id."@",
                'name' => $name,
                'photo' => $photo,
                'sex' => $sex,
                'birth' => $birth,
                'death' => $death,
            ];
            

        }
        
        return response()->json(['error'=>false,'persons' => $persons]);


    }

    public function settings(Request $request, $pedigree_id){
        
        
        // load settings
        if($request->isMethod('get')){

            $pedigree = $this->get_pedigree($pedigree_id);

            // if no settings created it
            if(Setting::where('pedigree_id',$pedigree->id)->first() == null){
                Setting::create(['pedigree_id' => $pedigree->id]);
            }

            $settings = Setting::where('pedigree_id',$pedigree->id)->first()->toArray();
            

            // get product features

            $user = Auth::user();
            $current_payment = $user->last_payment();
            if($current_payment == false){
                return redirect()->route('users.dashboard.index');
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
                'bg_template' => ['nullable',Rule::in(['0','1', '2', '3', '4'])],

                'note_type' => ['required',Rule::in(['1', '2', '3', '4'])],

                'note_text_color' => ['required',new HexColor],

                'default_date' => ['required',Rule::in(['MM-DD-YYYY', 'YYYY-MM-DD', 'DD-MM-YYYY'])]
            ])->validate();

            
            if($request->bg_template == null){
                $inputs['bg_template'] = '0';
            }

            $pedigree = $this->get_pedigree($pedigree_id);
            Setting::where('pedigree_id',$pedigree->id)->update($inputs);

            return redirect()->back()->with('success','settings updated with success');
        }
    }

    private function get_gedcom_file_for_delete($pedigree){
    
        
        if($pedigree == null){
            return null;
        }
        if($pedigree->gedcom_file == null){
          return null;
        }
    
        if (!Storage::disk('local')->exists($pedigree->gedcom_file)) {
            return null;
        } 
        
        $file = Storage::disk('local')->get($pedigree->gedcom_file);
    
        return $pedigree->gedcom_file;
    
    }

    public function delete_pedigree(Request $request, $id){

        $user = Auth::user();
        if(!$user->hasRole('superuser')){
            abort(404, 'Pedigree not found');
        }
        
        $pedigree = Pedigree::findOrFail($id);
        

        // delete all user data pedigree

        /// get gedcom file
        $gedcom_file = $this->get_gedcom_file_for_delete($pedigree);
        if($gedcom_file != null){
            /// get gedcom object
            $gedcom = $this->get_gedcom($gedcom_file);
            /// get all indis
            $indis = $gedcom->getIndi();
            /// iterate over all indis and delete photo if exist
            foreach($indis as $indi){
                  $note = $indi->getNote();
      
                  $photo = null;
                  if($note != null and $note != []){
                      $photo = $note[0]->getNote();
                  }
      
                  if($photo != null){
                    if (Storage::exists('portraits/'.$photo)) {
                        Storage::delete('portraits/'.$photo);
                    }
                  }
            }
      
            /// delete gedcom file
            if (Storage::exists('gedcoms/'.$gedcom_file)) {
              Storage::delete('gedcoms/'.$gedcom_file);
            }
          }

        $pedigree->delete();

        return redirect()->route('users.pedigree.list')->with('success', 'Pedigree deleted with success');
    }


    public function edit_pedigree(Request $request,$id){

        $user = Auth::user();
        if(!$user->hasRole('superuser')){
            abort(404, 'Pedigree not found');
        }

        $inputs = $request->except(['_token','_method']);
        
        Validator::make($inputs, [
            'name' => ['required','string','unique:pedigrees,name,'.$id],

        ])->validate();

        // create pedigree

        Pedigree::where('id',$id)->update([
            'name' => $request->name,
        ]);

        return redirect()->route('users.pedigree.list')->with('success', 'Pedigree updated with success');
    }


    public function store_pedigree(Request $request){

        $user = Auth::user();
        if(!$user->hasRole('superuser')){
            abort(404, 'Pedigree not found');
        }
        
        $inputs = $request->except(['_token']);
        
        Validator::make($inputs, [
            'name' => ['required','string','unique:pedigrees,name'],

        ])->validate();

        // create pedigree
        $user = Auth::user();

        Pedigree::create([
            'name' => $request->name,
            'user_id' => $user->id
        ]);

        return redirect()->route('users.pedigree.list')->with('success', 'Pedigree created with success');
    }

    public function list(PedigreesDataTable $dataTable){

        $user = Auth::user();
        if(!$user->hasRole('superuser')){
            $pedigree = Pedigree::where('user_id',$user->id)->first();
            return redirect()->route('users.pedigree.index',$pedigree->id);
        }
        return $dataTable->render('users.pedigree.list');
    }


    public function all(PedigreesDataTable $dataTable){

        $user = Auth::user();
        if(!$user->hasRole('superadmin')){
            abort(404, 'Pedigree not found');
        }

        return $dataTable->render('users.pedigree.list');
    }


    public function index(Request $request, $pedigree_id){

        $user = Auth::user();
        $pedigree = $this->get_pedigree($pedigree_id);
        $gedcom_file = $pedigree->gedcom_file;

        // get product features
        $has_payment = $user->has_payment();
        $current_payment = $user->last_payment();
        if($current_payment == false){
            return redirect()->route('users.dashboard.index');
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


        return view('users.pedigree.index',compact('pedigree_id','gedcom_file','print_types','selected_output_png','selected_output_pdf','has_payment'));
    }


    // import a new gedcom file
    public function importgedcom(Request $request, $pedigree_id){

        $input = $request->all();

            Validator::validate($input, [
                'file' => [
                    'required',
                    'file',
                    new GedcomFile
                ],
            ]);

        $pedigree = $this->get_pedigree($pedigree_id);
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
    public function getTree(Request $request, $pedigree_id){
        $pedigree = $this->get_pedigree($pedigree_id);

        if($pedigree->gedcom_file == null){
            return response(null, 200);
        }

        $file = Storage::disk('local')->get($pedigree->gedcom_file);

        // Return the file as a response
        return response($file, 200)
                  ->header('Content-Type', 'application/octet-stream')
                  ->header('Content-Disposition', 'attachment; filename="' . basename($pedigree->gedcom_file) . '"');
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
    // update person
    public function update(Request $request, $pedigree_id){

        $inputs = $request->except(['_token']);
        $gedcomService = new GedcomService();

        Validator::make($inputs, [
            'person_id' => ['required','string'],
            'firstname' => ['required','string'],
            'lastname' => ['required','string'],
            'status' => ['required','string'],
        ])->validate();
        
        // get gedcom file
        $gedcom_file = $this->get_gedcom_file($pedigree_id);
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


        // write modification to gedcom file
        $gedcomService->writer($gedcom,$gedcom_file);

        return response()->json(['error'=>false,'msg' => 'Person updated with success']);

        //return redirect()->back()->with('success','person updated with success');
    }

    // add spouse
    public function addspouse(Request $request, $pedigree_id){
        $inputs = $request->except(['_token']);
        $gedcomService = new GedcomService();

        Validator::make($inputs, [
            'person_id' => ['required','string'],
            'firstname' => ['required','string'],
            'lastname' => ['required','string'],
            'status' => ['required','string'],
        ])->validate();

        // get gedcom file
        $gedcom_file = $this->get_gedcom_file($pedigree_id);
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


    public function addancestor(Request $request, $pedigree_id){
        $inputs = $request->except(['_token']);
        $gedcomService = new GedcomService();

        Validator::make($inputs, [
            'person_id' => ['required','string'],
            'firstname' => ['required','string'],
            'lastname' => ['required','string'],
            'sex' => ['required','string'],
            'status' => ['required','string'],
        ])->validate();

        // get gedcom file
        $gedcom_file = $this->get_gedcom_file($pedigree_id);
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

        // create new indi as ancestor
        $ancestor = $this->create_indi($gedcom, $request->firstname, $request->lastname, $request->sex, $request->status, $request->birth_date, $request->death_date);

        // add ancestor to gedcom
        $gedcom->addIndi($ancestor);

        
        // create new family and add the child to it (it the person)
        $new_family = new Fam();
        $newFamId = $this->new_family_id($gedcom);
        $new_family->setId($newFamId);

        // add child
        $children = $new_family->getChil();
        array_push($children,$child->getId());
        $new_family->setChil($children);

        // add parent
        if($request->sex == 'M'){
            // father
            $new_family->setHusb($ancestor->getId());
        }
        else{
            // mother
            $new_family->setWife($ancestor->getId());
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
        $ancestor->addFams($fams);

        $gedcomService->writer($gedcom,$gedcom_file);

        return redirect()->back()->with('success','parent added with success');

    }

    private function extractNumber($item) {
        return intval(substr($item, 1));
    }

    public function addChild(Request $request, $pedigree_id){

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
        $gedcom_file = $this->get_gedcom_file($pedigree_id);
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

    public function delete(Request $request, $pedigree_id){

        $inputs = $request->except(['_token']);
        $gedcomService = new GedcomService();

        Validator::make($inputs, [
            'person_id' => ['required','string'],
        ])->validate();

        // get gedcom file
        $gedcom_file = $this->get_gedcom_file($pedigree_id);
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


    private function get_gedcom_file($pedigree_id){

        $pedigree = $this->get_pedigree($pedigree_id);
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


    public function updatecount(Request $request, $pedigree_id){
        $pedigree = $this->get_pedigree($pedigree_id);
        if($pedigree == null){
            return response()->json(['error'=>true,'msg' => 'error']);
        }

        $stats = $request->input('stats');
        $pedigree->stats = $stats;

        $pedigree->save();
        return response()->json(['error'=>false,'msg' => 'error']);


    }


}