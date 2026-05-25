<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onboarding | SkillWeave</title>
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
<body class="min-h-screen bg-slate-100 text-slate-950 dark:bg-slate-950 dark:text-slate-100">
    @php
        $usesDashboardShell = filled($profile?->id) || filled($user?->onboarded_at);
    @endphp

    @if ($usesDashboardShell)
        <div class="fixed inset-0 z-30 bg-slate-950/60 opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden" data-dashboard-sidebar-overlay></div>

        <div class="min-h-screen lg:grid lg:grid-cols-[19rem_1fr]">
            <div class="sticky top-0 z-30 flex items-center justify-between border-b border-slate-200 bg-white/90 px-4 py-3 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/90 lg:hidden">
                <div>
                    <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-slate-400">SkillWeave</p>
                    <p class="text-sm font-extrabold">Manage profile</p>
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
                :dailyMinutes="$profile?->daily_learning_time ?? 45"
                :pace="$user->learning_pace ?? 'Steady'"
            />
    @endif

    <main class="{{ $usesDashboardShell ? 'px-4 py-5 sm:px-6 lg:col-start-2 lg:px-8' : 'mx-auto max-w-[1500px] px-4 py-5 sm:px-6 lg:px-8' }}">
        @if ($usesDashboardShell)
            <div class="mx-auto max-w-[1500px]">
        @endif

        <section class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <div class="border-b border-slate-200 p-5 dark:border-slate-800 sm:p-6 lg:p-8">
                <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_24rem] xl:items-stretch">
                    <div>
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                            <span class="flex h-12 w-12 items-center justify-center rounded-xl bg-slate-950 text-lg font-extrabold text-white dark:bg-blue-600">S</span>
                            <span>
                                <span class="block text-lg font-extrabold">SkillWeave</span>
                                <span class="block text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Learner onboarding</span>
                            </span>
                        </a>
                        <p class="mt-7 text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400" data-step-counter>Step 1 of 6</p>
                        <h1 class="mt-3 max-w-4xl text-3xl font-extrabold tracking-tight sm:text-4xl">Build the profile that powers your assessment, dashboard, and roadmap.</h1>
                        <p class="mt-4 max-w-3xl text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">Choose your goal, current level, learning rhythm, and preferences. SkillWeave turns these inputs into a focused assessment and a roadmap that adapts to the result.</p>
                    </div>

                    <div class="rounded-xl bg-slate-950 p-5 text-white">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400">Progress</p>
                                <p class="mt-2 text-2xl font-extrabold" data-progress-label>16% complete</p>
                            </div>
                            <button type="button" data-theme-toggle class="rounded-xl border border-white/15 bg-white/10 px-4 py-3 text-sm font-extrabold text-white transition hover:bg-white/15">Theme</button>
                        </div>
                        <div class="mt-5 h-2 overflow-hidden rounded-full bg-white/10">
                            <div class="h-full rounded-full bg-blue-500 transition-all duration-300" data-progress-bar style="width: 16.6%"></div>
                        </div>
                        <p class="mt-5 text-sm font-semibold leading-6 text-slate-300" data-step-tip>Start with the destination so recommendations can branch intelligently.</p>
                    </div>
                </div>

                <div class="mt-7 overflow-x-auto pb-1">
                    <div class="grid min-w-[760px] grid-cols-6 gap-2" data-timeline>
                        @foreach ([
                            ['title' => 'Goal', 'text' => 'Path and role'],
                            ['title' => 'Level', 'text' => 'Experience'],
                            ['title' => 'Signals', 'text' => 'Focus areas'],
                            ['title' => 'Routine', 'text' => 'Study rhythm'],
                            ['title' => 'Style', 'text' => 'Preferences'],
                            ['title' => 'Review', 'text' => 'Assessment'],
                        ] as $index => $item)
                            <button type="button" data-step-jump="{{ $index }}" class="rounded-xl border border-slate-200 bg-slate-50 p-3 text-left transition hover:border-blue-300 dark:border-slate-700 dark:bg-slate-950">
                                <span data-step-marker class="inline-flex h-8 w-8 items-center justify-center rounded-lg border border-slate-300 bg-white text-xs font-extrabold text-slate-600 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-300">{{ $index + 1 }}</span>
                                <span class="mt-3 block text-sm font-extrabold">{{ $item['title'] }}</span>
                                <span data-step-status class="mt-1 block text-[11px] font-extrabold uppercase tracking-[0.16em] text-slate-400">{{ $index === 0 ? 'In progress' : 'Locked' }}</span>
                                <span class="mt-2 block text-xs font-semibold leading-5 text-slate-500 dark:text-slate-400">{{ $item['text'] }}</span>
                                @if ($index < 5)
                                    <span data-step-line class="hidden"></span>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="grid gap-0 xl:grid-cols-[minmax(0,1fr)_25rem]">
                <div class="p-5 sm:p-6 lg:p-8">
                    <form method="POST" action="{{ route('onboarding.store') }}" data-onboarding-form>
                        @csrf

                        @if ($errors->any())
                            <div class="mb-5 rounded-xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-700 dark:border-rose-900 dark:bg-rose-950/30 dark:text-rose-300">
                                Please review the highlighted profile fields and try again.
                            </div>
                        @endif

                        <section class="space-y-5" data-step-panel>
                            <div class="grid gap-4 lg:grid-cols-2">
                                <label class="block">
                                    <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Education level</span>
                                    <select name="education_level" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                        @foreach (['School', 'College', 'Graduate', 'Professional'] as $level)
                                            <option value="{{ $level }}" @selected(old('education_level', 'College') === $level)>{{ $level }}</option>
                                        @endforeach
                                    </select>
                                </label>

                                <label class="block">
                                    <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Career stage</span>
                                    <select name="career_stage" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                        @foreach (['Student', 'Early career', 'Switching careers', 'Working professional'] as $stage)
                                            <option value="{{ $stage }}" @selected(old('career_stage', 'Student') === $stage)>{{ $stage }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>

                            <div data-field="goal_choice" class="rounded-xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950">
                                <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                                    <div>
                                        <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-blue-600 dark:text-blue-400">Goal direction</p>
                                        <h2 class="mt-2 text-2xl font-extrabold">Choose the outcome to optimize for</h2>
                                        <p class="mt-2 max-w-2xl text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">This decides the assessment coverage and the first roadmap draft.</p>
                                    </div>
                                    <label class="block">
                                        <span class="sr-only">Search goals</span>
                                        <input type="search" data-goal-search placeholder="Search goals or stacks" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 lg:w-72">
                                    </label>
                                </div>

                                <div class="mt-5 grid gap-3 md:grid-cols-2" data-goal-types></div>

                                <div class="mt-5 rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                        <div>
                                            <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Selected goal</p>
                                            <p class="mt-2 text-lg font-extrabold" data-selected-goal>{{ old('learning_goal', 'Choose a specific goal') }}</p>
                                        </div>
                                        <input type="hidden" name="learning_goal" value="{{ old('learning_goal') }}">
                                    </div>
                                    <div class="mt-5 grid gap-3 md:grid-cols-2" data-goal-options></div>
                                </div>
                                <p class="mt-3 hidden text-sm font-semibold text-rose-600" data-error-for="goal_choice"></p>
                            </div>

                            <div data-field="target_role">
                                <label class="block">
                                    <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Target role or outcome</span>
                                    <input name="target_role" value="{{ old('target_role') }}" placeholder="Example: Product-focused frontend engineer" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-100">
                                </label>
                                <p class="mt-2 hidden text-sm font-semibold text-rose-600" data-error-for="target_role"></p>
                            </div>
                        </section>

                        <section class="hidden space-y-5" data-step-panel>
                            <div data-field="experience" class="rounded-xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950">
                                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-blue-600 dark:text-blue-400">Current level</p>
                                <h2 class="mt-2 text-2xl font-extrabold">Set the starting depth</h2>
                                <div class="mt-5 grid gap-3 md:grid-cols-3">
                                    @foreach ([
                                        ['value' => 'Beginner', 'text' => 'Starting from the fundamentals'],
                                        ['value' => 'Intermediate', 'text' => 'Comfortable with the basics'],
                                        ['value' => 'Advanced', 'text' => 'Ready for deeper review'],
                                    ] as $item)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="skill_level" value="{{ $item['value'] }}" class="peer sr-only" @checked(old('skill_level', 'Beginner') === $item['value'])>
                                            <span class="block min-h-32 rounded-xl border border-slate-200 bg-white p-4 transition peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:border-slate-700 dark:bg-slate-900 dark:peer-checked:bg-blue-950/40">
                                                <span class="block text-lg font-extrabold">{{ $item['value'] }}</span>
                                                <span class="mt-2 block text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $item['text'] }}</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>

                                <div class="mt-5 grid gap-4 lg:grid-cols-2">
                                    <label class="block">
                                        <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Relevant experience</span>
                                        <select name="experience_years" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                            @foreach (range(0, 10) as $year)
                                                <option value="{{ $year }}" @selected((int) old('experience_years', 0) === $year)>{{ $year }} {{ $year === 1 ? 'year' : 'years' }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    <label class="block">
                                        <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Current challenge</span>
                                        <textarea name="bio" rows="4" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" placeholder="Example: I can build small pages but struggle to structure larger apps.">{{ old('bio') }}</textarea>
                                    </label>
                                </div>
                                <p class="mt-3 hidden text-sm font-semibold text-rose-600" data-error-for="experience"></p>
                            </div>
                        </section>

                        <section class="hidden space-y-5" data-step-panel>
                            <div data-field="interests" class="rounded-xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950">
                                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-blue-600 dark:text-blue-400">Focus areas</p>
                                <h2 class="mt-2 text-2xl font-extrabold">Pick assessment signals</h2>
                                <div class="mt-5 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                                    @foreach (['Frontend', 'Backend', 'Full Stack', 'AI', 'Data Science', 'DSA', 'Mobile', 'Projects', 'DevOps'] as $interest)
                                        <label data-chip class="cursor-pointer">
                                            <input type="checkbox" name="interests[]" value="{{ $interest }}" class="peer sr-only" @checked(in_array($interest, old('interests', ['Frontend']), true))>
                                            <span class="flex min-h-28 items-start justify-between gap-4 rounded-xl border border-slate-200 bg-white p-4 transition peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:border-slate-700 dark:bg-slate-900 dark:peer-checked:bg-blue-950/40">
                                                <span>
                                                    <span class="block text-base font-extrabold">{{ $interest }}</span>
                                                    <span class="mt-2 block text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">Weights roadmap topics and assessment coverage.</span>
                                                </span>
                                                <span data-chip-indicator class="inline-flex h-8 min-w-8 items-center justify-center rounded-lg bg-slate-100 px-2 text-xs font-extrabold text-slate-700 dark:bg-slate-800 dark:text-slate-200">+</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="mt-3 hidden text-sm font-semibold text-rose-600" data-error-for="interests"></p>
                            </div>

                            <div data-field="strengths" class="rounded-xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950">
                                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-blue-600 dark:text-blue-400">Current strengths</p>
                                <h2 class="mt-2 text-2xl font-extrabold">What is already working?</h2>
                                <div class="mt-5 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                                    @foreach (['Consistency', 'Logic', 'Design sense', 'Debugging', 'Communication', 'Fast learning'] as $strength)
                                        <label data-chip class="cursor-pointer">
                                            <input type="checkbox" name="strengths[]" value="{{ $strength }}" class="peer sr-only" @checked(in_array($strength, old('strengths', ['Consistency']), true))>
                                            <span class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-white p-4 transition peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:border-slate-700 dark:bg-slate-900 dark:peer-checked:bg-blue-950/40">
                                                <span class="text-base font-extrabold">{{ $strength }}</span>
                                                <span data-chip-indicator class="inline-flex h-8 min-w-8 items-center justify-center rounded-lg bg-slate-100 px-2 text-xs font-extrabold text-slate-700 dark:bg-slate-800 dark:text-slate-200">+</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="mt-3 hidden text-sm font-semibold text-rose-600" data-error-for="strengths"></p>
                            </div>
                        </section>

                        <section class="hidden space-y-5" data-step-panel>
                            <div data-field="routine" class="rounded-xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950">
                                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-blue-600 dark:text-blue-400">Learning routine</p>
                                <h2 class="mt-2 text-2xl font-extrabold">Make the plan realistic</h2>
                                <div class="mt-5 grid gap-4 lg:grid-cols-3">
                                    <label class="block">
                                        <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Daily time</span>
                                        <select name="daily_learning_time" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                            @foreach ([30, 45, 60, 90, 120, 180] as $minutes)
                                                <option value="{{ $minutes }}" @selected((int) old('daily_learning_time', 45) === $minutes)>{{ $minutes }} minutes</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    <label class="block">
                                        <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Days per week</span>
                                        <select name="weekly_days" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                            @foreach (range(1, 7) as $days)
                                                <option value="{{ $days }}" @selected((int) old('weekly_days', 5) === $days)>{{ $days }} days</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    <label class="block">
                                        <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Best study window</span>
                                        <select name="preferred_study_window" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                            @foreach (['Morning', 'Afternoon', 'Evening', 'Late night', 'Weekend only'] as $window)
                                                <option value="{{ $window }}" @selected(old('preferred_study_window', 'Evening') === $window)>{{ $window }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                                <p class="mt-3 hidden text-sm font-semibold text-rose-600" data-error-for="routine"></p>
                            </div>
                        </section>

                        <section class="hidden space-y-5" data-step-panel>
                            <div class="grid gap-5 lg:grid-cols-2">
                                <div data-field="preferred_language" class="rounded-xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950">
                                    <label class="block">
                                        <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Preferred language</span>
                                        <input name="preferred_language" value="{{ old('preferred_language', 'English') }}" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                    </label>
                                    <p class="mt-3 hidden text-sm font-semibold text-rose-600" data-error-for="preferred_language"></p>
                                </div>
                                <div data-field="motivation" class="rounded-xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950">
                                    <label class="block">
                                        <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Primary motivation</span>
                                        <select name="motivation" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                            @foreach (['Get job-ready', 'Build confidence', 'Ship stronger projects', 'Prepare for interviews', 'Switch into a new domain'] as $motivation)
                                                <option value="{{ $motivation }}" @selected(old('motivation', 'Get job-ready') === $motivation)>{{ $motivation }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    <p class="mt-3 hidden text-sm font-semibold text-rose-600" data-error-for="motivation"></p>
                                </div>
                            </div>

                            <div data-field="preferences" class="rounded-xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950">
                                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-blue-600 dark:text-blue-400">Learning preferences</p>
                                <h2 class="mt-2 text-2xl font-extrabold">Choose the support style</h2>
                                <div class="mt-5 grid gap-4 lg:grid-cols-2">
                                    @foreach ([
                                        ['name' => 'learning_format', 'label' => 'Learning format', 'value' => old('learning_format', 'Projects first'), 'options' => ['Projects first', 'Videos + quizzes', 'Reading + practice', 'Case studies + builds']],
                                        ['name' => 'learning_pace', 'label' => 'Learning pace', 'value' => old('learning_pace', 'Steady'), 'options' => ['Steady', 'Fast track', 'Weekend focused']],
                                        ['name' => 'project_preference', 'label' => 'Project style', 'value' => old('project_preference', 'Real-world dashboards'), 'options' => ['Real-world dashboards', 'Mini daily exercises', 'Portfolio projects', 'Interview-style challenges']],
                                        ['name' => 'support_style', 'label' => 'Support style', 'value' => old('support_style', 'Mentor checkpoints'), 'options' => ['Mentor checkpoints', 'Self-paced with prompts', 'Weekly review structure', 'Project-first coaching']],
                                    ] as $select)
                                        <label class="block">
                                            <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">{{ $select['label'] }}</span>
                                            <select name="{{ $select['name'] }}" class="mt-2 w-full rounded-xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                                @foreach ($select['options'] as $option)
                                                    <option value="{{ $option }}" @selected($select['value'] === $option)>{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="mt-3 hidden text-sm font-semibold text-rose-600" data-error-for="preferences"></p>
                            </div>
                        </section>

                        <section class="hidden space-y-5" data-step-panel>
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950">
                                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-blue-600 dark:text-blue-400">Review</p>
                                <h2 class="mt-2 text-2xl font-extrabold">Ready to create the assessment</h2>
                                <p class="mt-3 max-w-3xl text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">This saves the profile and creates a 25-question assessment. The dashboard and roadmap will use your assessment performance to identify weak topics, strong topics, tasks, and resources.</p>
                                <div class="mt-6 grid gap-4 lg:grid-cols-3">
                                    @foreach ([
                                        ['title' => 'Assessment', 'value' => '25 questions', 'text' => 'Goal-aware topic coverage'],
                                        ['title' => 'Dashboard', 'value' => 'Analytics', 'text' => 'Score, weak areas, and action queue'],
                                        ['title' => 'Roadmap', 'value' => 'Weekly plan', 'text' => 'Tasks, resources, and project milestones'],
                                    ] as $item)
                                        <div class="rounded-xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                                            <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">{{ $item['title'] }}</p>
                                            <p class="mt-2 text-xl font-extrabold">{{ $item['value'] }}</p>
                                            <p class="mt-2 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $item['text'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </section>

                        <div class="mt-8 flex flex-col gap-3 border-t border-slate-200 pt-6 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
                            <button type="button" data-prev-step class="rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-extrabold text-slate-700 transition hover:border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">Back</button>
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                                <button type="button" data-next-step class="rounded-xl bg-slate-950 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-slate-800 dark:bg-blue-600 dark:hover:bg-blue-700">Continue</button>
                                <button type="submit" data-submit-step class="hidden items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                                    <span data-submit-spinner class="hidden h-4 w-4 rounded-full border-2 border-white/40 border-t-white"></span>
                                    <span data-submit-label>Save profile and start assessment</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <aside class="border-t border-slate-200 bg-slate-50 p-5 dark:border-slate-800 dark:bg-slate-950 sm:p-6 xl:border-l xl:border-t-0">
                    <div class="rounded-xl bg-slate-950 p-5 text-white">
                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400">Live profile</p>
                        <h2 class="mt-3 text-2xl font-extrabold">Preview</h2>
                        <div class="mt-5 grid gap-3">
                            @foreach ([
                                ['label' => 'Goal', 'attr' => 'data-summary-goal', 'fallback' => 'Choose a goal'],
                                ['label' => 'Target role', 'attr' => 'data-summary-role', 'fallback' => 'Add your target role'],
                                ['label' => 'Level', 'attr' => 'data-summary-level', 'fallback' => 'Beginner | 0 yrs'],
                                ['label' => 'Focus areas', 'attr' => 'data-summary-interests', 'fallback' => 'Pick your focus areas'],
                                ['label' => 'Strengths', 'attr' => 'data-summary-strengths', 'fallback' => 'Highlight what works'],
                            ] as $summary)
                                <div class="rounded-xl bg-white/10 p-4">
                                    <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-400">{{ $summary['label'] }}</p>
                                    <p class="mt-2 text-sm font-extrabold" {{ $summary['attr'] }}>{{ $summary['fallback'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-5 rounded-xl border border-slate-200 bg-white p-5 dark:border-slate-700 dark:bg-slate-900">
                        <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-blue-600 dark:text-blue-400">Recommendation engine</p>
                        <div class="mt-5 grid gap-3">
                            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-950">
                                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Estimated duration</p>
                                <p class="mt-2 text-lg font-extrabold" data-summary-duration>3 months</p>
                            </div>
                            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-950">
                                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Recommended stack</p>
                                <p class="mt-2 text-sm font-extrabold" data-summary-stack>Foundations -> Practice -> Projects</p>
                            </div>
                            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-950">
                                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Study rhythm</p>
                                <p class="mt-2 text-sm font-extrabold" data-summary-pace>45 min | 5 days | Evening</p>
                            </div>
                            <div class="rounded-xl bg-blue-600 p-4 text-white">
                                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-blue-100">Plan note</p>
                                <p class="mt-2 text-sm font-extrabold" data-summary-note>Your plan starts with the shortest route to useful momentum.</p>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        @if ($usesDashboardShell)
            </div>
        @endif
    </main>

    @if ($usesDashboardShell)
        </div>
    @endif

    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/75 p-6 backdrop-blur-sm" data-generating-overlay>
        <div class="w-full max-w-md rounded-2xl border border-white/10 bg-white p-6 text-center shadow-2xl dark:bg-slate-900">
            <div class="relative mx-auto h-20 w-20">
                <div class="absolute inset-0 rounded-full border-4 border-blue-100 dark:border-blue-950"></div>
                <div class="absolute inset-0 animate-spin rounded-full border-4 border-transparent border-t-blue-600 border-r-blue-600"></div>
                <div class="absolute inset-4 grid place-items-center rounded-full bg-blue-50 text-xs font-extrabold text-blue-700 dark:bg-blue-950/50 dark:text-blue-300">AI</div>
            </div>
            <h2 class="mt-5 text-2xl font-extrabold">Creating your assessment path</h2>
            <p class="mt-3 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">Saving your profile, matching the question set, and preparing the dashboard handoff.</p>
            <div class="mt-6 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                <div class="h-2 w-2/3 animate-pulse rounded-full bg-blue-600"></div>
            </div>
        </div>
    </div>

    <script type="module" src="{{ asset('js/onboarding/show.js') }}"></script>
    @if ($usesDashboardShell)
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const sidebar = document.querySelector('[data-dashboard-sidebar]');
                const openButton = document.querySelector('[data-dashboard-sidebar-button]');
                const closeButton = document.querySelector('[data-dashboard-sidebar-close]');
                const overlay = document.querySelector('[data-dashboard-sidebar-overlay]');

                if (!sidebar || !openButton || !overlay) return;

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
                    if (window.innerWidth >= 1024) setDrawerState(false);
                });
            });
        </script>
    @endif
</body>
</html>
