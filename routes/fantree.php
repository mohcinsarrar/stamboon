<?php

use App\Http\Controllers\users\DashboardController;
use App\Http\Controllers\users\SubscriptionController;
use App\Http\Controllers\users\FantreeController;
use App\Http\Controllers\users\ProfileController;
use App\Http\Controllers\users\NoteFantreeController;


Route::middleware(['auth:sanctum','verified','active','role:user','product_type:fantree','payment_reminder'])
    ->name('users.')
    ->group( function(){

        Route::get('/fantree/all', [FantreeController::class,'all'])->name('fantree.all')->middleware('role:superadmin');
        Route::get('/fantree/list', [FantreeController::class,'list'])->name('fantree.list');
        Route::post('/fantree/store', [FantreeController::class,'store_fantree'])->name('fantree.store');
        Route::put('/fantree/edit/{id}', [FantreeController::class,'edit_fantree'])->name('fantree.edit_fantree');
        Route::delete('/fantree/delete/{id}', [FantreeController::class,'delete_fantree'])->name('fantree.destroy');

        Route::get('/fantree/{fantree_id}', [FantreeController::class,'index'])->name('fantree.index');
        Route::get('/fantree/getTree/{fantree_id}', [FantreeController::class,'getTree'])->name('fantree.getTree');
        Route::post('/fantree/editchartstatus/{fantree_id}', [FantreeController::class,'editChartStatus'])->name('fantree.editchartstatus');
        Route::get('/fantree/getchartstatus/{fantree_id}', [FantreeController::class,'getChartStatus'])->name('fantree.getchartstatus');
        Route::post('/fantree/download/{fantree_id}', [FantreeController::class,'download'])->name('fantree.download');

        Route::get('/fantree/settings/{fantree_id}', [FantreeController::class,'settings'])->name('fantree.settings');

        Route::post('/fantree/updatecount/{fantree_id}', [FantreeController::class,'updatecount'])->name('fantree.updatecount');

        Route::get('/fantree/getnotes/{fantree_id}', [NoteFantreeController::class,'index'])->name('fantree.getnotes');

        Route::get('/fantree/loadweapon/{fantree_id}', [NoteFantreeController::class,'loadweapon'])->name('fantree.loadweapon');

        

    });



Route::middleware(['auth:sanctum','verified','active','role:user','product_type:fantree','payment_reminder','payment_end'])
    ->name('users.')
    ->group( function(){

        Route::post('/fantree/saveimage/{fantree_id}', [FantreeController::class,'saveimage'])->name('fantree.saveimage');
        Route::post('/fantree/deleteimage/{fantree_id}', [FantreeController::class,'deleteimage'])->name('fantree.deleteimage');

        
        
        Route::post('/fantree/settings/{fantree_id}', [FantreeController::class,'settings'])->name('fantree.settings');
        
        
        Route::post('/fantree/importgedcom/{fantree_id}', [FantreeController::class,'importgedcom'])->name('fantree.importgedcom');
        



        Route::post('/fantree/update/{fantree_id}', [FantreeController::class,'update'])->name('fantree.update');
        Route::post('/fantree/delete/{fantree_id}', [FantreeController::class,'delete'])->name('fantree.delete');
        Route::post('/fantree/addspouse/{fantree_id}', [FantreeController::class,'addspouse'])->name('fantree.addspouse');
        Route::post('/fantree/addparents/{fantree_id}', [FantreeController::class,'addparents'])->name('fantree.addparents');
        Route::post('/fantree/addperson/{fantree_id}', [FantreeController::class,'addperson'])->name('fantree.addperson');
        Route::post('/fantree/getpersons/{fantree_id}', [FantreeController::class,'getpersons'])->name('fantree.getpersons');
        Route::post('/fantree/orderspouses/{fantree_id}', [FantreeController::class,'orderspouses'])->name('fantree.orderspouses');
        Route::post('/fantree/print/{fantree_id}', [FantreeController::class,'print'])->name('fantree.print');
        

        // notes
        Route::post('/fantree/savenote/{fantree_id}', [NoteFantreeController::class,'save'])->name('fantree.savenote');
        Route::post('/fantree/editnoteposition/{fantree_id}', [NoteFantreeController::class,'edit_position'])->name('fantree.editnoteposition');
        Route::post('/fantree/editnotetext/{fantree_id}', [NoteFantreeController::class,'edit_text'])->name('fantree.editnotetext');
        Route::post('/fantree/deletenote', [NoteFantreeController::class,'delete'])->name('fantree.deletenote');
        

        // add weapon inside NoteFantreeController  
        Route::post('/fantree/addweapon/{fantree_id}', [NoteFantreeController::class,'addweapon'])->name('fantree.addweapon');
        Route::post('/fantree/deleteweapon/{fantree_id}', [NoteFantreeController::class,'deleteweapon'])->name('fantree.deleteweapon');
        
        Route::post('/fantree/editweaponposition/{fantree_id}', [NoteFantreeController::class,'editweaponposition'])->name('fantree.editweaponposition');
        

});