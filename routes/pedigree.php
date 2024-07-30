<?php

use App\Http\Controllers\users\DashboardController;
use App\Http\Controllers\users\SubscriptionController;
use App\Http\Controllers\users\PedigreeController;
use App\Http\Controllers\users\ProfileController;





Route::middleware(['auth:sanctum','verified','active','role:user'])
    ->name('users.')
    ->group( function(){
        Route::get('/pedigree', [PedigreeController::class,'index'])->name('pedigree.index');
        Route::get('/pedigree2', [PedigreeController::class,'index2'])->name('pedigree.index2');
        Route::get('/pedigree3', [PedigreeController::class,'index3'])->name('pedigree.index3');

        Route::get('/pedigree/getTree', [PedigreeController::class,'getTree'])->name('pedigree.getTree');
        Route::post('/pedigree/importgedcom', [PedigreeController::class,'importgedcom'])->name('pedigree.importgedcom');

        Route::post('/pedigree/update', [PedigreeController::class,'update'])->name('pedigree.update');
    

});