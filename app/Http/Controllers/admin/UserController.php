<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\DataTables\UsersDataTable;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(UsersDataTable $dataTable)
    {
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $user = User::findOrFail($id);
        $user->delete();

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
}
