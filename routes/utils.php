<?php

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;

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

Route::get('/products/createseed', function () {
    $product = Product::create([
        'name' => 'Basic',
        'amount' => 30,
        'description' => 'Basic',
        'features' => ['Basic'],
    ]);

    $product = Product::create([
        'name' => 'Standard',
        'amount' => 100,
        'description' => 'Standard',
        'features' => ['Standard'],
    ]);

    $product = Product::create([
        'name' => 'Pro',
        'amount' => 150,
        'description' => 'Pro',
        'features' => ['Pro'],
    ]);


    return "products created";
})->name('prducts.createseed');