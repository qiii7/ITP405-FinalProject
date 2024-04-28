<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Auth;

class RegistrationController extends Controller
{
    public function index()
    {
        return view('registration/index');
    }

    public function register(Request $request)
    {
        // validate
        $request->validate([
            'name' => 'required|max:20',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:7|max:25',
        ]);

        // INSERT
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password')); // bcrypt
        $user->save();

        Auth::login($user);
        return redirect()->route('artworks.index');
    }
}
