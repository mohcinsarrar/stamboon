<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rules\File;


class ProfileController extends Controller
{
  public function index()
  {
    $user = Auth::user();

    return view('users.profile.index', compact('user'));
  }

  public function edit(Request $request){

    $user = Auth::user();
    $input = $request->except(['_token']);
    Validator::make($input, [
        'email' => [
            'required',
            'string',
            'email',
            'max:255',
            Rule::unique('users')->ignore($user->id)
        ],
        'password' => ['nullable','confirmed',Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
        'fullname' => 'required|string',
        'image' => ['nullable',File::image()->max(3*1024*1024)]
    ])->validate();
    //dd(Hash::make($request->password));


    // update name
    $user->name = $request->fullname;
    $user->save();
    
    // update image if changed
    if($request->hasFile('image') && $request->file('image')->isValid()) {
        // delete old image if exist
        if($user->image != null){
            if(Storage::exists($user->image)){
                Storage::delete($user->image);
            }
        }
        $image = $request->file('image')->store('profile');
        $user->image = $image;
        $user->save();
    }  

    // update email if changed
    if($user->email != $request->email){
        $user->email = $request->email;
        $user->verification_code = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $user->email_verified_at = null;
        $user->sendEmailVerificationNotification();
        $user->save();
    }

    // update password if changed
    if($request->password != null){
        if(!Hash::check($request->password , $user->password)){
            $user->password = Hash::make($request->password);
            $user->save();
        }
        else{
            return redirect()->back()->with('error','New password must be different of your current password');
        }
    }
    
    return redirect()->back()->with('success','Profile updated with success');

  }



}
