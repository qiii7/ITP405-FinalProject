<!DOCTYPE html>
@extends('layout')

@section('title', $user->name . "'s bookmark")

@section('styles')
    <style>
        .alert {
            margin-bottom: 20px;
        }

        .bookmark {
            /* border: 1px solid #ccc; */
            position: relative;
            padding: 15px;
            margin-bottom: 20px;

            width: 500px;
        }

        .bookmark p {
            margin: 5px 0;
        }

        .bookmark button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 5px 10px;
            cursor: pointer;
        }

        .bookmark button:hover {
            background-color: #c82333;
        }

        .image-container {
            margin-top: 10px;
            width: 300px;
        }

        .image-container img {
            max-width: 100%;
            height: auto;
        }

        .comments {
            margin-top: 20px;
        }

        .comment {
            border-bottom: 1px solid #ccc;
            padding: 10px 0;
        }

        .comment p {
            margin: 5px 0;
        }
        .info {
            margin-top: 30px;
        }
        #all-bookmarks {
            /* border: 1px solid black; */
            width: 600px;

            margin-left: auto;
            margin-right: auto;
            margin-top: 20px;
        }
        .delete-btn {
            position: absolute; /* Set position to absolute */
            top: 5px; /* Adjust top position */
            right: 5px; /* Adjust right position */
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }
    </style>
@endsection

@section('main')

    <!-- display success message -->
    @if (session('success'))
        <div class="alert alert-success" style="padding: 15px;" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <h1>Hello, {{ $user->name }}.</h1>

    @if (count($bookmarks) == 0)
        <p>you haven't bookmarked any artworks. <a href="{{ route('artworks.index') }}">go</a> to check out some!</p>
    @else
        <div id="all-bookmarks">
            @foreach ($bookmarks as $bookmark)
                <div class="bookmark">
                    <!-- delete bookmarks -->
                    <form action="{{ route('bookmark.delete') }}" method="POST">
                        @csrf
                        <!-- hide comment id here -->
                        <input type="hidden" name="bookmarkId" value="{{ $bookmark->id }}">

                        <!-- hide artwork id here -->
                        <input type="hidden" name="artworkId" value="{{ $bookmark->artwork->id }}">

                        <button class="delete-btn" onclick="return confirmDelete()" type="submit">remove</button>
                    </form>

                    <div class="info">
                        <p>bookmarked in {{ $bookmark->created_at }} </p>

                        <div class="image-container">
                            <img src="https://www.artic.edu/iiif/2/{{ $bookmark->artwork->image_id }}/full/843,/0/default.jpg" alt="{{ $bookmark->artwork->title }}">
                        </div>
                        <p><b>{{ $bookmark->artwork->title }}</b></p>
                        <p><em>{{ $bookmark->artwork->artist_title }}</em></p>
                        <p><em>{{ $bookmark->artwork->date_end }}</em></p>
                        <p><em>{{ $bookmark->artwork->classification_title }}</em></p>
                        <p><em>{{ $bookmark->artwork->place_of_origin }}</em></p>
                    </div>


                    <div class="comments">
                        <h4>comments</h4>
                        @if (count($bookmark->artwork->comments) == 0)
                            <p>there is no comment for this artwork yet. be the first to 
                                <a href="{{ route('artwork.display', ['id' => $bookmark->artwork_id]) }}">comment</a>!
                                <!-- to the comment page -->
                            </p>
                        @else
                            @foreach ($bookmark->artwork->comments as $comment)
                                <div class="comment">
                                    <p>{{ $comment->comment }} @ {{ $comment->created_at }}</p>
                                    <p>by {{ $comment->user->name }}</p>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <hr>
                </div>
            @endforeach
        </div>
    @endif

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to remove this artwork?");
        }
    </script>
    
@endsection