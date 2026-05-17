<?php

namespace App\Http\Controllers;

use App\Support\RoadmapGenerator;
use Illuminate\Http\Request;

class RoadmapController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load('profile', 'assessmentAttempt.answers.question');

        if (!$user->hasOnboarded()) {
            return redirect()->route('onboarding');
        }

        $attempt = $user->assessmentAttempt;

        if (!$attempt || !$attempt->isCompleted()) {
            return redirect()->route('assessment.show');
        }

        return view('roadmap.show', [
            'user' => $user,
            'profile' => $user->profile,
            'attempt' => $attempt,
            'roadmap' => $attempt->ai_roadmap ?? [],
            'dailyMinutes' => (int) ($user->profile?->daily_learning_time ?? 45),
        ]);
    }

    public function generate(Request $request)
    {
        @set_time_limit(180);

        $user = $request->user()->load('profile', 'assessmentAttempt.answers.question');

        if (!$user->hasOnboarded()) {
            return redirect()->route('onboarding');
        }

        $attempt = $user->assessmentAttempt;

        if (!$attempt || !$attempt->isCompleted()) {
            return redirect()->route('assessment.show');
        }

        $generated = RoadmapGenerator::generate($user, $attempt);

        $attempt->forceFill([
            'ai_roadmap' => $generated['roadmap'],
            'roadmap_provider' => $generated['provider'],
            'roadmap_generated_at' => now(),
        ])->save();

        return redirect()
            ->route('roadmap.show')
            ->with('status', $generated['provider'] === 'gemini'
                ? 'Your Gemini 2.5 Pro roadmap is ready.'
                : 'Your roadmap is ready. Gemini was unavailable or timed out, so a fallback plan was used.');
    }
}
