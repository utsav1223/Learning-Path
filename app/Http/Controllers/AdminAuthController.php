<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin(Request $request)
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return redirect()->route('admin.dashboard');
        }

        if (Auth::check()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('admin.login')
                ->with('admin_login_status', 'You were logged out of the learner account. Now sign in with admin credentials.');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');
        $email = strtolower($credentials['login']) === 'admin'
            ? 'admin@skillweave.local'
            : $credentials['login'];

        if (!Auth::attempt(['email' => $email, 'password' => $credentials['password']], $remember)) {
            return back()->withErrors([
                'login' => 'Invalid admin credentials.',
            ])->onlyInput('login');
        }

        $request->session()->regenerate();

        if (!Auth::user()->is_admin) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'login' => 'This account does not have admin access.',
            ])->onlyInput('login');
        }

        return redirect()->route('admin.dashboard');
    }
}
