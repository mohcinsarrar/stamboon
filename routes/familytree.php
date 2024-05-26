<?php

use App\Http\Controllers\familytree\DashboardController;
use App\Http\Controllers\familytree\SubscriptionController;





Route::middleware(['auth:sanctum','verified','active'])
    ->name('familytree.')
    ->group( function(){

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('subscription', [SubscriptionController::class, 'index'])->name('subscription.index')->withoutMiddleware('active');
    Route::get('subscription/payment/order/{id}', [SubscriptionController::class, 'payment'])->name('subscription.payment')->withoutMiddleware('active');
    Route::get('subscription/payment/success', [SubscriptionController::class, 'success'])->name('subscription.success')->withoutMiddleware('active');
});