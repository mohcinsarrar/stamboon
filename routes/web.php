<?php

use Illuminate\Support\Facades\Route;

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
require_once __DIR__ .'/familytree.php';
require_once __DIR__ .'/auth.php';




Route::get('/', function(){
    return view('website.index');
});