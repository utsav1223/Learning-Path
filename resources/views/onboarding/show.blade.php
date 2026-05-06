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
        .step-panel.is-active { display: block; animation: stepIn 0.28s ease; }
        .choice input:checked + span { border-color: #2563eb; background: #eff6ff; color: #1d4ed8; box-shadow: 0 12px 28px rgba(37, 99, 235, 0.14); transform: translateY(-1px); }
        .choice input:checked + span [data-check] { background: #2563eb; color: #ffffff; }
        .roadmap-step.is-active { border-color: #2563eb; background: #eff6ff; color: #1d4ed8; }
        .roadmap-step.is-complete { border-color: #10b981; background: #ecfdf5; color: #047857; }
        @keyframes stepIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
    <main class="min-h-screen">
        <section class="border-b border-slate-200 bg-white">
            <div class="mx-auto flex max-w-7xl flex-col gap-5 px-4 py-5 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-600 text-lg font-extrabold text-white">S</span>
                    <span>
                        <span class="block text-lg font-extrabold">SkillWeave</span>
                        <span class="block text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Learning path setup</span>
                    </span>
                </a>

                <div class="min-w-0 flex-1 lg:max-w-xl">
                    <div class="flex items-center justify-between gap-3">
                        <p id="step-eyebrow" class="text-xs font-extrabold uppercase tracking-[0.2em] text-blue-600">Step 1 of 4</p>
                        <p id="progress-label" class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400">25%</p>
                    </div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                        <div id="progress-bar" class="h-full w-1/4 rounded-full bg-blue-600 transition-all duration-300"></div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="hidden rounded-full bg-emerald-50 px-3 py-2 text-xs font-extrabold uppercase tracking-[0.16em] text-emerald-700 sm:inline-flex" data-save-state>
                        Draft ready
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                    @csrf
                        <button class="rounded-full border border-slate-200 bg-white px-4 py-2 text-sm font-bold text-slate-600 hover:border-slate-300">Logout</button>
                    </form>
                </div>
            </div>
        </section>

        <section class="mx-auto grid max-w-7xl gap-6 px-4 py-6 sm:px-6 lg:grid-cols-[18rem_1fr_20rem] lg:px-8">
            <aside class="rounded-[1.5rem] border border-slate-200 bg-white p-3 shadow-sm lg:sticky lg:top-6 lg:self-start">
                <div class="grid grid-cols-2 gap-2 lg:grid-cols-1">
                    <button type="button" class="roadmap-step is-active rounded-2xl border border-slate-200 p-4 text-left transition hover:border-blue-200" data-step-jump="0">
                        <span class="flex items-center justify-between gap-2 text-xs font-extrabold uppercase tracking-[0.18em]">01 <span data-step-status>Active</span></span>
                        <span class="mt-1 block text-sm font-extrabold">Basics</span>
                        <span class="mt-2 block text-xs font-semibold leading-5 opacity-70">Goal and background</span>
                    </button>
                    <button type="button" class="roadmap-step rounded-2xl border border-slate-200 p-4 text-left transition hover:border-blue-200" data-step-jump="1">
                        <span class="flex items-center justify-between gap-2 text-xs font-extrabold uppercase tracking-[0.18em]">02 <span data-step-status>Next</span></span>
                        <span class="mt-1 block text-sm font-extrabold">Skill Level</span>
                        <span class="mt-2 block text-xs font-semibold leading-5 opacity-70">Starting difficulty</span>
                    </button>
                    <button type="button" class="roadmap-step rounded-2xl border border-slate-200 p-4 text-left transition hover:border-blue-200" data-step-jump="2">
                        <span class="flex items-center justify-between gap-2 text-xs font-extrabold uppercase tracking-[0.18em]">03 <span data-step-status>Next</span></span>
                        <span class="mt-1 block text-sm font-extrabold">Interests</span>
                        <span class="mt-2 block text-xs font-semibold leading-5 opacity-70">Content filters</span>
                    </button>
                    <button type="button" class="roadmap-step rounded-2xl border border-slate-200 p-4 text-left transition hover:border-blue-200" data-step-jump="3">
                        <span class="flex items-center justify-between gap-2 text-xs font-extrabold uppercase tracking-[0.18em]">04 <span data-step-status>Next</span></span>
                        <span class="mt-1 block text-sm font-extrabold">Study Style</span>
                        <span class="mt-2 block text-xs font-semibold leading-5 opacity-70">Learning rhythm</span>
                    </button>
                </div>
                <div class="mt-4 rounded-2xl bg-slate-950 p-4 text-white">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400">Tip</p>
                    <p class="mt-2 text-sm font-semibold leading-6" data-step-tip>Define a clear goal. Specific goals create better course recommendations.</p>
                </div>
            </aside>

            <form method="POST" action="{{ route('onboarding.store') }}" class="rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm sm:p-8" data-onboarding-form>
                @csrf

                @if ($errors->any())
                    <div class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-700">
                        Please review the fields and submit again.
                    </div>
                @endif

                <section class="step-panel is-active" data-step-panel>
                    <div class="rounded-[1.5rem] bg-slate-950 p-5 text-white">
                        <p class="text-sm font-extrabold uppercase tracking-[0.22em] text-blue-200">Basics</p>
                        <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">What path are you building?</h1>
                        <p class="mt-3 max-w-2xl text-sm font-semibold leading-7 text-slate-300">Tell SkillWeave your background and goal so the first roadmap starts in the right place.</p>
                    </div>

                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        <label class="block">
                            <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500">Education level</span>
                            <select name="education_level" data-summary="education" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold outline-none transition focus:border-blue-500">
                                @foreach (['School', 'College', 'Graduate', 'Professional'] as $level)
                                    <option value="{{ $level }}" @selected(old('education_level') === $level)>{{ $level }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block">
                            <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500">Learning goal</span>
                            <input name="learning_goal" data-summary="goal" value="{{ old('learning_goal', auth()->user()->goal ?? 'Frontend developer') }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold outline-none transition focus:border-blue-500">
                        </label>
                    </div>

                    <div class="mt-6 grid gap-3 sm:grid-cols-3">
                        @foreach (['Frontend developer', 'Laravel API developer', 'Placement preparation'] as $goal)
                            <button type="button" class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-left text-sm font-extrabold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700" data-goal-preset="{{ $goal }}">
                                {{ $goal }}
                            </button>
                        @endforeach
                    </div>
                </section>

                <section class="step-panel" data-step-panel>
                    <p class="text-sm font-extrabold uppercase tracking-[0.22em] text-blue-600">Skill Level</p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight">Where should the roadmap begin?</h2>
                    <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">Pick the level that feels honest today. You can always move faster after quizzes.</p>

                    <div class="mt-6 grid gap-4 sm:grid-cols-3">
                        @foreach ([['Beginner', 'Start with fundamentals'], ['Intermediate', 'Skip the basics'], ['Advanced', 'Focus on mastery']] as [$level, $hint])
                            <label class="choice cursor-pointer">
                                <input type="radio" name="skill_level" value="{{ $level }}" data-summary="skill" class="hidden" @checked(old('skill_level', 'Beginner') === $level)>
                                <span class="block h-full rounded-[1.25rem] border border-slate-200 bg-slate-50 p-5 transition">
                                    <span class="flex items-center justify-between gap-3">
                                        <span class="block text-lg font-extrabold">{{ $level }}</span>
                                        <span class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-sm font-extrabold shadow-sm" data-check>+</span>
                                    </span>
                                    <span class="mt-2 block text-sm font-semibold leading-6 text-slate-500">{{ $hint }}</span>
                                </span>
                            </label>
                        @endforeach
                    </div>

                    <label class="mt-6 block">
                        <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500">Current challenge</span>
                        <textarea name="bio" rows="4" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold outline-none transition focus:border-blue-500" placeholder="Example: I know HTML and CSS but struggle with JavaScript projects.">{{ old('bio') }}</textarea>
                    </label>
                </section>

                <section class="step-panel" data-step-panel>
                    <p class="text-sm font-extrabold uppercase tracking-[0.22em] text-blue-600">Interests</p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight">Choose your focus areas</h2>
                    <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">Select one or more. These shape recommended courses and dashboard sections.</p>

                    <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                        @foreach ([['Frontend', 'UI'], ['Backend', 'API'], ['DSA', 'Logic'], ['Data Science', 'Data'], ['AI', 'Models'], ['Projects', 'Build']] as [$interest, $tag])
                            <label class="choice cursor-pointer">
                                <input type="checkbox" name="interests[]" value="{{ $interest }}" data-summary="interests" class="hidden" @checked(in_array($interest, old('interests', ['Frontend']), true))>
                                <span class="flex min-h-24 items-center justify-between gap-3 rounded-[1.25rem] border border-slate-200 bg-slate-50 p-4 transition">
                                    <span>
                                        <span class="block text-base font-extrabold">{{ $interest }}</span>
                                        <span class="mt-1 block text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400">{{ $tag }}</span>
                                    </span>
                                    <span class="flex h-9 w-9 items-center justify-center rounded-full bg-white text-sm font-extrabold shadow-sm" data-check>+</span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                    <p class="mt-4 hidden rounded-2xl border border-amber-200 bg-amber-50 p-3 text-sm font-bold text-amber-800" data-interest-warning>
                        Choose at least one focus area to continue.
                    </p>
                </section>

                <section class="step-panel" data-step-panel>
                    <p class="text-sm font-extrabold uppercase tracking-[0.22em] text-blue-600">Study Style</p>
                    <h2 class="mt-2 text-3xl font-extrabold tracking-tight">Set your learning rhythm</h2>
                    <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">This controls lesson length, daily workload, and resource recommendations.</p>

                    <div class="mt-6 grid gap-5 sm:grid-cols-2">
                        <label class="block">
                            <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500">Daily learning time</span>
                            <select name="daily_learning_time" data-summary="time" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold outline-none transition focus:border-blue-500">
                                @foreach ([30, 45, 60, 90, 120] as $minutes)
                                    <option value="{{ $minutes }}" @selected((int) old('daily_learning_time', 45) === $minutes)>{{ $minutes }} minutes</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block">
                            <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500">Preferred language</span>
                            <input name="preferred_language" value="{{ old('preferred_language', 'English') }}" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold outline-none transition focus:border-blue-500">
                        </label>

                        <label class="block">
                            <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500">Learning format</span>
                            <select name="learning_format" data-summary="format" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold outline-none transition focus:border-blue-500">
                                @foreach (['Videos + quizzes', 'Reading + practice', 'Projects first'] as $format)
                                    <option value="{{ $format }}" @selected(old('learning_format') === $format)>{{ $format }}</option>
                                @endforeach
                            </select>
                        </label>

                        <label class="block">
                            <span class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500">Learning pace</span>
                            <select name="learning_pace" data-summary="pace" class="mt-2 w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 font-semibold outline-none transition focus:border-blue-500">
                                @foreach (['Steady', 'Fast track', 'Weekend focused'] as $pace)
                                    <option value="{{ $pace }}" @selected(old('learning_pace') === $pace)>{{ $pace }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                </section>

                <div class="mt-8 flex flex-col-reverse gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                    <button type="button" class="rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-extrabold text-slate-600 transition hover:border-slate-300" data-prev-step>
                        Back
                    </button>
                    <p class="text-center text-xs font-bold uppercase tracking-[0.16em] text-slate-400 sm:text-left" data-action-hint>
                        Press Enter to continue
                    </p>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button type="button" class="rounded-2xl bg-slate-950 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-slate-950/15 transition hover:bg-slate-800" data-next-step>
                            Continue
                        </button>
                        <button type="submit" class="hidden rounded-2xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700" data-submit-step>
                            Build My Dashboard
                        </button>
                    </div>
                </div>
            </form>

            <aside class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm lg:sticky lg:top-6 lg:self-start">
                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-blue-600">Live preview</p>
                <h2 class="mt-2 text-2xl font-extrabold">Your path</h2>

                <div class="mt-5 space-y-3">
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400">Goal</p>
                        <p class="mt-1 text-sm font-extrabold" data-preview="goal">Frontend developer</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400">Level</p>
                        <p class="mt-1 text-sm font-extrabold" data-preview="skill">Beginner</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 p-4">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400">Focus</p>
                        <p class="mt-1 text-sm font-extrabold" data-preview="interests">Frontend</p>
                    </div>
                    <div class="rounded-2xl bg-slate-950 p-4 text-white">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400">Rhythm</p>
                        <p class="mt-1 text-sm font-extrabold"><span data-preview="time">45</span> min, <span data-preview="pace">Steady</span></p>
                    </div>
                </div>

                <div class="mt-5 rounded-2xl border border-slate-200 p-4">
                    <div class="flex items-center justify-between gap-3">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400">Generated roadmap</p>
                        <span class="rounded-full bg-blue-50 px-2 py-1 text-xs font-extrabold text-blue-700" data-preview="match">82%</span>
                    </div>
                    <div class="mt-4 space-y-3">
                        <div class="flex gap-3">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-xs font-extrabold text-emerald-700">1</span>
                            <div>
                                <p class="text-sm font-extrabold" data-roadmap-one>Foundation refresh</p>
                                <p class="text-xs font-semibold leading-5 text-slate-500">First adaptive milestone</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-50 text-xs font-extrabold text-blue-700">2</span>
                            <div>
                                <p class="text-sm font-extrabold" data-roadmap-two>Practice sprint</p>
                                <p class="text-xs font-semibold leading-5 text-slate-500">Recommended daily workload</p>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-slate-100 text-xs font-extrabold text-slate-700">3</span>
                            <div>
                                <p class="text-sm font-extrabold" data-roadmap-three>Checkpoint quiz</p>
                                <p class="text-xs font-semibold leading-5 text-slate-500">Updates after onboarding</p>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const panels = Array.from(document.querySelectorAll('[data-step-panel]'));
            const steps = Array.from(document.querySelectorAll('[data-step-jump]'));
            const eyebrow = document.getElementById('step-eyebrow');
            const progressLabel = document.getElementById('progress-label');
            const progressBar = document.getElementById('progress-bar');
            const previousButton = document.querySelector('[data-prev-step]');
            const nextButton = document.querySelector('[data-next-step]');
            const submitButton = document.querySelector('[data-submit-step]');
            const form = document.querySelector('[data-onboarding-form]');
            const stepTip = document.querySelector('[data-step-tip]');
            const actionHint = document.querySelector('[data-action-hint]');
            const saveState = document.querySelector('[data-save-state]');
            const interestWarning = document.querySelector('[data-interest-warning]');
            const stepTips = [
                'Define a clear goal. Specific goals create better course recommendations.',
                'Pick where you are today, not where you want to be. The roadmap adapts later.',
                'Choose the areas you actually want to see in your first dashboard.',
                'Your rhythm decides lesson length, workload, and pacing.'
            ];
            let currentStep = 0;

            const showStep = function (index) {
                currentStep = Math.max(0, Math.min(index, panels.length - 1));
                const percent = Math.round(((currentStep + 1) / panels.length) * 100);

                panels.forEach(function (panel, panelIndex) {
                    panel.classList.toggle('is-active', panelIndex === currentStep);
                });

                steps.forEach(function (step, stepIndex) {
                    step.classList.toggle('is-active', stepIndex === currentStep);
                    step.classList.toggle('is-complete', stepIndex < currentStep);
                    const status = step.querySelector('[data-step-status]');
                    if (status) {
                        status.textContent = stepIndex < currentStep ? 'Done' : (stepIndex === currentStep ? 'Active' : 'Next');
                    }
                });

                eyebrow.textContent = 'Step ' + (currentStep + 1) + ' of ' + panels.length;
                progressLabel.textContent = percent + '%';
                progressBar.style.width = percent + '%';
                stepTip.textContent = stepTips[currentStep];
                previousButton.classList.toggle('opacity-40', currentStep === 0);
                previousButton.disabled = currentStep === 0;
                nextButton.classList.toggle('hidden', currentStep === panels.length - 1);
                submitButton.classList.toggle('hidden', currentStep !== panels.length - 1);
                actionHint.textContent = currentStep === panels.length - 1 ? 'Review your path before submitting' : 'Press Enter to continue';
                validateStep();
            };

            const updatePreview = function () {
                const goal = form.querySelector('[data-summary="goal"]').value || 'Frontend developer';
                const skill = form.querySelector('input[name="skill_level"]:checked')?.value || 'Beginner';
                const interests = Array.from(form.querySelectorAll('input[name="interests[]"]:checked')).map(function (input) {
                    return input.value;
                });
                const time = form.querySelector('[data-summary="time"]').value || '45';
                const pace = form.querySelector('[data-summary="pace"]').value || 'Steady';

                document.querySelector('[data-preview="goal"]').textContent = goal;
                document.querySelector('[data-preview="skill"]').textContent = skill;
                document.querySelector('[data-preview="interests"]').textContent = interests.length ? interests.join(', ') : 'Choose one';
                document.querySelector('[data-preview="time"]').textContent = time;
                document.querySelector('[data-preview="pace"]').textContent = pace;
                document.querySelector('[data-roadmap-one]').textContent = skill === 'Beginner' ? 'Foundation refresh' : 'Skill gap scan';
                document.querySelector('[data-roadmap-two]').textContent = (interests[0] || 'Learning') + ' practice sprint';
                document.querySelector('[data-roadmap-three]').textContent = pace === 'Fast track' ? 'Fast-track checkpoint' : 'Checkpoint quiz';
                document.querySelector('[data-preview="match"]').textContent = interests.length > 2 ? '94%' : (interests.length > 0 ? '88%' : '72%');
                saveState.textContent = 'Draft updated';
                window.clearTimeout(updatePreview.saveTimer);
                updatePreview.saveTimer = window.setTimeout(function () {
                    saveState.textContent = 'Draft ready';
                }, 900);
                syncChoiceIndicators();
                validateStep();
            };

            const hasInterests = function () {
                return form.querySelectorAll('input[name="interests[]"]:checked').length > 0;
            };

            const validateStep = function () {
                const goalInput = form.querySelector('[data-summary="goal"]');
                const isGoalReady = goalInput.value.trim().length > 1;
                const isInterestStep = currentStep === 2;
                const canContinue = currentStep === 0 ? isGoalReady : (isInterestStep ? hasInterests() : true);

                nextButton.disabled = !canContinue;
                nextButton.classList.toggle('opacity-45', !canContinue);
                nextButton.classList.toggle('cursor-not-allowed', !canContinue);
                if (interestWarning) {
                    interestWarning.classList.toggle('hidden', !(isInterestStep && !hasInterests()));
                }

                return canContinue;
            };

            const syncChoiceIndicators = function () {
                form.querySelectorAll('.choice input').forEach(function (input) {
                    const check = input.parentElement.querySelector('[data-check]');
                    if (check) {
                        check.textContent = input.checked ? '✓' : '+';
                    }
                });
            };

            steps.forEach(function (step) {
                step.addEventListener('click', function () {
                    showStep(Number(step.dataset.stepJump));
                });
            });

            previousButton.addEventListener('click', function () {
                showStep(currentStep - 1);
            });

            nextButton.addEventListener('click', function () {
                if (validateStep()) {
                    showStep(currentStep + 1);
                }
            });

            form.querySelectorAll('input, select, textarea').forEach(function (field) {
                field.addEventListener('input', updatePreview);
                field.addEventListener('change', updatePreview);
            });

            document.querySelectorAll('[data-goal-preset]').forEach(function (button) {
                button.addEventListener('click', function () {
                    form.querySelector('[data-summary="goal"]').value = button.dataset.goalPreset;
                    updatePreview();
                });
            });

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' && !event.target.matches('textarea') && currentStep < panels.length - 1) {
                    event.preventDefault();
                    if (validateStep()) {
                        showStep(currentStep + 1);
                    }
                }
                if (event.key === 'Escape' && currentStep > 0) {
                    showStep(currentStep - 1);
                }
            });

            updatePreview();
            showStep(0);
        });
    </script>
</body>
</html>
