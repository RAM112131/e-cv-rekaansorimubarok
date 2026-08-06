<?php

use App\Http\Controllers\CvController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/cv', [CvController::class, 'preview'])->name('cv.preview');
Route::get('/cv/download', [CvController::class, 'download'])->name('cv.download');
