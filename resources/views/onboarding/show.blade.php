<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onboarding | SkillWeave</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Manrope', sans-serif; }
        .step-panel { display: none; }
        .step-panel.is-active { display: block; animation: stepIn 0.32s cubic-bezier(0.22, 1, 0.36, 1); }
        .choice > span { transition: transform 0.25s cubic-bezier(0.22, 1, 0.36, 1), box-shadow 0.25s cubic-bezier(0.22, 1, 0.36, 1), border-color 0.25s cubic-bezier(0.22, 1, 0.36, 1), background-color 0.25s cubic-bezier(0.22, 1, 0.36, 1); }
        @media (hover: hover) {
            .choice:hover > span { transform: translateY(-2px); }
        }
        .choice input:checked + span { border-color: #2563eb; background: #eff6ff; color: #1d4ed8; box-shadow: 0 16px 34px rgba(37, 99, 235, 0.16); transform: translateY(-2px) scale(1.01); }
        .choice input:checked + span [data-check] { background: #2563eb; color: #ffffff; }
        .choice input:focus-visible + span { outline: 3px solid rgba(37, 99, 235, 0.28); outline-offset: 3px; }
        .choice input:disabled + span { cursor: not-allowed; opacity: 0.48; }
        .level-choice input:checked + span { border-left-color: #2563eb; background: linear-gradient(135deg, #eff6ff 0%, #ffffff 72%); box-shadow: 0 20px 42px rgba(37, 99, 235, 0.18); transform: translateY(-3px) scale(1.015); }
        .level-choice input:checked + span [data-level-bar] span { background: #2563eb; }
        .step-dot.is-active { background: #2563eb; transform: scale(1.15); }
        .step-dot.is-complete { background: #10b981; }
        .roadmap-step.is-active { border-color: #2563eb; background: #eff6ff; color: #1d4ed8; }
        .roadmap-step.is-complete { border-color: #10b981; background: #ecfdf5; color: #047857; }
        [data-field].is-invalid input,
        [data-field].is-invalid select,
        [data-field].is-invalid textarea { border-color: #f43f5e; background: #fff1f2; }
        [data-field].is-invalid .choice span { border-color: #fda4af; }
        [data-field].is-valid .choice input:checked + span { border-color: #2563eb; }
        [data-field].is-valid input,
        [data-field].is-valid select,
        [data-field].is-valid textarea { border-color: #10b981; }
        @keyframes stepIn {
            from { opacity: 0; transform: translateX(14px) scale(0.985); filter: blur(2px); }
            to { opacity: 1; transform: translateX(0) scale(1); filter: blur(0); }
        }
        @keyframes pulseLine {
            0%, 100% { opacity: 0.45; transform: scaleX(0.72); }
            50% { opacity: 1; transform: scaleX(1); }
        }
        /* Tablet visual refinements */
        .ambient::before {
            content: "";
            position: absolute;
            inset: -28px  -28px  -6px -28px;
            pointer-events: none;
            background: radial-gradient(circle at top,#dbeafe 0%,transparent 60%);
            z-index: 0;
            border-radius: inherit;
            opacity: 0.55;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-b from-slate-100 to-slate-50 text-slate-900">
    <main class="min-h-screen pb-28 sm:pb-0">
        <section class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-[1440px] flex-col gap-5 px-4 py-5 sm:px-6 md:p-5 lg:flex-row lg:items-center lg:justify-between lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-600 text-lg font-bold text-white">S</span>
                    <span>
                        <span class="block text-lg font-bold">SkillWeave</span>
                        <span class="block text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Learning path setup</span>
                    </span>
                </a>

                <div class="min-w-0 flex-1 lg:max-w-xl">
                    <div class="flex items-center justify-between gap-3">
                        <p id="step-eyebrow" class="text-xs font-bold uppercase tracking-[0.2em] text-blue-600">Step 1 of 4</p>
                        <p id="progress-label" class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">25%</p>
                    </div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                        <div id="progress-bar" class="h-full w-1/4 rounded-full bg-blue-600 transition-all duration-300"></div>
                    </div>
                    <div class="mt-3 flex items-center gap-2" data-step-dots>
                        <span class="step-dot is-active h-2.5 w-2.5 rounded-full bg-slate-300 transition-all duration-300"></span>
                        <span class="step-dot h-2.5 w-2.5 rounded-full bg-slate-300 transition-all duration-300"></span>
                        <span class="step-dot h-2.5 w-2.5 rounded-full bg-slate-300 transition-all duration-300"></span>
                        <span class="step-dot h-2.5 w-2.5 rounded-full bg-slate-300 transition-all duration-300"></span>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="hidden rounded-full bg-emerald-50 px-3 py-2 text-xs font-bold uppercase tracking-[0.16em] text-emerald-700 xl:inline-flex" data-save-state>
                        Draft ready
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                    @csrf
                        <button class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-600 hover:border-slate-300">Logout</button>
                    </form>
                </div>
            </div>
        </section>

        <section class="mx-auto grid max-w-[1440px] gap-3 px-3 py-3 sm:px-6 md:grid-cols-1 md:gap-4 lg:grid-cols-[1fr_16rem] lg:gap-5 xl:grid-cols-[15rem_1fr_minmax(18rem,22rem)] xl:gap-5 xl:px-8 xl:py-5">
            <aside class="rounded-2xl border border-slate-200 bg-white p-2 shadow-sm md:hidden xl:sticky xl:top-6 xl:block xl:self-start">
                <details class="md:hidden">
                    <summary class="cursor-pointer rounded-xl px-3 py-2 text-sm font-semibold text-slate-700">
                        Setup summary
                    </summary>
                    <p class="px-3 pb-3 text-sm font-semibold leading-6 text-slate-500" data-mobile-summary>Frontend developer · Beginner · Frontend</p>
                </details>
                <div class="hidden grid-cols-1 gap-2 xl:grid">
                    <button type="button" class="roadmap-step is-active rounded-xl border border-slate-200 p-3 text-left transition-all duration-300 hover:-translate-y-0.5 hover:border-blue-200" data-step-jump="0">
                        <span class="flex items-center justify-between gap-2 text-xs font-bold uppercase tracking-[0.18em]">01 <span data-step-status>Active</span></span>
                        <span class="mt-1 block text-sm font-bold">Basics</span>
                    </button>
                    <button type="button" class="roadmap-step rounded-xl border border-slate-200 p-3 text-left transition-all duration-300 hover:-translate-y-0.5 hover:border-blue-200" data-step-jump="1">
                        <span class="flex items-center justify-between gap-2 text-xs font-bold uppercase tracking-[0.18em]">02 <span data-step-status>Next</span></span>
                        <span class="mt-1 block text-sm font-bold">Skill Level</span>
                    </button>
                    <button type="button" class="roadmap-step rounded-xl border border-slate-200 p-3 text-left transition-all duration-300 hover:-translate-y-0.5 hover:border-blue-200" data-step-jump="2">
                        <span class="flex items-center justify-between gap-2 text-xs font-bold uppercase tracking-[0.18em]">03 <span data-step-status>Next</span></span>
                        <span class="mt-1 block text-sm font-bold">Interests</span>
                    </button>
                    <button type="button" class="roadmap-step rounded-xl border border-slate-200 p-3 text-left transition-all duration-300 hover:-translate-y-0.5 hover:border-blue-200" data-step-jump="3">
                        <span class="flex items-center justify-between gap-2 text-xs font-bold uppercase tracking-[0.18em]">04 <span data-step-status>Next</span></span>
                        <span class="mt-1 block text-sm font-bold">Study Style</span>
                    </button>
                </div>
                <div class="mt-2 hidden rounded-xl bg-slate-950 p-3 text-white xl:block">
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">Tip</p>
                    <p class="mt-2 text-sm font-semibold leading-6" data-step-tip>Set one clear goal so recommendations start in the right place.</p>
                </div>
            </aside>

            <x-onboarding.step-navigation :steps="['Basics', 'Level', 'Topics', 'Rhythm']" :currentStep="$currentStep ?? 0" />

                <form method="POST" action="{{ route('onboarding.store') }}" class="max-w-5xl xl:max-w-none mx-auto rounded-2xl border border-slate-200 bg-white p-3 sm:p-4 md:p-4 lg:p-6 shadow-sm" data-onboarding-form>
                @csrf

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-700">
                        Please review the fields and submit again.
                    </div>
                @endif

                <section class="step-panel is-active" data-step-panel>
                    <div class="rounded-2xl bg-gradient-to-br from-slate-950 to-blue-950 p-3 text-white shadow-lg shadow-blue-950/10 sm:p-4 lg:p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-200">Basics</p>
                        <h1 class="mt-2 text-xl font-bold tracking-tight md:text-2xl xl:text-3xl">What would you like to become better at?</h1>
                        <p class="mt-2 hidden max-w-2xl text-sm font-semibold leading-6 text-slate-300 sm:block">Pick an outcome first, then SkillWeave will shape the path around it.</p>
                    </div>

                    <div class="mt-4 grid gap-3 md:grid-cols-2 lg:mt-5 lg:gap-4">
                        <label class="block">
                            <span class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Education level</span>
                            <select name="education_level" data-summary="education" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 font-semibold outline-none transition focus:border-blue-500 sm:rounded-2xl sm:px-4 sm:py-3">
                                @foreach (['School', 'College', 'Graduate', 'Professional'] as $level)
                                    <option value="{{ $level }}" @selected(old('education_level') === $level)>{{ $level }}</option>
                                @endforeach
                            </select>
                        </label>

                        <div data-field="learning_goal">
                            <span class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Selected goal</span>
                            <input type="hidden" name="learning_goal" data-summary="goal" value="{{ old('learning_goal', auth()->user()->goal ?? '') }}">
                            <div class="mt-2 rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-bold text-slate-700 sm:rounded-2xl sm:px-4 sm:py-3" data-selected-goal>
                                Choose a goal type
                            </div>
                            <span class="mt-2 hidden text-xs font-semibold text-rose-600" data-error-for="learning_goal"></span>
                            @error('learning_goal')
                                <span class="mt-2 block text-xs font-semibold text-rose-600">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4 lg:mt-5" data-field="goal_type">
                        <span class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Goal type</span>
                        <div class="mt-2 grid gap-2 md:grid-cols-2 lg:gap-3" data-goal-types></div>
                        <p class="mt-2 hidden text-xs font-semibold text-rose-600" data-error-for="goal_type"></p>
                    </div>

                    <div class="mt-4 lg:mt-5" data-goal-options-shell>
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <span class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Recommended goals</span>
                            <label class="relative">
                                <span class="sr-only">Search careers or skills</span>
                                <input type="search" placeholder="Search careers or skills..." class="w-full rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-semibold outline-none transition focus:border-blue-500 sm:w-64" data-goal-search>
                            </label>
                        </div>
                        <div class="mt-2 grid gap-2 md:grid-cols-2 lg:gap-3" data-goal-options>
                            <p class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-4 text-sm font-semibold text-slate-500">
                                <span class="mb-2 flex h-8 w-8 items-center justify-center rounded-full bg-blue-50 text-xs font-bold text-blue-700">AI</span>
                                Choose a goal type to see focused options.
                            </p>
                        </div>
                    </div>

                    <div class="mt-4 hidden rounded-2xl bg-violet-50 p-4 ring-1 ring-violet-100" data-smart-panel>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-violet-700">Recommended next</p>
                        <p class="mt-2 text-sm font-medium leading-6 text-violet-950" data-smart-panel-text>Choose a goal to unlock a smarter starting plan.</p>
                    </div>
                </section>

                <section class="step-panel" data-step-panel>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600">Comfort check</p>
                    <h2 class="mt-2 text-xl font-bold tracking-tight md:text-2xl xl:text-3xl">How comfortable are you right now?</h2>
                    <p class="mt-2 hidden text-sm font-semibold leading-6 text-slate-500 sm:block">This decides whether your roadmap starts with foundations, projects, or mastery checkpoints.</p>

                    <div class="mt-4 grid gap-2 md:grid-cols-2 xl:grid-cols-3 lg:mt-5 lg:gap-3" data-field="skill_level">
                        <x-onboarding.skill-level-card
                            level="Beginner"
                            description="Starting from fundamentals"
                            fillCount="1"
                            :selected="old('skill_level', 'Beginner') === 'Beginner'"
                            name="skill_level"
                            value="Beginner"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 21v-8" stroke-linecap="round"/><path d="M12 13C8 13 5 10 5 6c4 0 7 3 7 7Z"/><path d="M12 13c4 0 7-3 7-7-4 0-7 3-7 7Z"/></svg>
                        </x-onboarding.skill-level-card>

                        <x-onboarding.skill-level-card
                            level="Intermediate"
                            description="Comfortable with basics"
                            fillCount="2"
                            :selected="old('skill_level', 'Beginner') === 'Intermediate'"
                            name="skill_level"
                            value="Intermediate"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="m13 2-8 12h6l-1 8 8-12h-6l1-8Z" stroke-linejoin="round"/></svg>
                        </x-onboarding.skill-level-card>

                        <x-onboarding.skill-level-card
                            level="Advanced"
                            description="Ready for mastery"
                            fillCount="3"
                            :selected="old('skill_level', 'Beginner') === 'Advanced'"
                            name="skill_level"
                            value="Advanced"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M5 15c-1.5 1-2 3-2 6 3 0 5-1 6-2"/><path d="M9 15 15 9"/><path d="M14 4c3 0 5 2 6 5-1 4-4 7-8 8l-5-5c1-4 4-7 7-8Z"/><path d="M15 9h.01"/></svg>
                        </x-onboarding.skill-level-card>
                    </div>
                    <p class="mt-2 hidden text-xs font-semibold text-rose-600" data-error-for="skill_level"></p>

                    <label class="mt-4 block lg:mt-5">
                        <span class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Current challenge</span>
                        <textarea name="bio" rows="3" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 font-semibold outline-none transition focus:border-blue-500 sm:rounded-2xl sm:px-4 sm:py-3" placeholder="Example: I know HTML and CSS but struggle with JavaScript projects.">{{ old('bio') }}</textarea>
                    </label>
                </section>

                <section class="step-panel" data-step-panel>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600">Topics</p>
                    <h2 class="mt-2 text-xl font-bold tracking-tight md:text-2xl xl:text-3xl">What topics excite you most?</h2>
                    <p class="mt-2 hidden text-sm font-semibold leading-6 text-slate-500 sm:block">Choose the areas you would actually enjoy seeing in your first roadmap.</p>

                    <div class="mt-4 flex flex-wrap gap-2 md:grid md:grid-cols-2 md:gap-3 xl:grid-cols-3" data-field="interests">
                        @foreach ([['Frontend', 'UI'], ['Backend', 'API'], ['DSA', 'Logic'], ['Data Science', 'Data'], ['AI', 'Models'], ['Projects', 'Build']] as [$interest, $tag])
                            <label class="choice cursor-pointer">
                                <input type="checkbox" name="interests[]" value="{{ $interest }}" data-summary="interests" class="sr-only" @checked(in_array($interest, old('interests', ['Frontend']), true))>
                                <span class="inline-flex min-h-[52px] items-center justify-between gap-2 rounded-full border border-slate-200 bg-slate-50 px-3 py-2 sm:flex sm:min-h-[52px] sm:gap-3 sm:rounded-xl sm:p-4">
                                    <span>
                                        <span class="block text-base font-bold">{{ $interest }}</span>
                                        <span class="mt-1 hidden text-xs font-semibold uppercase tracking-[0.18em] text-slate-400 sm:block">{{ $tag }}</span>
                                    </span>
                                    <span class="flex h-7 w-7 items-center justify-center rounded-full bg-white text-sm font-bold shadow-sm sm:h-9 sm:w-9" data-check>+</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <p class="mt-2 hidden text-xs font-semibold text-rose-600" data-error-for="interests"></p>
                    @error('interests')
                        <p class="mt-2 text-xs font-semibold text-rose-600">{{ $message }}</p>
                    @enderror
                </section>

                <section class="step-panel" data-step-panel>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600">Learning rhythm</p>
                    <h2 class="mt-2 text-xl font-bold tracking-tight md:text-2xl xl:text-3xl">What pace fits your real week?</h2>
                    <p class="mt-2 hidden text-sm font-semibold leading-6 text-slate-500 sm:block">Your roadmap will adapt lesson length, review days, and practice load.</p>

                    <div class="mt-4 grid gap-3 md:grid-cols-2 lg:mt-5 lg:gap-4">
                        <label class="block">
                            <span class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Daily learning time</span>
                            <select name="daily_learning_time" data-summary="time" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 font-semibold outline-none transition focus:border-blue-500 sm:rounded-2xl sm:px-4 sm:py-3">
                                @foreach ([30, 45, 60, 90, 120] as $minutes)
                                    <option value="{{ $minutes }}" @selected((int) old('daily_learning_time', 45) === $minutes)>{{ $minutes }} minutes</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block" data-field="preferred_language">
                            <span class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Preferred language</span>
                            <input name="preferred_language" value="{{ old('preferred_language', 'English') }}" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 font-semibold outline-none transition focus:border-blue-500 sm:rounded-2xl sm:px-4 sm:py-3">
                            <span class="mt-2 hidden text-xs font-semibold text-rose-600" data-error-for="preferred_language"></span>
                            @error('preferred_language')
                                <span class="mt-2 block text-xs font-semibold text-rose-600">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="block">
                            <span class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Learning format</span>
                            <select name="learning_format" data-summary="format" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 font-semibold outline-none transition focus:border-blue-500 sm:rounded-2xl sm:px-4 sm:py-3">
                                @foreach (['Videos + quizzes', 'Reading + practice', 'Projects first'] as $format)
                                    <option value="{{ $format }}" @selected(old('learning_format') === $format)>{{ $format }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block">
                            <span class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-500">Learning pace</span>
                            <select name="learning_pace" data-summary="pace" class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 font-semibold outline-none transition focus:border-blue-500 sm:rounded-2xl sm:px-4 sm:py-3">
                                @foreach (['Steady', 'Fast track', 'Weekend focused'] as $pace)
                                    <option value="{{ $pace }}" @selected(old('learning_pace') === $pace)>{{ $pace }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                </section>

                <div class="fixed inset-x-0 bottom-0 z-40 flex items-center justify-between gap-2 border-t border-slate-200 bg-white/95 p-3 shadow-[0_-18px_36px_rgba(15,23,42,0.12)] backdrop-blur md:inset-x-6 md:bottom-6 md:left-auto md:translate-x-0 md:max-w-3xl md:rounded-2xl md:border md:px-4 md:py-3 xl:static xl:mt-8 xl:border-t xl:border-slate-100 xl:bg-transparent xl:p-0 xl:pt-5 xl:shadow-none xl:backdrop-blur-0">
                    <button type="button" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold text-slate-600 transition hover:border-slate-300 sm:px-5" data-prev-step>
                        Back
                    </button>
                    <p class="hidden text-center text-xs font-bold uppercase tracking-[0.16em] text-slate-400 sm:block sm:text-left" data-action-hint>
                        Press Enter to continue
                    </p>
                    <div class="flex flex-1 justify-end gap-2 sm:flex-none sm:gap-3">
                        <button type="button" class="min-w-36 rounded-2xl bg-slate-950 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-slate-950/15 transition hover:bg-slate-800" data-next-step>
                            Continue
                        </button>
                        <button type="submit" class="hidden min-w-48 rounded-2xl bg-blue-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700" data-submit-step>
                            Generate My Skill Journey
                        </button>
                    </div>
                </div>
            </form>

            <aside class="hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm md:col-span-2 md:block lg:hidden">
                <details>
                    <summary class="cursor-pointer text-sm font-bold text-slate-700">Preview your path</summary>
                    <div class="mt-3 grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Goal</p>
                            <p class="mt-1 text-sm font-bold" data-preview="goal">Frontend developer</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Duration</p>
                            <p class="mt-1 text-sm font-bold" data-preview="duration">4 months</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Workload</p>
                            <p class="mt-1 text-sm font-bold" data-preview="workload">Steady weekly plan</p>
                        </div>
                        <div class="rounded-xl bg-slate-950 p-3 text-white">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Start</p>
                            <p class="mt-1 text-sm font-bold" data-preview="note">Personalized foundation</p>
                        </div>
                    </div>
                </details>
            </aside>

            <aside class="hidden rounded-2xl border border-slate-200 bg-white p-4 shadow-sm xl:sticky xl:top-6 xl:block xl:self-start">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600">Live preview</p>
                <h2 class="mt-2 text-2xl font-bold">Your path</h2>

                <div class="mt-4 space-y-2">
                    <div class="rounded-xl bg-slate-50 p-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Goal</p>
                        <p class="mt-1 text-sm font-bold" data-preview="goal">Frontend developer</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Level</p>
                        <p class="mt-1 text-sm font-bold" data-preview="skill">Beginner</p>
                    </div>
                    <div class="rounded-xl bg-slate-50 p-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Focus</p>
                        <p class="mt-1 text-sm font-bold" data-preview="interests">Frontend</p>
                    </div>
                    <div class="rounded-xl bg-slate-950 p-3 text-white">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Estimated completion</p>
                        <p class="mt-1 text-sm font-bold" data-preview="duration">4 months</p>
                    </div>
                </div>

                <div class="mt-4 rounded-xl border border-slate-200 p-3">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-slate-400">Roadmap intelligence</p>
                        <span class="rounded-full bg-blue-50 px-2 py-1 text-xs font-bold text-blue-700" data-preview="difficulty">Moderate</span>
                    </div>
                    <div class="mt-4 space-y-3">
                        <div class="flex gap-3">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-xs font-bold text-emerald-700">1</span>
                            <div>
                                <p class="text-sm font-bold">Recommended stack</p>
                                <p class="text-xs font-semibold leading-5 text-slate-500" data-preview="stack">HTML -> CSS -> JS -> React</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-50 text-xs font-bold text-blue-700">2</span>
                            <div>
                                <p class="text-sm font-bold">Weekly workload</p>
                                <p class="text-xs font-semibold leading-5 text-slate-500" data-preview="workload">Steady weekly plan</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-bold text-slate-700">3</span>
                            <div>
                                <p class="text-sm font-bold">Personalized start</p>
                                <p class="text-xs font-semibold leading-5 text-slate-500" data-preview="note">Roadmap starts with the shortest useful foundation path.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </section>
    </main>
    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/70 p-6 backdrop-blur" data-generating-overlay>
        <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-2xl">
            <div class="mx-auto h-1.5 w-28 origin-left rounded-full bg-blue-600" style="animation: pulseLine 1s ease-in-out infinite"></div>
            <h2 class="mt-5 text-xl font-bold tracking-tight">Generating your roadmap...</h2>
            <p class="mt-2 text-sm font-semibold leading-6 text-slate-500" data-generating-message>Personalizing your first milestones.</p>
        </div>
    </div>
    <script type="module" src="{{ asset('js/onboarding/show.js') }}"></script>
</body>
</html>


