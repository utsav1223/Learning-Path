<?php

namespace App\Http\Controllers\Auth;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Support\LearningPlanner;
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
        // $googleUser = Socialite::driver('google')->user();
         $googleUser = Socialite::driver('google')
            ->stateless()
            ->setHttpClient(new \GuzzleHttp\Client(['verify' => false]))
            ->user();

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
        $user->load('profile', 'assessmentAttempt');

        if (is_null($user->onboarded_at) && $user->profile) {
            $user->forceFill([
                'goal' => $user->goal ?? $user->profile->learning_goal,
                'onboarded_at' => now(),
            ])->save();
        }

        if (is_null($user->onboarded_at) && !$user->profile) {
            return redirect()->route('onboarding');
        }

        if (!$user->assessmentAttempt || !$user->assessmentAttempt->isCompleted()) {
            if ($user->profile) {
                LearningPlanner::ensureAttempt($user->fresh('profile'));
            }

            return redirect()->route('dashboard')
                ->with('status', 'First complete your assessment, then you can generate your roadmap.');
        }

        return redirect()->route('dashboard');
    }
}
