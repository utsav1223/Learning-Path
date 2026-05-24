<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Review | SkillWeave</title>
    <script>
        (() => {
            const theme = localStorage.getItem('skillweave-theme') || 'light';
            if (theme === 'dark') document.documentElement.classList.add('dark');
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
    <div class="fixed inset-0 z-30 bg-slate-950/60 opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden" data-dashboard-sidebar-overlay></div>

    <div class="min-h-screen lg:grid lg:grid-cols-[19rem_1fr]">
        <div class="sticky top-0 z-30 flex items-center justify-between border-b border-slate-200 bg-white/90 px-4 py-3 shadow-sm backdrop-blur-xl dark:border-slate-800 dark:bg-slate-900/90 lg:hidden">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-slate-400">SkillWeave</p>
                <p class="text-sm font-extrabold">Review</p>
            </div>
            <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" aria-label="Open navigation menu" aria-expanded="false" data-dashboard-sidebar-button>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"></path></svg>
            </button>
        </div>

        <x-dashboard.sidebar
            :user="$user"
            :profile="$profile"
            :currentGoal="$profile?->learning_goal ?? $user->goal ?? 'Skill growth'"
            :dailyMinutes="$profile?->daily_learning_time ?? 45"
            :pace="$user->learning_pace ?? 'Steady'"
        />

        <main class="px-4 py-5 sm:px-6 lg:col-start-2 lg:px-8">
            <div class="mx-auto max-w-[1300px]">
                <section class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="grid gap-0 xl:grid-cols-[1fr_22rem]">
                        <div class="p-5 sm:p-6 lg:p-8">
                            <p class="text-xs font-extrabold uppercase tracking-[0.24em] text-blue-600 dark:text-blue-300">Assessment review</p>
                            <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">All marked answers</h1>
                            <h2 class="mt-2 text-lg font-extrabold text-slate-700 dark:text-slate-200">Wrong answers to repair</h2>
                            <p class="mt-4 max-w-3xl text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">Check every question you answered, see whether it was correct or wrong, and review the correct answer plus explanation before continuing your roadmap.</p>
                            <div class="mt-6 flex flex-wrap gap-3">
                                <span class="rounded-xl bg-emerald-50 px-4 py-3 text-sm font-extrabold text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300">{{ $correctAnswers->count() }} correct</span>
                                <span class="rounded-xl bg-rose-50 px-4 py-3 text-sm font-extrabold text-rose-700 dark:bg-rose-950/30 dark:text-rose-300">{{ $wrongAnswers->count() }} wrong</span>
                                <span class="rounded-xl bg-slate-100 px-4 py-3 text-sm font-extrabold text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $attempt->percentage }}% score</span>
                            </div>
                        </div>
                        <div class="border-t border-slate-200 bg-slate-950 p-5 text-white dark:border-slate-800 xl:border-l xl:border-t-0">
                            <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400">Result summary</p>
                            <p class="mt-3 text-4xl font-extrabold">{{ $attempt->score }}/{{ $attempt->total_questions }}</p>
                            <p class="mt-2 text-sm font-semibold text-slate-300">Wrong answers are shown first so you can repair them quickly.</p>
                            <a href="{{ route('dashboard') }}" class="mt-5 inline-flex w-full items-center justify-center rounded-xl bg-white px-5 py-3 text-sm font-extrabold text-slate-950 transition hover:bg-slate-100">Back to dashboard</a>
                        </div>
                    </div>
                </section>

                <section class="mt-5 grid gap-4">
                    @forelse ($allAnswers as $answer)
                        <article class="rounded-[1.25rem] border {{ $answer->is_correct ? 'border-emerald-200 dark:border-emerald-950' : 'border-rose-200 dark:border-rose-950' }} bg-white p-5 shadow-sm dark:bg-slate-900 sm:p-6">
                            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                <div>
                                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] {{ $answer->is_correct ? 'text-emerald-600 dark:text-emerald-300' : 'text-rose-600 dark:text-rose-300' }}">{{ $answer->question->topic }} / {{ $answer->question->difficulty }}</p>
                                    <h2 class="mt-2 text-xl font-extrabold">{{ $answer->question->question }}</h2>
                                </div>
                                <span class="rounded-full {{ $answer->is_correct ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-300' }} px-3 py-1 text-xs font-extrabold">{{ $answer->is_correct ? 'Correct' : 'Needs review' }}</span>
                            </div>

                            <div class="mt-5 grid gap-3 md:grid-cols-2">
                                <div class="rounded-xl border {{ $answer->is_correct ? 'border-emerald-200 bg-emerald-50 dark:border-emerald-950 dark:bg-emerald-950/20' : 'border-rose-200 bg-rose-50 dark:border-rose-950 dark:bg-rose-950/20' }} p-4">
                                    <p class="text-xs font-extrabold uppercase tracking-[0.16em] {{ $answer->is_correct ? 'text-emerald-700 dark:text-emerald-300' : 'text-rose-700 dark:text-rose-300' }}">Your answer</p>
                                    <p class="mt-2 text-sm font-bold text-slate-800 dark:text-slate-100">{{ $answer->selected_answer ?: 'No answer selected' }}</p>
                                </div>
                                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-950 dark:bg-emerald-950/20">
                                    <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-emerald-700 dark:text-emerald-300">Correct answer</p>
                                    <p class="mt-2 text-sm font-bold text-slate-800 dark:text-slate-100">{{ $answer->question->correct_answer }}</p>
                                </div>
                            </div>

                            <div class="mt-4 rounded-xl bg-slate-50 p-4 dark:bg-slate-950">
                                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500">Explanation</p>
                                <p class="mt-2 text-sm font-semibold leading-6 text-slate-600 dark:text-slate-300">{{ $answer->question->explanation }}</p>
                            </div>
                        </article>
                    @empty
                        <div class="rounded-[1.5rem] border border-slate-200 bg-white p-6 text-center text-slate-700 shadow-sm dark:border-slate-800 dark:bg-slate-900 dark:text-slate-300">
                            <h2 class="text-2xl font-extrabold">No saved answers found.</h2>
                            <p class="mt-2 text-sm font-semibold">Your score exists, but this attempt has no answer rows saved. Retake after editing onboarding if you want a full question-by-question review.</p>
                        </div>
                    @endforelse
                </section>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('[data-dashboard-sidebar]');
            const openButton = document.querySelector('[data-dashboard-sidebar-button]');
            const closeButton = document.querySelector('[data-dashboard-sidebar-close]');
            const overlay = document.querySelector('[data-dashboard-sidebar-overlay]');
            if (!sidebar || !openButton || !overlay) return;
            const setDrawerState = (isOpen) => {
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
        });
    </script>
</body>
</html>
