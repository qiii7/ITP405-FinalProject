<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return view('main');
});

Route::get('/searchArt', [ArtController::class, 'searchArt'])->name('artworks.search');
Route::get('/searchExhibition', [ArtController::class, 'searchExhibition'])->name('exhibitions.search');

// Route::get('/displayComments', [CommentController::class, 'showForm'])->name('comments.display');
