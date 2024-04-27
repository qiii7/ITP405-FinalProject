<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\User;
use App\Models\Artwork;
use App\Models\Comment;

class CommentController extends Controller
{
    public function index($artwork_id, Request $request) {
        // 0) retrieve hidden data
        $artworkId = $request->input('artworkId');
        $jsonString = request()->input('responseObject');
        $responseObject = json_decode($jsonString); // decode from JSON

        return view('comment/index', [
            'id' => $artworkId,
            'responseObject' => $responseObject,
        ]);
    }


    public function store(Request $request) {
        // 0) retrieve hidden data
        $artworkId = $request->input('artworkId');
        $jsonString = request()->input('responseObject');
        $responseObject = json_decode($jsonString); // decode from JSON
        $user = Auth::user();
        // dd($user);
        $email = $user->email;

        // 1) store the artwork into Artwork (INSERT)
        $existingArtwork = Artwork::where('id', $artworkId)->exists(); // check existing data, avoid repetition -> boolean
        if (!$existingArtwork) {
            $artwork = new Artwork();
            $artwork->id = $artworkId;
            $artwork->title = $responseObject->data->title;
            $artwork->artist = $responseObject->data->artist_title;
            $artwork->classification_title = $responseObject->data->classification_title;
            $artwork->place_of_origin = $responseObject->data->place_of_origin;
            $artwork->save();
        }

        // 2) store the comment into Comment
        // a. validation
        $request->validate([
            'comment' => 'required',
        ]);

        // b. INSERT
        $comment = new Comment();
        $comment->artwork_id = $artworkId;
        $user = User::where('email', $email)->first(); // use 'email' to get the userId
        $userId = $user->id;
        $comment->user_id = $userId;
        $comment->comment = $request->input('comment');
        $comment->save();

        return redirect()
            ->route('artwork.display', [ 'id' => $artworkId ])
            ->with('success', "You've successfully made a comment.");
    }


    public function update($artwork_id, Request $request) {
        
    }

}
