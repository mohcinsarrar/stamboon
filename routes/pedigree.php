<?php

use App\Http\Controllers\users\DashboardController;
use App\Http\Controllers\users\SubscriptionController;
use App\Http\Controllers\users\PedigreeController;
use App\Http\Controllers\users\ProfileController;
use App\Http\Controllers\users\NoteController;


Route::middleware(['auth:sanctum','verified','active','role:user','product_type:pedigree','payment_reminder'])
    ->name('users.')
    ->group( function(){
        Route::get('/pedigree', [PedigreeController::class,'index'])->name('pedigree.index');
        Route::get('/pedigree/settings', [PedigreeController::class,'settings'])->name('pedigree.settings');
        Route::get('/pedigree/getTree', [PedigreeController::class,'getTree'])->name('pedigree.getTree');
        Route::post('/pedigree/download', [PedigreeController::class,'download'])->name('pedigree.download');
        Route::post('/pedigree/editchartstatus', [PedigreeController::class,'editChartStatus'])->name('pedigree.editchartstatus');
        Route::get('/pedigree/getchartstatus', [PedigreeController::class,'getChartStatus'])->name('pedigree.getchartstatus');
        Route::get('/pedigree/getnotes', [NoteController::class,'index'])->name('pedigree.getnotes');
        Route::get('/pedigree/loadweapon', [NoteController::class,'loadweapon'])->name('fantree.loadweapon');

        Route::post('/pedigree/updatecount', [PedigreeController::class,'updatecount'])->name('pedigree.updatecount');
    });


Route::middleware(['auth:sanctum','verified','active','role:user','product_type:pedigree','payment_reminder','payment_end'])
    ->name('users.')
    ->group( function(){

        Route::post('/pedigree/saveimage', [PedigreeController::class,'saveimage'])->name('pedigree.saveimage');
        
        Route::post('/pedigree/settings', [PedigreeController::class,'settings'])->name('pedigree.settings');
        
        
        Route::post('/pedigree/importgedcom', [PedigreeController::class,'importgedcom'])->name('pedigree.importgedcom');
        



        Route::post('/pedigree/update', [PedigreeController::class,'update'])->name('pedigree.update');
        Route::post('/pedigree/delete', [PedigreeController::class,'delete'])->name('pedigree.delete');
        Route::post('/pedigree/addspouse', [PedigreeController::class,'addspouse'])->name('pedigree.addspouse');
        Route::post('/pedigree/addchild', [PedigreeController::class,'addchild'])->name('pedigree.addchild');
        Route::post('/pedigree/addperson', [PedigreeController::class,'addperson'])->name('pedigree.addperson');
        Route::post('/pedigree/getpersons', [PedigreeController::class,'getpersons'])->name('pedigree.getpersons');
        Route::post('/pedigree/orderspouses', [PedigreeController::class,'orderspouses'])->name('pedigree.orderspouses');
        Route::post('/pedigree/print', [PedigreeController::class,'print'])->name('pedigree.print');
        

        // notes
        Route::post('/pedigree/savenote', [NoteController::class,'save'])->name('pedigree.savenote');
        Route::post('/pedigree/editnoteposition', [NoteController::class,'edit_position'])->name('pedigree.editnoteposition');
        Route::post('/pedigree/editnotetext', [NoteController::class,'edit_text'])->name('pedigree.editnotetext');
        Route::post('/pedigree/deletenote', [NoteController::class,'delete'])->name('pedigree.deletenote');
        
        
        // add weapon inside NotereeController  
        Route::post('/pedigree/addweapon', [NoteController::class,'addweapon'])->name('fantree.addweapon');
        Route::post('/pedigree/deleteweapon', [NoteController::class,'deleteweapon'])->name('fantree.deleteweapon');
        

    

});