<?php

use App\Http\Controllers\AuthenticationController;



Route::middleware(['auth:sanctum'])->group( function(){
    Route::post('verification_code/send', [AuthenticationController::class, 'verification_code'])->name('verification_code.send');
});