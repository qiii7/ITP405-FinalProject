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
        $bookmarks = Bookmark::with('artwork.comments')
                            ->where('user_id', $currentUserId)
                            ->orderBy('created_at', 'desc')
                            ->get(); // EAGER LOADING!
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
        $userId = $user->id;

        // 1) INSERT artworks  (check duplication)
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

        // 2) INSERT bookmarks (check duplication)
        $existingBookmark = Bookmark::where('artwork_id', $artworkId)
                                        ->where('user_id', $userId) 
                                        ->exists();             
        $alreadyBookmarked = false;
        if (!$existingBookmark) {
            $bookmark = new Bookmark();
            $bookmark->user_id = $user->id;
            $bookmark->artwork_id = $artworkId;
            $bookmark->save();
        } else {
            $alreadyBookmarked = true;
        }

        return redirect()
            ->route('artwork.display', ['id' => $artwork_id])
            ->with('bookmark-success', "You've bookmarked \"" . $responseObject->data->title . "\"")
            ->with('alreadyBookmarked', $alreadyBookmarked);
    }


    public function delete(Request $request){
        // $artworkId = $request->input('artworkId');
        // $artwork = Artwork::where('artwork_id', $artworkId);
        // dd($artwork);

        $bookmarkId = $request->input('bookmarkId');
        $theBookmark = Bookmark::with('artwork')->find($bookmarkId);
        // dd($theBookmark->artwork->title);
        $artworkTitle = $theBookmark->artwork->title;

        $theBookmark->delete();

        return redirect()
            ->route('bookmarks.index')
            ->with('success', "You've deleted \"" . $artworkTitle . "\" from your bookmarks.");
    }

}
