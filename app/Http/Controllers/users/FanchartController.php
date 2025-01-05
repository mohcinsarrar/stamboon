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
use App\Models\Product;

use Illuminate\Support\Facades\Http;
use Goutte\Client;

class FanchartController extends Controller
{
    //

    public function send(Request $request){
            $user = Auth::user();
            $tree = Tree::where("user_id",$user->id)->where('status','!=','waiting')->first();

            if($tree!=null){

                $tree->status = 'waiting';
                $tree->template = $request->template;
                $tree->generation = $request->generation; 
                $file_name = 'files/'.Str::random(40).'.xlsx';
                Excel::store(new TreeExport($user->id), $file_name );
                $tree->excel_file = $file_name;
                if($tree->save()){
                    $user->addNotification('Family Tree sent','family tree sent for validation');
                    $user->addActivity('Family Tree sent','family tree sent for validation');
                    // send notification to admin
                    $admin = User::hasRole('admin')->first();
                    $user->addNotification('Family Tree sent','user '.$user->name.' send family tree for validation' ,$admin->id);
                    return response()->json(['error'=>false,'msg' => 'Tree send with success']);
                }
                else{
                    return response()->json(['error'=>true,'msg' => 'Tree not found']);
                }

            }
            else{
                return response()->json(['error'=>true,'msg' => 'Tree not found']);
            }


    }

    public function change_generations(Request $request){
        $user = Auth::user();
        $tree = Tree::where("user_id",$user->id)->where('status','!=','waiting')->first();

        $tree->generation = $request->generation;
        $tree->save();

        return response()->json(['error'=>false, 'msg' => 'generation changed']);

    }

    public function change_template(Request $request){
        $user = Auth::user();
        $tree = Tree::where("user_id",$user->id)->where('status','!=','waiting')->first();

        $tree->template = $request->template;
        $tree->save();

        return response()->json(['error'=>false, 'msg' => 'template changed']);

    }
    

    public function importexcel(Request $request){

        $user = Auth::user();
        $tree = Tree::where("user_id",$user->id)->where('status','!=','waiting')->first();

        if($tree!=null){

            $input = $request->all();

            Validator::validate($input, [
                'file' => [
                    'required',
                    File::types(['xls', 'xlsx'])
                        ->max(2 * 1024),
                ],
            ]);

            $file = $request->file('file');
            Excel::import(new TreeImport, $file);
            $user->addActivity('Family Tree imported','family tree imported with success via excel file');
            return response()->json(['error'=>false,'msg' => 'File Added','redirect_url'=>route('users.fanchart.index')]);

        }
        else{
            return response()->json(['error'=>true,'msg' => 'Tree not found']);
        }

        


    }


    public function updateimageplacholder(Request $request){

        $user = Auth::user();
        $tree = Tree::where("user_id",$user->id)->where('status','!=','waiting')->first();

        if($tree!=null){
            $choix = $request->choix;
            $id = $request->id;
            $node = Node::findOrFail($id);
            $node->image = $node->sex.''.$choix.'.jpg';
            if($node->save()){
                return response()->json(['error'=>false,'msg' => 'Image Added']);
            }
            else{
                return response()->json(['error'=>true,'msg' => 'Error when adding image']);
            }
        }
        else{
            return response()->json(['error'=>true,'msg' => 'Tree not found']);
        }
        

    }

    public function saveimage(Request $request){

        $user = Auth::user();
        $tree = Tree::where("user_id",$user->id)->where('status','!=','waiting')->first();

        if($tree!=null){
            $image = $request->imageBase64;
            $id = $request->id;
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);
            $imageName = Str::random(40).'.'.'png';
            storage::put( "portraits/".$imageName, base64_decode($image));
            $node = Node::findOrFail($id);
            $node->image = $imageName;
            if($node->save()){
                return response()->json(['error'=>false,'msg' => 'Image Added']);
            }
            else{
                return response()->json(['error'=>true,'msg' => 'Error when adding image']);
            }
        }
        else{
            return response()->json(['error'=>true,'msg' => 'Tree not found']);
        }

        
    }


    function loadChildrenWithDepth($generation) {
        $relations = "";
        for ($i = 0; $i < $generation-1; $i++) {
            if($i==0){
                $relations = $relations.'children';
            }
            else{
                $relations = $relations.'.children';
            }
            
        }
        return $relations;
    }

    public function get_tree(Request $request,$generation){
        if($request->ajax()){
            $generation = (int)($generation);

            $user = Auth::user();
            $tree = Tree::where("user_id",$user->id)->first();

            if($tree!=null){

                $rootNode = Node::select('id','firstname','lastname','sex','birth','death','pid','image','position','empty','root','generation')
                ->with($this->loadChildrenWithDepth($generation))
                ->where('tree_id',$tree->id)
                ->where('root','=',true)
                ->first();
    
                $formattedTree =  new NodeResource($rootNode);
                return response()->json(['error'=>false,'data' => $formattedTree]);
            }
            else{
                return response()->json(['error'=>true,'msg' => 'Tree not found']);
            }
        }
        
    }

    public function updateperson(Request $request){
        if($request->ajax()){
            // add validation

            // test if person exist
            $user = Auth::user();
            $tree = Tree::where("user_id",$user->id)->where('status','!=','waiting')->first();
            if($tree != null){
                $node = Node::where('tree_id',$tree->id)->where('id',$request->id)->first();
                
                if($node != null){
                    // update Node
                    $inputs = $request->except(['id','generation']);
                    if($inputs['death'] == null){
                        $inputs['death'] = "";
                    }
                    $node->update($inputs);
                    $user->addActivity('Person updated','person '.$request->firstname.' '.$request->lastname.' updated with success');
                    return response()->json(['error'=>false,'msg' => 'Person updated']);
                }
                else{
                    return response()->json(['error'=>true,'msg' => 'Person not found']);
                }
            }
            else{
                return response()->json(['error'=>true,'msg' => 'Tree not found']);
            }
        }
        
    }

    public function deleteperson(Request $request){
        if($request->ajax()){

            // test if person exist
            $user = Auth::user();
            $tree = Tree::where("user_id",$user->id)->where('status','!=','waiting')->first();
            if($tree != null){
                $node = Node::with('children')->where('tree_id',$tree->id)->where('id',$request->id)->first();
                
                if($node != null){
                    if($node->root != true){
                        $node->empty = true;
                        if($node->save()){
                            Node::updateChildrenRecursively($node, true);
                            $user->addActivity('Person deleted','person '.$node->firstname.' '.$node->lastname.' deleted with success');
                            return response()->json(['error'=>false,'msg' => 'Person deleted']);
                        }
                        else{
                            return response()->json(['error'=>true,'msg' => 'Error when deleting person']);
                        }
                        
                    }
                    else{
                        return response()->json(['error'=>true,'msg' => 'You cannot delete the root person !!!']);
                    }
                    
                }
                else{
                    return response()->json(['error'=>true,'msg' => 'Person not found']);
                }
            }
            else{
                return response()->json(['error'=>true,'msg' => 'Tree not found']);
            }
        }
        
    }

    public function addperson(Request $request){
        if($request->ajax()){
            $user = Auth::user();
            $tree = Tree::where("user_id",$user->id)->where('status','!=','waiting')->first();
            if($tree != null){
                // add validation
                $id = $request->id;
                $sex = $request->sex;

                if($sex == 'M'){
                    $type_msg = 'Father';
                }
                else{
                    $type_msg = 'Mother';
                }
                // test if person has father or mother
                $node = Node::where('pid',$id)->where('sex',$sex)->where('empty',false)->first();
                
                // if node != null then the person added already exist return error
                if($node != null){
                    
                    return response()->json(['error'=>true,'msg' => $type_msg .' already exist']);
                }
                
                // person d'ont exist added it
                $user = Auth::user();
                $tree = Tree::where("user_id",$user->id)->first();

                // if tree exist
                if($tree != null){
                    $parent = Node::findOrFail($id);
                    $child = Node::where('pid',$id)
                            ->where('tree_id',$tree->id)
                            ->where('sex',$sex)
                            ->where('generation',$parent->generation + 1)
                            ->first();
                    if($child != null){
                        $child->empty = false;
                        if($child->save()){
                            $user->addActivity('Person added',$type_msg.' added for person '.$child->firstname.' '.$child->lastname.'');
                            return response()->json(['error'=>false,'msg' => $type_msg .' successfully added']);
                        }
                        else{
                            return response()->json(['error'=>true,'msg' => 'Error while adding '.$type_msg ]);
                        }
                    }
                    else{
                        return response()->json(['error'=>true,'msg' => 'Error while adding '.$type_msg ]);
                    }

                }
                // if tree not exist
                else{
                    return response()->json(['error'=>true,'msg' => 'Tree not found']);
                }
            }
            else{
                return response()->json(['error'=>true,'msg' => 'Tree not found']);
            }
            
        }
        
    }


    public function index(Request $request){

            $user = Auth::user();
            $tree = Tree::where("user_id",$user->id)->first();

            // get product features
            $current_payment = $user->has_payment();
            if($current_payment == false){
                return redirect()->route('users.dashboard.index');
            }
            $product = Product::where('id',$current_payment->product_id)->first();

            $fanchart_output_png = $product->fanchart_output_png;
            $fanchart_output_pdf = $product->fanchart_output_pdf;
            
            $print_types = [];
            if($fanchart_output_png == true){
                $print_types[] = 'png';
            }
            if($fanchart_output_pdf == true){
                $print_types[] = 'pdf';
            }

            $fanchart_max_output_png = $product->fanchart_max_output_png;
            $fanchart_max_output_pdf = $product->fanchart_max_output_pdf;

            $max_output_png = [
                '1' => '1344 x 839 px',
                '2' => '2688 x 1678 px',
                '3' => '4032 x 2517 px',
                '4' => '5376 x 3356 px',
                '5' => '6720 x 4195 px',
            ];
            $selected_output_png = array_slice($max_output_png, 0, $fanchart_max_output_png, true);

            $max_output_pdf = [
                'a0' => 'A0',
                'a1' => 'A1',
                'a2' => 'A2',
                'a3' => 'A3',
                'a4' => 'A4',
            ];
            $selected_output_pdf = array_slice($max_output_pdf, str_replace('a', '', $fanchart_max_output_pdf), 5,true);

            $max_generation = $product->fanchart_max_generation;


            if($tree != null){
                return view('users.fanchart.index',[
                    'id'                => uniqid(),
                    'title'             => 'Fan chart',
                    'ajaxUrl'           => '',
                    'moduleName'        => 'module name',
                    'individual'        => 0,
                    'tree'              => 0,
                    'configuration'     => [],
                    'chartParams'       => json_encode($this->getChartParameters(0), JSON_THROW_ON_ERROR),
                    'stylesheets'       => $this->getStylesheets(),
                    'exportStylesheets' => [],
                    'javascript'        => asset('js/webtree/fan-chart.js'),
                    'showColorGradients'=> true,
                    'status'            => $tree->status,
                    'print_types' => $print_types,
                    'selected_output_png' => $selected_output_png,
                    'selected_output_pdf' => $selected_output_pdf,
                    'generation' => $tree->generation,
                    'template' => $tree->template,
                    'max_generation' => $max_generation,
                ]);
            }
            else{
                return view('users.fanchart.index',[
                    'print_types' => $print_types,
                    'selected_output_png' => $selected_output_png,
                    'selected_output_pdf' => $selected_output_pdf,
                    'generation' => $tree->generation,
                    'template' => $tree->template,
                    'max_generation' => $max_generation,
                ]);
            }

        
    }

    public function print(Request $request){
        $user = Auth::user();
        $tree = Tree::where("user_id",$user->id)->first();
        $current_payment = $user->has_payment();
        if($current_payment == false){
            return redirect()->route('users.dashboard');
        }
        $product = Product::where('id',$current_payment->product_id)->first();
        // limit reached
        if($tree->print_number >= $product->print_number){
            return response()->json(['error'=>true,'msg' => 'limit reached']);
        }
        else{
            $tree->print_number = $tree->print_number + 1;
            $tree->save();
            return response()->json(['error'=>false,'msg' => 'limit unreached']);
        }
        
    }

    private function getChartParameters($individual): array
    {
        return [
            'circlePadding' => '70',
            'rtl'             => true,
            'showImages'      => true,
            'showSilhouettes' => true,
            'labels' => [
                'zoom' => 'Use Ctrl + scroll to zoom in the view',
                'move' => 'Move the view with two fingers',
            ],
        ];
    }

    private function getStylesheets(): array
    {
        $stylesheets = [];

        $stylesheets[] = asset('js/webtree/css/fan-chart.css');
        $stylesheets[] = asset('js/webtree/css/svg.css');

        return $stylesheets;
    }

    /*
    protected function formatChildren($children)
    {
        $formattedChildren = [];

        foreach ($children as $child) {
            $formattedChild = [
                'id' => $child->id,
                'firstNames' => $child->firstname,
                'lastNames' => $child->lastname,
                'alternativeNames' => "",
                'name' => $child->firstname . ' ' . $child->lastname,
                'birth' => $child->birth,
                'death' => $child->death,
                'timespan' => $child->birth . '-' . $child->death,
                'sex' => $child->sex,
            ];

            if ($child->children->isNotEmpty()) {
                $formattedChild['children'] = $this->formatChildren($child->children);
            }

            $formattedChildren[] = $formattedChild;
        }

        return $formattedChildren;
    }

    protected function formatTree($node)
    {
        $formattedNode = [
            'id' => $node->id,
            'firstNames' => $node->firstname,
            'lastNames' => $node->lastname,
            'alternativeNames' => "",
            'name' => $node->firstname . ' ' . $node->lastname,
            'birth' => $node->birth,
            'death' => $node->death,
            'timespan' => $node->birth . '-' . $node->death,
            'sex' => $node->sex,
        ];

        if ($node->children->isNotEmpty()) {
            $formattedNode['children'] = $this->formatChildren($node->children);
        }

        return $formattedNode;
    }


    */
}
