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
<body class="bg-slate-50 text-slate-900 dark:bg-slate-950 dark:text-slate-100">
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

        <main class="px-4 py-5 sm:px-6 lg:px-8">
            <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-extrabold uppercase tracking-[0.24em] text-blue-600 dark:text-blue-400">Assessment complete</p>
                        <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Welcome back, {{ $user->name }}</h1>
                        <p class="mt-3 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $analysisSummary }}</p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <button type="button" data-theme-toggle class="rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-extrabold text-slate-700 transition hover:border-slate-300 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">Toggle theme</button>
                        <a href="{{ route('onboarding') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-extrabold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">Edit profile</a>
                    </div>
                </div>
            </section>

            @if (session('status'))
                <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/30 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            <section class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Assessment score</p>
                    <p class="mt-3 text-3xl font-extrabold">{{ $correctCount }}/{{ $attempt?->total_questions ?? 25 }}</p>
                    <p class="mt-2 text-sm font-semibold text-slate-500 dark:text-slate-300">{{ $completionRate }}% correct</p>
                </div>
                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Readiness</p>
                    <p class="mt-3 text-3xl font-extrabold">{{ $progress }}%</p>
                    <p class="mt-2 text-sm font-semibold text-slate-500 dark:text-slate-300">{{ $profile?->skill_level ?? 'Beginner' }} level</p>
                </div>
                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Study rhythm</p>
                    <p class="mt-3 text-3xl font-extrabold">{{ $dailyMinutes }} min</p>
                    <p class="mt-2 text-sm font-semibold text-slate-500 dark:text-slate-300">{{ $profile?->weekly_days ?? 5 }} days per week</p>
                </div>
                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">Goal</p>
                    <p class="mt-3 text-xl font-extrabold">{{ $profile?->learning_goal ?? $user->goal ?? 'Skill growth' }}</p>
                    <p class="mt-2 text-sm font-semibold text-slate-500 dark:text-slate-300">{{ $profile?->target_role ?? 'Role-ready path' }}</p>
                </div>
            </section>

            <section class="mt-6 grid gap-5 xl:grid-cols-[1.05fr_0.95fr]">
                <div class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                    <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">Assessment analytics</p>
                            <h2 class="mt-2 text-2xl font-extrabold">Topic performance</h2>
                        </div>
                        <div class="rounded-full bg-slate-100 px-4 py-2 text-sm font-extrabold text-slate-700 dark:bg-slate-800 dark:text-slate-200">
                            One-time locked result
                        </div>
                    </div>

                    <div class="mt-6 grid gap-4 lg:grid-cols-2">
                        @foreach ($weakTopicBreakdown as $topic)
                            <div class="rounded-[1.25rem] border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="font-extrabold">{{ $topic['topic'] }}</p>
                                        <p class="mt-1 text-sm font-semibold text-slate-500 dark:text-slate-300">{{ $topic['correct'] }} correct, {{ $topic['wrong'] }} wrong</p>
                                    </div>
                                    <span class="text-lg font-extrabold">{{ $topic['score'] }}%</span>
                                </div>
                                <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-200 dark:bg-slate-800">
                                    <div class="h-full rounded-full {{ $topic['score'] >= 70 ? 'bg-emerald-500' : ($topic['score'] >= 45 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $topic['score'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                    <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">AI roadmap</p>
                    <h2 class="mt-2 text-2xl font-extrabold">Generate your detailed learning path</h2>
                    <p class="mt-3 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">
                        The dashboard stays focused on your assessment summary. Your full weekly plan, projects, resources, and todo lists live on a dedicated roadmap page.
                    </p>

                    <div class="mt-6 rounded-[1.5rem] bg-slate-950 p-5 text-white dark:bg-slate-800">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400">Roadmap status</p>
                        <p class="mt-3 text-xl font-extrabold">
                            @if ($attempt?->roadmap_generated_at)
                                Generated {{ $attempt->roadmap_generated_at->diffForHumans() }}
                            @else
                                Not generated yet
                            @endif
                        </p>
                        <p class="mt-2 text-sm font-semibold leading-6 text-slate-300">
                            Model: {{ config('services.gemini.model') }}
                        </p>
                    </div>

                    <div class="mt-6 grid gap-3 sm:grid-cols-2">
                        <form method="POST" action="{{ route('roadmap.generate') }}">
                            @csrf
                            <button class="inline-flex w-full items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                                {{ $attempt?->roadmap_generated_at ? 'Regenerate roadmap' : 'Generate roadmap' }}
                            </button>
                        </form>
                        <a href="{{ route('roadmap.show') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm font-extrabold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100">
                            View roadmap
                        </a>
                    </div>

                    <div class="mt-6 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-[1.25rem] border border-rose-200 bg-rose-50 p-4 dark:border-rose-900 dark:bg-rose-950/30">
                            <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-rose-700 dark:text-rose-300">Weak areas</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach (($insights['weak_areas'] ?? []) as $area)
                                    <span class="rounded-full bg-white px-3 py-2 text-xs font-extrabold text-rose-700 dark:bg-slate-900 dark:text-rose-300">{{ $area }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="rounded-[1.25rem] border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900 dark:bg-emerald-950/30">
                            <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-emerald-700 dark:text-emerald-300">Strong areas</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                @foreach (($insights['strong_areas'] ?? []) as $area)
                                    <span class="rounded-full bg-white px-3 py-2 text-xs font-extrabold text-emerald-700 dark:bg-slate-900 dark:text-emerald-300">{{ $area }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        @if (session('block_back_navigation'))
            (() => {
                const lockedUrl = window.location.href;
                const historyBufferSize = 12;
                let refillQueued = false;

                const lockedState = (index) => ({
                    skillweaveFlowLocked: true,
                    index,
                });

                const refillHistoryBuffer = () => {
                    window.history.replaceState(lockedState(0), '', lockedUrl);

                    for (let index = 1; index <= historyBufferSize; index += 1) {
                        window.history.pushState(lockedState(index), '', lockedUrl);
                    }
                };

                refillHistoryBuffer();
                window.addEventListener('popstate', () => {
                    if (refillQueued) {
                        return;
                    }

                    refillQueued = true;
                    window.setTimeout(() => {
                        refillHistoryBuffer();
                        refillQueued = false;
                    }, 0);
                });

                window.addEventListener('pageshow', (event) => {
                    if (event.persisted) {
                        refillHistoryBuffer();
                    }
                });
            })();
        @endif

        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('[data-dashboard-sidebar]');
            const openButton = document.querySelector('[data-dashboard-sidebar-button]');
            const closeButton = document.querySelector('[data-dashboard-sidebar-close]');
            const overlay = document.querySelector('[data-dashboard-sidebar-overlay]');
            const themeToggle = document.querySelector('[data-theme-toggle]');

            themeToggle?.addEventListener('click', function () {
                const nextTheme = document.documentElement.classList.contains('dark') ? 'light' : 'dark';
                document.documentElement.classList.toggle('dark', nextTheme === 'dark');
                localStorage.setItem('skillweave-theme', nextTheme);
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
