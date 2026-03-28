<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;

// Halaman utama
Route::get('/', [LandingController::class, 'index']);

// Alamat pencarian AJAX (Sangat Penting!)
Route::get('/search', [LandingController::class, 'search'])->name('search');