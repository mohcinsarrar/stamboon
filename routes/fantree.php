<?php

use App\Http\Controllers\users\DashboardController;
use App\Http\Controllers\users\SubscriptionController;
use App\Http\Controllers\users\FantreeController;
use App\Http\Controllers\users\ProfileController;
use App\Http\Controllers\users\NoteFantreeController;


Route::middleware(['auth:sanctum','verified','active','role:user','product_type:fantree','payment_reminder'])
    ->name('users.')
    ->group( function(){


        
        Route::get('/fantree', [FantreeController::class,'index'])->name('fantree.index');
        Route::get('/fantree/getTree', [FantreeController::class,'getTree'])->name('fantree.getTree');
        Route::post('/fantree/editchartstatus', [FantreeController::class,'editChartStatus'])->name('fantree.editchartstatus');
        Route::get('/fantree/getchartstatus', [FantreeController::class,'getChartStatus'])->name('fantree.getchartstatus');
        Route::post('/fantree/download', [FantreeController::class,'download'])->name('fantree.download');

        Route::get('/fantree/settings', [FantreeController::class,'settings'])->name('fantree.settings');

        Route::post('/fantree/updatecount', [FantreeController::class,'updatecount'])->name('fantree.updatecount');

        Route::get('/fantree/getnotes', [NoteFantreeController::class,'index'])->name('fantree.getnotes');

        Route::get('/fantree/loadweapon', [NoteFantreeController::class,'loadweapon'])->name('fantree.loadweapon');

        

    });



Route::middleware(['auth:sanctum','verified','active','role:user','product_type:fantree','payment_reminder','payment_end'])
    ->name('users.')
    ->group( function(){

        Route::post('/fantree/saveimage', [FantreeController::class,'saveimage'])->name('fantree.saveimage');
        Route::post('/fantree/deleteimage', [FantreeController::class,'deleteimage'])->name('fantree.deleteimage');

        
        
        Route::post('/fantree/settings', [FantreeController::class,'settings'])->name('fantree.settings');
        
        
        Route::post('/fantree/importgedcom', [FantreeController::class,'importgedcom'])->name('fantree.importgedcom');
        



        Route::post('/fantree/update', [FantreeController::class,'update'])->name('fantree.update');
        Route::post('/fantree/delete', [FantreeController::class,'delete'])->name('fantree.delete');
        Route::post('/fantree/addspouse', [FantreeController::class,'addspouse'])->name('fantree.addspouse');
        Route::post('/fantree/addparents', [FantreeController::class,'addparents'])->name('fantree.addparents');
        Route::post('/fantree/addperson', [FantreeController::class,'addperson'])->name('fantree.addperson');
        Route::post('/fantree/getpersons', [FantreeController::class,'getpersons'])->name('fantree.getpersons');
        Route::post('/fantree/orderspouses', [FantreeController::class,'orderspouses'])->name('fantree.orderspouses');
        Route::post('/fantree/print', [FantreeController::class,'print'])->name('fantree.print');
        

        // notes
        Route::post('/fantree/savenote', [NoteFantreeController::class,'save'])->name('fantree.savenote');
        Route::post('/fantree/editnoteposition', [NoteFantreeController::class,'edit_position'])->name('fantree.editnoteposition');
        Route::post('/fantree/editnotetext', [NoteFantreeController::class,'edit_text'])->name('fantree.editnotetext');
        Route::post('/fantree/deletenote', [NoteFantreeController::class,'delete'])->name('fantree.deletenote');
        

        // add weapon inside NoteFantreeController  
        Route::post('/fantree/addweapon', [NoteFantreeController::class,'addweapon'])->name('fantree.addweapon');
        Route::post('/fantree/deleteweapon', [NoteFantreeController::class,'deleteweapon'])->name('fantree.deleteweapon');
        
        Route::post('/fantree/editweaponposition', [NoteFantreeController::class,'editweaponposition'])->name('fantree.editweaponposition');
        

});