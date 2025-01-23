<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use App\Services\AdminDashboardService;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;


class DashboardController extends Controller
{
  public function index()
  {

    $adminDashboardService = new AdminDashboardService();

    // total users
    $total_users = $adminDashboardService->total_users();
    $last_month_users = $adminDashboardService->last_month_users();

    // total sales
    $total_sales = $adminDashboardService->total_sales();
    $last_month_sales = $adminDashboardService->last_month_sales();

    $total_fantree_print = $adminDashboardService->total_fantree_print();
    $total_pedigree_print = $adminDashboardService->total_pedigree_print();

    $products = $adminDashboardService->get_products();

    $purpleColor = '#836AF9';
    $yellowColor = '#ffe800';
    $cyanColor = '#28dac6';
    $orangeColor = '#FF8132';
    $orangeLightColor = '#FDAC34';
    $oceanBlueColor = '#299AFF';
    $greyColor = '#4F5D70';
    $greyLightColor = '#EDF1F4';
    $blueColor = '#2B9AFF';
    $blueLightColor = '#84D0FF';

    $colors = [
      $orangeColor,
      $greyColor,
      $blueColor,
      $orangeLightColor,
      $greyLightColor,
      $blueLightColor,
      $oceanBlueColor,
      $blueLightColor,
      $purpleColor,
      $yellowColor,
      $cyanColor,
    ];

    $last13j_sales = $adminDashboardService->last13j_sales();

    return view('admin.dashboard.index',compact(
      'total_users',
      'last_month_users',
      'total_sales',
      'last_month_sales',
      'total_fantree_print',
      'total_pedigree_print',
      'products',
      'colors',
      'last13j_sales'
    ));
  }

  public function add_country(Request $request){
    dd($request);
  }

  public function settings(Request $request){

    $path = resource_path('views/admin/settings/settings.json');
    
    // Get the file contents
    $json = File::get($path);

    // Decode the JSON data
    $data = json_decode($json, true);

    
    return view('admin.settings.index',compact('data'));
  }

  public function settings_update(Request $request){

    $path = resource_path('views/admin/settings/settings.json');
    
    // Get the file contents
    $json = File::get($path);

    // Decode the JSON data
    $data = json_decode($json, true);


    if($request->can_register != null){
      $data['can_register'] = true;
    }
    else{
      $data['can_register'] = false;
    }

    $path = resource_path('views/admin/settings/settings.json');

    $newJson = json_encode($data, JSON_PRETTY_PRINT);

    File::put($path, $newJson);
    
    return redirect()->back()->with('success', "Settings updated");

  }

  public function documentations(){
    $path = resource_path('views/admin/documentations/documentations.json');

    // Get the file contents
    $json = File::get($path);

    // Decode the JSON data
    $data = json_decode($json, true);


    return view('admin.documentations.index',compact('data'));
  }

  public function documentations_destory(Request $request, $id){



    $path = resource_path('views/admin/documentations/documentations.json');

    // Get the file contents
    $json = File::get($path);

    // Decode the JSON data
    $data = json_decode($json, true);

    $documents = $data['documents'];

    foreach ($documents as $key => $document) {
      if ($document['id'] == $id) {
          // delete file
          if (Storage::exists($document['file'])) {
            Storage::delete($document['file']);
          }
          unset($documents[$key]);

          break; // Optional: remove this if you want to delete all matches of the ID
      }
    }

    
    
    // Reindex array to remove gaps in keys if necessary
    $documents = array_values($documents);

    $data['documents'] = $documents;

    $newJson = json_encode($data, JSON_PRETTY_PRINT);

    File::put($path, $newJson);

    return redirect()->back()->with('success','document deleted with success');

  }

  private function titleExists($documents, $title) {
    foreach ($documents as $document) {
        if ($document['title'] == $title) {
            return true; // Title exists
        }
    }
    return false; // Title does not exist
  }

  private function titleExistsExcludingId($documents, $title, $excludeId) {
    foreach ($documents as $document) {
        // Skip the document with the excluded ID
        if ($document['id'] == $excludeId) {
            continue;
        }
        // Check if the title matches
        if ($document['title'] === $title) {
            return true; // Title exists
        }
    }
    return false; // Title does not exist
  }

  public function documentations_store(Request $request){

    $inputs = $request->except('_token');

    Validator::make($inputs, [
      'title' => 'required|string',
      'file' => ['required','file'],
    ])->validate();

    $path = resource_path('views/admin/documentations/documentations.json');

    // Get the file contents
    $json = File::get($path);

    // Decode the JSON data
    $data = json_decode($json, true);

    $documents = $data['documents'];

    if ($this->titleExists($documents, $request->title)) {
      return redirect()->back()->with('error','title already exist');
    } 

    $maxId = 0;
    if(count($documents) == 0){
      $maxId = 0;
    }
    else{
      $maxId = max(array_column($documents, 'id'));
    }
    


    $title = $request->title;
    $file = null;

    // update file if loaded
    if($request->hasFile('file') && $request->file('file')->isValid()) {
      // store the new image
      $fileName = Str::slug($title ). '.' . $request->file('file')->getClientOriginalExtension();
      $file = $request->file('file')->storeAs('documentations', $fileName);
    }

    if($file == null){
      return redirect()->back()->with('error','cant add document');
    }

    // save new document

    $newDocument = [
      "id" => $maxId+1,
      "title" => $title,
      "file" => $file
    ];

    $data['documents'][] = $newDocument;

    $newJson = json_encode($data, JSON_PRETTY_PRINT);

    File::put($path, $newJson);

    return redirect()->back()->with('success','document added with success');

  }

  public function documentations_update(Request $request){

    $inputs = $request->except('_token');
    Validator::make($inputs, [
      'id' => 'required',
      'title' => 'required|string',
      'file' => ['nullable','file'],
    ])->validate();
    

    $path = resource_path('views/admin/documentations/documentations.json');

    // Get the file contents
    $json = File::get($path);

    // Decode the JSON data
    $data = json_decode($json, true);
    $documents = $data['documents'];


    if ($this->titleExistsExcludingId($documents, $request->title, $request->id)) {
      return redirect()->back()->with('error','title already exist');
    } 

    //store file
    $file = null;
    
    // update file if loaded
    if($request->hasFile('file') && $request->file('file')->isValid()) {
      // store the new image
      $fileName = Str::slug($request->title). '.' . $request->file('file')->getClientOriginalExtension();
      $file = $request->file('file')->storeAs('documentations', $fileName);
    }


    // ID of the item to update
    $idToUpdate = $request->id;

    // New values for the item
    

    // Find and update the item with the given ID
    foreach ($documents as $key => $document) {
        if ($document['id'] == $idToUpdate) {

            if($file != null){
              if (Storage::exists($document['file'])) {
                Storage::delete($document['file']);
              }

              $updatedData = [
                "id" => $idToUpdate,
                "title" => $request->title,
                "file" => $file
              ];

            }
            else{

              // Rename the file
              $newFileName = $document['file'];
              if (Storage::exists($document['file'])) {
                $newFileName = "documentations/" . Str::slug($request->title). '.' . pathinfo($document['file'], PATHINFO_EXTENSION);
                Storage::move($document['file'], $newFileName);
              }

              $updatedData = [
                "id" => $idToUpdate,
                "title" => $request->title,
                "file" => $newFileName
              ];
            }

            
            
            $documents[$key] = array_merge($document, $updatedData);
            break; // Optional: stop loop after updating the item
        }
    }

    $data['documents'] = $documents;

    $newJson = json_encode($data, JSON_PRETTY_PRINT);

    File::put($path, $newJson);

    return redirect()->back()->with('success','document updated with success');


  }

  public function notifications(){
    return view('admin.notifications.index');
  }

  public function notifications_markasread(Request $request){
    if($request->ajax()){
      if($request->notifications != null){
        Notification::whereIn('id',$request->notifications)->update(['read_at'=> Carbon::now()]);
        return response()->json(['error'=>false,'msg' => 'notifications mark as read with success']);
      }
      else{
        return response()->json(['error'=>true,'msg' => 'no notification selected']);
      }
    }
    else{
      if($request->notification != null){
        Notification::where('id',$request->notification)->update(['read_at'=> Carbon::now()]);
        return redirect()->route('admin.notifications')->with('success','notification mark as read with success');
      }
      else{
        return redirect()->route('admin.notifications')->with('error','no notification selected');
      }
    }
  }



  public function notifications_delete(Request $request){
    if($request->notifications != null){
      Notification::whereIn('id',$request->notifications)->delete();
      return response()->json(['error'=>false,'msg' => 'notifications deleted with success']);
    }
    else{
      return response()->json(['error'=>true,'msg' => 'no notification selected']);
    }
  }

  public function notifications_load(Request $request){
    $user = Auth::user();
    $notifications = $user->getNotification();
    return response()->json(['error'=>false,'notifications' => $notifications]);
  }
}
