@extends('layout')

@section('title', 'Register')

@section('styles')
    <style>
        h1 {
            text-align: center;
        }
        .reg-container {
            display: flex;
            justify-content: center;
            align-items: center;
            /* height: 100vh; */
        }

        .registration-form {
            width: 300px; /* Adjust the width as needed */
        }

    </style>
@endsection

@section('main')
    <h1>Register</h1>
    
    <div class="reg-container">
        <form method="post" action="{{ route('registration.create') }}" class="registration-form">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="name">Username</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}">
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" value="{{ old('password') }}">
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <input type="submit" value="Register" class="btn btn-primary">
        </form>
    </div>

@endsection 