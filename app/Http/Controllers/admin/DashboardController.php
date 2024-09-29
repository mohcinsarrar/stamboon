<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Carbon\Carbon;


class DashboardController extends Controller
{
  public function index()
  {
    return view('admin.dashboard.index');
  }

  public function notifications(){
    return view('admin.notifications.index');
  }

  public function notifications_markasread(Request $request){
    if($request->notifications != null){
      Notification::whereIn('id',$request->notifications)->update(['read_at'=> Carbon::now()]);
      return response()->json(['error'=>false,'msg' => 'notifications mark as read with success']);
    }
    else{
      return response()->json(['error'=>true,'msg' => 'no notification selected']);
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
