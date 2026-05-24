<?php

namespace App\Support;

use App\Models\AssessmentAttempt;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class RoadmapGenerator
{
    public static function generate(User $user, AssessmentAttempt $attempt): array
    {
        $profile = $user->profile;
        $fallback = self::fallbackRoadmap($user, $attempt, $profile);

        if (!config('services.gemini.enabled') || !config('services.gemini.api_key')) {
            Log::warning('Gemini roadmap generation skipped because it is disabled or missing an API key.', [
                'enabled' => (bool) config('services.gemini.enabled'),
                'has_api_key' => filled(config('services.gemini.api_key')),
                'attempt_id' => $attempt->id,
                'user_id' => $user->id,
            ]);

            return [
                'provider' => 'fallback',
                'roadmap' => $fallback,
            ];
        }

        try {
            $prompt = self::buildPrompt($user, $attempt, $profile, $fallback);
            $timeout = min(120, max(30, (int) config('services.gemini.timeout', 90)));

            set_time_limit($timeout + 15);

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
                        'temperature' => 0.35,
                        'maxOutputTokens' => 8192,
                    ],
                ]);

            if (!$response->successful()) {
                Log::warning('Gemini roadmap generation failed with a non-success response.', [
                    'status' => $response->status(),
                    'reason' => data_get($response->json(), 'error.message'),
                    'model' => config('services.gemini.model'),
                    'attempt_id' => $attempt->id,
                    'user_id' => $user->id,
                ]);

                return [
                    'provider' => 'fallback',
                    'roadmap' => $fallback,
                ];
            }

            $text = data_get($response->json(), 'candidates.0.content.parts.0.text');
            $decoded = self::decodeJsonResponse((string) $text);

            if (!is_array($decoded)) {
                Log::warning('Gemini roadmap generation returned invalid JSON.', [
                    'json_error' => json_last_error_msg(),
                    'response_preview' => Str::limit((string) $text, 500),
                    'attempt_id' => $attempt->id,
                    'user_id' => $user->id,
                ]);

                return [
                    'provider' => 'fallback',
                    'roadmap' => $fallback,
                ];
            }

            return [
                'provider' => 'gemini',
                'roadmap' => self::normalizeRoadmap($decoded, $fallback),
            ];
        } catch (Throwable $exception) {
            Log::warning('Gemini roadmap generation threw an exception.', [
                'message' => $exception->getMessage(),
                'exception' => get_class($exception),
                'attempt_id' => $attempt->id,
                'user_id' => $user->id,
            ]);

            return [
                'provider' => 'fallback',
                'roadmap' => $fallback,
            ];
        }
    }

    private static function buildPrompt(User $user, AssessmentAttempt $attempt, ?Profile $profile, array $fallback): string
    {
        $insights        = $attempt->insights ?? [];
        $weakAreas       = collect($insights['weak_areas'] ?? [])->filter()->values()->all();
        $strongAreas     = collect($insights['strong_areas'] ?? [])->filter()->values()->all();
        $topicBreakdown  = collect($insights['topic_breakdown'] ?? [])->filter()->values()->all();
        $stack           = collect($attempt->recommended_stack ?? [])->filter()->values()->all();
        $dailyMinutes    = max(20, (int) ($profile?->daily_learning_time ?? 45));
        $weeklyDays      = max(3, (int) ($profile?->weekly_days ?? 5));
        $weeklyMinutes   = $dailyMinutes * $weeklyDays;
        $goal            = $profile?->learning_goal ?? $user->goal ?? 'Skill Growth';
        $targetRole      = $profile?->target_role ?? 'developer';
        $skillLevel      = $profile?->skill_level ?? 'intermediate';
        $pace            = $user->learning_pace ?? 'Steady';
        $format          = $user->learning_format ?? 'Mixed';
        $score           = $attempt->score ?? 0;
        $percentage      = $attempt->percentage ?? 0;
        $studyWindow     = $profile?->preferred_study_window ?? 'flexible';
        $projectPref     = $profile?->project_preference ?? 'small builds';
        $supportStyle    = $profile?->support_style ?? 'self-directed';
        $interests       = collect($profile?->interests ?? [])->implode(', ');
        $strengths       = collect($profile?->strengths ?? [])->implode(', ');
    
        $weakList        = implode(', ', $weakAreas) ?: 'not identified';
        $strongList      = implode(', ', $strongAreas) ?: 'not identified';
        $stackList       = implode(', ', $stack) ?: 'general web development';
    
        $topicBreakdownText = '';
        foreach ($topicBreakdown as $topic) {
            $topicName  = $topic['topic'] ?? 'Unknown';
            $topicScore = $topic['score'] ?? ($topic['percentage'] ?? 'N/A');
            $topicBreakdownText .= "  - {$topicName}: {$topicScore}%\n";
        }
        $topicBreakdownText = $topicBreakdownText ?: '  - No breakdown available';
    
        $effortLabel = match (true) {
            $dailyMinutes <= 30 => 'very short sessions',
            $dailyMinutes <= 45 => 'short focused sessions',
            $dailyMinutes <= 75 => 'standard sessions',
            default             => 'long deep-work sessions',
        };
    
        $levelContext = match (strtolower($skillLevel)) {
            'beginner'     => 'Assume they need concepts explained from first principles. Avoid jargon without explanation. Prefer interactive tutorials and guided practice over raw documentation.',
            'intermediate' => 'Assume they know the basics but have gaps. Skip the 101-level intros. Point to official docs, focused exercises, and small project work.',
            'advanced'     => 'Assume solid foundations. Focus on depth, edge cases, architecture decisions, and production-grade resources. Skip beginner materials entirely.',
            default        => 'Calibrate difficulty to their performance evidence in the assessment.',
        };
    
        $paceContext = match (strtolower($pace)) {
            'fast'   => 'They want to move quickly. Pack tasks tightly. Keep explanations brief. Push toward shipping over studying.',
            'slow'   => 'They prefer depth and reflection. Allow time to revisit each concept. Include review checkpoints.',
            default  => 'Steady pace: balanced between covering ground and consolidating understanding.',
        };
    
        return <<<PROMPT
You are an expert learning coach writing a personalised 4-week study roadmap for a real person.
Your output will be parsed as JSON and displayed directly on their dashboard.
You must return ONLY valid JSON — no markdown, no backticks, no prose outside the JSON object.

════════════════════════════════════════
LEARNER PROFILE
════════════════════════════════════════
Name              : {$user->name}
Goal              : {$goal}
Target role       : {$targetRole}
Skill level       : {$skillLevel}
Learning pace     : {$pace}
Learning format   : {$format}
Study window      : {$studyWindow}
Daily study time  : {$dailyMinutes} minutes/day
Weekly study days : {$weeklyDays} days/week  →  {$weeklyMinutes} total minutes/week
Project preference: {$projectPref}
Support style     : {$supportStyle}
Interests         : {$interests}
Self-reported strengths: {$strengths}

════════════════════════════════════════
ASSESSMENT RESULTS
════════════════════════════════════════
Score             : {$score} ({$percentage}%)
Recommended stack : {$stackList}

Weak areas (prioritise these):
  {$weakList}

Strong areas (use these to build momentum):
  {$strongList}

Topic-by-topic breakdown:
{$topicBreakdownText}

════════════════════════════════════════
COACHING CONTEXT
════════════════════════════════════════
Skill level guidance : {$levelContext}
Pace guidance        : {$paceContext}
Session length       : Tasks should be sized for {$effortLabel} ({$dailyMinutes} min). Never write a task that would take longer than one session.

════════════════════════════════════════
STRICT OUTPUT RULES
════════════════════════════════════════
1.  WEAK AREAS FIRST. Every Week 1 task and at least 60% of Week 2 tasks must directly address the lowest-scoring topic ({$weakList}). Name the topic explicitly in the task title — never write generic titles like "Study core concept".

2.  USE THE SCORE. If percentage < 50, the roadmap must be remedial: slow down, repeat, drill. If 50–75, balanced repair + building. If > 75, focus on depth and shipping, not fundamentals.

3.  TASK TITLES MUST BE SPECIFIC. Bad: "Practice JavaScript". Good: "Build a closure-based counter using IIFE pattern in JavaScript". Every task title must name the exact concept, technique, or deliverable.

4.  EFFORT MUST BE REALISTIC. Every task effort must be a specific time string like "35 min" or "45 min". It must be <= {$dailyMinutes} min. Never write "1 hour" if daily_minutes is 45.

5.  RESOURCES MUST BE REAL. Every URL must be a real, working HTTPS URL pointing to a specific page (not a homepage). Prefer:
    - Official docs (MDN, docs.python.org, laravel.com/docs, react.dev)
    - Free courses (javascript.info, fullstackopen.com, theodinproject.com)
    - Practice tools (exercism.org, codewars.com, sqlbolt.com)
    - Video (YouTube specific tutorials, not the homepage)
    Never invent URLs. If unsure, use a well-known specific page you are confident exists.

6.  MENTOR NOTES must feel personal to {$user->name}'s situation — reference their actual score ({$percentage}%), their weak area ({$weakList}), and their goal ({$goal}). No generic advice.

7.  PRIORITY ACTIONS must be concrete next steps, not motivational quotes. Start with an action verb. Name the topic.

8.  DELIVERABLES must describe a real artefact the learner can hold up as evidence: a GitHub repo, a working function, a set of handwritten notes, a passing test suite — not "understand the concept".

9.  CHECKPOINT questions must be pass/fail testable. Good: "Can you explain what a Promise chain does without looking at notes?" Bad: "Review your progress."

10. PROJECT MILESTONES must align with the target role ({$targetRole}) and use the recommended stack ({$stackList}). Each milestone should be completable in 1 week given {$dailyMinutes} min/day.

11. METRICS must be pulled from real data: use the actual score, daily minutes, weekly days, and primary weak area. Do not invent numbers.

12. TODO ITEMS must reference specific topics from the assessment breakdown, not generic categories.

════════════════════════════════════════
REQUIRED JSON SHAPE
════════════════════════════════════════
Return exactly this structure. Do not add or remove top-level keys.

{
  "headline": "One sharp sentence naming the learner, goal, and primary focus area",
  "summary": "2-3 sentence coaching summary that references their score, top weak area, and the 4-week arc. Be direct and encouraging but honest.",
  "metrics": [
    {"label": "Assessment score", "value": "{$percentage}%"},
    {"label": "Daily focus block", "value": "{$dailyMinutes} min"},
    {"label": "Study cadence", "value": "{$weeklyDays} days/week"},
    {"label": "Primary repair area", "value": "string — name the #1 weak topic"}
  ],
  "priority_actions": [
    "5 specific action strings. Each starts with a verb and names a topic. E.g. 'Complete 10 SQL JOIN exercises on SQLBolt before touching any other topic this week.'"
  ],
  "mentor_notes": [
    "4 personal coaching observations. Each must reference something specific from the learner's data — their score, a named weak area, their daily time, or their goal."
  ],
  "study_tracks": [
    {
      "title": "Name the track after the specific skill area, not a generic label",
      "reason": "1-2 sentences explaining why this track matters for THIS learner based on their assessment data",
      "focus_topics": ["specific topic 1", "specific topic 2"],
      "confidence": "High|Medium|Low"
    }
  ],
  "weekly_focus": [
    {
      "week": "Week 1",
      "title": "Name the primary skill being drilled this week",
      "goal": "One sentence: what specific competency will improve by end of this week",
      "deliverable": "A concrete artefact — name it specifically",
      "tasks": [
        {
          "title": "Specific task name with the exact concept/technique",
          "detail": "Exactly what to do, how to do it, and what output to produce. 2-3 sentences.",
          "effort": "{$dailyMinutes} min",
          "priority": "High|Medium|Low"
        }
      ],
      "resources": [
        {
          "title": "Exact resource title",
          "type": "Docs|Video|Course|Practice|Article",
          "url": "https://exact-specific-page-url.com/path",
          "why": "One sentence explaining why this specific resource fits this specific learner's gap"
        }
      ],
      "checkpoint": "A specific testable question the learner can answer yes/no to verify mastery"
    }
  ],
  "todo_sections": [
    {
      "title": "string",
      "summary": "string",
      "items": [
        {
          "task": "Specific task — name the topic",
          "outcome": "What they will have produced or understood after completing it",
          "priority": "High|Medium|Low",
          "effort": "specific time e.g. 30 min"
        }
      ]
    }
  ],
  "resource_stack": [
    {
      "title": "string",
      "type": "Docs|Video|Course|Practice|Article",
      "url": "https://real-specific-url.com/path",
      "topic": "exact topic name from the assessment breakdown",
      "why": "why this resource for this learner"
    }
  ],
  "project_milestones": [
    {
      "title": "string",
      "description": "What the project is and why it matches their target role",
      "deliverable": "The exact output — a repo URL format, a script, a deployed page, etc.",
      "skills": ["skill1", "skill2"]
    }
  ]
}
PROMPT;
    }

    private static function decodeJsonResponse(string $text): ?array
    {
        $decoded = json_decode($text, true);

        if (is_array($decoded)) {
            return $decoded;
        }

        $cleaned = trim($text);
        $cleaned = preg_replace('/^```(?:json)?\s*/i', '', $cleaned) ?? $cleaned;
        $cleaned = preg_replace('/\s*```$/', '', $cleaned) ?? $cleaned;
        $decoded = json_decode(trim($cleaned), true);

        if (is_array($decoded)) {
            return $decoded;
        }

        if (preg_match('/\{.*\}/s', $text, $matches) !== 1) {
            return null;
        }

        $decoded = json_decode($matches[0], true);

        return is_array($decoded) ? $decoded : null;
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
