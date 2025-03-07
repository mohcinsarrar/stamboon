<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\DataTables\UsersDataTable;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Pedigree;
use App\Models\Fantree;
use Gedcom\Parser as GedcomParser;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionEmail;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UsersDataTable $dataTable)
    {

        dd($dataTable);
        $total_users = User::role('user')->count();
        $new_users = User::role('user')->get()->filter(
            function ($user) {
                return $user->status()['status'] == 'New';
            }
        )->count();
        $active_users = User::role('user')->get()->filter(
            function ($user) {
                return $user->status()['status'] == 'Active';
            }
        )->count();
        $expired_users = User::role('user')->get()->filter(
            function ($user) {
                return $user->status()['status'] == 'Expired';
            }
        )->count();
        return $dataTable->render('admin.users.index',compact('total_users','new_users','expired_users','active_users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }


    private function get_gedcom_file($type = "pedigree", $user_id){
    
        if($type == 'pedigree'){
          $pedigree = Pedigree::where('user_id',$user_id)->first();
        }
        else{
          $pedigree = Fantree::where('user_id',$user_id)->first();
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

    private function parse_gedcom($file){


        $parser = new GedcomParser();
        $gedcom = $parser->parse($file);
    
        return $gedcom;
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //

        $user = User::findOrFail($id);

        // delete all user data pedigree
    
        /// get gedcom file
        $gedcom_file = $this->get_gedcom_file('pedigree',$user->id);
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
        $gedcom_file = $this->get_gedcom_file('fantree',$user->id);
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

        
        $user->delete();

        $title = "Your Account deleted forever";
        $user_fullname = $user->firstname. " " . $user->lastname;
        $content = "<b>Thank you for your journey in thestamboom.</b><br> all your data has been removed forever, you can create new account any time at thestamboom.com  ";
        Mail::to($user->email)->send(new SubscriptionEmail($title, $user_fullname, $content));

        return redirect()->back()->with('success','user deleted with success');
    }

    public function toggle_active(Request $request,string $id)
    {
        $user = User::findOrFail($id);

        if($user->active == 0){
            $user->active = 1;
            $user->save();
            return redirect()->back()->with('success','User Activated with success');
        }
        else{
            $user->active = 0;
            $user->save();
            DB::table('sessions')->where('user_id', $user->id)->delete();
            return redirect()->back()->with('success','User Deactivated with success');
        }

        
    }

    public function toggle_role(Request $request,string $id)
    {
        $user = User::findOrFail($id);

        if($user->hasRole('superuser') == true){
            $user->removeRole('superuser');
            $user->save();
            return redirect()->back()->with('success','User role changed to user with success');
        }
        else{
            $user->assignRole('superuser');
            $user->save();
            return redirect()->back()->with('success','User role changed to superuser with success');
        }

        
    }
}
