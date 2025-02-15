<?php

namespace App\Http\Controllers\superuser;

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
use Illuminate\Support\Facades\File as FileStorage;
use LaravelCountries;
use App\Models\Pedigree;
use App\Models\Fantree;
use Gedcom\Parser as GedcomParser;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionEmail;
class ProfileController extends Controller
{
  public function index(Request $request)
  {
    $user = Auth::user();
    $countries = LaravelCountries::getCountries()->getData();
    return view('users.profile.index', compact('user','countries'));
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
        'address' => 'required|string',
        'city' => 'required|string',
        'country' => 'required|string',
    ])->validate();

    // change firstname lastname and email
    $user->firstname = $request->firstname;
    $user->lastname = $request->lastname;
    $user->address = $request->address;
    $user->city = $request->city;
    $user->country = $request->country;
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

  private function parse_gedcom($file){


    $parser = new GedcomParser();
    $gedcom = $parser->parse($file);

    return $gedcom;

}

  private function get_gedcom_file($type = "pedigree"){
    
    if($type == 'pedigree'){
      $pedigree = Pedigree::where('user_id',Auth::user()->id)->first();
    }
    else{
      $pedigree = Fantree::where('user_id',Auth::user()->id)->first();
    }
    
    if($pedigree == null){
        return null;
    }
    if($pedigree->gedcom_file == null){
      return null;
  }

    if (!Storage::disk('local')->exists($pedigree->gedcom_file)) {
        return null;
    } 
    
    $file = Storage::disk('local')->get($pedigree->gedcom_file);

    return $pedigree->gedcom_file;

}

private function get_gedcom($gedcom_file){

  $gedcom = $this->parse_gedcom('storage/'.$gedcom_file);

  return $gedcom;

}

  public function delete(Request $request){

    $user = Auth::user();

    // delete all user data pedigree

    /// get gedcom file
    $gedcom_file = $this->get_gedcom_file();
    if($gedcom_file != null){
      /// get gedcom object
      $gedcom = $this->get_gedcom($gedcom_file);
      /// get all indis
      $indis = $gedcom->getIndi();
      /// iterate over all indis and delete photo if exist
      foreach($indis as $indi){
            $note = $indi->getNote();

            $photo = null;
            if($note != null and $note != []){
                $photo = $note[0]->getNote();
            }

            if($photo != null){
              if (Storage::exists('portraits/'.$photo)) {
                  Storage::delete('portraits/'.$photo);
              }
            }
      }

      /// delete gedcom file
      if (Storage::exists('gedcoms/'.$gedcom_file)) {
        Storage::delete('gedcoms/'.$gedcom_file);
      }
    }

    // delete all user data fantree

    /// get gedcom file
    $gedcom_file = $this->get_gedcom_file("fantree");
    if($gedcom_file != null){
      /// get gedcom object
      $gedcom = $this->get_gedcom($gedcom_file);
      /// get all indis
      $indis = $gedcom->getIndi();
      /// iterate over all indis and delete photo if exist
      foreach($indis as $indi){
            $note = $indi->getNote();

            $photo = null;
            if($note != null and $note != []){
                $photo = $note[0]->getNote();
            }

            if($photo != null){
              if (Storage::exists('portraits_fantree/'.$photo)) {
                  Storage::delete('portraits_fantree/'.$photo);
              }
            }
      }

      /// delete gedcom file
      if (Storage::exists('fantree_gedcoms/'.$gedcom_file)) {
        Storage::delete('fantree_gedcoms/'.$gedcom_file);
      }
    }
    

    $title = "Your Account deleted forever";
    $user_fullname = $user->firstname. " " . $user->lastname;
    $content = "<b>Thank you for your journey in thestamboom.</b><br> all your data has been removed forever, you can create new account any time at thestamboom.com  ";
    Mail::to($user->email)->send(new SubscriptionEmail($title, $user_fullname, $content));

    $user->delete();

    

    return redirect()->route('login');
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
        return redirect()->route('users.profile.notifications')->with('success','notification mark as read with success');
      }
      else{
        return redirect()->route('users.profile.notifications')->with('error','no notification selected');
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


    public function documentations(Request $request){

      $path = resource_path('views/admin/documentations/documentations.json');

      // Get the file contents
      $json = FileStorage::get($path);

      // Decode the JSON data
      $data = json_decode($json, true);
      $documents = $data['documents'];

      return view('users.documentations.index',compact('documents'));
    }
  


}
