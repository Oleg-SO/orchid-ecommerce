<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ProductController;

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/delivery', function () {
    return view('delivery');
})->name('delivery');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contacts', function () {
    return view('contacts');
})->name('contacts');

// Страница товара
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/product/quick-view/{id}', [ProductController::class, 'quickView'])->name('product.quick-view');
Route::post('/product/{productId}/review', [ProductController::class, 'storeReview'])->name('product.review');
Route::get('/product/check-availability/{id}', [ProductController::class, 'checkAvailability'])->name('product.check-availability');
