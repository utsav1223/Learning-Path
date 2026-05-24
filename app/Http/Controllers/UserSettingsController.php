<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserSettingsController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load('profile');
        $profile = $user->profile;

        return view('settings.show', compact('user', 'profile'));
    }

    public function updateAccount(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $request->user()->forceFill([
            'name' => $validated['name'],
        ])->save();

        return back()->with('status', 'Account details updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ]);

        $request->user()->forceFill([
            'password' => Hash::make($validated['password']),
        ])->save();

        return back()->with('status', 'Password changed successfully.');
    }
}
