<?php

namespace App\Support;

use App\Models\AssessmentAttempt;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class RoadmapGenerator
{
    public static function generate(User $user, AssessmentAttempt $attempt): array
    {
        $profile = $user->profile;
        $fallback = self::fallbackRoadmap($user, $attempt, $profile);

        if (!config('services.gemini.enabled') || !config('services.gemini.api_key')) {
            return [
                'provider' => 'fallback',
                'roadmap' => $fallback,
            ];
        }

        try {
            $prompt = self::buildPrompt($user, $attempt, $profile, $fallback);
            $timeout = min(120, max(30, (int) config('services.gemini.timeout', 90)));

            $response = Http::connectTimeout(15)
                ->timeout($timeout)
                ->withQueryParameters([
                    'key' => config('services.gemini.api_key'),
                ])
                ->post(rtrim(config('services.gemini.endpoint'), '/') . '/models/' . config('services.gemini.model') . ':generateContent', [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'responseMimeType' => 'application/json',
                        'temperature' => 0.45,
                        'maxOutputTokens' => 4096,
                    ],
                ]);

            if (!$response->successful()) {
                return [
                    'provider' => 'fallback',
                    'roadmap' => $fallback,
                ];
            }

            $text = data_get($response->json(), 'candidates.0.content.parts.0.text');
            $decoded = json_decode((string) $text, true);

            if (!is_array($decoded)) {
                return [
                    'provider' => 'fallback',
                    'roadmap' => $fallback,
                ];
            }

            return [
                'provider' => 'gemini',
                'roadmap' => self::normalizeRoadmap($decoded, $fallback),
            ];
        } catch (Throwable) {
            return [
                'provider' => 'fallback',
                'roadmap' => $fallback,
            ];
        }
    }

    private static function buildPrompt(User $user, AssessmentAttempt $attempt, ?Profile $profile, array $fallback): string
    {
        $insights = $attempt->insights ?? [];
        $payload = [
            'user_name' => $user->name,
            'goal' => $profile?->learning_goal ?? $user->goal,
            'target_role' => $profile?->target_role,
            'skill_level' => $profile?->skill_level,
            'daily_minutes' => $profile?->daily_learning_time,
            'weekly_days' => $profile?->weekly_days,
            'study_window' => $profile?->preferred_study_window,
            'format' => $user->learning_format,
            'pace' => $user->learning_pace,
            'interests' => $profile?->interests ?? [],
            'strengths' => $profile?->strengths ?? [],
            'project_preference' => $profile?->project_preference,
            'support_style' => $profile?->support_style,
            'score' => $attempt->score,
            'percentage' => $attempt->percentage,
            'weak_areas' => $insights['weak_areas'] ?? [],
            'strong_areas' => $insights['strong_areas'] ?? [],
            'topic_breakdown' => $insights['topic_breakdown'] ?? [],
            'recommended_stack' => $attempt->recommended_stack ?? [],
        ];

        return <<<PROMPT
You are generating a practical, professional study roadmap for a learner dashboard after a one-time assessment.
Return only valid JSON.
Use this exact top-level shape:
{
  "headline": "string",
  "summary": "string",
  "metrics": [
    {"label": "string", "value": "string"}
  ],
  "priority_actions": ["string"],
  "mentor_notes": ["string"],
  "study_tracks": [
    {
      "title": "string",
      "reason": "string",
      "focus_topics": ["string"],
      "confidence": "High|Medium|Low"
    }
  ],
  "weekly_focus": [
    {
      "week": "Week 1",
      "title": "string",
      "goal": "string",
      "deliverable": "string",
      "tasks": [
        {"title": "string", "detail": "string", "effort": "string", "priority": "High|Medium|Low"}
      ],
      "resources": [
        {"title": "string", "type": "Docs|Video|Course|Practice|Article", "url": "https://...", "why": "string"}
      ],
      "checkpoint": "string"
    }
  ],
  "todo_sections": [
    {
      "title": "string",
      "summary": "string",
      "items": [
        {"task": "string", "outcome": "string", "priority": "High|Medium|Low", "effort": "string"}
      ]
    }
  ],
  "resource_stack": [
    {"title": "string", "type": "Docs|Video|Course|Practice|Article", "url": "https://...", "topic": "string", "why": "string"}
  ],
  "project_milestones": [
    {"title": "string", "description": "string", "deliverable": "string", "skills": ["string"]}
  ]
}

Rules:
- Make it specific to the learner's weak and strong areas.
- Give realistic tasks that fit the learner's daily minutes and weekly days.
- Provide real-looking study resources with valid https URLs where possible.
- Keep tasks actionable, concise, and useful inside a dashboard.
- Prefer official docs and respected learning resources.
- Include 4 weeks of focus.
- Include 3 todo sections and 4 to 8 total resources.

Learner data:
PROMPT
            . json_encode($payload, JSON_PRETTY_PRINT);
    }

    private static function normalizeRoadmap(array $roadmap, array $fallback): array
    {
        $normalizedWeeks = collect(Arr::get($roadmap, 'weekly_focus', $fallback['weekly_focus']))
            ->filter(fn ($week) => is_array($week))
            ->take(4)
            ->map(function (array $week, int $index) use ($fallback) {
                $fallbackWeek = $fallback['weekly_focus'][$index] ?? [];

                return [
                    'week' => Arr::get($week, 'week', Arr::get($fallbackWeek, 'week', 'Week ' . ($index + 1))),
                    'title' => Arr::get($week, 'title', Arr::get($fallbackWeek, 'title', 'Focus block')),
                    'goal' => Arr::get($week, 'goal', Arr::get($fallbackWeek, 'goal', '')),
                    'deliverable' => Arr::get($week, 'deliverable', Arr::get($fallbackWeek, 'deliverable', '')),
                    'tasks' => self::normalizeTasks(Arr::get($week, 'tasks', Arr::get($fallbackWeek, 'tasks', []))),
                    'resources' => self::normalizeResources(Arr::get($week, 'resources', Arr::get($fallbackWeek, 'resources', []))),
                    'checkpoint' => Arr::get($week, 'checkpoint', Arr::get($fallbackWeek, 'checkpoint', 'Ship the deliverable and write down key mistakes.')),
                ];
            })
            ->values()
            ->all();

        $resourceStack = self::normalizeResources(Arr::get($roadmap, 'resource_stack', $fallback['resource_stack']));
        $todoSections = collect(Arr::get($roadmap, 'todo_sections', $fallback['todo_sections']))
            ->filter(fn ($section) => is_array($section))
            ->take(3)
            ->map(function (array $section, int $index) use ($fallback) {
                $fallbackSection = $fallback['todo_sections'][$index] ?? [];

                return [
                    'title' => Arr::get($section, 'title', Arr::get($fallbackSection, 'title', 'Next actions')),
                    'summary' => Arr::get($section, 'summary', Arr::get($fallbackSection, 'summary', '')),
                    'items' => self::normalizeTodoItems(Arr::get($section, 'items', Arr::get($fallbackSection, 'items', []))),
                ];
            })
            ->values()
            ->all();

        $studyTracks = collect(Arr::get($roadmap, 'study_tracks', $fallback['study_tracks']))
            ->filter(fn ($track) => is_array($track))
            ->take(4)
            ->map(function (array $track, int $index) use ($fallback) {
                $fallbackTrack = $fallback['study_tracks'][$index] ?? [];

                return [
                    'title' => Arr::get($track, 'title', Arr::get($fallbackTrack, 'title', 'Track')),
                    'reason' => Arr::get($track, 'reason', Arr::get($fallbackTrack, 'reason', '')),
                    'focus_topics' => array_values(array_slice(array_filter((array) Arr::get($track, 'focus_topics', Arr::get($fallbackTrack, 'focus_topics', []))), 0, 4)),
                    'confidence' => Arr::get($track, 'confidence', Arr::get($fallbackTrack, 'confidence', 'Medium')),
                ];
            })
            ->values()
            ->all();

        $projectMilestones = collect(Arr::get($roadmap, 'project_milestones', $fallback['project_milestones']))
            ->filter(fn ($milestone) => is_array($milestone))
            ->take(4)
            ->map(function (array $milestone, int $index) use ($fallback) {
                $fallbackMilestone = $fallback['project_milestones'][$index] ?? [];

                return [
                    'title' => Arr::get($milestone, 'title', Arr::get($fallbackMilestone, 'title', 'Milestone')),
                    'description' => Arr::get($milestone, 'description', Arr::get($fallbackMilestone, 'description', '')),
                    'deliverable' => Arr::get($milestone, 'deliverable', Arr::get($fallbackMilestone, 'deliverable', '')),
                    'skills' => array_values(array_slice(array_filter((array) Arr::get($milestone, 'skills', Arr::get($fallbackMilestone, 'skills', []))), 0, 5)),
                ];
            })
            ->values()
            ->all();

        return [
            'headline' => Arr::get($roadmap, 'headline', $fallback['headline']),
            'summary' => Arr::get($roadmap, 'summary', $fallback['summary']),
            'metrics' => array_values(array_slice(Arr::get($roadmap, 'metrics', $fallback['metrics']), 0, 4)),
            'priority_actions' => array_values(array_slice(array_filter((array) Arr::get($roadmap, 'priority_actions', $fallback['priority_actions'])), 0, 5)),
            'mentor_notes' => array_values(array_slice(array_filter((array) Arr::get($roadmap, 'mentor_notes', $fallback['mentor_notes'])), 0, 4)),
            'study_tracks' => $studyTracks,
            'weekly_focus' => $normalizedWeeks,
            'todo_sections' => $todoSections,
            'resource_stack' => array_values(array_slice($resourceStack, 0, 8)),
            'project_milestones' => $projectMilestones,
        ];
    }

    private static function normalizeTasks(array $tasks): array
    {
        return collect($tasks)
            ->filter(fn ($task) => is_array($task))
            ->take(5)
            ->map(function (array $task) {
                return [
                    'title' => Arr::get($task, 'title', 'Task'),
                    'detail' => Arr::get($task, 'detail', ''),
                    'effort' => Arr::get($task, 'effort', '30-45 min'),
                    'priority' => Arr::get($task, 'priority', 'Medium'),
                ];
            })
            ->values()
            ->all();
    }

    private static function normalizeTodoItems(array $items): array
    {
        return collect($items)
            ->filter(fn ($item) => is_array($item))
            ->take(5)
            ->map(function (array $item) {
                return [
                    'task' => Arr::get($item, 'task', 'Task'),
                    'outcome' => Arr::get($item, 'outcome', ''),
                    'priority' => Arr::get($item, 'priority', 'Medium'),
                    'effort' => Arr::get($item, 'effort', '30-45 min'),
                ];
            })
            ->values()
            ->all();
    }

    private static function normalizeResources(array $resources): array
    {
        return collect($resources)
            ->filter(fn ($resource) => is_array($resource))
            ->take(8)
            ->map(function (array $resource) {
                return [
                    'title' => Arr::get($resource, 'title', 'Learning resource'),
                    'type' => Arr::get($resource, 'type', 'Docs'),
                    'url' => Arr::get($resource, 'url', '#'),
                    'why' => Arr::get($resource, 'why', ''),
                    'topic' => Arr::get($resource, 'topic', Arr::get($resource, 'title', 'General')),
                ];
            })
            ->values()
            ->all();
    }

    private static function fallbackRoadmap(User $user, AssessmentAttempt $attempt, ?Profile $profile): array
    {
        $goal = $profile?->learning_goal ?? $user->goal ?? 'Skill Growth';
        $stack = collect($attempt->recommended_stack ?? [])->filter()->values();
        $weakAreas = collect($attempt->insights['weak_areas'] ?? [])->filter()->values();
        $strongAreas = collect($attempt->insights['strong_areas'] ?? [])->filter()->values();
        $topicBreakdown = collect($attempt->insights['topic_breakdown'] ?? []);
        $dailyMinutes = max(20, (int) ($profile?->daily_learning_time ?? 45));
        $weeklyDays = max(3, (int) ($profile?->weekly_days ?? 5));
        $headline = $goal . ' improvement roadmap';

        $primaryWeak = $weakAreas->get(0, $stack->get(0, 'Core foundations'));
        $secondaryWeak = $weakAreas->get(1, $stack->get(1, 'Problem solving'));
        $primaryStrong = $strongAreas->get(0, $stack->get(2, 'Applied work'));
        $focusTopics = $topicBreakdown->pluck('topic')->filter()->take(4)->values();

        $resourceTopics = $focusTopics
            ->merge([$primaryWeak, $secondaryWeak, $primaryStrong])
            ->filter()
            ->unique()
            ->take(6)
            ->values();

        $resources = $resourceTopics->map(function (string $topic) {
            return self::resourceForTopic($topic);
        })->values();

        $weeklyFocus = [
            [
                'week' => 'Week 1',
                'title' => $primaryWeak . ' repair sprint',
                'goal' => 'Close the biggest accuracy gap from the assessment and rebuild confidence.',
                'deliverable' => 'Concept notes, 8 to 10 solved practice questions, and one recap summary.',
                'tasks' => [
                    self::task('Audit mistakes from the assessment', 'Write down why each wrong answer was wrong and what concept was missing.', '25 min', 'High'),
                    self::task('Study the core concept', 'Read the primary documentation and rewrite the idea in your own words.', self::effortWindow($dailyMinutes), 'High'),
                    self::task('Practice with short drills', 'Do targeted exercises until you can solve them without looking up the pattern.', self::effortWindow($dailyMinutes), 'High'),
                ],
                'resources' => $resources->filter(fn ($resource) => strcasecmp($resource['topic'], $primaryWeak) === 0)->take(2)->values()->all(),
                'checkpoint' => 'You should be able to explain the topic clearly and solve a basic question set without help.',
            ],
            [
                'week' => 'Week 2',
                'title' => $secondaryWeak . ' reinforcement',
                'goal' => 'Convert shaky understanding into a repeatable routine under time pressure.',
                'deliverable' => 'A completed drill block plus one mini implementation or notebook.',
                'tasks' => [
                    self::task('Review the top patterns', 'Create a short cheat sheet for the most repeated ideas in this topic.', '30 min', 'High'),
                    self::task('Build one tiny artifact', 'Turn the topic into a visible piece of work so it sticks.', self::effortWindow($dailyMinutes), 'Medium'),
                    self::task('Run a timed checkpoint', 'Attempt a short timed quiz or exercise set and track where you hesitated.', '20 min', 'Medium'),
                ],
                'resources' => $resources->filter(fn ($resource) => strcasecmp($resource['topic'], $secondaryWeak) === 0)->take(2)->values()->all(),
                'checkpoint' => 'You should finish a short task on this topic with fewer references and fewer repeated errors.',
            ],
            [
                'week' => 'Week 3',
                'title' => $primaryStrong . ' leverage week',
                'goal' => 'Use a strong area to ship something visible and recover motivation.',
                'deliverable' => 'A portfolio-ready mini build aligned with the target role.',
                'tasks' => [
                    self::task('Choose a scoped mini project', 'Pick a deliverable small enough to finish inside the week.', '20 min', 'High'),
                    self::task('Build in focused sessions', 'Use your strongest area to move quickly while documenting decisions.', self::effortWindow($dailyMinutes), 'High'),
                    self::task('Review for quality', 'Add polish, fix mistakes, and write a short retrospective.', '30 min', 'Medium'),
                ],
                'resources' => $resources->filter(fn ($resource) => strcasecmp($resource['topic'], $primaryStrong) === 0)->take(2)->values()->all(),
                'checkpoint' => 'Publish or demo the mini build and note which skills now feel stronger.',
            ],
            [
                'week' => 'Week 4',
                'title' => $goal . ' consolidation',
                'goal' => 'Tie together weak-area repair, stronger execution, and the next milestone.',
                'deliverable' => 'One revision pack, one reflection note, and a next-step backlog.',
                'tasks' => [
                    self::task('Revisit the weakest concepts', 'Do a final review pass on the first two weak topics.', '30 min', 'High'),
                    self::task('Summarize learning evidence', 'Capture what you built, what improved, and what still needs work.', '25 min', 'Medium'),
                    self::task('Prepare the next month', 'Create the next list of topics and project ideas based on this roadmap.', '20 min', 'Medium'),
                ],
                'resources' => $resources->take(2)->values()->all(),
                'checkpoint' => 'You should leave this week with a clear next roadmap and proof of progress.',
            ],
        ];

        return [
            'headline' => $headline,
            'summary' => 'This plan prioritizes the weakest assessment topics first, then uses your stronger areas to help you ship visible work and keep momentum.',
            'metrics' => [
                ['label' => 'Assessment score', 'value' => (string) (($attempt->percentage ?? 0) . '%')],
                ['label' => 'Study cadence', 'value' => $weeklyDays . ' days/week'],
                ['label' => 'Daily focus block', 'value' => $dailyMinutes . ' min'],
                ['label' => 'Primary repair area', 'value' => $primaryWeak],
            ],
            'priority_actions' => [
                'Start each study week with the weakest topic before moving into comfortable work.',
                'Turn every study block into one visible output: notes, drills, code, or a checkpoint recap.',
                'Keep a mistake log from the assessment and update it whenever you get stuck again.',
                'Use your strongest topic to build confidence, but do not let it replace weak-area repair.',
            ],
            'mentor_notes' => [
                'Treat wrong answers as the roadmap input, not as failure.',
                'A smaller project finished cleanly is more valuable than a large unfinished one.',
                'If a concept still feels fuzzy after two sessions, switch from passive reading to active exercises.',
            ],
            'study_tracks' => [
                [
                    'title' => 'Weak-area recovery',
                    'reason' => 'Your score shows the biggest return will come from strengthening the lowest-performing topics first.',
                    'focus_topics' => [$primaryWeak, $secondaryWeak],
                    'confidence' => 'High',
                ],
                [
                    'title' => 'Strength-powered shipping',
                    'reason' => 'Leaning on a strong topic helps you keep progress visible while learning harder material.',
                    'focus_topics' => [$primaryStrong, $stack->get(0, $primaryStrong)],
                    'confidence' => 'Medium',
                ],
                [
                    'title' => 'Goal alignment',
                    'reason' => 'The roadmap stays close to the selected goal so the practice remains career-relevant.',
                    'focus_topics' => $stack->take(3)->values()->all(),
                    'confidence' => 'High',
                ],
            ],
            'weekly_focus' => $weeklyFocus,
            'todo_sections' => [
                [
                    'title' => 'Immediate fixes',
                    'summary' => 'Clear the conceptual blockers that showed up during the assessment.',
                    'items' => [
                        self::todo('Review every wrong answer in ' . $primaryWeak, 'You should understand the missing concept behind each mistake.', 'High', '25 min'),
                        self::todo('Rewrite one-page notes for ' . $primaryWeak, 'You get a reusable reference for future revision.', 'High', '30 min'),
                        self::todo('Solve a fresh drill set on ' . $secondaryWeak, 'You confirm that the second weakest area is improving.', 'Medium', self::effortWindow($dailyMinutes)),
                    ],
                ],
                [
                    'title' => 'Build and apply',
                    'summary' => 'Use practical work to make the assessment feedback stick.',
                    'items' => [
                        self::todo('Build a small feature using ' . $primaryStrong, 'You create visible proof that supports your target role.', 'High', self::effortWindow($dailyMinutes)),
                        self::todo('Document what went well and where you hesitated', 'You turn practice into repeatable learning.', 'Medium', '15 min'),
                    ],
                ],
                [
                    'title' => 'Checkpoint rhythm',
                    'summary' => 'Keep your learning loop measurable and honest each week.',
                    'items' => [
                        self::todo('Run one timed self-check every week', 'You see whether recall is improving under pressure.', 'Medium', '20 min'),
                        self::todo('Update your next-study backlog every Sunday', 'You always know the next best task to start.', 'Low', '10 min'),
                    ],
                ],
            ],
            'resource_stack' => $resources->values()->all(),
            'project_milestones' => [
                [
                    'title' => 'Repair sprint artifact',
                    'description' => 'Convert the weakest topic into a small but testable piece of work.',
                    'deliverable' => 'A mini implementation or notebook that proves the concept is now understood.',
                    'skills' => [$primaryWeak, $secondaryWeak],
                ],
                [
                    'title' => 'Portfolio mini project',
                    'description' => 'Use a stronger topic to create something shareable and role-aligned.',
                    'deliverable' => 'A compact project with a README or demo summary.',
                    'skills' => [$primaryStrong, $stack->get(0, $primaryStrong), $stack->get(1, $secondaryWeak)],
                ],
            ],
        ];
    }

    private static function task(string $title, string $detail, string $effort, string $priority): array
    {
        return compact('title', 'detail', 'effort', 'priority');
    }

    private static function todo(string $task, string $outcome, string $priority, string $effort): array
    {
        return compact('task', 'outcome', 'priority', 'effort');
    }

    private static function effortWindow(int $dailyMinutes): string
    {
        return $dailyMinutes <= 35 ? '25-35 min' : ($dailyMinutes <= 55 ? '35-45 min' : '45-60 min');
    }

    private static function resourceForTopic(string $topic): array
    {
        $normalized = Str::of($topic)->lower()->trim()->value();

        $map = [
            'html' => ['MDN HTML guides', 'Docs', 'https://developer.mozilla.org/en-US/docs/Web/HTML', 'Solid reference for structure, semantics, and accessibility basics.'],
            'css' => ['MDN CSS guides', 'Docs', 'https://developer.mozilla.org/en-US/docs/Web/CSS', 'Useful when the assessment shows weak layout or styling fundamentals.'],
            'javascript' => ['JavaScript.info', 'Course', 'https://javascript.info/', 'A strong structured path for core JavaScript concepts and practice.'],
            'react' => ['React docs', 'Docs', 'https://react.dev/learn', 'Best place to strengthen component thinking, state, and rendering patterns.'],
            'node.js' => ['Node.js learn', 'Docs', 'https://nodejs.org/en/learn', 'Helpful for backend runtime concepts and practical server basics.'],
            'node' => ['Node.js learn', 'Docs', 'https://nodejs.org/en/learn', 'Helpful for backend runtime concepts and practical server basics.'],
            'php' => ['PHP manual', 'Docs', 'https://www.php.net/manual/en/', 'Good for core language behavior, syntax, and standard library usage.'],
            'laravel' => ['Laravel documentation', 'Docs', 'https://laravel.com/docs', 'Best source for framework conventions, routing, Eloquent, and testing.'],
            'sql' => ['SQLBolt', 'Practice', 'https://sqlbolt.com/', 'A quick interactive route to query practice and database thinking.'],
            'mysql' => ['MySQL tutorial', 'Article', 'https://dev.mysql.com/doc/', 'Useful when query reasoning or relational concepts need cleanup.'],
            'api' => ['MDN Web APIs', 'Docs', 'https://developer.mozilla.org/en-US/docs/Web/API', 'Good for browser API understanding and interface-level debugging.'],
            'git' => ['Git book', 'Docs', 'https://git-scm.com/book/en/v2', 'A reliable reference for version-control fundamentals and day-to-day workflows.'],
            'docker' => ['Docker getting started', 'Docs', 'https://docs.docker.com/get-started/', 'Helpful for container basics and environment consistency.'],
            'kubernetes' => ['Kubernetes basics', 'Docs', 'https://kubernetes.io/docs/tutorials/kubernetes-basics/', 'Good for understanding orchestration and deployment flow.'],
            'python' => ['Python tutorial', 'Docs', 'https://docs.python.org/3/tutorial/', 'A clean starting point for language fundamentals and scripts.'],
            'pandas' => ['Pandas getting started', 'Docs', 'https://pandas.pydata.org/docs/getting_started/index.html', 'Useful for dataframe operations and day-to-day data handling.'],
            'numpy' => ['NumPy user guide', 'Docs', 'https://numpy.org/doc/stable/user/', 'Good for numerical computing fundamentals.'],
            'machine learning' => ['scikit-learn user guide', 'Docs', 'https://scikit-learn.org/stable/user_guide.html', 'Strong for practical ML workflows and baseline models.'],
            'ai/ml' => ['Google Machine Learning Crash Course', 'Course', 'https://developers.google.com/machine-learning/crash-course', 'A practical overview for foundational ML reasoning.'],
            'data science' => ['Kaggle micro-courses', 'Course', 'https://www.kaggle.com/learn', 'Hands-on path for analysis, notebooks, and data workflows.'],
            'dsa' => ['GeeksforGeeks DSA', 'Practice', 'https://www.geeksforgeeks.org/data-structures/', 'Helpful for patterns, explanations, and revision practice.'],
            'algorithms' => ['VisuAlgo', 'Practice', 'https://visualgo.net/en', 'Great for understanding data-structure and algorithm behavior visually.'],
            'frontend' => ['Frontend Mentor', 'Practice', 'https://www.frontendmentor.io/', 'Useful for converting concepts into interface implementation practice.'],
            'backend' => ['Roadmap.sh backend', 'Article', 'https://roadmap.sh/backend', 'Helpful when backend direction or sequencing needs clarity.'],
            'full stack' => ['Full Stack Open', 'Course', 'https://fullstackopen.com/en/', 'Strong project-driven path across frontend, backend, and APIs.'],
            'mobile' => ['React Native docs', 'Docs', 'https://reactnative.dev/docs/getting-started', 'Useful for cross-platform app concepts and setup.'],
            'devops' => ['AWS DevOps learning plan', 'Course', 'https://skillbuilder.aws/learning-plan/88/devops-engineer-learning-plan/', 'Helpful for deployment, CI/CD, and operations habits.'],
            'projects' => ['Roadmap.sh project ideas', 'Article', 'https://roadmap.sh/projects', 'A practical source of scoped builds when project direction is unclear.'],
        ];

        foreach ($map as $needle => [$title, $type, $url, $why]) {
            if (Str::contains($normalized, $needle)) {
                return [
                    'title' => $title,
                    'type' => $type,
                    'url' => $url,
                    'why' => $why,
                    'topic' => $topic,
                ];
            }
        }

        return [
            'title' => $topic . ' study guide',
            'type' => 'Article',
            'url' => 'https://roadmap.sh/',
            'why' => 'Use this as a starting point while refining resources for the topic.',
            'topic' => $topic,
        ];
    }
}
