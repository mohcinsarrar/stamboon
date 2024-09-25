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
    Route::get('/profile/security', [ProfileController::class,'security'])->name('profile.security');
    Route::get('/profile/notifications', [ProfileController::class,'notifications'])->name('profile.notifications');
    Route::get('/profile/account_delete', [ProfileController::class,'account_delete'])->name('profile.account_delete');

    Route::post('/profile/edit', [ProfileController::class,'edit'])->name('profile.edit');
    Route::post('/profile/delete', [ProfileController::class,'delete'])->name('profile.delete');
    Route::post('/profile/notifications/markasread', [ProfileController::class,'notifications_markasread'])->name('profile.notifications.markasread');
    Route::post('/profile/notifications/delete', [ProfileController::class,'notifications_delete'])->name('profile.notifications.delete');
    Route::get('/profile/notifications/load', [ProfileController::class,'notifications_load'])->name('profile.notifications.load');
    Route::get('/profile/session_delete/{id}', [ProfileController::class, 'session_delete'])->name('profile.session_delete');




});