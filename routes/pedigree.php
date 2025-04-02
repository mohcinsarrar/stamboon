<?php

use App\Http\Controllers\users\DashboardController;
use App\Http\Controllers\users\SubscriptionController;
use App\Http\Controllers\users\PedigreeController;
use App\Http\Controllers\users\ProfileController;
use App\Http\Controllers\users\NoteController;


Route::middleware(['auth:sanctum','verified','active','role:user','product_type:pedigree','payment_reminder'])
    ->name('users.')
    ->group( function(){

        Route::get('/pedigree/all', [PedigreeController::class,'all'])->name('pedigree.all')->middleware('role:superadmin');
        Route::get('/pedigree/list', [PedigreeController::class,'list'])->name('pedigree.list');
        Route::post('/pedigree/store', [PedigreeController::class,'store_pedigree'])->name('pedigree.store');
        Route::put('/pedigree/edit/{id}', [PedigreeController::class,'edit_pedigree'])->name('pedigree.edit_pedigree');
        Route::delete('/pedigree/delete/{id}', [PedigreeController::class,'delete_pedigree'])->name('pedigree.destroy');

        Route::get('/pedigree/{pedigree_id}', [PedigreeController::class,'index'])->name('pedigree.index');
        Route::get('/pedigree/settings/{pedigree_id}', [PedigreeController::class,'settings'])->name('pedigree.settings');
        Route::get('/pedigree/getTree/{pedigree_id}', [PedigreeController::class,'getTree'])->name('pedigree.getTree');
        Route::post('/pedigree/download/{pedigree_id}', [PedigreeController::class,'download'])->name('pedigree.download');
        Route::post('/pedigree/editchartstatus/{pedigree_id}', [PedigreeController::class,'editChartStatus'])->name('pedigree.editchartstatus');
        Route::get('/pedigree/getchartstatus/{pedigree_id}', [PedigreeController::class,'getChartStatus'])->name('pedigree.getchartstatus');
        Route::get('/pedigree/getnotes/{pedigree_id}', [NoteController::class,'index'])->name('pedigree.getnotes');
        Route::get('/pedigree/loadweapon/{pedigree_id}', [NoteController::class,'loadweapon'])->name('pedigree.loadweapon');

        Route::post('/pedigree/updatecount/{pedigree_id}', [PedigreeController::class,'updatecount'])->name('pedigree.updatecount');
    });


Route::middleware(['auth:sanctum','verified','active','role:user','product_type:pedigree','payment_reminder','payment_end'])
    ->name('users.')
    ->group( function(){

        Route::post('/pedigree/saveimage/{pedigree_id}', [PedigreeController::class,'saveimage'])->name('pedigree.saveimage');
        
        Route::post('/pedigree/settings/{pedigree_id}', [PedigreeController::class,'settings'])->name('pedigree.settings');
        
        
        Route::post('/pedigree/importgedcom/{pedigree_id}', [PedigreeController::class,'importgedcom'])->name('pedigree.importgedcom');
        



        Route::post('/pedigree/update/{pedigree_id}', [PedigreeController::class,'update'])->name('pedigree.update');
        Route::post('/pedigree/delete/{pedigree_id}', [PedigreeController::class,'delete'])->name('pedigree.delete');
        Route::post('/pedigree/addspouse/{pedigree_id}', [PedigreeController::class,'addspouse'])->name('pedigree.addspouse');
        Route::post('/pedigree/addancestor/{pedigree_id}', [PedigreeController::class,'addancestor'])->name('pedigree.addancestor');
        Route::post('/pedigree/addchild/{pedigree_id}', [PedigreeController::class,'addchild'])->name('pedigree.addchild');
        Route::post('/pedigree/addperson/{pedigree_id}', [PedigreeController::class,'addperson'])->name('pedigree.addperson');
        Route::post('/pedigree/getpersons/{pedigree_id}', [PedigreeController::class,'getpersons'])->name('pedigree.getpersons');
        Route::post('/pedigree/orderspouses/{pedigree_id}', [PedigreeController::class,'orderspouses'])->name('pedigree.orderspouses');
        Route::post('/pedigree/print/{pedigree_id}', [PedigreeController::class,'print'])->name('pedigree.print');
        

        // notes
        Route::post('/pedigree/savenote/{pedigree_id}', [NoteController::class,'save'])->name('pedigree.savenote');
        Route::post('/pedigree/editnoteposition/{pedigree_id}', [NoteController::class,'edit_position'])->name('pedigree.editnoteposition');
        Route::post('/pedigree/editnotetext/{pedigree_id}', [NoteController::class,'edit_text'])->name('pedigree.editnotetext');
        Route::post('/pedigree/deletenote', [NoteController::class,'delete'])->name('pedigree.deletenote');
        
        
        // add weapon inside NotereeController  
        Route::post('/pedigree/addweapon/{pedigree_id}', [NoteController::class,'addweapon'])->name('pedigree.addweapon');
        Route::post('/pedigree/deleteweapon/{pedigree_id}', [NoteController::class,'deleteweapon'])->name('pedigree.deleteweapon');
        
        Route::post('/pedigree/editweaponposition/{pedigree_id}', [NoteController::class,'editweaponposition'])->name('pedigree.editweaponposition');


    

});