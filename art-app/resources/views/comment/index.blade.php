<!DOCTYPE html>
@extends('layout')

@section('title', "commenting on " . $responseObject->data->title)

@section('styles')
    <style>
        .back-link {
            display: inline-block;
            padding: 7px 14px;
            background-color: rgba(245, 193, 39, 0.8);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
        }

        .back-link:hover {
            background-color: rgba(245, 193, 39, 0.95);
        }


        .comment-form-container {
            max-width: 700px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.15);

            margin-top: 50px;
        }

        label {
            font-weight: bold;
            font-size: 20px;
            padding:7px;
        }

        textarea {
            width: 100%;
            margin-bottom: 10px;
            padding: 12px;
            border-radius: 4px;
            border: 1px solid #ddd;
            resize: vertical;
        }

        button.comment-btn {
            padding: 10px 20px;
            background-color: rgba(245, 193, 39, 0.8);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            width: 100%;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        button.comment-btn:hover {
            background-color: rgba(245, 193, 39, 0.95);
        }

        .text-danger {
            color: #dc3545;
        }

    </style>
@endsection

@section('main')

    <a href="{{ route('artwork.display', [ 'id' => $id ]) }}" class="back-link">Go Back</a>

    <div class="comment-form-container">
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
            
            <button type="submit" class="comment-btn">Comment</button>
        </form>
    </div>

@endsection