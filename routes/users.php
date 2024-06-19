<?php

use App\Http\Controllers\users\DashboardController;
use App\Http\Controllers\users\SubscriptionController;
use App\Http\Controllers\users\FanchartController;
use App\Http\Controllers\users\ProfileController;





Route::middleware(['auth:sanctum','verified','active','role:user'])
    ->name('users.')
    ->group( function(){

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('subscription', [SubscriptionController::class, 'index'])->name('subscription.index')->withoutMiddleware('active');
    Route::get('subscription/payment/order/{id}', [SubscriptionController::class, 'payment'])->name('subscription.payment')->withoutMiddleware('active');
    Route::get('subscription/payment/success', [SubscriptionController::class, 'success'])->name('subscription.success')->withoutMiddleware('active');


    // fanchart
    Route::get('/fanchart', [FanchartController::class,'index'])->name('fanchart.index');
    Route::get('/fanchart/get/{generation}', [FanchartController::class,'get_tree'])->name('fanchart.get_tree');
    Route::post('/fanchart/updateperson', [FanchartController::class,'updateperson'])->name('fanchart.updateperson');
    Route::post('/fanchart/deleteperson', [FanchartController::class,'deleteperson'])->name('fanchart.deleteperson');
    Route::post('/fanchart/addperson', [FanchartController::class,'addperson'])->name('fanchart.addperson');
    Route::post('/fanchart/saveimage', [FanchartController::class,'saveimage'])->name('fanchart.saveimage');
    Route::post('/fanchart/updateimageplacholder', [FanchartController::class,'updateimageplacholder'])->name('fanchart.updateimageplacholder');
    Route::post('/fanchart/importexcel', [FanchartController::class,'importexcel'])->name('fanchart.importexcel');
    Route::post('/fanchart/send/', [FanchartController::class,'send'])->name('fanchart.send');

    // profile
    Route::get('/profile', [ProfileController::class,'index'])->name('profile.index');
    Route::post('/profile/edit', [ProfileController::class,'edit'])->name('profile.edit');

});