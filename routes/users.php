<?php

use App\Http\Controllers\users\DashboardController;
use App\Http\Controllers\users\SubscriptionController;
use App\Http\Controllers\users\FanchartController;
use App\Http\Controllers\users\ProfileController;





Route::middleware(['auth:sanctum','verified','active','role:user'])
    ->name('users.')
    ->group( function(){

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('subscription', [SubscriptionController::class, 'index'])->name('subscription.index')->withoutMiddleware('active');
    Route::get('subscription/payment/order/{id}', [SubscriptionController::class, 'payment'])->name('subscription.payment')->withoutMiddleware('active');
    Route::get('subscription/payment/success', [SubscriptionController::class, 'success'])->name('subscription.success')->withoutMiddleware('active');

    // profile
    Route::get('/profile', [ProfileController::class,'index'])->name('profile.index');
    Route::post('/profile/edit', [ProfileController::class,'edit'])->name('profile.edit');

});