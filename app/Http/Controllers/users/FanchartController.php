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
            return response()->json(['error'=>false,'msg' => 'File Added','redirect_url'=>route('trees.index')]);

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
                ]);
            }
            else{
                return view('users.fanchart.index',[]);
            }

        
    }

    private function getChartParameters($individual): array
    {
        return [
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
