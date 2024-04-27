<!DOCTYPE html>
@extends('layout')

@section('title', 'specific artwork! (customized to artwork title)')

@section('styles')
    <style>
        #info {
            width: 500px;
            height: auto;

            float: left;
        }

        #comment {
            width: 400px;
            height: auto;

            float:left;
        }
    </style>
@endsection

@section('main')

        <div id="back">
            <a href="{{ route('artworks.index') }}">Back</a>
        </div>


        <!-- id,title,image_id,artist_title,date_end,classification_title,place_of_origin -->
        <div id="info">
            <h1>{{ $result->data->title }}</h1>
            <p>{{ $result->data->artist_title }}</p>
            <p>{{ $result->data->date_end }}</p>
            <p>Classification: {{ $result->data->classification_title }}</p>
            <p>Place of Origin: {{ $result->data->place_of_origin }}</p>
                    
            <div class="image-container">
                <img src="https://www.artic.edu/iiif/2/{{ $result->data->image_id }}/full/843,/0/default.jpg" alt="{{ $result->data->title }}">
            </img> 
        </div>

        <div id="comment">
            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <label for="comment">Add a comment:</label><br>
                <textarea id="comment" name="comment" rows="4" cols="50" required></textarea><br>
                <button type="submit">Submit</button>
            </form>
        </div>

@endsection