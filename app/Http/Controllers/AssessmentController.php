<?php

namespace App\Http\Controllers;

use App\Models\AssessmentAnswer;
use App\Models\AssessmentQuestion;
use App\Support\LearningPlanner;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user()->load('profile', 'assessmentAttempt.answers');

        if (!$user->hasOnboarded()) {
            return redirect()->route('onboarding');
        }

        $attempt = LearningPlanner::ensureAttempt($user);

        if ($attempt->isCompleted()) {
            return redirect()->route('dashboard')
                ->with('status', 'Your assessment is already locked in and reflected on the dashboard.')
                ->with('block_back_navigation', true);
        }

        $questions = AssessmentQuestion::query()
            ->whereIn('id', $attempt->question_ids)
            ->get()
            ->sortBy(fn ($question) => array_search($question->id, $attempt->question_ids, true))
            ->values()
            ->map(function (AssessmentQuestion $question) use ($attempt) {
                $seed = crc32($attempt->id . ':' . $question->id);
                $options = collect($question->options)
                    ->sortBy(fn ($option) => crc32($seed . ':' . $option))
                    ->values()
                    ->all();

                $question->setAttribute('shuffled_options', $options);

                return $question;
            });

        $questionMeta = $questions->map(function ($question) {
            return [
                'id' => $question->id,
                'correct_answer' => $question->correct_answer,
                'explanation' => $question->explanation,
            ];
        })->values();

        return view('assessment.show', [
            'user' => $user,
            'attempt' => $attempt,
            'questions' => $questions,
            'recommendedStack' => $attempt->recommended_stack,
            'questionMeta' => $questionMeta,
        ]);
    }

    public function review(Request $request)
    {
        $user = $request->user()->load('profile', 'assessmentAttempt.answers.question');

        if (!$user->hasOnboarded()) {
            return redirect()->route('onboarding');
        }

        $attempt = $user->assessmentAttempt;

        if (!$attempt || !$attempt->isCompleted()) {
            return redirect()->route('dashboard')->with('status', 'Complete your assessment first to review wrong answers.');
        }

        $profile = $user->profile;
        $attemptAnswers = $attempt
            ? $attempt->answers()->with('question')->get()
            : collect();
        $wrongAnswers = $attemptAnswers
            ->filter(fn ($answer) => !$answer->is_correct && $answer->question)
            ->sortBy(fn ($answer) => $answer->question->topic)
            ->values();
        $allAnswers = $attemptAnswers
            ->filter(fn ($answer) => $answer->question)
            ->sortBy([
                ['is_correct', 'asc'],
                fn ($answer) => $answer->question->topic,
            ])
            ->values();
        $correctAnswers = $allAnswers->where('is_correct', true)->values();

        return view('assessment.review', compact('user', 'profile', 'attempt', 'wrongAnswers', 'correctAnswers', 'allAnswers'));
    }

    public function store(Request $request)
    {
        $user = $request->user()->load('assessmentAttempt');
        $attempt = $user->assessmentAttempt;

        if (!$attempt) {
            return redirect()->route('assessment.show');
        }

        if ($attempt->isCompleted()) {
            return redirect()->route('dashboard')
                ->with('status', 'Your assessment was already submitted.')
                ->with('block_back_navigation', true);
        }

        $rules = [
            'answers' => ['nullable', 'array'],
            'auto_submitted' => ['nullable', 'boolean'],
        ];

        foreach ($attempt->question_ids as $questionId) {
            $rules["answers.$questionId"] = ['nullable', 'string'];
        }

        $validated = $request->validate($rules);
        $submittedAnswers = $validated['answers'] ?? [];
        $wasAutoSubmitted = $request->boolean('auto_submitted');

        $questions = AssessmentQuestion::query()
            ->whereIn('id', $attempt->question_ids)
            ->get()
            ->keyBy('id');

        $score = 0;

        foreach ($attempt->question_ids as $questionId) {
            $question = $questions->get($questionId);
            $selectedAnswer = $submittedAnswers[$questionId] ?? '';
            $isCorrect = $question && $question->correct_answer === $selectedAnswer;

            if ($isCorrect) {
                $score++;
            }

            AssessmentAnswer::updateOrCreate(
                [
                    'assessment_attempt_id' => $attempt->id,
                    'assessment_question_id' => $questionId,
                ],
                [
                    'selected_answer' => $selectedAnswer,
                    'is_correct' => $isCorrect,
                ]
            );
        }

        $percentage = round(($score / max(1, count($attempt->question_ids))) * 100, 2);
        $attempt->refresh();
        $insights = LearningPlanner::buildInsights($attempt);

        $attempt->forceFill([
            'score' => $score,
            'percentage' => $percentage,
            'insights' => $insights,
            'completed_at' => now(),
        ])->save();

        $user->forceFill([
            'proficiency' => (int) round($percentage),
        ])->save();

        $status = $wasAutoSubmitted
            ? 'Time is up. Your assessment was submitted automatically and your dashboard is ready.'
            : 'Assessment submitted. Your dashboard is ready. Generate your AI roadmap when you are ready.';

        return redirect()->route('dashboard')
            ->with('status', $status)
            ->with('block_back_navigation', true);
    }
}
