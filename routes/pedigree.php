<?php

use App\Http\Controllers\users\DashboardController;
use App\Http\Controllers\users\SubscriptionController;
use App\Http\Controllers\users\PedigreeController;
use App\Http\Controllers\users\ProfileController;





Route::middleware(['auth:sanctum','verified','active','role:user'])
    ->name('users.')
    ->group( function(){

        Route::post('/pedigree/saveimage', [PedigreeController::class,'saveimage'])->name('pedigree.saveimage');
        Route::get('/pedigree', [PedigreeController::class,'index'])->name('pedigree.index');
        Route::get('/pedigree/settings', [PedigreeController::class,'settings'])->name('pedigree.settings');
        Route::post('/pedigree/settings', [PedigreeController::class,'settings'])->name('pedigree.settings');
        Route::get('/pedigree/getTree', [PedigreeController::class,'getTree'])->name('pedigree.getTree');
        Route::post('/pedigree/importgedcom', [PedigreeController::class,'importgedcom'])->name('pedigree.importgedcom');

        Route::post('/pedigree/update', [PedigreeController::class,'update'])->name('pedigree.update');
        Route::post('/pedigree/delete', [PedigreeController::class,'delete'])->name('pedigree.delete');
        Route::post('/pedigree/addspouse', [PedigreeController::class,'addspouse'])->name('pedigree.addspouse');
        Route::post('/pedigree/addchild', [PedigreeController::class,'addchild'])->name('pedigree.addchild');
        Route::post('/pedigree/addperson', [PedigreeController::class,'addperson'])->name('pedigree.addperson');



    

});