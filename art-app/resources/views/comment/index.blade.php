<!DOCTYPE html>
@extends('layout')

@section('title', "commenting on " . $responseObject->data->title)

@section('styles')
    <style>
    </style>
@endsection

@section('main')

    <a href="{{ route('artwork.display', [ 'id' => $id ]) }}">back</a>


    <form action="{{ route('comment.store', ['id' => $id] ) }}" method="POST">
        @csrf
        <!-- hide artwork id here -->
        <input type="hidden" name="artworkId" value="{{ $id }}">
        <!-- hide the api object here -->
        <input type="hidden" name="responseObject" value="{{ json_encode($responseObject) }}">

        <label for="comment">Make a comment/note:</label><br>
        <textarea id="comment" name="comment" rows="4" cols="50" required>{{ old('comment') }}</textarea><br>
        @error('comment')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        
        <button type="submit">Comment</button>
    </form>
@endsection