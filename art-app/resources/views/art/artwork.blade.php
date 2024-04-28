<!DOCTYPE html>
@extends('layout')

@section('title', $result->data->title)

@section('styles')
    <style>
        #info-section {
            /* border: 1px solid black; */
            width: 500px;
            height: auto;
            padding: 20px;
            text-align: center;

            float: left;
        }
        #info-section img{
            width: 300px;
        }

        #comment-section {
            /* border: 1px solid black; */
            width: 500px;
            height: auto;
            padding: 20px;
            text-align: center;

            float:left;
        }
        .comment-container {
            border: 1px solid rgba(221, 214, 217, 0.88);
            text-align: left;
            padding: 20px;
        }
        /* Main container */
        #info-section {
            margin-bottom: 20px;
        }

        /* Image container */
        .image-container {
            margin-bottom: 20px;
        }

        .image-container img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        p {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .classification, .origin {
            font-size: 16px;
            font-style: italic;
            margin-bottom: 5px;
        }

        button {
            padding: 7px 14px;
            background-color: rgba(245, 193, 39, 0.8);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: rgba(245, 193, 39, 0.95);
        }

        #comment-section {
            margin-top: 20px;
        }

        #comment-section h2 {
            font-size: 20px;
            margin-bottom: 10px;
        }

        .comment-container {
            margin-bottom: 15px;
        }

        .comment-container button {
            margin-right: 10px;
        }

        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        #all-content {
            /* border: 1px solid red; */
            width: 1100px;
            height: 400px;
            margin-top: 60px;
            margin-left: auto;
            margin-right: auto;
        }
        .bookmark-btn {
            margin-top: 20px;
        }
        .comment-btn {
            margin-top: 20px;
        }

        #edit-btn, #delete-btn {
            display: inline-block;
            padding: 4px 8px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
            transition: background-color 0.3s ease;

            font-size: 12px;
        }

        #edit-btn:hover, #delete-btn:hover {
            background-color: #0056b3;
        }
        .edit-delete-container {
            display: flex; /* Display forms side by side */
            align-items: center; /* Align forms vertically */

            margin-top: 10px;
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


        <div id="all-content">
            <!-- id,title,image_id,artist_title,date_end,classification_title,place_of_origin -->
            <div id="info-section">
                <div class="image-container">
                    <img src="https://www.artic.edu/iiif/2/{{ $result->data->image_id }}/full/843,/0/default.jpg" alt="{{ $result->data->title }}">
                </div>

                <h1>{{ $result->data->title }}</h1>
                <p>{{ $result->data->artist_title }}</p>
                <p>{{ $result->data->date_end }}</p>
                <p>Classification: {{ $result->data->classification_title }}</p>
                <p>Place of Origin: {{ $result->data->place_of_origin }}</p>

                <!-- to store as bookmarked -->
                <form class="bookmark-btn" action="{{ route('bookmark.store', [ 'id' => $result->data->id ]) }}" method="POST">
                    @csrf
                    <!-- hide artwork id here -->
                    <input type="hidden" name="artworkId" value="{{ $id }}">
                    <!-- hide the api object here -->
                    <input type="hidden" name="responseObject" value="{{ json_encode($result) }}">

                    <button type="submit">Bookmark It!</button>
                </form>

                <!-- to the comment page -->
                <form action="{{ route('comment.index', [ 'id' => $result->data->id ]) }}" method="GET">
                    @csrf
                    <!-- hide artwork id here -->
                    <input type="hidden" name="artworkId" value="{{ $id }}">
                    <!-- hide the api object here -->
                    <input type="hidden" name="responseObject" value="{{ json_encode($result) }}">

                    <button class="comment-btn" type="submit">Comment</button>
                </form>
            </div>

            <div id="comment-section">

                <div id="comments">
                    <h2>Comments</h2>
                    @if (count($comments) == 0)
                        <p>No comments yet. Be the first one to comment!</p>
                    @else
                        @foreach ($comments as $comment)
                            <div class="comment-container">

                                @if (Auth::check() && Auth::user()->email === $comment->user->email)
                                    <!-- actual comments -->
                                    <p>{{ $comment->created_at }}</p>
                                    <p>from '{{ $comment->user->name }}': {{ $comment->comment }}</p>

                                    <!-- edit and delete comment -->
                                    <div class="edit-delete-container">
                                        <!-- edit comment -->
                                        <form class="comment-form" action="{{ route('comment.edit', [ 'id' => $result->data->id ]) }}" method="GET">
                                            @csrf
                                            <!-- hide comment id here -->
                                            <input type="hidden" name="commentId" value="{{ $comment->id }}">
                                            <!-- hide artwork id here -->
                                            <input type="hidden" name="artworkId" value="{{ $result->data->id }}">
                                            <!-- hide the api object here -->
                                            <input type="hidden" name="responseObject" value="{{ json_encode($result) }}">

                                            <button id="edit-btn" type="submit">Edit</button>
                                        </form>

                                        <!-- delete comment -->
                                        <form class="delete-form" action="{{ route('comment.delete', [ 'id' => $comment->id ]) }}" method="POST">
                                            @csrf
                                            <!-- hide artwork id here -->
                                            <input type="hidden" name="artworkId" value="{{ $result->data->id }}">
                                            <!-- hide the api object here -->
                                            <input type="hidden" name="responseObject" value="{{ json_encode($result) }}">

                                            <button id="delete-btn" onclick="return confirmDelete()" type="submit">Delete</button>
                                        </form>
                                    </div>

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
        </div> <!-- all content -->

        <script>
            function confirmDelete() {
                return confirm("Are you sure you want to delete this comment?");
            }
        </script>

@endsection