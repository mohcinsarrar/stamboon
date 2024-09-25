<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactEmail;

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
require_once __DIR__ .'/fanchart.php';
require_once __DIR__ .'/pedigree.php';
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

    return view('website.index',compact('data','products'));
});


Route::post('/contact', function(Request $request){

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
        return redirect('/#contact')->with('success','Your message sent with success !!');
    }
    catch(Exception $e){
        return redirect('/#contact')->with('error','Unable send your message, try again !!');
    }
    
    return redirect('/#contact')->with('error','Unable send your message, try again !!');

})->name('contact');