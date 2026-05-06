<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user()->load('profile');
        $profile = $user->profile;

        $interests = collect($profile?->interests ?? ['Frontend', 'DSA', 'Projects']);
        $primaryInterest = $interests->first() ?? 'Frontend';
        $progress = (int) ($user->proficiency ?? 34);
        $dailyMinutes = (int) ($profile?->daily_learning_time ?? 45);

        $modules = [
            [
                'title' => $primaryInterest . ' foundations',
                'status' => 'Completed',
                'match' => 96,
                'accent' => 'emerald',
                'description' => 'Core concepts confirmed from your starting profile.',
            ],
            [
                'title' => 'Applied practice sprint',
                'status' => 'In progress',
                'match' => 88,
                'accent' => 'blue',
                'description' => 'Hands-on tasks calibrated to your available study time.',
            ],
            [
                'title' => 'Adaptive checkpoint quiz',
                'status' => 'Up next',
                'match' => 81,
                'accent' => 'amber',
                'description' => 'A short quiz that updates the next section of your roadmap.',
            ],
        ];

        $resources = [
            ['type' => 'Video', 'title' => 'Build one focused concept in 20 minutes', 'time' => '20 min'],
            ['type' => 'Quiz', 'title' => 'Readiness check for the next milestone', 'time' => '10 min'],
            ['type' => 'Project', 'title' => 'Mini project based on your goal', 'time' => $dailyMinutes . ' min'],
        ];

        return view('dashboard.index', compact('user', 'profile', 'interests', 'progress', 'dailyMinutes', 'modules', 'resources'));
    }
}
