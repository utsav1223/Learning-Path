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
            $user->load('assessmentAttempt');

            // email verified check
            if (is_null($user->email_verified_at)) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Please verify your email first.'
                ]);
            }

            // onboarding check
            if (is_null($user->onboarded_at)) {
                return redirect()->route('onboarding');
            }

            if (!$user->assessmentAttempt || !$user->assessmentAttempt->isCompleted()) {
                LearningPlanner::ensureAttempt($user->load('profile'));

                return redirect()->route('assessment.show');
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
