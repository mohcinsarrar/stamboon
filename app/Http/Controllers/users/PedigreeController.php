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

class PedigreeController extends Controller
{

    public function index(Request $request){
        
        return view('users.pedigree.index');
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

    public function parse_gedcom(Request $request){
        $file = $request->file('file');
            $tmpPath = $file->getRealPath();

            $parser = new GedcomParser();
            $gedcom = $parser->parse($tmpPath);

            foreach ($gedcom->getIndi() as $individual) {
                $names = $individual->getName();
                if (!empty($names)) {
                    $name = reset($names); // Get the first name object from the array
                    echo $individual->getId() . ': ' . $name->getName() . ', ' . $name->getGivn() . PHP_EOL;
                }
            }

            dd($gedcom);
    }

    public function getTree(Request $request){
        $pedigree = Pedigree::where('user_id',Auth::user()->id)->first();

        $file = Storage::disk('local')->get($pedigree->gedcom_file);
        
        // Return the file as a response
        return response($file, 200)
                  ->header('Content-Type', 'application/octet-stream')
                  ->header('Content-Disposition', 'attachment; filename="' . basename($pedigree->gedcom_file) . '"');
    }

}