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
        $profile ??= $user->profile;
        $goal = self::goalDetails($profile?->learning_goal ?? $user->goal);
        $stack = self::recommendedStackForUser($user, $profile);
        $interests = collect($profile?->interests ?? [])->map(fn ($item) => (string) $item);
        $coverage = collect($goal['assessmentCoverage'] ?? [])->map(fn ($item) => (string) $item);

        return collect($stack)
            ->merge([$profile?->learning_goal ?? $user->goal, $profile?->target_role])
            ->merge($coverage)
            ->merge($interests)
            ->map(fn (?string $item) => self::normalizeTechnology($item))
            ->filter()
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
        $normalized = collect($technologies)
            ->map(fn (?string $technology) => self::normalizeTechnology($technology))
            ->filter()
            ->unique()
            ->values();
        $questionPool = self::relatedTechnologiesFor($normalized->all());
        $questions = collect();

        foreach ($questionPool as $technology) {
            $questions = $questions->merge(
                AssessmentQuestion::query()
                    ->where('technology', $technology)
                    ->where('is_active', true)
                    ->inRandomOrder()
                    ->limit($normalized->contains($technology) ? 8 : 5)
                    ->get()
            );

            if ($questions->unique('id')->count() >= 25) {
                break;
            }
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

    private static function normalizeTechnology(?string $item): ?string
    {
        $value = strtolower(trim((string) $item));

        if ($value === '') {
            return null;
        }

        return match (true) {
            str_contains($value, 'frontend'),
            str_contains($value, 'react'),
            str_contains($value, 'html'),
            str_contains($value, 'css'),
            str_contains($value, 'javascript'),
            str_contains($value, 'component'),
            str_contains($value, 'accessibility'),
            str_contains($value, 'ui basics') => 'Frontend',

            str_contains($value, 'backend'),
            str_contains($value, 'laravel'),
            str_contains($value, 'php'),
            str_contains($value, 'eloquent'),
            str_contains($value, 'mvc'),
            str_contains($value, 'auth'),
            str_contains($value, 'sql'),
            str_contains($value, 'api') => 'Backend',

            str_contains($value, 'full stack'),
            str_contains($value, 'node'),
            str_contains($value, 'express'),
            str_contains($value, 'database'),
            str_contains($value, 'architecture'),
            str_contains($value, 'system design'),
            str_contains($value, 'saas') => 'Full Stack',

            str_contains($value, 'ai'),
            str_contains($value, 'machine learning'),
            str_contains($value, 'prompt'),
            str_contains($value, 'model'),
            str_contains($value, 'llm') => 'AI/ML',

            str_contains($value, 'data science'),
            str_contains($value, 'data analyst'),
            str_contains($value, 'data literacy'),
            str_contains($value, 'python'),
            str_contains($value, 'data handling'),
            str_contains($value, 'pandas'),
            str_contains($value, 'visualization'),
            str_contains($value, 'charts'),
            str_contains($value, 'dashboard'),
            str_contains($value, 'statistics'),
            str_contains($value, 'metrics'),
            str_contains($value, 'spreadsheets'),
            str_contains($value, 'cleaning'),
            str_contains($value, 'reasoning'),
            str_contains($value, 'storytelling') => 'Data Science',

            str_contains($value, 'dsa'),
            str_contains($value, 'problem solving'),
            str_contains($value, 'programming fundamentals'),
            str_contains($value, 'syntax'),
            str_contains($value, 'function'),
            str_contains($value, 'data structure'),
            str_contains($value, 'array'),
            str_contains($value, 'string'),
            str_contains($value, 'recursion'),
            str_contains($value, 'logic'),
            str_contains($value, 'pattern'),
            str_contains($value, 'complexity'),
            str_contains($value, 'searching'),
            str_contains($value, 'mock test'),
            str_contains($value, 'practice') => 'DSA',

            str_contains($value, 'mobile'),
            str_contains($value, 'flutter'),
            str_contains($value, 'dart'),
            str_contains($value, 'widget') => 'Mobile',

            str_contains($value, 'devops'),
            str_contains($value, 'linux'),
            str_contains($value, 'github actions'),
            str_contains($value, 'docker'),
            str_contains($value, 'cloud'),
            str_contains($value, 'ci/cd'),
            str_contains($value, 'container'),
            str_contains($value, 'deployment'),
            str_contains($value, 'cli'),
            str_contains($value, 'monitoring') => 'DevOps',

            str_contains($value, 'project'),
            str_contains($value, 'portfolio'),
            str_contains($value, 'git'),
            str_contains($value, 'delivery'),
            str_contains($value, 'quality'),
            str_contains($value, 'scope'),
            str_contains($value, 'iteration'),
            str_contains($value, 'mini') => 'Projects',

            default => null,
        };
    }

    private static function relatedTechnologiesFor(array $technologies): Collection
    {
        $related = [
            'Frontend' => ['Frontend', 'Projects', 'Full Stack'],
            'Backend' => ['Backend', 'Full Stack', 'Projects'],
            'Full Stack' => ['Full Stack', 'Frontend', 'Backend', 'Projects', 'DevOps'],
            'AI/ML' => ['AI/ML', 'Data Science', 'Projects'],
            'Data Science' => ['Data Science', 'AI/ML', 'Projects', 'Backend'],
            'DSA' => ['DSA', 'Projects', 'Frontend', 'Backend'],
            'Mobile' => ['Mobile', 'Projects', 'Frontend', 'Backend'],
            'DevOps' => ['DevOps', 'Backend', 'Full Stack', 'Projects'],
            'Projects' => ['Projects', 'Frontend', 'Backend', 'Full Stack'],
        ];

        $normalized = collect($technologies)
            ->map(fn (?string $technology) => self::normalizeTechnology($technology))
            ->filter()
            ->unique()
            ->values();

        if ($normalized->isEmpty()) {
            return collect(['Frontend', 'Backend', 'Projects', 'DSA']);
        }

        return $normalized
            ->flatMap(fn (string $technology) => $related[$technology] ?? [$technology])
            ->unique()
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

    public static function practiceFocusForTopic(string $topic, ?Profile $profile = null): array
    {
        $normalized = strtolower(trim($topic));

        $focusMap = [
            'html' => ['Semantic layout', 'Forms and labels', 'SEO-friendly headings', 'Accessible landmarks'],
            'css' => ['Flexbox alignment', 'Responsive grids', 'Spacing scale', 'Hover/focus states'],
            'javascript' => ['Array methods', 'DOM events', 'Async promises', 'Stateful logic'],
            'react' => ['Component props', 'List keys', 'useState/useEffect', 'Reusable UI patterns'],
            'accessibility' => ['ARIA labels', 'Keyboard navigation', 'Color contrast', 'Screen-reader names'],
            'state management' => ['Lifted state', 'Derived state', 'Shared data flow', 'Form state'],
            'performance' => ['Loading states', 'Render cost', 'Asset size', 'Perceived speed'],
            'php' => ['Associative arrays', 'Functions', 'Request data', 'Error handling'],
            'laravel' => ['Routes/controllers', 'Blade views', 'Eloquent basics', 'Validation flow'],
            'databases' => ['Indexes', 'Relationships', 'Query filters', 'Data modeling'],
            'apis' => ['HTTP methods', 'JSON payloads', 'Status codes', 'Error responses'],
            'authentication' => ['Password hashing', 'Session flow', 'Authorization checks', 'Token safety'],
            'eloquent' => ['Relationships', 'Eager loading', 'Query scopes', 'Factories'],
            'validation' => ['Rule design', 'Form errors', 'Server-side checks', 'Edge cases'],
            'architecture' => ['Separation of concerns', 'Module boundaries', 'API contracts', 'Data flow'],
            'security' => ['CSRF', 'Input handling', 'Secrets', 'Auth boundaries'],
            'deployment' => ['Environment config', 'Build steps', 'Rollback thinking', 'Release checklist'],
            'debugging' => ['Reproduction steps', 'Logs', 'Request tracing', 'Root-cause notes'],
            'projects' => ['Scope control', 'README quality', 'Edge cases', 'Delivery checklist'],
            'python' => ['Syntax fluency', 'Functions', 'Data structures', 'Notebook workflow'],
            'data' => ['Cleaning data', 'Missing values', 'Train/test splits', 'Data quality checks'],
            'model evaluation' => ['Validation sets', 'Metrics', 'Overfitting signals', 'Baseline comparison'],
            'machine learning' => ['Supervised learning', 'Overfitting', 'Feature inputs', 'Model iteration'],
            'features' => ['Feature engineering', 'Transformations', 'Scaling', 'Signal quality'],
            'llm basics' => ['Prompt structure', 'Context design', 'Output constraints', 'Evaluation examples'],
            'arrays' => ['Index access', 'Two pointers', 'Frequency maps', 'In-place updates'],
            'complexity' => ['Big O basics', 'Time vs space', 'Nested loops', 'Constraint reading'],
            'strings' => ['Palindrome checks', 'Sliding windows', 'Character counts', 'Substring logic'],
            'recursion' => ['Base cases', 'Call stack tracing', 'Subproblem design', 'Tree recursion'],
            'searching' => ['Binary search', 'Sorted inputs', 'Boundary conditions', 'Loop invariants'],
            'problem solving' => ['Small examples', 'Edge cases', 'Pattern selection', 'Dry runs'],
            'flutter' => ['Widget composition', 'Layout constraints', 'Navigation', 'Reusable components'],
            'state' => ['Predictable updates', 'Scoped rebuilds', 'Async state', 'Form state'],
            'ux' => ['Touch targets', 'Form clarity', 'Empty states', 'Error states'],
            'testing' => ['UI flow tests', 'Assertions', 'Regression cases', 'Test data'],
            'pandas' => ['DataFrames', 'Filtering', 'Groupby', 'Missing values'],
            'cleaning' => ['Missing values', 'Duplicates', 'Type conversion', 'Outlier checks'],
            'visualization' => ['Chart choice', 'Axis labels', 'Comparison clarity', 'Annotation'],
            'statistics' => ['Mean/median', 'Distribution shape', 'Variance', 'Outliers'],
            'reasoning' => ['Assumptions', 'Outliers', 'Causation vs correlation', 'Decision notes'],
            'cli' => ['Navigation', 'Pipes', 'Scripts', 'Repeatable commands'],
            'containers' => ['Images', 'Dockerfiles', 'Volumes', 'Environment parity'],
            'ci/cd' => ['Pipeline steps', 'Automated tests', 'Build checks', 'Release gates'],
            'monitoring' => ['Health checks', 'Logs', 'Alerts', 'Incident notes'],
            'scope' => ['Feature boundaries', 'Acceptance criteria', 'MVP planning', 'Tradeoffs'],
            'portfolio' => ['Project writeups', 'Demo links', 'Decision logs', 'Impact summaries'],
            'iteration' => ['Feedback review', 'Refinement backlog', 'Versioning', 'User gaps'],
            'quality' => ['Edge cases', 'Consistency', 'Polish pass', 'Manual QA'],
            'delivery' => ['Deployment', 'Demo readiness', 'README steps', 'Handoff checklist'],
        ];

        foreach ($focusMap as $needle => $focusItems) {
            if (str_contains($normalized, $needle)) {
                return $focusItems;
            }
        }

        $interests = collect($profile?->interests ?? [])->take(2)->values()->all();

        return array_values(array_filter(array_merge(
            ['Core concepts', 'Guided practice', 'Mistake review', 'Mini project application'],
            $interests
        )));
    }

    public static function weakAreaPracticePlan(AssessmentAttempt $attempt, ?Profile $profile = null): array
    {
        return collect($attempt->insights['topic_breakdown'] ?? [])
            ->sortBy('score')
            ->take(6)
            ->map(function (array $topic) use ($profile) {
                $topicName = (string) ($topic['topic'] ?? 'Focus area');
                $wrong = (int) ($topic['wrong'] ?? 0);
                $score = (int) ($topic['score'] ?? 0);
                $focusItems = self::practiceFocusForTopic($topicName, $profile);

                return [
                    'topic' => $topicName,
                    'score' => $score,
                    'correct' => (int) ($topic['correct'] ?? 0),
                    'wrong' => $wrong,
                    'focus_items' => $focusItems,
                    'practice_goal' => $wrong > 0
                        ? 'Practice ' . implode(', ', array_slice($focusItems, 0, 3)) . ' until the missed concepts feel repeatable.'
                        : 'Keep this topic warm with one applied exercise and use it to support weaker areas.',
                ];
            })
            ->values()
            ->all();
    }
}
