<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Главная страница
Route::get('/', [HomeController::class, 'index'])->name('home');

// Другие страницы (пока заглушки)
Route::get('/catalog', function () {
    return view('catalog');
})->name('catalog');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/contacts', function () {
    return view('contacts');
})->name('contacts');