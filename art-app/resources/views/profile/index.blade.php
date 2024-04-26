@extends('layout')

@section('title', 'Profile')

@section('main')
    <p>Hello, {{ $user->name }}. Your email is {{ $user->email }}.</p>
@endsection