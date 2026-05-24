<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Support\LearningPlanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // show login page
    public function showForm()
    {
        return view('auth.login');
    }

    // handle login
    public function login(Request $request)
    {
        // ✅ validation
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {

            $request->session()->regenerate();

            $user = Auth::user();
            $user->load('profile', 'assessmentAttempt');

            // email verified check
            if (is_null($user->email_verified_at)) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Please verify your email first.'
                ]);
            }

            if (is_null($user->onboarded_at) && $user->profile) {
                $user->forceFill([
                    'goal' => $user->goal ?? $user->profile->learning_goal,
                    'onboarded_at' => now(),
                ])->save();
            }

            // onboarding check
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

        return back()->withErrors([
            'email' => 'Invalid credentials'
        ]);
    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
