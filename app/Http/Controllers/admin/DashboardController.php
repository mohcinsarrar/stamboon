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

    $total_fanchart_print = $adminDashboardService->total_fanchart_print();
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

    return view('admin.dashboard.index',compact(
      'total_users',
      'last_month_users',
      'total_sales',
      'last_month_sales',
      'total_fanchart_print',
      'total_pedigree_print',
      'products',
      'colors'
    ));
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
