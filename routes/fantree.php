<?php

use App\Http\Controllers\users\DashboardController;
use App\Http\Controllers\users\SubscriptionController;
use App\Http\Controllers\users\FantreeController;
use App\Http\Controllers\users\ProfileController;
use App\Http\Controllers\users\NoteController;





Route::middleware(['auth:sanctum','verified','active','role:user','product_type:fantree','payment_reminder'])
    ->name('users.')
    ->group( function(){

        Route::post('/fantree/saveimage', [FantreeController::class,'saveimage'])->name('fantree.saveimage');
        Route::post('/fantree/deleteimage', [FantreeController::class,'deleteimage'])->name('fantree.deleteimage');

        Route::get('/fantree', [FantreeController::class,'index'])->name('fantree.index');
        Route::get('/fantree/settings', [FantreeController::class,'settings'])->name('fantree.settings');
        Route::post('/fantree/settings', [FantreeController::class,'settings'])->name('fantree.settings');
        Route::post('/fantree/download', [FantreeController::class,'download'])->name('fantree.download');
        Route::get('/fantree/getTree', [FantreeController::class,'getTree'])->name('fantree.getTree');
        Route::post('/fantree/importgedcom', [FantreeController::class,'importgedcom'])->name('fantree.importgedcom');
        Route::post('/fantree/editchartstatus', [FantreeController::class,'editChartStatus'])->name('fantree.editchartstatus');
        Route::get('/fantree/getchartstatus', [FantreeController::class,'getChartStatus'])->name('fantree.getchartstatus');



        Route::post('/fantree/update', [FantreeController::class,'update'])->name('fantree.update');
        Route::post('/fantree/delete', [FantreeController::class,'delete'])->name('fantree.delete');
        Route::post('/fantree/addspouse', [FantreeController::class,'addspouse'])->name('fantree.addspouse');
        Route::post('/fantree/addparents', [FantreeController::class,'addparents'])->name('fantree.addparents');
        Route::post('/fantree/addperson', [FantreeController::class,'addperson'])->name('fantree.addperson');
        Route::post('/fantree/getpersons', [FantreeController::class,'getpersons'])->name('fantree.getpersons');
        Route::post('/fantree/orderspouses', [FantreeController::class,'orderspouses'])->name('fantree.orderspouses');
        Route::post('/fantree/print', [FantreeController::class,'print'])->name('fantree.print');
        

        // notes
        Route::post('/fantree/savenote', [NoteController::class,'save'])->name('fantree.savenote');
        Route::post('/fantree/editnoteposition', [NoteController::class,'edit_position'])->name('fantree.editnoteposition');
        Route::post('/fantree/editnotetext', [NoteController::class,'edit_text'])->name('fantree.editnotetext');
        Route::post('/fantree/deletenote', [NoteController::class,'delete'])->name('fantree.deletenote');
        Route::get('/fantree/getnotes', [NoteController::class,'index'])->name('fantree.getnotes');
        



    

});