<!DOCTYPE html>
@extends('layout')

@section('title', "editing the comment of " . $responseObject->data->title)

@section('styles')
    <style>
    </style>
@endsection

@section('main')
    <div id="back">
        <a href="{{ route('artwork.display', ['id' => $id]) }}">back</a>
    </div>
    <form action="{{ route('comment.update', ['id' => $id]) }}" method="POST">
        @csrf
        <!-- hide comment id here -->
        <input type="hidden" name="commentId" value="{{ $commentId }}">
        <!-- hide artwork id here -->
        <input type="hidden" name="artworkId" value="{{ $id }}">
        <!-- hide the api object here -->
        <input type="hidden" name="responseObject" value="{{ json_encode($responseObject) }}">

        <label for="comment">Make a comment/note:</label><br>
        <textarea id="comment" name="comment" rows="4" cols="50" required>{{ old('comment', $commentBody) }}</textarea><br>
        @error('comment')
            <small class="text-danger">{{ $message }}</small>
        @enderror
        
        <button type="submit">Update</button>
    </form>
@endsection