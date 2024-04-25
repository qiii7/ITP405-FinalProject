<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/search', function() {
    $term = urlencode('monet');
    $response = Http::get("https://api.artic.edu/api/v1/artworks/search?q={$term}&fields=id,title,image_id");
    // $response = Http::get("https://api.artic.edu/api/v1/artworks/search?q={$term}");
    // https://api.artic.edu/api/v1/artworks/27992?fields=id,title,image_id

    $responseObject = $response->Object();
    // dd($responseObject);

    return view("artworks/results", [
        'results' => $responseObject,
    ]);
});