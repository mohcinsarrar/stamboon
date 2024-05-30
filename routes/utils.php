<?php

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;


Route::get('/roles/create', function () {
    $role = Role::create(['name' => 'admin']);
    $role = Role::create(['name' => 'user']);
    $role = Role::create(['name' => 'superadmin']);
    return "routes created";
})->name('roles.create');


Route::get('/admin/create', function () {
    $user = User::create([
        'name' => "admin",
        'email' => 'admin@admin.com',
        'password' => Hash::make("rootroot"),
        'email_verified_at' => Carbon::now(),
        'verification_code' => str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT)
    ]);

    $user->assignRole("admin");
    $user->save();

    return "admin created";
})->name('admin.create');