<?php

namespace App\Http\Controllers;

use App\Support\LearningPlanner;
use App\Models\Profile;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function show(Request $request)
    {
        return view('onboarding.show');
    }

    public function store(Request $request)
    {
        $user = $request->user()->load('assessmentAttempt');

        $validated = $request->validate([
            'education_level' => ['required', 'string', 'in:School,College,Graduate,Professional'],
            'career_stage' => ['required', 'string', 'max:80'],
            'experience_years' => ['required', 'integer', 'min:0', 'max:25'],
            'skill_level' => ['required', 'string', 'in:Beginner,Intermediate,Advanced'],
            'interests' => ['required', 'array', 'min:1'],
            'interests.*' => ['string', 'max:80'],
            'learning_goal' => ['required', 'string', 'max:255'],
            'target_role' => ['required', 'string', 'max:255'],
            'preferred_language' => ['required', 'string', 'max:80'],
            'daily_learning_time' => ['required', 'integer', 'min:15', 'max:480'],
            'weekly_days' => ['required', 'integer', 'min:1', 'max:7'],
            'preferred_study_window' => ['required', 'string', 'max:80'],
            'motivation' => ['required', 'string', 'max:120'],
            'project_preference' => ['required', 'string', 'max:120'],
            'support_style' => ['required', 'string', 'max:120'],
            'strengths' => ['required', 'array', 'min:1'],
            'strengths.*' => ['string', 'max:80'],
            'learning_format' => ['required', 'string', 'max:80'],
            'learning_pace' => ['required', 'string', 'max:80'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        Profile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'bio' => $validated['bio'] ?? null,
                'education_level' => $validated['education_level'],
                'career_stage' => $validated['career_stage'],
                'experience_years' => $validated['experience_years'],
                'skill_level' => $validated['skill_level'],
                'interests' => $validated['interests'],
                'learning_goal' => $validated['learning_goal'],
                'target_role' => $validated['target_role'],
                'preferred_language' => $validated['preferred_language'],
                'daily_learning_time' => $validated['daily_learning_time'],
                'weekly_days' => $validated['weekly_days'],
                'preferred_study_window' => $validated['preferred_study_window'],
                'motivation' => $validated['motivation'],
                'project_preference' => $validated['project_preference'],
                'support_style' => $validated['support_style'],
                'strengths' => $validated['strengths'],
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

        if ($user->assessmentAttempt) {
            $user->assessmentAttempt()->delete();
        }

        LearningPlanner::ensureAttempt($user->fresh('profile'));

        return redirect()->route('dashboard')
            ->with('status', 'Your learning path is ready. First complete the assessment, then you can generate your roadmap.')
            ->with('block_back_navigation', true);
    }
}
