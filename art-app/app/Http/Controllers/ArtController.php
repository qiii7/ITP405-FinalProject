<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http; // solve the 'Class "App\Http\Controllers\Http" not found' bug

use Auth;
use App\Models\User;
use App\Models\Artwork;
use App\Models\Comment;


class ArtController extends Controller
{

    public function index() {
        return view('art/index');
    }

    public function searchArt(Request $request) {
        // 1. get a random artwork
        $randomNumber = rand(50, 67);
        // dd($randomNumber);
        $randomObject = Cache::remember("aic-api-$randomNumber", 60, function() use ($randomNumber) {
            $random = Http::get("https://api.artic.edu/api/v1/artworks/$randomNumber");
            // dd($random->Object());
            return $random->Object();
        });

        // 2. retrieve user query / cache data
        // https://api.artic.edu/api/v1/artworks/27992?fields=id,title,image_id
        $term = urlencode($request->input('user-query')); // $term ALSO receives input from the form in 'artwork.blade.php'
        $seconds = 60;
        
        // results
        $cacheKeyOfResults = "aic-api-$term";
        $responseObject = Cache::remember($cacheKeyOfResults, $seconds, function() use ($term) {
            $response = Http::get("https://api.artic.edu/api/v1/artworks/search?q={$term}&limit=8&fields=id,title,image_id,artist_title,date_end,classification_title,place_of_origin");
            return $response->Object();
        });
        
        // total num of results
        $cacheKeyOfTotalNum = "aic-api-$term-totalNum";
        $totalNumObject = Cache::remember($cacheKeyOfTotalNum, $seconds, function() use ($term) {
            $totalNum = Http::get("https://api.artic.edu/api/v1/artworks/search?q={$term}&limit=0");
            return $totalNum->Object();
        });

        // 3. pagination: get the current page
        $currentPage = $totalNumObject->pagination->current_page;
        $totalPages = $totalNumObject->pagination->total_pages;


        // 4. manually flash old ata
        $request->flashOnly('user-query');

        // just return - no redirect() because the db not processing the user input
        return view("art/artworks", [
            'randomArtwork' => $randomObject,
            'query' => $term,
            'results' => $responseObject,
            'numOfResults' => $totalNumObject,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    }


    public function specificDisplay($artworkId, Request $request) {

        // 1) display artwork's specifics
        $cacheKey = "aic-api-$artworkId";
        $seconds = 60;
        $artworkInfoObject = Cache::remember($cacheKey, $seconds, function() use ($artworkId) {
            $response = Http::get("https://api.artic.edu/api/v1/artworks/{$artworkId}?fields=id,title,image_id,artist_title,date_end,classification_title,place_of_origin");
            // dd($response->Object());
            return $response->Object();
        });

        // 2) get comments, if any (READ)
        $comments = Comment::with(['user'])->where('artwork_id', $artworkId)->orderBy('created_at', 'desc')->get();

        // 3) get user query term to go back
        $query = $request->input('user-query');
        // dd($query);

        return view('art/artwork', [
            'id' => $artworkId,
            'result' => $artworkInfoObject,
            'comments' => $comments,
            'query' => $query,
        ]);
    }

}
