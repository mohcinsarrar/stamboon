<?php

use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

Route::get('/invoice', function () {
    $path = resource_path('views/website/website.json');
    $json = File::get($path);
    $data = json_decode($json, true);

    $logo_path = $data['colors']['logo'];
    $logo_file = Storage::get($logo_path);


    $base64Logo = base64_encode($logo_file);
    $mimeTypeLogo = Storage::mimeType($logo_path);
    $logo = 'data:' . $mimeTypeLogo . ';base64,' . $base64Logo;
    return view("users.subscription.invoice",compact('logo'));
});

Route::get('/invoice/pdf', function () {
    $path = resource_path('views/website/website.json');
    $json = File::get($path);
    $data = json_decode($json, true);

    $logo_path = $data['colors']['logo'];
    $logo_file = Storage::get($logo_path);


    $base64Logo = base64_encode($logo_file);
    $mimeTypeLogo = Storage::mimeType($logo_path);
    $logo = 'data:' . $mimeTypeLogo . ';base64,' . $base64Logo;

    $pdf = PDF::loadView('users.subscription.invoice',compact('logo'));
    return $pdf->download('invoice.pdf');
});


Route::get('/roles/create', function () {
    
    $role = Role::create(['name' => 'admin']);
    $role = Role::create(['name' => 'user']);
    $role = Role::create(['name' => 'superadmin']);
    $role = Role::create(['name' => 'superuser']);
    $role = Role::create(['name' => 'superuser2']);

    return "routes created";
})->name('roles.create');


Route::get('/admin/create', function () {
    $user = User::create([
        'name' => "admin",
        'email' => 'ron@thestamboom.com',
        'password' => Hash::make("ron@thestamboom"),
        'email_verified_at' => Carbon::now(),
        'verification_code' => str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT)
    ]);

    $user->assignRole("admin");
    $user->save();

    return "admin created";
})->name('admin.create');

Route::get('/products/createseed', function () {
    $product = Product::create([
        'name' => "Basic",
        'description' => "Basic",
        'fantree' => 1,
        'pedigree' => 0,
        'duration' => 3,
        'print_number' => 25,
        'price' => 14.95,
        'fantree_max_generation' => 7,
        'pedigree_max_generation' => 7,
        'max_nodes' => 50,
        'fantree_max_output_png' => "5",
        'pedigree_max_output_png' => "4",
        'fantree_max_output_pdf' => "a0",
        'pedigree_max_output_pdf' => "a1",
        'fantree_output_png' => 1,
        'pedigree_output_png' => 1,
        'fantree_output_pdf' => 1,
        'pedigree_output_pdf' => 1,
    ]);

    $product = Product::create([
        'name' => "Standard",
        'description' => "Standard",
        'fantree' => 1,
        'pedigree' => 1,
        'duration' => 3,
        'print_number' => 100,
        'price' => 25.95,
        'fantree_max_generation' => 7,
        'pedigree_max_generation' => 25,
        'max_nodes' => 100,
        'fantree_max_output_png' => "5",
        'pedigree_max_output_png' => "5",
        'fantree_max_output_pdf' => "a0",
        'pedigree_max_output_pdf' => "a0",
        'fantree_output_png' => 1,
        'pedigree_output_png' => 1,
        'fantree_output_pdf' => 1,
        'pedigree_output_pdf' => 1,
    ]);

    $product = Product::create([
        'name' => "Pro",
        'description' => "Pro",
        'fantree' => 1,
        'pedigree' => 1,
        'duration' => 12,
        'print_number' => 200,
        'price' => 49.95,
        'fantree_max_generation' => 7,
        'pedigree_max_generation' => 25,
        'max_nodes' => 100,
        'fantree_max_output_png' => "5",
        'pedigree_max_output_png' => "5",
        'fantree_max_output_pdf' => "a0",
        'pedigree_max_output_pdf' => "a0",
        'fantree_output_png' => 1,
        'pedigree_output_png' => 1,
        'fantree_output_pdf' => 1,
        'pedigree_output_pdf' => 1,
    ]);





    return "products created";
})->name('prducts.createseed');