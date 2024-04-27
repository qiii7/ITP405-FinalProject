<!DOCTYPE html>
@extends('layout')

@section('title', $user->name . "'s bookmark")

@section('styles')
    <style>
        .bookmark, .comments {
            border: 1px solid black;
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
    <p>Hello, {{ $user->name }}.</p>

    @if (count($bookmarks) == 0)
        <p>you haven't bookmarked any artworks. <a href="{{ route('artworks.index') }}">go</a> to check out some!</p>
    @else
        @foreach ($bookmarks as $bookmark)
            <div class="bookmark">
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
                    @foreach ($bookmark->artwork->comments as $comment)
                        <div class="comment">
                            <p>{{ $comment->comment }}</p>
                            <p>by {{ $comment->user->name }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endif
    
@endsection