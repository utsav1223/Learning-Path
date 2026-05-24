<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SkillWeave</title>
    <script>
        (() => {
            const theme = localStorage.getItem('skillweave-theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>tailwind.config = { darkMode: 'class' };</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Manrope', sans-serif; }</style>
</head>
<body class="bg-slate-100 text-slate-950 dark:bg-slate-950 dark:text-slate-100">
    @php
        $primaryWeakArea = $insights['weak_areas'][0] ?? 'Focus topic';
        $primaryStrongArea = $insights['strong_areas'][0] ?? 'Strength area';
        $totalQuestions = $attempt?->total_questions ?? 25;
        $incorrectRate = max(0, 100 - (float) $completionRate);
        $roadmapReady = (bool) $attempt?->roadmap_generated_at;
        $todayTodos = $hasCompletedAssessment
            ? [
                ['task' => 'Review wrong answers in ' . $primaryWeakArea, 'meta' => 'High impact', 'time' => '20 min'],
                ['task' => 'Complete one drill set for ' . $primaryWeakArea, 'meta' => 'Practice', 'time' => $dailyMinutes . ' min'],
                ['task' => 'Use ' . $primaryStrongArea . ' in a small artifact', 'meta' => 'Build proof', 'time' => '30 min'],
                ['task' => 'Update your roadmap checklist', 'meta' => 'Planning', 'time' => '10 min'],
            ]
            : [
                ['task' => 'Complete the one-time assessment', 'meta' => 'Required', 'time' => '20 min'],
                ['task' => 'Unlock topic analytics', 'meta' => 'Dashboard', 'time' => 'After assessment'],
                ['task' => 'Generate your AI roadmap', 'meta' => 'Locked', 'time' => 'After assessment'],
            ];
    @endphp

    <div class="fixed inset-0 z-30 bg-slate-950/60 opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden" data-dashboard-sidebar-overlay></div>

    <div class="min-h-screen lg:grid lg:grid-cols-[19rem_1fr]">
        <div class="sticky top-0 z-30 flex items-center justify-between border-b border-slate-200 bg-white/90 px-4 py-3 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/90 lg:hidden">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-slate-400">SkillWeave</p>
                <p class="text-sm font-extrabold">Dashboard</p>
            </div>
            <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" aria-label="Open navigation menu" aria-expanded="false" aria-controls="dashboard-sidebar" data-dashboard-sidebar-button>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"></path>
                </svg>
            </button>
        </div>

        <x-dashboard.sidebar
            :user="$user"
            :profile="$profile"
            :currentGoal="$profile?->learning_goal ?? $user->goal ?? 'Skill growth'"
            :dailyMinutes="$dailyMinutes"
            :pace="$user->learning_pace ?? 'Steady'"
        />

        <main class="px-4 py-5 sm:px-6 lg:col-start-2 lg:px-8">
            <div class="mx-auto max-w-[1500px]">
                <section class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="grid gap-0 xl:grid-cols-[1fr_22rem]">
                        <div class="p-5 sm:p-6 lg:p-8">
                            <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                                <div class="max-w-3xl">
                                    <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">Learner command center</p>
                                    <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Welcome back, {{ $user->name }}</h1>
                                    <p class="mt-3 max-w-2xl text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $analysisSummary }}</p>
                                </div>
                            </div>

                            <div class="mt-7 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">
                                    <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500">Score</p>
                                    <p class="mt-3 text-3xl font-extrabold">{{ $hasCompletedAssessment ? $correctCount . '/' . $totalQuestions : 'Pending' }}</p>
                                    <p class="mt-1 text-sm font-bold text-slate-500 dark:text-slate-400">{{ $hasCompletedAssessment ? $completionRate . '% correct' : 'Complete assessment first' }}</p>
                                </div>
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">
                                    <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500">Readiness</p>
                                    <p class="mt-3 text-3xl font-extrabold">{{ $hasCompletedAssessment ? $progress . '%' : 'Locked' }}</p>
                                    <p class="mt-1 text-sm font-bold text-slate-500 dark:text-slate-400">{{ $profile?->skill_level ?? 'Beginner' }} level</p>
                                </div>
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">
                                    <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500">Study rhythm</p>
                                    <p class="mt-3 text-3xl font-extrabold">{{ $dailyMinutes }}m</p>
                                    <p class="mt-1 text-sm font-bold text-slate-500 dark:text-slate-400">{{ $profile?->weekly_days ?? 5 }} days/week</p>
                                </div>
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-800 dark:bg-slate-950">
                                    <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500">Roadmap</p>
                                    <p class="mt-3 text-xl font-extrabold">{{ $roadmapReady ? 'Ready' : ($hasCompletedAssessment ? 'Pending' : 'Locked') }}</p>
                                    <p class="mt-1 text-sm font-bold text-slate-500 dark:text-slate-400">{{ $roadmapReady ? $attempt->roadmap_generated_at->diffForHumans() : ($hasCompletedAssessment ? 'Generate next' : 'Assessment required') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-slate-200 bg-slate-950 p-5 text-white dark:border-slate-800 xl:border-l xl:border-t-0">
                            <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400">Current goal</p>
                            <h2 class="mt-3 text-2xl font-extrabold">{{ $profile?->learning_goal ?? $user->goal ?? 'Skill growth' }}</h2>
                            <p class="mt-3 text-sm font-semibold leading-6 text-slate-300">{{ $profile?->target_role ?? 'Role-ready path' }}</p>
                            <div class="mt-6 grid gap-3">
                                @foreach ($recommendedStack as $stackItem)
                                    <div class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-extrabold text-slate-200">{{ $stackItem }}</div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>

                @if (session('status'))
                    <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/30 dark:text-emerald-300">
                        {{ session('status') }}
                    </div>
                @endif

                @unless ($hasCompletedAssessment)
                    <section class="mt-5 rounded-[1.5rem] border border-amber-200 bg-amber-50 p-5 shadow-sm dark:border-amber-900 dark:bg-amber-950/20 sm:p-6">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-amber-700 dark:text-amber-300">Assessment required</p>
                                <h2 class="mt-2 text-2xl font-extrabold text-slate-950 dark:text-slate-100">First complete assessment, then you can generate roadmap.</h2>
                                <p class="mt-3 max-w-2xl text-sm font-semibold leading-6 text-amber-800 dark:text-amber-200">Your onboarding is complete. Finish the 25-question assessment to unlock analytics, roadmap generation, resources, and projects. You can still edit your profile anytime.</p>
                            </div>
                            <a href="{{ route('assessment.show') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-slate-800 dark:bg-blue-600 dark:hover:bg-blue-500">Go to assessment</a>
                        </div>
                    </section>
                @endunless

                <section class="mt-5 grid gap-5 xl:grid-cols-[minmax(0,1.15fr)_minmax(24rem,0.85fr)]">
                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                        <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                            <div>
                                <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">Assessment analytics</p>
                                <h2 class="mt-2 text-2xl font-extrabold">Topic performance</h2>
                            </div>
                            <span class="rounded-full bg-slate-100 px-4 py-2 text-sm font-extrabold text-slate-600 dark:bg-slate-800 dark:text-slate-300">Locked result</span>
                        </div>

                        <div class="mt-6 grid gap-6 lg:grid-cols-[13rem_1fr] lg:items-center">
                            <div class="mx-auto grid h-48 w-48 place-items-center rounded-full" style="background: conic-gradient(#2563eb 0 {{ $completionRate }}%, #f43f5e {{ $completionRate }}% 100%);">
                                <div class="grid h-36 w-36 place-items-center rounded-full bg-white text-center shadow-inner dark:bg-slate-900">
                                    <span class="block text-4xl font-extrabold">{{ $completionRate }}%</span>
                                    <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">accuracy</span>
                                </div>
                            </div>

                            <div class="grid gap-3">
                                @forelse ($weakTopicBreakdown as $topic)
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950">
                                        <div class="flex items-center justify-between gap-4">
                                            <div class="min-w-0">
                                                <p class="truncate font-extrabold">{{ $topic['topic'] }}</p>
                                                <p class="mt-1 text-sm font-semibold text-slate-500 dark:text-slate-400">{{ $topic['correct'] }} correct, {{ $topic['wrong'] }} needs review</p>
                                            </div>
                                            <span class="shrink-0 text-lg font-extrabold">{{ $topic['score'] }}%</span>
                                        </div>
                                        <div class="mt-3 h-2.5 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-800">
                                            <div class="h-full rounded-full {{ $topic['score'] >= 70 ? 'bg-emerald-500' : ($topic['score'] >= 45 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $topic['score'] }}%"></div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 text-sm font-semibold text-slate-500 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-400">Complete the assessment to unlock topic analytics.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">Today</p>
                                <h2 class="mt-2 text-2xl font-extrabold">Action queue</h2>
                                <p class="mt-2 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">Manage today's review, practice, and roadmap tasks from one place.</p>
                            </div>
                            <div class="grid gap-2 sm:flex sm:flex-wrap sm:justify-end">
                                @if ($hasCompletedAssessment)
                                    <a href="{{ route('assessment.review') }}" class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-extrabold text-rose-700 transition hover:bg-rose-100 dark:border-rose-900 dark:bg-rose-950/30 dark:text-rose-300">Review wrong answers</a>
                                @endif
                                @if ($hasCompletedAssessment)
                                    <button type="button" class="rounded-xl bg-slate-950 px-4 py-3 text-sm font-extrabold text-white transition hover:bg-slate-800 dark:bg-blue-600 dark:hover:bg-blue-500" data-dashboard-mark-all>Mark all</button>
                                @else
                                    <a href="{{ route('assessment.show') }}" class="rounded-xl bg-slate-950 px-4 py-3 text-sm font-extrabold text-white transition hover:bg-slate-800 dark:bg-blue-600 dark:hover:bg-blue-500">Start assessment</a>
                                @endif
                            </div>
                        </div>

                        <div class="mt-4 hidden rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-extrabold text-emerald-700 dark:border-emerald-950 dark:bg-emerald-950/20 dark:text-emerald-300" data-dashboard-todo-complete>
                            Nice. Today's action queue is complete.
                        </div>

                        <div class="mt-6 grid gap-3" data-dashboard-todos>
                            @foreach ($todayTodos as $todoIndex => $todo)
                                <label class="flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-blue-300 hover:bg-blue-50 dark:border-slate-700 dark:bg-slate-950 dark:hover:border-blue-500 dark:hover:bg-blue-950/20">
                                    <input type="checkbox" class="mt-1 h-5 w-5 shrink-0 rounded border-slate-300 text-blue-600 focus:ring-blue-500" data-dashboard-todo-check data-todo-key="dashboard-todo-user-{{ $user->id }}-attempt-{{ $attempt?->id ?? 'pending' }}-{{ $todoIndex }}">
                                    <span class="min-w-0 flex-1">
                                        <span class="block text-sm font-extrabold leading-6">{{ $todo['task'] }}</span>
                                        <span class="mt-3 flex flex-wrap gap-2">
                                            <span class="rounded-full bg-white px-3 py-1 text-xs font-extrabold text-slate-600 dark:bg-slate-900 dark:text-slate-300">{{ $todo['meta'] }}</span>
                                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-extrabold text-blue-700 dark:bg-blue-950/40 dark:text-blue-300">{{ $todo['time'] }}</span>
                                            @if ($hasCompletedAssessment && $todoIndex === 0)
                                                <a href="{{ route('assessment.review') }}" class="rounded-full bg-rose-50 px-3 py-1 text-xs font-extrabold text-rose-700 dark:bg-rose-950/40 dark:text-rose-300">Open review</a>
                                            @endif
                                        </span>
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        @if ($hasCompletedAssessment)
                            <div class="mt-5 rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-700 dark:text-blue-300">Assessment answer review</p>
                                        <p class="mt-2 text-sm font-extrabold text-slate-900 dark:text-slate-100">Questions you marked correct and wrong</p>
                                        <p class="mt-1 text-xs font-bold text-slate-600 dark:text-slate-300">{{ $correctCount }} correct, {{ $wrongCount }} wrong from this assessment.</p>
                                    </div>
                                    <a href="{{ route('assessment.review') }}" class="shrink-0 rounded-full bg-white px-3 py-1 text-xs font-extrabold text-blue-700 ring-1 ring-blue-200 dark:bg-slate-900 dark:text-blue-300 dark:ring-blue-900">Open review</a>
                                </div>

                                <div class="mt-4 grid gap-3">
                                    @forelse ($answerReviewPreview as $answer)
                                        <a href="{{ route('assessment.review') }}" class="block rounded-xl bg-white p-4 transition hover:ring-2 {{ $answer->is_correct ? 'hover:ring-emerald-200 dark:hover:ring-emerald-900' : 'hover:ring-rose-200 dark:hover:ring-rose-900' }} dark:bg-slate-900">
                                            <div class="flex items-start justify-between gap-3">
                                                <p class="text-xs font-extrabold uppercase tracking-[0.14em] {{ $answer->is_correct ? 'text-emerald-600 dark:text-emerald-300' : 'text-rose-600 dark:text-rose-300' }}">{{ $answer->question->topic }}</p>
                                                <span class="shrink-0 rounded-full {{ $answer->is_correct ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-300' }} px-3 py-1 text-xs font-extrabold">{{ $answer->is_correct ? 'Correct' : 'Wrong' }}</span>
                                            </div>
                                            <p class="mt-2 max-h-12 overflow-hidden text-sm font-bold leading-6 text-slate-800 dark:text-slate-100">{{ $answer->question->question }}</p>
                                            <div class="mt-3 grid gap-2 text-xs font-semibold text-slate-500 dark:text-slate-400">
                                                <p>Your answer: <span class="font-extrabold {{ $answer->is_correct ? 'text-emerald-700 dark:text-emerald-300' : 'text-rose-700 dark:text-rose-300' }}">{{ $answer->selected_answer ?: 'No answer selected' }}</span></p>
                                                @unless ($answer->is_correct)
                                                    <p>Correct answer: <span class="font-extrabold text-emerald-700 dark:text-emerald-300">{{ $answer->question->correct_answer }}</span></p>
                                                @endunless
                                            </div>
                                        </a>
                                    @empty
                                        <a href="{{ route('assessment.review') }}" class="block rounded-xl bg-white p-4 text-sm font-bold text-slate-600 transition hover:ring-2 hover:ring-blue-200 dark:bg-slate-900 dark:text-slate-300 dark:hover:ring-blue-900">No saved answers found. Open assessment review to see your completed result.</a>
                                    @endforelse
                                </div>
                            </div>
                        @endif

                        <button type="button" class="mt-4 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-extrabold text-slate-600 hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 sm:w-auto" data-dashboard-clear-todos>Clear completed</button>
                    </div>
                </section>

                @if ($hasCompletedAssessment)
                    <section class="mt-5 rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                            <div>
                                <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-rose-600 dark:text-rose-300">Weak area practice map</p>
                                <h2 class="mt-2 text-2xl font-extrabold">What to practice inside each weak topic</h2>
                                <p class="mt-3 max-w-3xl text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">Based on your onboarding goal, selected interests, and assessment answers, these are the exact subtopics to repair before generating or following the roadmap.</p>
                            </div>
                            <span class="rounded-xl bg-rose-50 px-4 py-3 text-sm font-extrabold text-rose-700 dark:bg-rose-950/30 dark:text-rose-300">{{ count($weakAreaPracticePlan) }} focus areas</span>
                        </div>

                        <div class="mt-6 grid gap-4 lg:grid-cols-2">
                            @foreach ($weakAreaPracticePlan as $area)
                                <article class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <p class="text-lg font-extrabold">{{ $area['topic'] }}</p>
                                            <p class="mt-1 text-sm font-semibold text-slate-500 dark:text-slate-400">{{ $area['wrong'] }} missed, {{ $area['correct'] }} correct</p>
                                        </div>
                                        <span class="rounded-full {{ $area['score'] >= 70 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300' : ($area['score'] >= 45 ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-300') }} px-3 py-1 text-xs font-extrabold">{{ $area['score'] }}%</span>
                                    </div>
                                    <div class="mt-4 flex flex-wrap gap-2">
                                        @foreach ($area['focus_items'] as $focusItem)
                                            <span class="rounded-full bg-white px-3 py-2 text-xs font-extrabold text-slate-700 ring-1 ring-slate-200 dark:bg-slate-900 dark:text-slate-200 dark:ring-slate-700">{{ $focusItem }}</span>
                                        @endforeach
                                    </div>
                                    <p class="mt-4 rounded-xl bg-white p-3 text-sm font-semibold leading-6 text-slate-600 dark:bg-slate-900 dark:text-slate-300">{{ $area['practice_goal'] }}</p>
                                </article>
                            @endforeach
                        </div>
                    </section>
                @endif

                <section class="mt-5 grid gap-5 xl:grid-cols-[1fr_0.85fr]">
                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                        <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                            <div>
                                <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-indigo-600 dark:text-indigo-300">Platform support</p>
                                <h2 class="mt-2 text-2xl font-extrabold">Facing a difficulty?</h2>
                                <p class="mt-3 max-w-3xl text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">Tell the admin what is not working for you. Include the page, what you clicked, and what you expected so the issue can be fixed faster.</p>
                            </div>
                            <span class="rounded-xl bg-indigo-50 px-4 py-3 text-sm font-extrabold text-indigo-700 dark:bg-indigo-950/30 dark:text-indigo-300">Admin review</span>
                        </div>

                        <form method="POST" action="{{ route('support-tickets.store') }}" class="mt-6 grid gap-4">
                            @csrf
                            <div class="grid gap-4 md:grid-cols-3">
                                <label class="grid gap-2">
                                    <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Area</span>
                                    <select name="category" class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:ring-indigo-950">
                                        @foreach (['Assessment', 'Roadmap', 'Dashboard', 'Login', 'Resources', 'Account deletion', 'Malpractice report', 'Other'] as $category)
                                            <option value="{{ $category }}">{{ $category }}</option>
                                        @endforeach
                                    </select>
                                </label>
                                <label class="grid gap-2">
                                    <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Priority</span>
                                    <select name="priority" class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:ring-indigo-950">
                                        @foreach (['Low', 'Medium', 'High'] as $priority)
                                            <option value="{{ $priority }}" @selected($priority === 'Medium')>{{ $priority }}</option>
                                        @endforeach
                                    </select>
                                </label>
                                <label class="grid gap-2 md:col-span-1">
                                    <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Subject</span>
                                    <input name="subject" value="{{ old('subject') }}" maxlength="120" placeholder="Example: roadmap video not loading" class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:ring-indigo-950">
                                </label>
                            </div>
                            <label class="grid gap-2">
                                <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">What happened?</span>
                                <textarea name="message" rows="4" maxlength="1200" placeholder="Describe the issue clearly..." class="resize-y rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold leading-6 text-slate-800 outline-none transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100 dark:focus:ring-indigo-950">{{ old('message') }}</textarea>
                            </label>
                            @if ($errors->any())
                                <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-bold text-rose-700 dark:border-rose-950 dark:bg-rose-950/20 dark:text-rose-300">
                                    Please complete the support form correctly before submitting.
                                </div>
                            @endif
                            <button class="inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-indigo-700 sm:w-auto">Send to admin</button>
                        </form>
                    </div>

                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                        <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-indigo-600 dark:text-indigo-300">Your reports</p>
                        <h2 class="mt-2 text-2xl font-extrabold">Recent tickets</h2>
                        <p class="mt-2 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">These reports are saved to this account and stay visible after logout, login, and onboarding edits.</p>
                        <div class="mt-6 grid gap-3">
                            @forelse ($supportTickets as $ticket)
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="truncate text-sm font-extrabold">{{ $ticket->subject }}</p>
                                            <p class="mt-1 text-xs font-bold text-slate-500 dark:text-slate-400">{{ $ticket->category }} - {{ $ticket->created_at->diffForHumans() }}</p>
                                        </div>
                                        <span class="shrink-0 rounded-full {{ $ticket->status === 'Resolved' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300' : ($ticket->status === 'In Progress' ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-300' : 'bg-indigo-50 text-indigo-700 dark:bg-indigo-950/30 dark:text-indigo-300') }} px-3 py-1 text-xs font-extrabold">{{ $ticket->status }}</span>
                                    </div>
                                    @if ($ticket->admin_notes)
                                        <p class="mt-3 rounded-xl bg-white p-3 text-sm font-semibold leading-6 text-slate-600 dark:bg-slate-900 dark:text-slate-300">{{ $ticket->admin_notes }}</p>
                                    @endif
                                </div>
                            @empty
                                <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 text-sm font-semibold text-slate-500 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-400">No support tickets yet.</div>
                            @endforelse
                        </div>
                    </div>
                </section>

                <section class="mt-5 grid gap-5 xl:grid-cols-[0.9fr_1.1fr]">
                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                        <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">Strength map</p>
                        <h2 class="mt-2 text-2xl font-extrabold">Repair and leverage</h2>
                        <div class="mt-6 grid gap-4 md:grid-cols-2">
                            <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-900 dark:bg-rose-950/30">
                                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-rose-700 dark:text-rose-300">Repair first</p>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @forelse (($insights['weak_areas'] ?? []) as $area)
                                        <span class="rounded-full bg-white px-3 py-2 text-xs font-extrabold text-rose-700 dark:bg-slate-900 dark:text-rose-300">{{ $area }}</span>
                                    @empty
                                        <span class="text-sm font-semibold text-rose-700 dark:text-rose-300">No weak areas yet.</span>
                                    @endforelse
                                </div>
                            </div>
                            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900 dark:bg-emerald-950/30">
                                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-emerald-700 dark:text-emerald-300">Use for momentum</p>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    @forelse (($insights['strong_areas'] ?? []) as $area)
                                        <span class="rounded-full bg-white px-3 py-2 text-xs font-extrabold text-emerald-700 dark:bg-slate-900 dark:text-emerald-300">{{ $area }}</span>
                                    @empty
                                        <span class="text-sm font-semibold text-emerald-700 dark:text-emerald-300">No strong areas yet.</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                            <div class="max-w-xl">
                                <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">AI roadmap</p>
                                <h2 class="mt-2 text-2xl font-extrabold">Generate your detailed learning path</h2>
                                <p class="mt-3 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $hasCompletedAssessment ? 'Generate a structured roadmap with weekly tasks, resources, projects, and YouTube channels based on the latest profile and assessment.' : 'Roadmap generation is locked until the one-time assessment is complete.' }}</p>
                            </div>
                            <div class="rounded-xl bg-slate-950 px-4 py-3 text-sm font-extrabold text-white dark:bg-slate-800">
                                {{ $roadmapReady ? 'Generated ' . $attempt->roadmap_generated_at->diffForHumans() : ($hasCompletedAssessment ? 'Not generated' : 'Assessment required') }}
                            </div>
                        </div>

                        <div class="mt-6 grid gap-3 sm:grid-cols-2">
                            @if ($hasCompletedAssessment)
                                <form method="POST" action="{{ route('roadmap.generate') }}" data-roadmap-generate-form>
                                    @csrf
                                    <button class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700" data-roadmap-generate-button>
                                        <span data-ai-icon class="hidden h-5 w-5 items-center justify-center rounded-full bg-white/20 text-xs">AI</span>
                                        <span data-ai-label>{{ $roadmapReady ? 'Regenerate roadmap' : 'Generate roadmap' }}</span>
                                    </button>
                                </form>
                                <a href="{{ route('roadmap.show') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm font-extrabold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">View roadmap</a>
                            @else
                                <a href="{{ route('assessment.show') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">Complete assessment</a>
                                <span class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-slate-100 px-5 py-3 text-sm font-extrabold text-slate-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-400">Roadmap locked</span>
                            @endif
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/70 px-4 backdrop-blur-md" data-roadmap-overlay>
        <div class="w-full max-w-md rounded-2xl border border-white/10 bg-white p-6 text-center shadow-2xl dark:bg-slate-900">
            <div class="mx-auto grid h-20 w-20 place-items-center rounded-2xl bg-blue-600 text-xl font-extrabold text-white shadow-lg shadow-blue-600/30" data-ai-overlay-icon>G</div>
            <h2 class="mt-5 text-2xl font-extrabold">Gemini 2.5 Pro assistant is generating</h2>
            <p class="mt-3 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300" data-ai-overlay-status>Analyzing your assessment, onboarding goal, and weak areas...</p>
            <div class="mt-6 h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                <div class="h-full w-1/2 animate-pulse rounded-full bg-blue-600"></div>
            </div>
        </div>
    </div>

    <script>
        @if (session('block_back_navigation'))
            (() => {
                const lockedUrl = window.location.href;
                const historyBufferSize = 12;
                let refillQueued = false;
                const lockedState = (index) => ({ skillweaveFlowLocked: true, index });
                const refillHistoryBuffer = () => {
                    window.history.replaceState(lockedState(0), '', lockedUrl);
                    for (let index = 1; index <= historyBufferSize; index += 1) {
                        window.history.pushState(lockedState(index), '', lockedUrl);
                    }
                };
                refillHistoryBuffer();
                window.addEventListener('popstate', () => {
                    if (refillQueued) return;
                    refillQueued = true;
                    window.setTimeout(() => {
                        refillHistoryBuffer();
                        refillQueued = false;
                    }, 0);
                });
                window.addEventListener('pageshow', (event) => {
                    if (event.persisted) refillHistoryBuffer();
                });
            })();
        @endif

        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('[data-dashboard-sidebar]');
            const openButton = document.querySelector('[data-dashboard-sidebar-button]');
            const closeButton = document.querySelector('[data-dashboard-sidebar-close]');
            const overlay = document.querySelector('[data-dashboard-sidebar-overlay]');
            const dashboardTodoChecks = Array.from(document.querySelectorAll('[data-dashboard-todo-check]'));
            const dashboardMarkAll = document.querySelector('[data-dashboard-mark-all]');
            const dashboardClearTodos = document.querySelector('[data-dashboard-clear-todos]');
            const dashboardComplete = document.querySelector('[data-dashboard-todo-complete]');

            const updateDashboardTodoState = () => {
                const allComplete = dashboardTodoChecks.length > 0 && dashboardTodoChecks.every((check) => check.checked);
                dashboardComplete?.classList.toggle('hidden', !allComplete);
                dashboardTodoChecks.forEach((check) => {
                    localStorage.setItem(check.dataset.todoKey, check.checked ? '1' : '0');
                    check.closest('label')?.classList.toggle('opacity-70', check.checked);
                    check.closest('label')?.classList.toggle('ring-2', check.checked);
                    check.closest('label')?.classList.toggle('ring-emerald-200', check.checked);
                });
            };

            dashboardTodoChecks.forEach((check) => {
                check.checked = localStorage.getItem(check.dataset.todoKey) === '1';
                check.addEventListener('change', updateDashboardTodoState);
            });
            dashboardMarkAll?.addEventListener('click', () => {
                dashboardTodoChecks.forEach((check) => { check.checked = true; });
                updateDashboardTodoState();
            });
            dashboardClearTodos?.addEventListener('click', () => {
                dashboardTodoChecks.forEach((check) => { check.checked = false; });
                updateDashboardTodoState();
            });
            updateDashboardTodoState();

            document.querySelectorAll('[data-roadmap-generate-form]').forEach((generateForm) => {
                generateForm.addEventListener('submit', () => {
                    const button = generateForm.querySelector('[data-roadmap-generate-button]');
                    const icon = generateForm.querySelector('[data-ai-icon]');
                    const label = generateForm.querySelector('[data-ai-label]');
                    const roadmapOverlay = document.querySelector('[data-roadmap-overlay]');
                    const overlayIcon = document.querySelector('[data-ai-overlay-icon]');
                    const overlayStatus = document.querySelector('[data-ai-overlay-status]');
                    const frames = ['G', '2.5', 'AI', '{}', 'OK'];
                    const statuses = [
                        'Analyzing your assessment, onboarding goal, and weak areas...',
                        'Finding what to practice inside each topic...',
                        'Building weekly focus blocks with Gemini 2.5 Pro...',
                        'Adding MDN, YouTube, and practice resources...',
                        'Preparing project milestones and checkpoints...'
                    ];
                    let frameIndex = 0;

                    button.disabled = true;
                    button.classList.add('opacity-80', 'cursor-wait');
                    icon.classList.remove('hidden');
                    icon.classList.add('inline-flex', 'animate-pulse');
                    label.textContent = 'AI is generating...';
                    roadmapOverlay?.classList.remove('hidden');
                    roadmapOverlay?.classList.add('flex');
                    document.body.classList.add('overflow-hidden');

                    window.setInterval(() => {
                        const nextFrame = frames[frameIndex % frames.length];
                        icon.textContent = nextFrame;
                        if (overlayIcon) overlayIcon.textContent = nextFrame;
                        if (overlayStatus) overlayStatus.textContent = statuses[frameIndex % statuses.length];
                        frameIndex += 1;
                    }, 450);
                });
            });

            if (!sidebar || !openButton || !overlay) {
                return;
            }

            const setDrawerState = function (isOpen) {
                sidebar.classList.toggle('translate-x-0', isOpen);
                sidebar.classList.toggle('-translate-x-full', !isOpen);
                overlay.classList.toggle('opacity-0', !isOpen);
                overlay.classList.toggle('pointer-events-none', !isOpen);
                overlay.classList.toggle('opacity-100', isOpen);
                openButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                document.body.classList.toggle('overflow-hidden', isOpen);
            };

            setDrawerState(false);
            openButton.addEventListener('click', () => setDrawerState(openButton.getAttribute('aria-expanded') !== 'true'));
            closeButton?.addEventListener('click', () => setDrawerState(false));
            overlay.addEventListener('click', () => setDrawerState(false));
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    setDrawerState(false);
                }
            });
        });
    </script>
</body>
</html>
