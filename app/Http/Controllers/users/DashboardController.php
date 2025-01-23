<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use App\Models\Payment;
use App\Models\Product;
use App\Models\Pedigree;
use App\Models\Fantree;

class DashboardController extends Controller
{
  public function index()
  {

    // Path to the JSON file
    $path = resource_path('views/website/website.json');

    // Get the file contents
    $json = File::get($path);

    // Decode the JSON data
    $data = json_decode($json, true);

    $video = $data['hero']['videoUrl'];

    $user = Auth::user();
    $payment = $user->has_payment();
    $current_payment = $user->last_payment();
    if($current_payment == false){
      abort(404);
    }
    $product = Product::where('id',$current_payment->product_id)->first();

    $pedigree = Pedigree::where('user_id',$user->id)->first();
    $fantree = Fantree::where("user_id",$user->id)->first();

    return view('users.dashboard.index',compact('video', 'payment','pedigree','fantree','product'));
  }
}
