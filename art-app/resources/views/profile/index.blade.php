@extends('layout')

@section('title', 'Profile')

@section('main')
    <p>Hello, {{ $user->name }}. Your email is {{ $user->email }}.</p>
    <p>display 1. bookmarks; 2. sidenotes
@endsection