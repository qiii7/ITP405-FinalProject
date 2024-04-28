<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ArtController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookmarkController;

// Route::get('/', [ArtController::class, 'index'])->name('search.index');

// user
Route::get('/register', [RegistrationController::class, 'index'])->name('registration.index');
Route::post('/register', [RegistrationController::class, 'register'])->name('registration.create');
Route::get('/login', [AuthController::class, 'loginForm'])->name('login'); // intentionally named "login" per "auth" middleware
Route::post('/login', [AuthController::class, 'login'])->name('auth.login');

// artworks - search
Route::get('/artworks/search/{id}', [ArtController::class, 'specificDisplay'])->name('artwork.display');
Route::get('/artworks/search', [ArtController::class, 'searchArt'])->name('artworks.index');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');

    // bookmarks
    Route::get('/profile/bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');
    Route::post('/profile/bookmarks/delete', [BookmarkController:: class, 'delete'])->name('bookmark.delete');
    Route::post('/artworks/search/{id}/bookmark', [BookmarkController::class, 'store'])->name('bookmark.store');

    // edit comments
    Route::post('/artworks/search/{id}/update', [CommentController::class, 'update'])->name('comment.update');
    Route::get('/artworks/search/{id}/edit', [CommentController::class, 'edit'])->name('comment.edit');

    // make comments
    Route::post('/artworks/search/{id}/store', [CommentController::class, 'store'])->name('comment.store');
    Route::get('/artworks/search/{id}/comment', [CommentController:: class, 'index'])->name('comment.index');
    Route::post('/artworks/search/{id}/delete', [CommentController::class, 'delete'])->name('comment.delete');
});