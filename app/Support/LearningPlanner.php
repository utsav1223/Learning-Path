<?php

namespace App\Support;

use App\Models\AssessmentAttempt;
use App\Models\AssessmentQuestion;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Collection;

class LearningPlanner
{
    public static function onboardingConfig(): array
    {
        static $config = null;

        if ($config !== null) {
            return $config;
        }

        $path = public_path('js/onboarding/config/onboarding.json');
        $config = json_decode(file_get_contents($path), true);

        return $config;
    }

    public static function goalCatalog(): Collection
    {
        return collect(self::onboardingConfig()['goalTypes'] ?? [])->flatMap(function (array $type) {
            return collect($type['goals'] ?? [])->map(function (array $goal) use ($type) {
                return array_merge($goal, [
                    'goal_type' => $type['id'],
                    'goal_type_label' => $type['label'],
                ]);
            });
        });
    }

    public static function goalDetails(?string $goal): ?array
    {
        if (!$goal) {
            return null;
        }

        return self::goalCatalog()->firstWhere('label', $goal);
    }

    public static function recommendedStackForUser(User $user, ?Profile $profile = null): array
    {
        $profile ??= $user->profile;
        $goal = self::goalDetails($profile?->learning_goal ?? $user->goal);

        if ($goal && !empty($goal['stack'])) {
            return $goal['stack'];
        }

        return match ($profile?->skill_level) {
            'Advanced' => ['System Design', 'Architecture', 'Optimization', 'Delivery'],
            'Intermediate' => ['Core Concepts', 'Practice', 'Projects', 'Interview Prep'],
            default => ['Foundations', 'Guided Practice', 'Mini Projects', 'Review'],
        };
    }

    public static function recommendedTechnologiesForUser(User $user, ?Profile $profile = null): array
    {
        $stack = self::recommendedStackForUser($user, $profile);
        $interests = collect($profile?->interests ?? [])->map(fn ($item) => (string) $item);

        return collect($stack)
            ->merge($interests)
            ->map(function (string $item) {
                return match (strtolower($item)) {
                    'html', 'css', 'javascript', 'react', 'ui', 'frontend' => 'Frontend',
                    'php', 'laravel', 'sql', 'apis', 'rest apis', 'backend' => 'Backend',
                    'node.js', 'express', 'databases' => 'Full Stack',
                    'python', 'data handling', 'models', 'machine learning', 'ai', 'ai/ml' => 'AI/ML',
                    'logic', 'arrays', 'strings', 'recursion', 'dsa', 'problem solving' => 'DSA',
                    'dart', 'widgets', 'mobile ui', 'flutter' => 'Mobile',
                    default => $item,
                };
            })
            ->unique()
            ->values()
            ->all();
    }

    public static function ensureAttempt(User $user): AssessmentAttempt
    {
        $attempt = $user->assessmentAttempt;

        if ($attempt) {
            return $attempt;
        }

        $technologies = self::recommendedTechnologiesForUser($user, $user->profile);
        $questions = self::buildQuestionSet($technologies);

        return AssessmentAttempt::create([
            'user_id' => $user->id,
            'selected_goal' => $user->profile?->learning_goal ?? $user->goal ?? 'Skill Growth',
            'recommended_stack' => self::recommendedStackForUser($user, $user->profile),
            'question_ids' => $questions->pluck('id')->all(),
            'total_questions' => $questions->count(),
        ]);
    }

    public static function buildQuestionSet(array $technologies): Collection
    {
        $normalized = collect($technologies)->filter()->values();
        $questions = collect();

        foreach ($normalized as $technology) {
            $questions = $questions->merge(
                AssessmentQuestion::query()
                    ->where('technology', $technology)
                    ->where('is_active', true)
                    ->inRandomOrder()
                    ->limit(6)
                    ->get()
            );
        }

        if ($questions->count() < 25) {
            $questions = $questions->merge(
                AssessmentQuestion::query()
                    ->where('is_active', true)
                    ->whereNotIn('id', $questions->pluck('id'))
                    ->inRandomOrder()
                    ->limit(25 - $questions->count())
                    ->get()
            );
        }

        return $questions
            ->unique('id')
            ->take(25)
            ->values();
    }

    public static function buildInsights(AssessmentAttempt $attempt): array
    {
        $answers = $attempt->answers()
            ->with('question')
            ->get()
            ->filter(fn ($answer) => $answer->question !== null);

        if ($answers->isEmpty()) {
            return [
                'weak_areas' => [],
                'strong_areas' => [],
                'topic_breakdown' => [],
            ];
        }

        $byTopic = $answers
            ->groupBy(fn ($answer) => $answer->question->topic)
            ->map(function (Collection $answers, string $topic) {
                $correct = $answers->where('is_correct', true)->count();
                $total = $answers->count();

                return [
                    'topic' => $topic,
                    'correct' => $correct,
                    'wrong' => $total - $correct,
                    'score' => $total > 0 ? round(($correct / $total) * 100) : 0,
                ];
            })
            ->sortBy('score')
            ->values();

        $weakAreas = $byTopic->take(3)->pluck('topic')->all();
        $strongAreas = $byTopic->sortByDesc('score')->take(3)->pluck('topic')->all();

        return [
            'weak_areas' => $weakAreas,
            'strong_areas' => $strongAreas,
            'topic_breakdown' => $byTopic->all(),
        ];
    }
}
