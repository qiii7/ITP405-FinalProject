<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http; // solve the 'Class "App\Http\Controllers\Http" not found' bug

class ArtController extends Controller
{

    public function index() {
        return view('art/index');
    }


    public function searchArt(Request $request) {
        // // 1. validation
        // $request->validate([
        //     'user-query' => 'required|min:1', 
        // ]);
        
        // 2. insert validated input into query
        // https://api.artic.edu/api/v1/artworks/27992?fields=id,title,image_id
        $term = urlencode($request->input('user-query'));
        $response = Http::get("https://api.artic.edu/api/v1/artworks/search?q={$term}&limit=8&fields=id,title,image_id,artist_title,date_end,classification_title,place_of_origin");

        // get the total num
        $getTotalNum = Http::get("https://api.artic.edu/api/v1/artworks/search?q={$term}&limit=0");
        $getTotalNumObject = $getTotalNum->Object();
        // dd($getTotalNum->Object());

        $responseObject = $response->Object();
        // dd($responseObject);


        // 3. pagination: get the current page
        $currentPage = $getTotalNumObject->pagination->current_page;
        $totalPages = $getTotalNumObject->pagination->total_pages;

        // just return - no redirect() because the db not processing the user input
        return view("art/searchArt", [
            'results' => $responseObject,
            'numOfResults' => $getTotalNumObject,
            'currentPage' => $currentPage,
            'totalPages' => $totalPages,
        ]);
    }

    public function searchExhibition(Request $request) {
        return view("art/searchExhibition");
    }
}
