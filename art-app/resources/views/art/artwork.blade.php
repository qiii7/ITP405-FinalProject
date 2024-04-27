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

        <div id="back">
            <a href="{{ route('artworks.index') }}">Back</a>
        </div>


        <!-- id,title,image_id,artist_title,date_end,classification_title,place_of_origin -->
        <div id="info-section">
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
            <form action="{{ route('comment.index', [ 'id' => $id ]) }}" method="GET">
                @csrf
                <!-- hide artwork id here -->
                <input type="hidden" name="artworkId" value="{{ $id }}">
                <!-- hide the api object here -->
                <input type="hidden" name="responseObject" value="{{ json_encode($result) }}">

                <button type="submit">Comment</button>
            </form>

            <div id="comments">
                <h2>Comments</h2>
                @if (count($comments) == 0)
                    <p>No comments yet. Be the first one to comment!</p>
                @else
                    @foreach ($comments as $comment)
                        <div class="comment-container">
                            <p>{{ $comment->created_at }}</p>
                            <p>from '{{ $comment->user->name }}': {{ $comment->comment }}</p>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>

@endsection