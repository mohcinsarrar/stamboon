<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

use App\Http\Requests\StoreClubRequest;
use Illuminate\Support\Facades\Auth;

use App\Notifications\VerifyCodeNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\Region;
use App\Models\Club;
use App\Models\User;

use Carbon\Carbon;

class AuthenticationController extends Controller
{


  public function verification_code(Request $request){
    $code = $request->otp;
    if($code != null){
      $user = auth()->user();
      if($code !== $user->verification_code){
          return redirect()->back()->with('error','Le code de vérification est incorrect, veuillez réessayer');
      }
      else{
          $user->email_verified_at = Carbon::now();
          $user->save();
      }
    }
    //dd($request);
    return redirect()->route('familytree.dashboard.index');

  }


}
