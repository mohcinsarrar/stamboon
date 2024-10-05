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
use App\Models\Notification;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\DB;


class ProfileController extends Controller
{
  public function index(Request $request)
  {
    $user = Auth::user();
    

    return view('users.profile.index', compact('user'));
  }

  public function security(Request $request){
    $user = Auth::user();
    $devices = collect(
      DB::table(config('session.table', 'sessions'))
          ->where('user_id', auth()->user()->id)
          ->orderBy('last_activity', 'desc')
          ->get()
      )->map(
          function ($session) use ($request) {
              $agent = tap(new Agent, fn($agent) => $agent->setUserAgent($session->user_agent));

              return [
                  'id' => $session->id,
                  'agent'           => [
                      'platform' => $agent->platform(),
                      'browser'  => $agent->browser(),
                  ],
                  'ip'              => $session->ip_address,
                  'isCurrentDevice' => $session->id === $request->session()->getId(),
                  'lastActive'      => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
              ];
          }
      )->toArray();
    return view('users.profile.security', compact('user','devices'));
  }

  public function notifications(){
    return view('users.profile.notifications');
  }

  public function account_delete(){
    return view('users.profile.account_delete');
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
        'firstname' => 'required|string',
        'lastname' => 'required|string',
    ])->validate();

    // change firstname lastname and email
    $user->firstname = $request->firstname;
    $user->lastname = $request->lastname;
    $user->name = $request->firstname .' '.$request->lastname;
    $user->save();
    
    $changed = false;
    // update email if changed
    if($user->email != $request->email){
        $user->email = $request->email;
        $user->verification_code = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $user->email_verified_at = null;
        $user->sendEmailVerificationNotification();
        $user->save();
        $changed = true;
    }

    // update password if changed
    if($request->password != null){
        if(!Hash::check($request->password , $user->password)){
            $user->password = Hash::make($request->password);
            $user->save();
            $changed = true;
        }
        else{
            return redirect()->back()->with('error','New password must be different of your current password');
        }
    }

    if($changed == true){
        Auth::guard('web')->logout();

        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the login page
        return redirect()->route('login');
    }
    
    return redirect()->back()->with('success','Profile updated with success');

  }

  public function delete(Request $request){
    $user = Auth::user();
    $user->delete();
    return redirect()->route('login');
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

  public function session_delete(Request $request,$id)
    {
        DB::beginTransaction();
        try{
            $deleted = DB::table(config('session.table', 'sessions'))->where('id', $id)->delete();
            DB::commit();
            return redirect()->route('users.profile.security')->with('success','device successfully removed');
        }
        catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('users.profile.security')->with('error', "Error deleting device, try again later
            "); 
        }
    }
  


}
