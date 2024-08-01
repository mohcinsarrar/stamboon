<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\NodeResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use Illuminate\Support\Str;
use ReflectionClass;

use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\HeadingRowImport;
use App\Imports\TreeImport;
use App\Exports\TreeExport;

use App\Models\User;
use App\Models\Tree;
use App\Models\Node;
use App\Models\Pedigree;

use Illuminate\Support\Facades\Http;
use Goutte\Client;

use App\Rules\GedcomFile;
use Gedcom\Parser as GedcomParser;
use Gedcom\Writer as GedcomWriter;
use Gedcom\Record\Indi as Indi;
use Gedcom\Record\Indi\Name as IndiName;
use Gedcom\Record\Indi\Birt as IndiBirt;
use Gedcom\Record\Indi\Deat as IndiDeat;
use Gedcom\Record\Indi\Fams as IndiFams;
use Gedcom\Record\Indi\Famc as IndiFamc;
use Gedcom\Record\Fam as Fam;


use App\Services\GedcomService;



class PedigreeController extends Controller
{

    public function index(Request $request){
        
        $pedigree = Pedigree::where('user_id',Auth::user()->id)->first();
        $gedcom_file = $pedigree->gedcom_file;
        return view('users.pedigree.index',compact('gedcom_file'));
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

        return response()->json(['error'=>false,'msg' => 'gedcom file imported']);
        
    }

    // load the gedcom file for the current auth user
    public function getTree(Request $request){
        $pedigree = Pedigree::where('user_id',Auth::user()->id)->first();

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

        return redirect()->back()->with('success','person updated with success');
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
        $maxFamId = max(array_map([$this, 'extractNumber'], $families));
        $newFamId = 'F' . ($maxFamId + 1);

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

        // add FAMC to child
        $famc = new IndiFamc();
        $famc->setFamc($family->getId());
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
        $maxIndiId = max(array_map([$this, 'extractNumber'], $indis));
        $newIndiId = 'I' . ($maxIndiId + 1);
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