<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roadmap | SkillWeave</title>
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
                <p class="text-sm font-extrabold">Roadmap</p>
            </div>
            <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" aria-label="Open navigation menu" aria-expanded="false" aria-controls="dashboard-sidebar" data-dashboard-sidebar-button>
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"></path>
                </svg>
            </button>
        </div>

        <x-dashboard.sidebar
            :user="$user"
            :profile="$user->profile"
            :currentGoal="$user->profile?->learning_goal ?? $user->goal ?? 'Skill growth'"
            :dailyMinutes="$user->profile?->daily_learning_time ?? 45"
            :pace="$user->learning_pace ?? 'Steady'"
        />

        <main class="px-4 py-5 sm:px-6 lg:px-8">
            <section class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                <div class="flex flex-col gap-6 xl:flex-row xl:items-end xl:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-extrabold uppercase tracking-[0.24em] text-blue-600 dark:text-blue-400">Personalized Learning Path</p>
                        <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Your AI Roadmap</h1>
                        <p class="mt-3 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $analysisSummary }}</p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <form method="POST" action="{{ route('roadmap.generate') }}">
                            @csrf
                            <button class="inline-flex w-full items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700">
                                {{ $attempt?->roadmap_generated_at ? 'Regenerate roadmap' : 'Generate roadmap' }}
                            </button>
                        </form>
                    </div>
                </div>
            </section>

            @if(empty($roadmap))
                <div class="mt-6 rounded-[2rem] border border-slate-200 bg-white p-10 text-center shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-lg font-extrabold text-slate-700 dark:text-slate-300">Your roadmap has not been generated yet.</p>
                    <p class="mt-2 text-sm font-semibold text-slate-500 dark:text-slate-400">Click the button above to generate a personalized learning path based on your assessment.</p>
                </div>
            @else
    {{-- ── Headline + Metrics ── --}}
    <section class="mt-6 rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
        <h2 class="text-2xl font-extrabold">{{ $roadmap['headline'] ?? 'Your Roadmap' }}</h2>
        @if(!empty($metrics))
            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                @foreach($metrics as $metric)
                    <div class="rounded-2xl bg-slate-50 p-4 dark:bg-slate-800">
                        <p class="text-xs font-extrabold uppercase tracking-widest text-slate-400">{{ $metric['label'] }}</p>
                        <p class="mt-1 text-lg font-extrabold text-blue-600 dark:text-blue-400">{{ $metric['value'] }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- ── Study Tracks ── --}}
    @if(!empty($studyTracks))
        <section class="mt-5 rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-xl font-extrabold">Study Tracks</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                @foreach($studyTracks as $track)
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800">
                        <div class="flex items-center justify-between">
                            <p class="font-extrabold text-slate-800 dark:text-white">{{ $track['title'] }}</p>
                            <span class="rounded-full px-2 py-0.5 text-xs font-extrabold
                                {{ ($track['confidence'] ?? '') === 'High'   ? 'bg-emerald-100 text-emerald-700' :
                                  (($track['confidence'] ?? '') === 'Low'    ? 'bg-rose-100 text-rose-700' :
                                                                               'bg-amber-100 text-amber-700') }}">
                                {{ $track['confidence'] ?? 'Medium' }}
                            </span>
                        </div>
                        <p class="mt-1 text-xs font-semibold text-slate-500 dark:text-slate-400">{{ $track['reason'] }}</p>
                        @if(!empty($track['focus_topics']))
                            <div class="mt-2 flex flex-wrap gap-1">
                                @foreach($track['focus_topics'] as $topic)
                                    <span class="rounded-lg bg-blue-50 px-2 py-0.5 text-xs font-bold text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">{{ $topic }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ── Weekly Focus ── --}}
    @if(!empty($weeklyFocus))
        <section class="mt-5 rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-xl font-extrabold">4-Week Plan</h2>
            <div class="mt-4 space-y-6">
                @foreach($weeklyFocus as $week)
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5 dark:border-slate-700 dark:bg-slate-800">
                        <div class="flex items-center gap-3">
                            <span class="rounded-xl bg-blue-600 px-3 py-1 text-xs font-extrabold text-white">{{ $week['week'] }}</span>
                            <p class="font-extrabold text-slate-800 dark:text-white">{{ $week['title'] }}</p>
                        </div>
                        <p class="mt-2 text-sm font-semibold text-slate-500 dark:text-slate-400">Goal: {{ $week['goal'] }}</p>
                        <p class="mt-1 text-sm font-semibold text-emerald-600 dark:text-emerald-400">Deliverable: {{ $week['deliverable'] }}</p>

                        {{-- Tasks --}}
                        @if(!empty($week['tasks']))
                            <div class="mt-3 space-y-2">
                                @foreach($week['tasks'] as $task)
                                    <div class="flex items-start gap-2 rounded-xl bg-white p-3 shadow-sm dark:bg-slate-900">
                                        <span class="mt-0.5 rounded-lg px-2 py-0.5 text-xs font-extrabold
                                            {{ ($task['priority'] ?? '') === 'High'   ? 'bg-rose-100 text-rose-700' :
                                              (($task['priority'] ?? '') === 'Low'    ? 'bg-slate-100 text-slate-500' :
                                                                                        'bg-amber-100 text-amber-700') }}">
                                            {{ $task['priority'] ?? 'Medium' }}
                                        </span>
                                        <div>
                                            <p class="text-sm font-extrabold text-slate-800 dark:text-white">{{ $task['title'] }}</p>
                                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ $task['detail'] }}</p>
                                            <p class="mt-1 text-xs font-bold text-blue-500">⏱ {{ $task['effort'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Resources --}}
                        @if(!empty($week['resources']))
                            <div class="mt-3">
                                <p class="text-xs font-extrabold uppercase tracking-widest text-slate-400">Resources</p>
                                <div class="mt-1 space-y-1">
                                    @foreach($week['resources'] as $res)
                                        <a href="{{ $res['url'] }}" target="_blank" rel="noopener"
                                           class="flex items-center gap-2 text-sm font-semibold text-blue-600 hover:underline dark:text-blue-400">
                                            <span class="rounded bg-blue-50 px-1.5 py-0.5 text-xs font-bold text-blue-700 dark:bg-blue-900/30">{{ $res['type'] }}</span>
                                            {{ $res['title'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <p class="mt-3 rounded-xl border border-emerald-200 bg-emerald-50 p-3 text-xs font-bold text-emerald-700 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-400">
                            ✓ Checkpoint: {{ $week['checkpoint'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ── Priority Actions + Mentor Notes ── --}}
    <div class="mt-5 grid gap-5 xl:grid-cols-2">
        @if(!empty($priorityActions))
            <section class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-xl font-extrabold">Priority Actions</h2>
                <ol class="mt-4 space-y-2 list-none">
                    @foreach($priorityActions as $i => $action)
                        <li class="flex items-start gap-3 text-sm font-semibold text-slate-700 dark:text-slate-300">
                            <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-rose-100 text-xs font-extrabold text-rose-600">{{ $i + 1 }}</span>
                            {{ $action }}
                        </li>
                    @endforeach
                </ol>
            </section>
        @endif

        @if(!empty($mentorNotes))
            <section class="rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-xl font-extrabold">Mentor Notes</h2>
                <ul class="mt-4 space-y-2">
                    @foreach($mentorNotes as $note)
                        <li class="flex items-start gap-2 text-sm font-semibold text-slate-600 dark:text-slate-300">
                            <span class="mt-1 text-amber-500">💡</span> {{ $note }}
                        </li>
                    @endforeach
                </ul>
            </section>
        @endif
    </div>

    {{-- ── Project Milestones ── --}}
    @if(!empty($projectMilestones))
        <section class="mt-5 rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-xl font-extrabold">Project Milestones</h2>
            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                @foreach($projectMilestones as $i => $milestone)
                    <div class="rounded-2xl border border-slate-100 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-800">
                        <div class="flex items-center gap-2">
                            <span class="flex h-7 w-7 items-center justify-center rounded-full bg-blue-600 text-xs font-extrabold text-white">{{ $i + 1 }}</span>
                            <p class="font-extrabold text-slate-800 dark:text-white">{{ $milestone['title'] }}</p>
                        </div>
                        <p class="mt-2 text-xs font-semibold text-slate-500 dark:text-slate-400">{{ $milestone['description'] }}</p>
                        <p class="mt-1 text-xs font-bold text-emerald-600 dark:text-emerald-400">📦 {{ $milestone['deliverable'] }}</p>
                        @if(!empty($milestone['skills']))
                            <div class="mt-2 flex flex-wrap gap-1">
                                @foreach($milestone['skills'] as $skill)
                                    <span class="rounded-lg bg-slate-200 px-2 py-0.5 text-xs font-bold text-slate-700 dark:bg-slate-700 dark:text-slate-300">{{ $skill }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ── Resource Stack ── --}}
    @if(!empty($resourceStack))
        <section class="mt-5 rounded-[2rem] border border-slate-200 bg-white p-6 shadow-sm dark:border-slate-800 dark:bg-slate-900">
            <h2 class="text-xl font-extrabold">Resource Stack</h2>
            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                @foreach($resourceStack as $res)
                    <a href="{{ $res['url'] }}" target="_blank" rel="noopener"
                       class="flex items-start gap-3 rounded-2xl border border-slate-100 bg-slate-50 p-4 transition hover:border-blue-300 dark:border-slate-700 dark:bg-slate-800">
                        <span class="rounded-lg bg-blue-100 px-2 py-1 text-xs font-extrabold text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 whitespace-nowrap">{{ $res['type'] }}</span>
                        <div>
                            <p class="text-sm font-extrabold text-blue-600 dark:text-blue-400">{{ $res['title'] }}</p>
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">{{ $res['why'] ?? $res['topic'] ?? '' }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
            @endif
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('[data-dashboard-sidebar]');
            const openButton = document.querySelector('[data-dashboard-sidebar-button]');
            const closeButton = document.querySelector('[data-dashboard-sidebar-close]');
            const overlay = document.querySelector('[data-dashboard-sidebar-overlay]');

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