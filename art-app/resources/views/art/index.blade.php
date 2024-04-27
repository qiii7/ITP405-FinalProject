<!DOCTYPE html>
@extends('layout')

@section('title', 'search art')

@section('styles')

@endsection

@section('main')
    <body>
        <h1>a blog about art</h1>
        <a href="{{ route('artworks.search') }}">Search Artworks</a>
        <a href="{{ route('exhibitions.search') }}">Search Exhibitions</a>
    </body>
    </html>
@endsection