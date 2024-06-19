<?php

use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\WebshopController;


Route::middleware(['auth:sanctum','verified','role:admin'])
    ->name('admin.')
    ->prefix('admin')
    ->group( function(){

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::resource('webshop/products', ProductController::class)->names('webshop.products');
    
    Route::post('users/toggle_active/{id}', [UserController::class, 'toggle_active'])->name('users.toggle_active');
    Route::resource('users', UserController::class)->names('users');

    Route::get('webshop/hero', [WebshopController::class, 'hero'])->name('webshop.hero');
    Route::post('webshop/hero', [WebshopController::class, 'hero_update'])->name('webshop.hero.update');

    Route::get('webshop/aboutus', [WebshopController::class, 'aboutus'])->name('webshop.aboutus');
    Route::post('webshop/aboutus', [WebshopController::class, 'aboutus_update'])->name('webshop.aboutus.update');

    Route::get('webshop/features', [WebshopController::class, 'features'])->name('webshop.features');
    Route::post('webshop/features', [WebshopController::class, 'features_update'])->name('webshop.features.update');

    Route::get('webshop/pricing', [WebshopController::class, 'pricing'])->name('webshop.pricing');
    Route::post('webshop/pricing', [WebshopController::class, 'pricing_update'])->name('webshop.pricing.update');

    Route::get('webshop/cta', [WebshopController::class, 'cta'])->name('webshop.cta');
    Route::post('webshop/cta', [WebshopController::class, 'cta_update'])->name('webshop.cta.update');

    Route::get('webshop/faq', [WebshopController::class, 'faq'])->name('webshop.faq');
    Route::post('webshop/faq', [WebshopController::class, 'faq_update'])->name('webshop.faq.update');

    Route::get('webshop/contact', [WebshopController::class, 'contact'])->name('webshop.contact');
    Route::post('webshop/contact', [WebshopController::class, 'contact_update'])->name('webshop.contact.update');
});