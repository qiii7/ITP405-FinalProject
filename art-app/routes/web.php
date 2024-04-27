<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ArtController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;


Route::get('/', [ArtController::class, 'index'])->name('search.index');

// user
Route::get('/register', [RegistrationController::class, 'index'])->name('registration.index');
Route::post('/register', [RegistrationController::class, 'register'])->name('registration.create');
Route::get('/login', [AuthController::class, 'loginForm'])->name('login'); // intentionally named "login" per "auth" middleware
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// comments (post back to the display specific page)
Route::post('/artworks/search/{id}', [ArtController::class, 'store'])->name('comment.store');

// artworks
Route::get('/artworks/search/{id}', [ArtController::class, 'specificDisplay'])->name('artwork.display');
Route::get('/artworks/search', [ArtController::class, 'searchArt'])->name('artworks.index');
Route::get('/searchExhibition', [ArtController::class, 'searchExhibition'])->name('exhibitions.search');



// login allows
// 1)
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
});