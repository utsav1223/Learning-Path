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
    <script>
        tailwind.config = { darkMode: 'class' };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body class="min-h-screen bg-[linear-gradient(180deg,#f8fafc_0%,#eef4ff_45%,#ffffff_100%)] text-slate-900 dark:bg-slate-950 dark:text-slate-100">
    <main class="mx-auto max-w-[1600px] px-4 py-5 sm:px-6 lg:px-8 lg:py-8">
        <section class="rounded-[2rem] border border-white/70 bg-white/85 shadow-[0_24px_80px_rgba(15,23,42,0.08)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/90">
            <div class="border-b border-slate-200/80 p-5 dark:border-slate-800 sm:p-6 lg:p-8">
                <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
                    <div class="max-w-3xl">
                        <a href="{{ route('home') }}" class="flex items-center gap-3">
                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-950 text-lg font-extrabold text-white dark:bg-blue-600">S</span>
                            <span>
                                <span class="block text-lg font-extrabold">SkillWeave</span>
                                <span class="block text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">Learner onboarding</span>
                            </span>
                        </a>
                        <p class="mt-6 text-xs font-extrabold uppercase tracking-[0.24em] text-blue-600 dark:text-blue-400" data-step-counter>Step 1 of 6</p>
                        <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100 sm:text-4xl">Build a profile that can drive a real assessment and dashboard.</h1>
                        <p class="mt-3 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">We use your goal, strengths, time, and preferences to generate a one-time 25-question assessment and a dashboard focused on weak areas, correct answers, and next actions.</p>
                    </div>

                    <div class="rounded-[1.75rem] bg-slate-950 p-5 text-white xl:max-w-sm">
                        <div class="mb-4 flex justify-end">
                            <button type="button" data-theme-toggle class="rounded-2xl border border-white/15 bg-white/10 px-4 py-2 text-sm font-extrabold text-white">
                                Toggle theme
                            </button>
                        </div>
                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400">Planner note</p>
                        <p class="mt-3 text-sm font-semibold leading-6 text-slate-200" data-step-tip>Start with the destination so recommendations can branch intelligently.</p>
                        <div class="mt-5 h-2 overflow-hidden rounded-full bg-white/10">
                            <div class="h-full rounded-full bg-blue-500 transition-all duration-300" data-progress-bar style="width: 16.6%"></div>
                        </div>
                        <p class="mt-3 text-sm font-bold text-slate-300" data-progress-label>16% complete</p>
                    </div>
                </div>

                <div class="mt-8 overflow-x-auto pb-1">
                    <div class="min-w-[880px] rounded-[1.75rem] border border-slate-200 bg-white px-5 py-6 shadow-sm dark:border-slate-800 dark:bg-slate-950">
                        <div class="grid grid-cols-6 items-start gap-0" data-timeline>
                            @foreach ([
                                ['title' => 'Destination', 'text' => 'Goal and role'],
                                ['title' => 'Experience', 'text' => 'Current level'],
                                ['title' => 'Signals', 'text' => 'Interests and strengths'],
                                ['title' => 'Routine', 'text' => 'Time and rhythm'],
                                ['title' => 'Preferences', 'text' => 'Format and support'],
                                ['title' => 'Review', 'text' => 'Assessment handoff'],
                            ] as $index => $item)
                                <button type="button" data-step-jump="{{ $index }}" class="group relative flex flex-col items-center px-2 text-center">
                                    @if ($index < 5)
                                        <span data-step-line class="absolute left-1/2 top-5 hidden h-[3px] w-full bg-slate-200 dark:bg-slate-700 lg:block"></span>
                                    @endif
                                    <span data-step-marker class="relative z-10 inline-flex h-11 w-11 items-center justify-center rounded-full border-2 border-slate-300 bg-white text-xs font-extrabold text-slate-500 transition-all duration-300 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-300">{{ $index + 1 }}</span>
                                    <span class="mt-4 block text-sm font-extrabold text-slate-900 dark:text-slate-100">{{ $item['title'] }}</span>
                                    <span data-step-status class="mt-1 block text-[11px] font-extrabold uppercase tracking-[0.18em] text-slate-400 dark:text-slate-500">{{ $index === 0 ? 'In progress' : 'Locked' }}</span>
                                    <span class="mt-2 block text-xs font-semibold leading-5 text-slate-500 dark:text-slate-400">{{ $item['text'] }}</span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_22rem]">
                <div class="p-5 lg:p-7">
                    <form method="POST" action="{{ route('onboarding.store') }}" data-onboarding-form>
                        @csrf

                        @if ($errors->any())
                            <div class="mb-6 rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm font-semibold text-rose-700 dark:border-rose-900 dark:bg-rose-950/30 dark:text-rose-300">
                                Please review the highlighted profile fields and try again.
                            </div>
                        @endif

                        <section class="space-y-6" data-step-panel>
                            <div class="grid gap-4 lg:grid-cols-2">
                                <label class="block">
                                    <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Education level</span>
                                    <select name="education_level" class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                        @foreach (['School', 'College', 'Graduate', 'Professional'] as $level)
                                            <option value="{{ $level }}" @selected(old('education_level', 'College') === $level)>{{ $level }}</option>
                                        @endforeach
                                    </select>
                                </label>

                                <label class="block">
                                    <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Career stage</span>
                                    <select name="career_stage" class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                        @foreach (['Student', 'Early career', 'Switching careers', 'Working professional'] as $stage)
                                            <option value="{{ $stage }}" @selected(old('career_stage', 'Student') === $stage)>{{ $stage }}</option>
                                        @endforeach
                                    </select>
                                </label>
                            </div>

                            <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950" data-field="goal_choice">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                                    <div>
                                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Goal direction</p>
                                        <p class="mt-2 text-lg font-extrabold text-slate-900 dark:text-slate-100">Choose the outcome this learner profile should optimize for.</p>
                                    </div>
                                    <label class="block">
                                        <span class="sr-only">Search goals</span>
                                        <input type="search" data-goal-search placeholder="Search goals or technologies" class="w-full rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100 sm:w-72">
                                    </label>
                                </div>

                                <div class="mt-5 grid gap-3 md:grid-cols-2" data-goal-types></div>

                                <div class="mt-6 rounded-[1.5rem] border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                                    <div class="flex items-center justify-between gap-4">
                                        <div>
                                            <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Selected goal</p>
                                            <p class="mt-2 text-lg font-extrabold text-slate-900 dark:text-slate-100" data-selected-goal>{{ old('learning_goal', 'Choose a specific goal') }}</p>
                                        </div>
                                        <input type="hidden" name="learning_goal" value="{{ old('learning_goal') }}">
                                    </div>
                                    <div class="mt-5 grid gap-3 md:grid-cols-2" data-goal-options></div>
                                </div>

                                <p class="mt-3 hidden text-sm font-semibold text-rose-600" data-error-for="goal_choice"></p>
                            </div>

                            <div data-field="target_role">
                                <label class="block">
                                    <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Target role or outcome</span>
                                    <input name="target_role" value="{{ old('target_role') }}" placeholder="Example: Product-focused frontend engineer" class="mt-2 w-full rounded-3xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                                </label>
                                <p class="mt-2 hidden text-sm font-semibold text-rose-600" data-error-for="target_role"></p>
                            </div>
                        </section>

                        <section class="hidden space-y-6" data-step-panel>
                            <div data-field="experience" class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950">
                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Current level</p>
                                <div class="mt-4 grid gap-3 md:grid-cols-3">
                                    @foreach ([
                                        ['value' => 'Beginner', 'text' => 'Starting from the fundamentals'],
                                        ['value' => 'Intermediate', 'text' => 'Comfortable with the basics'],
                                        ['value' => 'Advanced', 'text' => 'Ready for higher-level review'],
                                    ] as $item)
                                        <label class="cursor-pointer">
                                            <input type="radio" name="skill_level" value="{{ $item['value'] }}" class="peer sr-only" @checked(old('skill_level', 'Beginner') === $item['value'])>
                                            <span class="block rounded-3xl border border-slate-200 bg-white p-5 transition peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:border-slate-700 dark:bg-slate-900 dark:peer-checked:bg-blue-950/40">
                                                <span class="block text-lg font-extrabold text-slate-900 dark:text-slate-100">{{ $item['value'] }}</span>
                                                <span class="mt-2 block text-sm font-semibold text-slate-500 dark:text-slate-300">{{ $item['text'] }}</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>

                                <div class="mt-5 grid gap-4 lg:grid-cols-2">
                                    <label class="block">
                                        <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Relevant experience</span>
                                        <select name="experience_years" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                            @foreach (range(0, 10) as $year)
                                                <option value="{{ $year }}" @selected((int) old('experience_years', 0) === $year)>{{ $year }} {{ $year === 1 ? 'year' : 'years' }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    <label class="block">
                                        <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Current challenge</span>
                                        <textarea name="bio" rows="4" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100" placeholder="Example: I can build small pages but struggle to structure larger apps.">{{ old('bio') }}</textarea>
                                    </label>
                                </div>
                                <p class="mt-3 hidden text-sm font-semibold text-rose-600" data-error-for="experience"></p>
                            </div>
                        </section>

                        <section class="hidden space-y-6" data-step-panel>
                            <div data-field="interests" class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950">
                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Focus areas</p>
                                <div class="mt-4 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                                    @foreach (['Frontend', 'Backend', 'Full Stack', 'AI', 'Data Science', 'DSA', 'Mobile', 'Projects', 'DevOps'] as $interest)
                                        <label data-chip class="cursor-pointer">
                                            <input type="checkbox" name="interests[]" value="{{ $interest }}" class="peer sr-only" @checked(in_array($interest, old('interests', ['Frontend']), true))>
                                            <span class="flex min-h-24 items-start justify-between gap-4 rounded-3xl border border-slate-200 bg-white p-4 transition peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:border-slate-700 dark:bg-slate-900 dark:peer-checked:bg-blue-950/40">
                                                <span>
                                                    <span class="block text-base font-extrabold text-slate-900 dark:text-slate-100">{{ $interest }}</span>
                                                    <span class="mt-2 block text-sm font-semibold text-slate-500 dark:text-slate-300">Use this to weight roadmap topics and assessment coverage.</span>
                                                </span>
                                                <span data-chip-indicator class="inline-flex h-9 min-w-9 items-center justify-center rounded-2xl bg-slate-100 px-3 text-xs font-extrabold text-slate-700 dark:bg-slate-800 dark:text-slate-200">+</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="mt-3 hidden text-sm font-semibold text-rose-600" data-error-for="interests"></p>
                            </div>

                            <div data-field="strengths" class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950">
                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Current strengths</p>
                                <div class="mt-4 grid gap-3 md:grid-cols-2 xl:grid-cols-3">
                                    @foreach (['Consistency', 'Logic', 'Design sense', 'Debugging', 'Communication', 'Fast learning'] as $strength)
                                        <label data-chip class="cursor-pointer">
                                            <input type="checkbox" name="strengths[]" value="{{ $strength }}" class="peer sr-only" @checked(in_array($strength, old('strengths', []), true))>
                                            <span class="flex items-center justify-between gap-4 rounded-3xl border border-slate-200 bg-white p-4 transition peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:border-slate-700 dark:bg-slate-900 dark:peer-checked:bg-blue-950/40">
                                                <span class="text-base font-extrabold text-slate-900 dark:text-slate-100">{{ $strength }}</span>
                                                <span data-chip-indicator class="inline-flex h-9 min-w-9 items-center justify-center rounded-2xl bg-slate-100 px-3 text-xs font-extrabold text-slate-700 dark:bg-slate-800 dark:text-slate-200">+</span>
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="mt-3 hidden text-sm font-semibold text-rose-600" data-error-for="strengths"></p>
                            </div>
                        </section>

                        <section class="hidden space-y-6" data-step-panel>
                            <div data-field="routine" class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950">
                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Learning routine</p>
                                <div class="mt-4 grid gap-4 lg:grid-cols-3">
                                    <label class="block">
                                        <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Daily time</span>
                                        <select name="daily_learning_time" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                            @foreach ([30, 45, 60, 90, 120, 180] as $minutes)
                                                <option value="{{ $minutes }}" @selected((int) old('daily_learning_time', 45) === $minutes)>{{ $minutes }} minutes</option>
                                            @endforeach
                                        </select>
                                    </label>

                                    <label class="block">
                                        <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Days per week</span>
                                        <select name="weekly_days" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                            @foreach (range(1, 7) as $days)
                                                <option value="{{ $days }}" @selected((int) old('weekly_days', 5) === $days)>{{ $days }} days</option>
                                            @endforeach
                                        </select>
                                    </label>

                                    <label class="block">
                                        <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Best study window</span>
                                        <select name="preferred_study_window" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                            @foreach (['Morning', 'Afternoon', 'Evening', 'Late night', 'Weekend only'] as $window)
                                                <option value="{{ $window }}" @selected(old('preferred_study_window', 'Evening') === $window)>{{ $window }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                                <p class="mt-3 hidden text-sm font-semibold text-rose-600" data-error-for="routine"></p>
                            </div>
                        </section>

                        <section class="hidden space-y-6" data-step-panel>
                            <div class="grid gap-5 lg:grid-cols-2">
                                <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950" data-field="preferred_language">
                                    <label class="block">
                                        <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Preferred language</span>
                                        <input name="preferred_language" value="{{ old('preferred_language', 'English') }}" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                    </label>
                                    <p class="mt-3 hidden text-sm font-semibold text-rose-600" data-error-for="preferred_language"></p>
                                </div>

                                <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950" data-field="motivation">
                                    <label class="block">
                                        <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Primary motivation</span>
                                        <select name="motivation" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                            @foreach (['Get job-ready', 'Build confidence', 'Ship stronger projects', 'Prepare for interviews', 'Switch into a new domain'] as $motivation)
                                                <option value="{{ $motivation }}" @selected(old('motivation', 'Get job-ready') === $motivation)>{{ $motivation }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                    <p class="mt-3 hidden text-sm font-semibold text-rose-600" data-error-for="motivation"></p>
                                </div>
                            </div>

                            <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950" data-field="preferences">
                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Learning preferences</p>
                                <div class="mt-4 grid gap-4 lg:grid-cols-2">
                                    <label class="block">
                                        <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Learning format</span>
                                        <select name="learning_format" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                            @foreach (['Projects first', 'Videos + quizzes', 'Reading + practice', 'Case studies + builds'] as $format)
                                                <option value="{{ $format }}" @selected(old('learning_format', 'Projects first') === $format)>{{ $format }}</option>
                                            @endforeach
                                        </select>
                                    </label>

                                    <label class="block">
                                        <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Learning pace</span>
                                        <select name="learning_pace" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                            @foreach (['Steady', 'Fast track', 'Weekend focused'] as $pace)
                                                <option value="{{ $pace }}" @selected(old('learning_pace', 'Steady') === $pace)>{{ $pace }}</option>
                                            @endforeach
                                        </select>
                                    </label>

                                    <label class="block">
                                        <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Project style</span>
                                        <select name="project_preference" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                            @foreach (['Real-world dashboards', 'Mini daily exercises', 'Portfolio projects', 'Interview-style challenges'] as $project)
                                                <option value="{{ $project }}" @selected(old('project_preference', 'Real-world dashboards') === $project)>{{ $project }}</option>
                                            @endforeach
                                        </select>
                                    </label>

                                    <label class="block">
                                        <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Support style</span>
                                        <select name="support_style" class="mt-2 w-full rounded-3xl border border-slate-200 bg-white px-4 py-3 font-semibold text-slate-700 outline-none transition focus:border-blue-500 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                            @foreach (['Mentor checkpoints', 'Self-paced with prompts', 'Weekly review structure', 'Project-first coaching'] as $support)
                                                <option value="{{ $support }}" @selected(old('support_style', 'Mentor checkpoints') === $support)>{{ $support }}</option>
                                            @endforeach
                                        </select>
                                    </label>
                                </div>
                                <p class="mt-3 hidden text-sm font-semibold text-rose-600" data-error-for="preferences"></p>
                            </div>
                        </section>

                        <section class="hidden space-y-6" data-step-panel>
                            <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950">
                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Before you continue</p>
                                <h2 class="mt-3 text-2xl font-extrabold tracking-tight text-slate-900 dark:text-slate-100">This creates a one-time assessment.</h2>
                                <p class="mt-3 max-w-3xl text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">After this step, SkillWeave generates a single 25-question assessment based on your selected goal, recommended stack, interests, and current level. Every question must be answered before you move forward. Wrong answers will show the correct answer immediately, and the resulting dashboard will lock to this attempt.</p>

                                <div class="mt-6 grid gap-4 lg:grid-cols-3">
                                    <div class="rounded-3xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Assessment size</p>
                                        <p class="mt-2 text-xl font-extrabold text-slate-900 dark:text-slate-100">25 questions</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-500 dark:text-slate-300">Balanced from seeded technologies and filtered by your goal.</p>
                                    </div>
                                    <div class="rounded-3xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Results</p>
                                        <p class="mt-2 text-xl font-extrabold text-slate-900 dark:text-slate-100">Weak vs strong areas</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-500 dark:text-slate-300">We show correct, wrong, topic breakdown, and a graph-backed summary.</p>
                                    </div>
                                    <div class="rounded-3xl border border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Dashboard outcome</p>
                                        <p class="mt-2 text-xl font-extrabold text-slate-900 dark:text-slate-100">Adaptive roadmap</p>
                                        <p class="mt-2 text-sm font-semibold text-slate-500 dark:text-slate-300">Next milestones and study recommendations change based on the score.</p>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <div class="mt-8 flex flex-col gap-3 border-t border-slate-200 pt-6 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
                            <button type="button" data-prev-step class="rounded-3xl border border-slate-200 bg-white px-5 py-3 text-sm font-extrabold text-slate-700 transition hover:border-slate-300 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-100">
                                Back
                            </button>
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                                <button type="button" data-next-step class="rounded-3xl bg-slate-950 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-slate-800 dark:bg-blue-600 dark:hover:bg-blue-700">
                                    Continue
                                </button>
                                <button type="submit" data-submit-step class="hidden rounded-3xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                                    Save Profile and Start Assessment
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <aside class="border-t border-slate-200/80 p-5 dark:border-slate-800 xl:border-l xl:border-t-0 xl:p-6">
                    <div class="rounded-[1.75rem] bg-slate-950 p-5 text-white">
                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400">Live profile</p>
                        <h2 class="mt-3 text-2xl font-extrabold">Dashboard preview</h2>
                        <div class="mt-5 space-y-3">
                            <div class="rounded-3xl bg-white/10 p-4">
                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400">Goal</p>
                                <p class="mt-2 text-sm font-extrabold" data-summary-goal>Choose a goal</p>
                            </div>
                            <div class="rounded-3xl bg-white/10 p-4">
                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400">Target role</p>
                                <p class="mt-2 text-sm font-extrabold" data-summary-role>Add your target role</p>
                            </div>
                            <div class="rounded-3xl bg-white/10 p-4">
                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400">Level</p>
                                <p class="mt-2 text-sm font-extrabold" data-summary-level>Beginner - 0 yrs</p>
                            </div>
                            <div class="rounded-3xl bg-white/10 p-4">
                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400">Focus areas</p>
                                <p class="mt-2 text-sm font-extrabold" data-summary-interests>Pick your focus areas</p>
                            </div>
                            <div class="rounded-3xl bg-white/10 p-4">
                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400">Current strengths</p>
                                <p class="mt-2 text-sm font-extrabold" data-summary-strengths>Highlight what is already working</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-5 rounded-[1.75rem] border border-slate-200 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-950">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Recommendation engine</p>
                        <div class="mt-5 space-y-4">
                            <div class="rounded-3xl bg-white p-4 dark:bg-slate-900">
                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Estimated duration</p>
                                <p class="mt-2 text-lg font-extrabold text-slate-900 dark:text-slate-100" data-summary-duration>3 months</p>
                            </div>
                            <div class="rounded-3xl bg-white p-4 dark:bg-slate-900">
                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Recommended stack</p>
                                <p class="mt-2 text-sm font-extrabold text-slate-900 dark:text-slate-100" data-summary-stack>Foundations -> Practice -> Projects</p>
                            </div>
                            <div class="rounded-3xl bg-white p-4 dark:bg-slate-900">
                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Study rhythm</p>
                                <p class="mt-2 text-sm font-extrabold text-slate-900 dark:text-slate-100" data-summary-pace>45 min - 5 days - Evening</p>
                            </div>
                            <div class="rounded-3xl bg-blue-600 p-4 text-white">
                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-100">Plan note</p>
                                <p class="mt-2 text-sm font-extrabold" data-summary-note>Your plan starts with the shortest route to useful momentum.</p>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </section>
    </main>

    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/70 p-6 backdrop-blur" data-generating-overlay>
        <div class="w-full max-w-md rounded-[2rem] bg-white p-6 text-center shadow-2xl dark:bg-slate-900">
            <div class="mx-auto h-2 w-32 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                <div class="h-full w-2/3 animate-pulse rounded-full bg-blue-600"></div>
            </div>
            <h2 class="mt-5 text-2xl font-extrabold text-slate-900 dark:text-slate-100">Creating your assessment path</h2>
            <p class="mt-3 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">We are saving the profile, generating a goal-matched 25-question assessment, and preparing the analytics dashboard.</p>
        </div>
    </div>

    <script type="module" src="{{ asset('js/onboarding/show.js') }}"></script>
</body>
</html>
