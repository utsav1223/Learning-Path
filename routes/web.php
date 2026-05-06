<?php
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home.index');
})->name('home');


Route::get('/get-started', function () {

    // if user not logged in
    if (!auth()->check()) {
        return redirect()->route('register');
    }

    $user = auth()->user();

    // email not verified
    if (!$user->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }

    // onboarding incomplete
    if (is_null($user->onboarded_at)) {
        return redirect()->route('onboarding');
    }

    // fully ready
    return redirect()->route('dashboard');

})->name('get.started');