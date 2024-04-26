<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ArtController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

Route::get('/', [ArtController::class, 'index'])->name('search.index');

Route::get('/register', [RegistrationController::class, 'index'])->name('registration.index');
Route::post('/register', [RegistrationController::class, 'register'])->name('registration.create');

Route::get('/login', [AuthController::class, 'loginForm'])->name('login'); // intentionally named "login" per "auth" middleware
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');


Route::get('/searchArt', [ArtController::class, 'searchArt'])->name('artworks.search');
Route::get('/searchExhibition', [ArtController::class, 'searchExhibition'])->name('exhibitions.search');

// Route::get('/displayComments', [CommentController::class, 'showForm'])->name('comments.display');


// login allows
// 1)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});