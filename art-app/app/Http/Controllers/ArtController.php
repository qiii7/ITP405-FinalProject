<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http; // solve the 'Class "App\Http\Controllers\Http" not found' bug

class ArtController extends Controller
{

    public function index() {
        return view('art/index');
    }

    public function searchArt(Request $request) {
        // 1. validation

        // 2. retrieve user query / cache data
        // https://api.artic.edu/api/v1/artworks/27992?fields=id,title,image_id
        $term = urlencode($request->input('user-query'));
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

        // just return - no redirect() because the db not processing the user input
        return view("art/artworks", [
            'query' => $term,
            'results' => $responseObject,
            'numOfResults' => $totalNumObject,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    }


    public function specificDisplay($artworkId) {

        // 1) display artwork's specifics
        $cacheKey = "aic-api-$artworkId";
        $seconds = 60;
        $artworkInfoObject = Cache::remember($cacheKey, $seconds, function() use ($artworkId) {
            $response = Http::get("https://api.artic.edu/api/v1/artworks/{$artworkId}?fields=id,title,image_id,artist_title,date_end,classification_title,place_of_origin");
            // dd($response->Object());
            return $response->Object();
        });

        // 2) display artwork's comments, if any
        

        return view('art/artwork', [
            'id' => $artworkId,
            'result' => $artworkInfoObject,
            // 'query' => $query,
        ]);
    }


    // once the submit button of comment page is clicked...
    public function store(Request $request) {
        // 0) retrieve hidden data
        $artworkId = $request->input('artworkId');
        $responseObject = $request->input('responseObject');
        $user = Auth::user();
        $username = $user->username;

        // 1) store the artwork into Artwork (INSERT)
        $artwork = new Artwork();
        $artwork->title = $responseObject->data->title;
        $artwork->artist = $responseObject->data->artist_title;
        $artwork->classification_title = $responseObject->data->classification_title;
        $artwork->place_of_origin = $responseObject->data->place_of_origin;
        $artwork->medium_display = $responseObject->data->medium_display;
        $artwork->save();


        // 2) store the comment into Comment
        // a. validation
        $request->validate([
            'comment' => 'required',
        ]);

        // b. INSERT
        $comment = new Comment();
        $comment->artwork_id = $artworkId;
        $comment->user_id = $username;
        $comment->comment = $request->input('comment');
        $comment->save();

        return redirect()
            ->route('artwork.display', [ 'id' => $artworkId ])
            ->with('success', "You've successfully made a comment.");
    }


    public function searchExhibition(Request $request) {
        return view("art/searchExhibition");
    }
}
