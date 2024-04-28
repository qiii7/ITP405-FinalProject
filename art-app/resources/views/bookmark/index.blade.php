<!DOCTYPE html>
@extends('layout')

@section('title', $user->name . "'s bookmark")

@section('styles')
    <style>
        .bookmark, .comments {
            border: 1px solid black;
        }
        .bookmark {
            width: 700px;
            margin: 30px;
        }
        .comment {
            border: 1px solid red;
        }
        .image-container img{
            width: 450px;
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

    <p>Hello, {{ $user->name }}.</p>

    @if (count($bookmarks) == 0)
        <p>you haven't bookmarked any artworks. <a href="{{ route('artworks.index') }}">go</a> to check out some!</p>
    @else
        @foreach ($bookmarks as $bookmark)
            <div class="bookmark">
                <!-- delete bookmarks -->
                <form action="{{ route('bookmark.delete') }}" method="POST">
                    @csrf
                    <!-- hide comment id here -->
                    <input type="hidden" name="bookmarkId" value="{{ $bookmark->id }}">

                    <!-- hide artwork id here -->
                    <input type="hidden" name="artworkId" value="{{ $bookmark->artwork->id }}">

                    <button onclick="return confirmDelete()" type="submit">Delete</button>
                </form>

                <p>bookmarked in {{ $bookmark->created_at }} </p>
                <h1>{{ $bookmark->artwork->title }}</h1>
                <p>{{ $bookmark->artwork->artist_title }}</p>
                <p>{{ $bookmark->artwork->date_end }}</p>
                <p>Classification: {{ $bookmark->artwork->classification_title }}</p>
                <p>Place of Origin: {{ $bookmark->artwork->place_of_origin }}</p>

                <div class="image-container">
                    <img src="https://www.artic.edu/iiif/2/{{ $bookmark->artwork->image_id }}/full/843,/0/default.jpg" alt="{{ $bookmark->artwork->title }}">
                </div>

                <div class="comments">
                    <h2>comments:</h2>
                    @if (count($bookmark->artwork->comments) == 0)
                        <p>there is no comment for this artwork yet. be the first to 
                            <a href="{{ route('artwork.display', ['id' => $bookmark->artwork_id]) }}">comment</a>!
                            <!-- to the comment page -->
                        </p>
                    @else
                        @foreach ($bookmark->artwork->comments as $comment)
                            <div class="comment">
                                <p>{{ $comment->comment }}</p>
                                <p>by {{ $comment->user->name }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endforeach
    @endif

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this comment?");
        }
    </script>
    
@endsection