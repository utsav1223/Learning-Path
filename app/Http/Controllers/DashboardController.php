<?php

namespace App\Http\Controllers;

use App\Support\LearningPlanner;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = $request->user()->load('profile', 'assessmentAttempt.answers.question');

        if (!$user->hasOnboarded()) {
            return redirect()->route('onboarding');
        }

        if (!$user->assessmentAttempt || !$user->assessmentAttempt->isCompleted()) {
            LearningPlanner::ensureAttempt($user->load('profile'));

            return redirect()->route('assessment.show');
        }

        $profile = $user->profile;
        $attempt = $user->assessmentAttempt;

        $interests = collect($profile?->interests ?? ['Frontend', 'DSA', 'Projects']);
        $primaryInterest = $interests->first() ?? 'Frontend';
        $progress = (int) ($user->proficiency ?? 34);
        $dailyMinutes = (int) ($profile?->daily_learning_time ?? 45);
        $recommendedStack = LearningPlanner::recommendedStackForUser($user, $profile);
        $insights = $attempt?->insights ?? [
            'weak_areas' => [],
            'strong_areas' => [],
            'topic_breakdown' => [],
        ];
        $topicBreakdown = collect($insights['topic_breakdown'] ?? []);
        $correctCount = (int) ($attempt?->score ?? 0);
        $wrongCount = $attempt?->completed_at ? max(0, (int) $attempt->total_questions - $correctCount) : 0;
        $hasCompletedAssessment = (bool) $attempt?->completed_at;
        $completionRate = $attempt?->percentage ?? 0;
        $weakTopicBreakdown = $topicBreakdown->sortBy('score')->take(4)->values();
        $strongTopicBreakdown = $topicBreakdown->sortByDesc('score')->take(4)->values();
        $roadmap = $attempt?->ai_roadmap ?? [];
        $roadmapMetrics = collect($roadmap['metrics'] ?? [])->take(4)->values();
        $studyTracks = collect($roadmap['study_tracks'] ?? [])->take(4)->values();
        $weeklyFocus = collect($roadmap['weekly_focus'] ?? [])->take(4)->values();
        $todoSections = collect($roadmap['todo_sections'] ?? [])->take(3)->values();
        $roadmapResources = collect($roadmap['resource_stack'] ?? [])->take(8)->values();
        $projectMilestones = collect($roadmap['project_milestones'] ?? [])->take(4)->values();
        $priorityActions = collect($roadmap['priority_actions'] ?? [])->take(4)->values();
        $mentorNotes = collect($roadmap['mentor_notes'] ?? [])->take(4)->values();
        $analysisSummary = $hasCompletedAssessment
            ? 'Assessment signals now shape both the recovery plan and the project sequence.'
            : 'Finish the assessment to unlock topic-level analysis and roadmap generation.';
        $topicLabels = $weakTopicBreakdown->pluck('topic')->all();
        $topicScores = $weakTopicBreakdown->pluck('score')->all();

        $modules = [
            [
                'title' => $recommendedStack[0] . ' calibration',
                'status' => $hasCompletedAssessment ? 'Completed' : 'Pending',
                'match' => $hasCompletedAssessment ? min(98, max(68, $progress + 8)) : 72,
                'accent' => 'emerald',
                'description' => $hasCompletedAssessment ? 'Your baseline assessment locked in the right starting depth.' : 'Finish the one-time assessment to confirm your baseline.',
            ],
            [
                'title' => $primaryInterest . ' practice sprint',
                'status' => 'In progress',
                'match' => $hasCompletedAssessment ? min(96, max(64, $progress + 3)) : 84,
                'accent' => 'blue',
                'description' => 'Hands-on tasks calibrated to your daily time and strongest motivation.',
            ],
            [
                'title' => $hasCompletedAssessment ? 'Weak-area repair loop' : 'Assessment checkpoint',
                'status' => $hasCompletedAssessment ? 'Up next' : 'Required',
                'match' => $hasCompletedAssessment ? 89 : 100,
                'accent' => 'amber',
                'description' => $hasCompletedAssessment
                    ? 'Targeted review based on the lowest-scoring topics from your assessment.'
                    : 'A 25-question checkpoint unlocks your personalized analytics dashboard.',
            ],
        ];

        return view('dashboard.index', compact(
            'user',
            'profile',
            'attempt',
            'interests',
            'progress',
            'dailyMinutes',
            'modules',
            'recommendedStack',
            'hasCompletedAssessment',
            'completionRate',
            'correctCount',
            'wrongCount',
            'weakTopicBreakdown',
            'strongTopicBreakdown',
            'insights',
            'roadmap',
            'roadmapMetrics',
            'studyTracks',
            'weeklyFocus',
            'todoSections',
            'roadmapResources',
            'projectMilestones',
            'priorityActions',
            'mentorNotes',
            'analysisSummary',
            'topicLabels',
            'topicScores'
        ));
    }
}
