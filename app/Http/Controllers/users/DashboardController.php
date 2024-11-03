<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

use App\Models\Payment;
use App\Models\Product;
use App\Models\Tree;
use App\Models\Pedigree;

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


    $pedigree = Pedigree::where('user_id',$user->id)->first();
    $fanchart = Tree::where("user_id",$user->id)->first();

    return view('users.dashboard.index',compact('video', 'payment','pedigree','fanchart'));
  }
}
