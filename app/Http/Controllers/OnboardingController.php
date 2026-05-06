<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function show()
    {
        return view('onboarding.show');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'education_level' => ['required', 'string', 'in:School,College,Graduate,Professional'],
            'skill_level' => ['required', 'string', 'in:Beginner,Intermediate,Advanced'],
            'interests' => ['required', 'array', 'min:1'],
            'interests.*' => ['string', 'max:80'],
            'learning_goal' => ['required', 'string', 'max:255'],
            'preferred_language' => ['required', 'string', 'max:80'],
            'daily_learning_time' => ['required', 'integer', 'min:15', 'max:480'],
            'learning_format' => ['required', 'string', 'max:80'],
            'learning_pace' => ['required', 'string', 'max:80'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $user = $request->user();

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'bio' => $validated['bio'] ?? null,
                'education_level' => $validated['education_level'],
                'skill_level' => $validated['skill_level'],
                'interests' => $validated['interests'],
                'learning_goal' => $validated['learning_goal'],
                'preferred_language' => $validated['preferred_language'],
                'daily_learning_time' => $validated['daily_learning_time'],
            ]
        );

        $proficiency = [
            'Beginner' => 28,
            'Intermediate' => 58,
            'Advanced' => 82,
        ][$validated['skill_level']];

        $user->forceFill([
            'goal' => $validated['learning_goal'],
            'proficiency' => $proficiency,
            'learning_format' => $validated['learning_format'],
            'learning_pace' => $validated['learning_pace'],
            'onboarded_at' => now(),
        ])->save();

        return redirect()->route('dashboard')->with('status', 'Your learning path is ready.');
    }
}
