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
use App\Models\Fantree;
use App\Models\SettingFantree;
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


class FantreeController extends Controller
{


    public function index(Request $request){

        $user = Auth::user();

        SettingFantree::create(['user_id'=>$user->id]);

        // get product features
        $has_payment = $user->has_payment();

        return view('users.fantree.index', compact('has_payment'));
    }

    // import a new gedcom file
    public function importgedcom(Request $request){

        Fantree::create([
            'user_id' => Auth::user()->id
        ]);
        
        $input = $request->all();

        Validator::validate($input, [
                'file' => [
                    'required',
                    'file',
                    new GedcomFile
                ],
            ]);

        $fantree = Fantree::where('user_id',Auth::user()->id)->first();
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

    public function getTree(Request $request){
        $fantree = Fantree::where('user_id',Auth::user()->id)->first();

        if($fantree->gedcom_file == null){
            return response(null, 200);
        }

        $file = Storage::disk('local')->get($fantree->gedcom_file);
        
        // Return the file as a response
        return response($file, 200)
                  ->header('Content-Type', 'application/octet-stream')
                  ->header('Content-Disposition', 'attachment; filename="' . basename($fantree->gedcom_file) . '"');
    }


    public function settings(Request $request){
        
        
        // load settings
        if($request->isMethod('get')){
            $user = Auth::user();
            $settings = SettingFantree::where('user_id',$user->id)->first()->toArray();
            $fantree = Fantree::where('user_id',$user->id)->first();
            if($fantree == null){
                return response()->json(['error'=>true,'msg' => 'error']);
            }
            // get product features
            $current_payment = $user->last_payment();
            if($current_payment == false){
                return redirect()->route('users.dashboard.index');
            }
            $product = Product::where('id',$current_payment->product_id)->first();
            $settings['max_nodes'] = $product->max_nodes;
            $settings['max_generation'] = $product->fantree_max_generation;

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
                'bg_template' => ['required',Rule::in(['1', '2', '3', '4'])],

                'default_date' => ['required',Rule::in(['MM-DD-YYYY', 'YYYY-MM-DD', 'DD-MM-YYYY'])]
            ])->validate();

            
            SettingFantree::where('user_id',Auth::user()->id)->update($inputs);

            return redirect()->back()->with('success','settings updated with success');
        }
    }

}