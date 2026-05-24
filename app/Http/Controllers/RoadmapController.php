<?php

namespace App\Http\Controllers;

use App\Support\LearningPlanner;
use App\Support\RoadmapGenerator;
use Illuminate\Http\Request;

class RoadmapController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load('profile', 'assessmentAttempt');

        if (!$user->hasOnboarded()) {
            return redirect()->route('onboarding');
        }

        if (!$user->assessmentAttempt || !$user->assessmentAttempt->isCompleted()) {
            return redirect()->route('dashboard')->with('status', 'Please complete your assessment to view your roadmap.');
        }

        $attempt = $user->assessmentAttempt;
        $profile = $user->profile;
        $dailyMinutes = (int) ($profile?->daily_learning_time ?? 45);
        $roadmap = $attempt?->ai_roadmap ?? [];
        $weakAreaPracticePlan = LearningPlanner::weakAreaPracticePlan($attempt, $profile);

        return view('roadmap.show', compact(
            'user',
            'profile',
            'attempt',
            'dailyMinutes',
            'roadmap',
            'weakAreaPracticePlan'
        ));
    }

    public function generate(Request $request)
    {
        $user = $request->user()->load('profile', 'assessmentAttempt');
        $attempt = $user->assessmentAttempt;

        if (!$attempt || !$attempt->isCompleted()) {
            return redirect()->route('dashboard')->with('status', 'Please complete your assessment first.');
        }

        $result = RoadmapGenerator::generate($user, $attempt);

        $attempt->forceFill([
            'ai_roadmap' => $result['roadmap'],
            'roadmap_provider' => $result['provider'],
            'roadmap_generated_at' => now(),
        ])->save();

        return redirect()->route('roadmap.show')->with('status', 'Your AI roadmap has been successfully generated!');
    }
}
