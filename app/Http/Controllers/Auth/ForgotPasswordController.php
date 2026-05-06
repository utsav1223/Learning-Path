<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }



public function sendResetLinkEmail(Request $request)
{
    // ✅ validate input
    $request->validate([
        'email' => 'required|email'
    ]);

    // 🔥 send reset link
    $status = Password::sendResetLink(
        $request->only('email')
    );

    // ✅ if email exists → success
    if ($status === Password::RESET_LINK_SENT) {
        return back()->with('status', __($status));
    }

    // ❌ if email does NOT exist
    throw ValidationException::withMessages([
        'email' => [__('We can’t find a user with that email address.')],
    ]);
}
}