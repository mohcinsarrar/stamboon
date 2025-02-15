<?php


use App\Http\Controllers\superuser\DashboardController;
use App\Http\Controllers\superuser\SubscriptionController;
use App\Http\Controllers\superuser\FantreeController;
use App\Http\Controllers\superuser\ProfileController;
use App\Http\Controllers\superuser\NoteFantreeController;



Route::middleware(['auth:sanctum','verified','active','role:user'])
    ->name('superuser.')
    ->prefix('superuser')
    ->group( function(){
        // super user
        Route::get('/fantree/list', [FantreeController::class,'list'])->name('fantree.list')->middleware('role:superuser');

        Route::get('/fantree/{fantree_id}', [FantreeController::class,'index'])->name('fantree.index')->middleware('role:superuser');
        Route::get('/fantree/getTree/{fantree_id}', [FantreeController::class,'getTree'])->name('fantree.getTree')->middleware('role:superuser');
        Route::post('/fantree/editchartstatus/{fantree_id}', [FantreeController::class,'editChartStatus'])->name('fantree.editchartstatus')->middleware('role:superuser');
        Route::get('/fantree/getchartstatus/{fantree_id}', [FantreeController::class,'getChartStatus'])->name('fantree.getchartstatus')->middleware('role:superuser');
        Route::post('/fantree/download/{fantree_id}', [FantreeController::class,'download'])->name('fantree.download')->middleware('role:superuser');

        Route::get('/fantree/settings/{fantree_id}', [FantreeController::class,'settings'])->name('fantree.settings')->middleware('role:superuser');

        Route::post('/fantree/updatecount/{fantree_id}', [FantreeController::class,'updatecount'])->name('fantree.updatecount')->middleware('role:superuser');

        Route::get('/fantree/getnotes/{fantree_id}', [NoteFantreeController::class,'index'])->name('fantree.getnotes')->middleware('role:superuser');

        Route::get('/fantree/loadweapon/{fantree_id}', [NoteFantreeController::class,'loadweapon'])->name('fantree.loadweapon')->middleware('role:superuser');






        Route::post('/fantree/saveimage/{fantree_id}', [FantreeController::class,'saveimage'])->name('fantree.saveimage')->middleware('role:superuser');
        Route::post('/fantree/deleteimage/{fantree_id}', [FantreeController::class,'deleteimage'])->name('fantree.deleteimage')->middleware('role:superuser');

        
        
        Route::post('/fantree/settings/{fantree_id}', [FantreeController::class,'settings'])->name('fantree.settings')->middleware('role:superuser');
        
        
        Route::post('/fantree/importgedcom/{fantree_id}', [FantreeController::class,'importgedcom'])->name('fantree.importgedcom')->middleware('role:superuser');
        



        Route::post('/fantree/update/{fantree_id}', [FantreeController::class,'update'])->name('fantree.update')->middleware('role:superuser');
        Route::post('/fantree/delete/{fantree_id}', [FantreeController::class,'delete'])->name('fantree.delete')->middleware('role:superuser');
        Route::post('/fantree/addparents/{fantree_id}', [FantreeController::class,'addparents'])->name('fantree.addparents')->middleware('role:superuser');
        Route::post('/fantree/addperson/{fantree_id}', [FantreeController::class,'addperson'])->name('fantree.addperson')->middleware('role:superuser');
        Route::post('/fantree/print/{fantree_id}', [FantreeController::class,'print'])->name('fantree.print')->middleware('role:superuser');
        

        // notes
        Route::post('/fantree/savenote/{fantree_id}', [NoteFantreeController::class,'save'])->name('fantree.savenote')->middleware('role:superuser');
        Route::post('/fantree/editnoteposition/{fantree_id}', [NoteFantreeController::class,'edit_position'])->name('fantree.editnoteposition')->middleware('role:superuser');
        Route::post('/fantree/editnotetext/{fantree_id}', [NoteFantreeController::class,'edit_text'])->name('fantree.editnotetext')->middleware('role:superuser');
        Route::post('/fantree/deletenote', [NoteFantreeController::class,'delete'])->name('fantree.deletenote')->middleware('role:superuser');
        

        // add weapon inside NoteFantreeController  
        Route::post('/fantree/addweapon/{fantree_id}', [NoteFantreeController::class,'addweapon'])->name('fantree.addweapon')->middleware('role:superuser');
        Route::post('/fantree/deleteweapon/{fantree_id}', [NoteFantreeController::class,'deleteweapon'])->name('fantree.deleteweapon')->middleware('role:superuser');
        
        Route::post('/fantree/editweaponposition/{fantree_id}', [NoteFantreeController::class,'editweaponposition'])->name('fantree.editweaponposition')->middleware('role:superuser');
        


    });


