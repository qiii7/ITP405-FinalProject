@extends('layout')

@section('title', 'Profile')

@section('main')
    <p>Hello, {{ $user->name }}. Your email is {{ $user->email }}.</p>
    <p>display <a href="{{ route('bookmarks.index') }}">1. bookmarks;</a> 2. sidenotes</p>
@endsection