<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    // Show register form
    public function showForm()
    {
        return view('auth.register'); // loads blade file
    }

    // Handle form submit
    public function register(Request $request)
    {
        // ✅ Step 1: Validate form dataF

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'confirmed', // matches password_confirmation
                Password::min(8)        // length
                    ->letters()         // a-zA-Z
                    ->mixedCase()       // A + a
                    ->numbers()         // 0-9
                    ->symbols()         // !@#$...
                    ->uncompromised(),  // checks against known leaked passwords
            ],
            'goal' => 'required'
        ]);

        // ✅ Step 2: Create user (IMPORTANT: password hashed)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // NEVER plain text
            'goal' => $request->goal,
        ]);

        Auth::login($user);

        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice');
    }
}