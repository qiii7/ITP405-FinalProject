<!DOCTYPE html>
@extends('layout')

@section('title', $result->data->title)

@section('styles')
    <style>
        #info-section {
            border: 1px solid black;
            width: 600px;
            height: auto;
            padding: 20px;

            float: left;
        }
        #info-section img{
            width: 300px;
        }

        #comment-section {
            border: 1px solid black;
            width: 600px;
            height: auto;
            padding: 20px;

            float:left;
        }
        #comments {
            /* border: 1px solid black; */

            margin-top: 30px;
        }
        .comment-container {
            border: 1px solid black;
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

        <!-- target 'bookmark' message / if already bookmarked -->
        @if (session('bookmark-success') && !session('alreadyBookmarked'))
            <div class="alert alert-success" style="padding: 15px;" role="alert">
                {{ session('bookmark-success') }}
            </div>
        @elseif (session('alreadyBookmarked'))
            <div class="alert alert-warning" style="padding: 15px;" role="alert">
                <p>The artwork has already been bookmarked.</p>
                <p>Go to your <a href="{{ route('bookmarks.index') }}">bookmarks</a>.</p>
            </div>
        @endif

        <form id="back" action="{{ route('artworks.index') }}" method="GET">
            @csrf
            <!-- hide user query here -->
                <input type="hidden" name="user-query" value="{{ $query }}">

                <button type="submit">Go Back</button>
        </form>


        <!-- id,title,image_id,artist_title,date_end,classification_title,place_of_origin -->
        <div id="info-section">
            
            <!-- to store as bookmarked -->
            <form action="{{ route('bookmark.store', [ 'id' => $result->data->id ]) }}" method="POST">
                @csrf
                <!-- hide artwork id here -->
                <input type="hidden" name="artworkId" value="{{ $id }}">
                <!-- hide the api object here -->
                <input type="hidden" name="responseObject" value="{{ json_encode($result) }}">

                <button type="submit">Bookmark It!</button>
            </form>

            <h1>{{ $result->data->title }}</h1>
            <p>{{ $result->data->artist_title }}</p>
            <p>{{ $result->data->date_end }}</p>
            <p>Classification: {{ $result->data->classification_title }}</p>
            <p>Place of Origin: {{ $result->data->place_of_origin }}</p>
                    
            <div class="image-container">
                <img src="https://www.artic.edu/iiif/2/{{ $result->data->image_id }}/full/843,/0/default.jpg" alt="{{ $result->data->title }}">
            </div>
        </div>

        <div id="comment-section">
            <!-- to the comment page -->
            <form action="{{ route('comment.index', [ 'id' => $result->data->id ]) }}" method="GET">
                @csrf
                <!-- hide artwork id here -->
                <input type="hidden" name="artworkId" value="{{ $id }}">
                <!-- hide the api object here -->
                <input type="hidden" name="responseObject" value="{{ json_encode($result) }}">

                <button type="submit">Add Comment</button>
            </form>

            <div id="comments">
                <h2>Comments</h2>
                @if (count($comments) == 0)
                    <p>No comments yet. Be the first one to comment!</p>
                @else
                    @foreach ($comments as $comment)
                        <div class="comment-container">

                            @if (Auth::check() && Auth::user()->email === $comment->user->email)
                                <!-- edit comment -->
                                <form action="{{ route('comment.edit', [ 'id' => $result->data->id ]) }}" method="GET">
                                    @csrf
                                    <!-- hide comment id here -->
                                    <input type="hidden" name="commentId" value="{{ $comment->id }}">
                                    <!-- hide artwork id here -->
                                    <input type="hidden" name="artworkId" value="{{ $result->data->id }}">
                                    <!-- hide the api object here -->
                                    <input type="hidden" name="responseObject" value="{{ json_encode($result) }}">

                                    <button type="submit">Edit</button>
                                </form>

                                <!-- delete comment -->
                                <form action="{{ route('comment.delete', [ 'id' => $comment->id ]) }}" method="POST">
                                    @csrf
                                    <!-- hide artwork id here -->
                                    <input type="hidden" name="artworkId" value="{{ $result->data->id }}">
                                    <!-- hide the api object here -->
                                    <input type="hidden" name="responseObject" value="{{ json_encode($result) }}">

                                    <button onclick="return confirmDelete()" type="submit">Delete</button>
                                </form>

                                <!-- actual comments -->
                                <p>{{ $comment->created_at }}</p>
                                <p>from '{{ $comment->user->name }}': {{ $comment->comment }}</p>

                            @else
                                <!-- actual comments -->
                                <p>{{ $comment->created_at }}</p>
                                <p>from '{{ $comment->user->name }}': {{ $comment->comment }}</p>
                            @endif
                        </div>
                    @endforeach
                @endif
            </div>

        </div>

        <script>
            function confirmDelete() {
                return confirm("Are you sure you want to delete this comment?");
            }
        </script>

@endsection