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
            border: 1px solid black;

            margin-top: 30px;
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
            <form action="{{ route('comment.store', ['id' => $id]) }}" method="POST">
                @csrf
                <!-- hide artwork id here -->
                <input type="hidden" name="artworkId" value="{{ $id }}">
                <!-- hide the api object here -->
                <input type="hidden" name="responseObject" value="{{ $result }}">

                <label for="comment">Make a comment/note:</label><br>
                <textarea id="comment" name="comment" rows="4" cols="50" required>{{ old('comment') }}</textarea><br>
                <button type="submit">Submit</button>
            </form>

            <div id="comments">
                <h2>Comments</h2>
                <p>get data from db</p>

                <p>No comments yet. Be the first one to comment!</p>
            </div>
        </div>

@endsection