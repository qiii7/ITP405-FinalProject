@extends('layout')

@section('title', 'Login')

@section('styles')
<style>
    h1 {
        text-align: center;
    }
    .login-container {
        display: flex;
        justify-content: center;
        align-items: center;
        /* height: 100vh; */
    }

    .login-form {
        width: 300px; /* Adjust the width as needed */
    }
</style>
@endsection

@section('main')
    <h1>Login</h1>
    <div class="login-container">
        <form method="post" action="{{ route('auth.login') }}" class="login-form">
            @csrf
            <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}">
            </div>

            <div class="mb-3">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" value="{{ old('password') }}">
            </div>
            <input type="submit" value="Login" class="btn btn-primary">
        </form>
    </div>
@endsection
