<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\CatalogController;

// Главная страница
Route::get('/', [LandingController::class, 'index'])->name('home');

// Другие страницы (пока заглушки)
Route::get('/catalog',[CatalogController::class, 'index'], function () {
    return view('catalog');
})->name('catalog');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contacts', function () {
    return view('contacts');
})->name('contacts');
