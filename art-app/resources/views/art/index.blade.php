<!DOCTYPE html>
@extends('layout')

@section('title', 'search art')

@section('styles')

@endsection

@section('main')
    <body>
        <h1>a blog about art</h1>
        <a href="{{ route('artworks.index') }}">Search Artworks</a>
    </body>
    </html>
@endsection