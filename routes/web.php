<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;

use App\Mail\ContactEmail;
use App\Mail\SubscriptionEmail;
use Illuminate\Support\Facades\Mail;

use Illuminate\Support\Arr;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
require_once __DIR__ .'/auth.php';
require_once __DIR__ .'/users.php';
require_once __DIR__ .'/admin.php';
require_once __DIR__ .'/pedigree.php';
require_once __DIR__ .'/fantree.php';
require_once __DIR__ .'/utils.php';





Route::get('/', function(){
    // Path to the JSON file
    $path = resource_path('views/website/website.json');

    // Get the file contents
    $json = File::get($path);

    // Decode the JSON data
    $data = json_decode($json, true);

    // get products
    $products = Product::orderByRaw('CONVERT(price, SIGNED) asc')->get();

    // Path to the JSON file
    $path = resource_path('views/website/footer_pages.json');

    // Get the file contents
    $json = File::get($path);

    // Decode the JSON data
    $pages = json_decode($json, true);

    $pages = $pages['pages'];

    return view('website.index',compact('data','products','pages'));
})->name('webshop.index');


Route::get('/pages/{slug}', function($slug){

    // Path to the JSON file
    $path = resource_path('views/website/website.json');

    // Get the file contents
    $json = File::get($path);

    // Decode the JSON data
    $data = json_decode($json, true);

    // Path to the JSON file
    $path = resource_path('views/website/footer_pages.json');

    // Get the file contents
    $json = File::get($path);

    // Decode the JSON data
    $pages = json_decode($json, true);

    $pages = $pages['pages'];

    $page = collect($pages)->firstWhere('slug', $slug);

    if($page == null){
        abort(404, 'Page not found.'); 
    }
    
    return view('website.pages',compact('data','page','pages'));
})->name('webshop.pages');


Route::post('/contact', function(Request $request){


    // User's IP address or email as a unique identifier
    $userKey = 'contact-email:' . $request->ip(); // or $request->email for email-based

    // Define the limit and time period
    $maxAttempts = 2; // Maximum emails allowed
    $decayMinutes = 60; // Time period (in minutes)

    // Check the current email count for the user
    if (Cache::has($userKey) && Cache::get($userKey) >= $maxAttempts) {
        return redirect('/#contact')->with('error', 'You have reached the email limit. Please try again after 1 hour.');
    }


    // validate
    $inputs = $request->except('_token');

    $validator = Validator::make($inputs, [
        'name' => 'required|string',
        'email' => 'required|email',
        'subject' => 'required|string',
        'message' => 'required|string|min:30',
      ]);

    if ($validator->fails()) {
        return redirect('/#contact')->with('error','Unable send your message, try again !!');
    }


    

    try{
        Mail::to("admin@admin.com")->send(new ContactEmail($request->name, $request->email, $request->subject, $request->message));

        // Increment the user's email count in cache
        Cache::increment($userKey);
        Cache::put($userKey, Cache::get($userKey), $decayMinutes * 60); // Set the cache expiration time

        return redirect('/#contact')->with('success','Your message sent with success !!');
    }
    catch(Exception $e){
        return redirect('/#contact')->with('error','Unable to send your message, try again !!');
    }
    
    return redirect('/#contact')->with('error','Unable to send your message, try again !!');

})->name('contact');



Route::get('/testemail', function(Request $request){
    $title = "Your subscription to plan "."base product"." paid with success";
    $user_fullname = "sarrar mohcin";
    $content = "Thank you for signing up. We're excited to have you on board! <br> you subscription active until ". "2024";
    Mail::to("mohcin.sarrar2@gmail.com")->send(new SubscriptionEmail($title, $user_fullname, $content));
    echo "succes";

}

);