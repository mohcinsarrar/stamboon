<?php


use App\Http\Controllers\superadmin\DashboardController;
use App\Http\Controllers\superadmin\SubscriptionController;
use App\Http\Controllers\superadmin\FantreeController;
use App\Http\Controllers\superadmin\ProfileController;
use App\Http\Controllers\superadmin\NoteFantreeController;



Route::middleware(['auth:sanctum','verified','active','role:user'])
    ->name('superadmin.')
    ->prefix('superadmin')
    ->group( function(){
        // super user
        Route::get('/fantree/list', [FantreeController::class,'list'])->name('fantree.list')->middleware('role:superadmin');

        Route::get('/fantree/{fantree_id}', [FantreeController::class,'index'])->name('fantree.index')->middleware('role:superadmin');
        Route::get('/fantree/getTree/{fantree_id}', [FantreeController::class,'getTree'])->name('fantree.getTree')->middleware('role:superadmin');
        Route::post('/fantree/editchartstatus/{fantree_id}', [FantreeController::class,'editChartStatus'])->name('fantree.editchartstatus')->middleware('role:superadmin');
        Route::get('/fantree/getchartstatus/{fantree_id}', [FantreeController::class,'getChartStatus'])->name('fantree.getchartstatus')->middleware('role:superadmin');
        Route::post('/fantree/download/{fantree_id}', [FantreeController::class,'download'])->name('fantree.download')->middleware('role:superadmin');

        Route::get('/fantree/settings/{fantree_id}', [FantreeController::class,'settings'])->name('fantree.settings')->middleware('role:superadmin');

        Route::post('/fantree/updatecount/{fantree_id}', [FantreeController::class,'updatecount'])->name('fantree.updatecount')->middleware('role:superadmin');

        Route::get('/fantree/getnotes/{fantree_id}', [NoteFantreeController::class,'index'])->name('fantree.getnotes')->middleware('role:superadmin');

        Route::get('/fantree/loadweapon/{fantree_id}', [NoteFantreeController::class,'loadweapon'])->name('fantree.loadweapon')->middleware('role:superadmin');






        Route::post('/fantree/saveimage/{fantree_id}', [FantreeController::class,'saveimage'])->name('fantree.saveimage')->middleware('role:superadmin');
        Route::post('/fantree/deleteimage/{fantree_id}', [FantreeController::class,'deleteimage'])->name('fantree.deleteimage')->middleware('role:superadmin');

        
        
        Route::post('/fantree/settings/{fantree_id}', [FantreeController::class,'settings'])->name('fantree.settings')->middleware('role:superadmin');
        
        
        Route::post('/fantree/importgedcom/{fantree_id}', [FantreeController::class,'importgedcom'])->name('fantree.importgedcom')->middleware('role:superadmin');
        



        Route::post('/fantree/update/{fantree_id}', [FantreeController::class,'update'])->name('fantree.update')->middleware('role:superadmin');
        Route::post('/fantree/delete/{fantree_id}', [FantreeController::class,'delete'])->name('fantree.delete')->middleware('role:superadmin');
        Route::post('/fantree/addparents/{fantree_id}', [FantreeController::class,'addparents'])->name('fantree.addparents')->middleware('role:superadmin');
        Route::post('/fantree/addperson/{fantree_id}', [FantreeController::class,'addperson'])->name('fantree.addperson')->middleware('role:superadmin');
        Route::post('/fantree/print/{fantree_id}', [FantreeController::class,'print'])->name('fantree.print')->middleware('role:superadmin');
        

        // notes
        Route::post('/fantree/savenote/{fantree_id}', [NoteFantreeController::class,'save'])->name('fantree.savenote')->middleware('role:superadmin');
        Route::post('/fantree/editnoteposition/{fantree_id}', [NoteFantreeController::class,'edit_position'])->name('fantree.editnoteposition')->middleware('role:superadmin');
        Route::post('/fantree/editnotetext/{fantree_id}', [NoteFantreeController::class,'edit_text'])->name('fantree.editnotetext')->middleware('role:superadmin');
        Route::post('/fantree/deletenote', [NoteFantreeController::class,'delete'])->name('fantree.deletenote')->middleware('role:superadmin');
        

        // add weapon inside NoteFantreeController  
        Route::post('/fantree/addweapon/{fantree_id}', [NoteFantreeController::class,'addweapon'])->name('fantree.addweapon')->middleware('role:superadmin');
        Route::post('/fantree/deleteweapon/{fantree_id}', [NoteFantreeController::class,'deleteweapon'])->name('fantree.deleteweapon')->middleware('role:superadmin');
        
        Route::post('/fantree/editweaponposition/{fantree_id}', [NoteFantreeController::class,'editweaponposition'])->name('fantree.editweaponposition')->middleware('role:superadmin');
        


    });


