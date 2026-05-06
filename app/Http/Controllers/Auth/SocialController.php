<?php

namespace App\Http\Controllers\Auth;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SocialController
{
    // Redirect to Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Handle callback
    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        // check if user already exists
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // create new user
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt('google_login'), // dummy
                'goal' => null,
            ]);
        }

        Auth::login($user);

        if (is_null($user->onboarded_at)) {
            return redirect()->route('onboarding');
        }

        return redirect()->route('onboarding');
    }
}