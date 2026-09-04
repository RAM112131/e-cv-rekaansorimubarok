<?php

use App\Http\Controllers\CvController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/project/{slug}', [ProjectController::class, 'show'])->name('project.show');
Route::get('/certificates', [\App\Http\Controllers\CertificateController::class, 'index'])->name('certificates.index');

Route::get('/cv', [CvController::class, 'preview'])->name('cv.preview');
Route::get('/cv/download', [CvController::class, 'download'])->name('cv.download');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
