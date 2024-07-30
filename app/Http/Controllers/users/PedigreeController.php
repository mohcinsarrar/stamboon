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
use App\Services\GedcomService;


class PedigreeController extends Controller
{

    public function index(Request $request){
        
        $pedigree = Pedigree::where('user_id',Auth::user()->id)->first();
        $gedcom_file = $pedigree->gedcom_file;
        return view('users.pedigree.index',compact('gedcom_file'));
    }

    public function index2(Request $request){
        
        return view('users.pedigree.index2');
    }

    public function index3(Request $request){

        $pedigree = Pedigree::where('user_id',Auth::user()->id)->first();
        $gedcom_file = $pedigree->gedcom_file;
        return view('users.pedigree.index33',compact('gedcom_file'));
    }

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

    private function parse_gedcom($file){


            $parser = new GedcomParser();
            $gedcom = $parser->parse($file);

            return $gedcom;

    }

    public function getTree(Request $request){
        $pedigree = Pedigree::where('user_id',Auth::user()->id)->first();

        $file = Storage::disk('local')->get($pedigree->gedcom_file);
        
        // Return the file as a response
        return response($file, 200)
                  ->header('Content-Type', 'application/octet-stream')
                  ->header('Content-Disposition', 'attachment; filename="' . basename($pedigree->gedcom_file) . '"');
    }


    public function update(Request $request){
        
        $inputs = $request->except(['_token']);
        $gedcomService = new GedcomService();

        Validator::make($inputs, [
            'person_id' => ['required','string'],
            'firstname' => ['required','string'],
            'lastname' => ['required','string'],
            'status' => ['required','string'],
        ])->validate();

        $pedigree = Pedigree::where('user_id',Auth::user()->id)->first();
        if($pedigree == null){
            return redirect()->back()->with('error','cant load your family tree');
        }

        if (!Storage::disk('local')->exists($pedigree->gedcom_file)) {
            return redirect()->back()->with('error','cant load your family tree');
        } 

        $person_id = str_replace('@','',$request->person_id);
        $file = Storage::disk('local')->get($pedigree->gedcom_file);
        $gedcom = $this->parse_gedcom('storage/'.$pedigree->gedcom_file);

        $person = null;
        foreach ($gedcom->getIndi() as $individual) {
            $id = $individual->getId();
            if ($id == $person_id) {
                $person = $individual;
            }
        }

        if($person == null){
            return redirect()->back()->with('error','cant find person');
        }


        // edit death event
        $death_date_array = [$request->death_day,$request->death_month,$request->death_year];
        $gedcomService->edit_death($person, $request->status,$death_date_array);

        // edit birt event
        $birth_date_array = [$request->birth_day,$request->birth_month,$request->birth_year];
        $gedcomService->edit_birth($person,$birth_date_array);

        // edit names
        $name = $request->firstname."'\' ".$request->lastname."'\'";
        $name = str_replace("'\'","",$name);
        $gedcomService->edit_name($person,$name);

        // write modification to gedcom file
        $gedcomService->writer($gedcom,$pedigree->gedcom_file);

        return redirect()->back()->with('success','person updated with success');
    }


    private function gedcom_writer($gedcom){
        
    }

}