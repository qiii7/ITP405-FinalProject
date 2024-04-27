<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\Models\User;
use App\Models\Artwork;
use App\Models\Comment;
use App\Models\Bookmark;

class BookmarkController extends Controller
{
    public function index() {
        // 0) target the logged-in user
        $currentUser = Auth::user(); // this returns the logged in user
        // dd($currentUser->id);
        $currentUserId = $currentUser->id;
        
        // 1) get the corresponding bookmarks of that user: display info about bookmarked artworks (including comments)
        $bookmarks = Bookmark::with('artwork.comments')->where('user_id', $currentUserId)->get(); // EAGER LOADING!
        // dd($bookmarks);

        return view('bookmark/index', [
            'user' => $currentUser,
            'bookmarks' => $bookmarks,
        ]);
    }

    public function store(Request $request, $artwork_id) {
        // 0) retrieve hidden data
        $artworkId = $request->input('artworkId');
        $jsonString = request()->input('responseObject');
        $responseObject = json_decode($jsonString); // decode from JSON
        $user = Auth::user();
        $email = $user->email;

        // 1) INSERT artworks
        $existingArtwork = Artwork::where('id', $artworkId)->exists(); // check existing data, avoid repetition -> boolean
        if (!$existingArtwork) {
            $artwork = new Artwork();
            $artwork->id = $artworkId;
            $artwork->title = $responseObject->data->title;
            $artwork->artist = $responseObject->data->artist_title;
            $artwork->classification_title = $responseObject->data->classification_title;
            $artwork->place_of_origin = $responseObject->data->place_of_origin;
            $artwork->image_id = $responseObject->data->image_id;
            $artwork->save();
        }

        // 2) INSERT bookmarks
        $bookmark = new Bookmark();
        $bookmark->user_id = $user->id;
        $bookmark->artwork_id = $artworkId;
        $bookmark->save();

        return redirect()
            ->route('artwork.display', ['id' => $artwork_id])
            ->with('success', "You've bookmarked xxx.");
    }

    public function delete(){
        $bookmarkId = $request->input('bookmarkId');

        $theBookmark = Bookmark::find($bookmarkId);
        $theBookmark->delete();

        return redirect()
            ->route('bookmark.index')
            ->with('success', "You've deleted {{ $artwork->title }} from your bookmarks.");
    }

}
