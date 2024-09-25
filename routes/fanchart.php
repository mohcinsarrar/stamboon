<?php

use App\Http\Controllers\users\DashboardController;
use App\Http\Controllers\users\SubscriptionController;
use App\Http\Controllers\users\FanchartController;
use App\Http\Controllers\users\ProfileController;





Route::middleware(['auth:sanctum','verified','active','role:user','product_type:fanchart'])
    ->name('users.')
    ->group( function(){

    // fanchart
    Route::get('/fanchart', [FanchartController::class,'index'])->name('fanchart.index');
    Route::get('/fanchart/get/{generation}', [FanchartController::class,'get_tree'])->name('fanchart.get_tree');
    Route::post('/fanchart/updateperson', [FanchartController::class,'updateperson'])->name('fanchart.updateperson');
    Route::post('/fanchart/change_generations', [FanchartController::class,'change_generations'])->name('fanchart.change_generations');
    Route::post('/fanchart/change_template', [FanchartController::class,'change_template'])->name('fanchart.change_template');
    Route::post('/fanchart/print', [FanchartController::class,'print'])->name('fanchart.print');
    Route::post('/fanchart/deleteperson', [FanchartController::class,'deleteperson'])->name('fanchart.deleteperson');
    Route::post('/fanchart/addperson', [FanchartController::class,'addperson'])->name('fanchart.addperson');
    Route::post('/fanchart/saveimage', [FanchartController::class,'saveimage'])->name('fanchart.saveimage');
    Route::post('/fanchart/updateimageplacholder', [FanchartController::class,'updateimageplacholder'])->name('fanchart.updateimageplacholder');
    Route::post('/fanchart/importexcel', [FanchartController::class,'importexcel'])->name('fanchart.importexcel');
    Route::post('/fanchart/send/', [FanchartController::class,'send'])->name('fanchart.send');


});