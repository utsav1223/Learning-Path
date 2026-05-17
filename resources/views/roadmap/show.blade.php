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
    @php
        $metrics = collect($roadmap['metrics'] ?? [])->take(4);
        $studyTracks = collect($roadmap['study_tracks'] ?? [])->take(4);
        $weeklyFocus = collect($roadmap['weekly_focus'] ?? [])->take(4);
        $todoSections = collect($roadmap['todo_sections'] ?? [])->take(3);
        $resources = collect($roadmap['resource_stack'] ?? [])->take(8);
        $milestones = collect($roadmap['project_milestones'] ?? [])->take(4);
        $priorityActions = collect($roadmap['priority_actions'] ?? [])->take(5);
        $mentorNotes = collect($roadmap['mentor_notes'] ?? [])->take(4);
        $hasRoadmap = $weeklyFocus->isNotEmpty();
    @endphp

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
            :profile="$profile"
            :currentGoal="$profile?->learning_goal ?? $user->goal ?? 'Skill growth'"
            :dailyMinutes="$dailyMinutes"
            :pace="$user->learning_pace ?? 'Steady'"
        />

        <main class="px-4 py-5 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/30 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            <section class="rounded-[2rem] bg-slate-950 p-5 text-white shadow-sm dark:bg-slate-900 sm:p-6 lg:p-8">
                <div class="grid gap-8 xl:grid-cols-[1.15fr_0.85fr] xl:items-end">
                    <div>
                        <p class="text-xs font-extrabold uppercase tracking-[0.24em] text-blue-300">AI learning roadmap</p>
                        <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">{{ $roadmap['headline'] ?? 'Generate your personalized roadmap' }}</h1>
                        <p class="mt-4 max-w-3xl text-sm font-semibold leading-6 text-slate-300">{{ $roadmap['summary'] ?? 'Create a detailed, assessment-aware roadmap with weekly focus blocks, practical tasks, resources, and milestones.' }}</p>
                    </div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        <form method="POST" action="{{ route('roadmap.generate') }}">
                            @csrf
                            <button class="inline-flex w-full items-center justify-center rounded-2xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-500">
                                {{ $hasRoadmap ? 'Regenerate roadmap' : 'Generate roadmap' }}
                            </button>
                        </form>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-2xl border border-white/10 bg-white/10 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-white/15">Back to dashboard</a>
                    </div>
                </div>
            </section>

            @unless ($hasRoadmap)
                <section class="mt-6 rounded-[2rem] border border-slate-200 bg-white p-6 text-center shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">No roadmap yet</p>
                    <h2 class="mt-3 text-2xl font-extrabold">Generate your roadmap from the dashboard signals</h2>
                    <p class="mx-auto mt-3 max-w-2xl text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">Your assessment is complete. Click generate to ask Gemini 2.5 Pro for a detailed plan tailored to your score, weak areas, goal, daily time, and preferred study style.</p>
                </section>
            @else
                <section class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    @foreach ($metrics as $metric)
                        <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                            <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500 dark:text-slate-400">{{ $metric['label'] ?? 'Metric' }}</p>
                            <p class="mt-3 text-2xl font-extrabold">{{ $metric['value'] ?? '--' }}</p>
                        </div>
                    @endforeach
                </section>

                <section class="mt-6 grid gap-5 xl:grid-cols-[0.78fr_1.22fr]">
                    <div class="space-y-5">
                        <div class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                            <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">Priority actions</p>
                            <div class="mt-5 space-y-3">
                                @foreach ($priorityActions as $action)
                                    <div class="rounded-2xl bg-slate-50 px-4 py-3 text-sm font-semibold leading-6 text-slate-700 dark:bg-slate-950 dark:text-slate-200">{{ $action }}</div>
                                @endforeach
                            </div>
                        </div>

                        <div class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                            <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">Tracks</p>
                            <div class="mt-5 space-y-4">
                                @foreach ($studyTracks as $track)
                                    <div class="rounded-[1.25rem] border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950">
                                        <div class="flex items-start justify-between gap-3">
                                            <p class="font-extrabold">{{ $track['title'] ?? 'Study track' }}</p>
                                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-extrabold uppercase tracking-[0.14em] text-blue-700 dark:bg-blue-950/40 dark:text-blue-300">{{ $track['confidence'] ?? 'Medium' }}</span>
                                        </div>
                                        <p class="mt-3 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $track['reason'] ?? '' }}</p>
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @foreach (($track['focus_topics'] ?? []) as $topic)
                                                <span class="rounded-full bg-white px-3 py-2 text-xs font-extrabold text-slate-600 dark:bg-slate-900 dark:text-slate-200">{{ $topic }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div id="path" class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">4-week plan</p>
                                <h2 class="mt-2 text-2xl font-extrabold">Detailed study timeline</h2>
                            </div>
                            <div class="rounded-full bg-slate-100 px-4 py-2 text-sm font-extrabold text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ $profile?->weekly_days ?? 5 }} study days/week</div>
                        </div>

                        <div class="mt-6 space-y-4">
                            @foreach ($weeklyFocus as $week)
                                <article class="rounded-[1.5rem] border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950">
                                    <div class="grid gap-4 lg:grid-cols-[1fr_16rem]">
                                        <div>
                                            <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">{{ $week['week'] ?? 'Week' }}</p>
                                            <h3 class="mt-2 text-xl font-extrabold">{{ $week['title'] ?? 'Focus block' }}</h3>
                                            <p class="mt-3 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $week['goal'] ?? '' }}</p>
                                        </div>
                                        <div class="rounded-2xl bg-white p-4 text-sm font-semibold leading-6 text-slate-700 dark:bg-slate-900 dark:text-slate-200">Deliverable: {{ $week['deliverable'] ?? '' }}</div>
                                    </div>

                                    <div class="mt-5 grid gap-4 xl:grid-cols-[1fr_0.82fr]">
                                        <div class="space-y-3">
                                            @foreach (($week['tasks'] ?? []) as $task)
                                                <div class="rounded-2xl bg-white p-4 dark:bg-slate-900">
                                                    <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                                        <p class="text-sm font-extrabold">{{ $task['title'] ?? 'Task' }}</p>
                                                        <div class="flex flex-wrap gap-2">
                                                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-extrabold text-blue-700 dark:bg-blue-950/40 dark:text-blue-300">{{ $task['priority'] ?? 'Medium' }}</span>
                                                            <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $task['effort'] ?? '30 min' }}</span>
                                                        </div>
                                                    </div>
                                                    <p class="mt-2 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $task['detail'] ?? '' }}</p>
                                                </div>
                                            @endforeach
                                        </div>

                                        <div class="space-y-3">
                                            @foreach (($week['resources'] ?? []) as $resource)
                                                <a href="{{ $resource['url'] ?? '#' }}" target="_blank" rel="noreferrer" class="block rounded-2xl border border-slate-200 bg-white p-4 transition hover:border-blue-300 dark:border-slate-700 dark:bg-slate-900 dark:hover:border-blue-500">
                                                    <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500 dark:text-slate-400">{{ $resource['type'] ?? 'Docs' }}</p>
                                                    <p class="mt-2 text-sm font-extrabold">{{ $resource['title'] ?? 'Resource' }}</p>
                                                    <p class="mt-2 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $resource['why'] ?? '' }}</p>
                                                </a>
                                            @endforeach
                                            <div class="rounded-2xl bg-slate-950 p-4 text-white dark:bg-slate-800">
                                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400">Checkpoint</p>
                                                <p class="mt-2 text-sm font-semibold leading-6 text-slate-200">{{ $week['checkpoint'] ?? '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </div>
                </section>

                <section id="resources" class="mt-6 grid gap-5 xl:grid-cols-2">
                    <div class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                        <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">Todo board</p>
                        <div class="mt-5 space-y-4">
                            @foreach ($todoSections as $section)
                                <div class="rounded-[1.25rem] border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950">
                                    <p class="font-extrabold">{{ $section['title'] ?? 'Next actions' }}</p>
                                    <p class="mt-2 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $section['summary'] ?? '' }}</p>
                                    <div class="mt-4 space-y-3">
                                        @foreach (($section['items'] ?? []) as $item)
                                            <div class="rounded-2xl bg-white p-4 dark:bg-slate-900">
                                                <p class="text-sm font-extrabold">{{ $item['task'] ?? 'Task' }}</p>
                                                <p class="mt-2 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $item['outcome'] ?? '' }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-5">
                        <div class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                            <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">Resources</p>
                            <div class="mt-5 grid gap-3 sm:grid-cols-2">
                                @foreach ($resources as $resource)
                                    <a href="{{ $resource['url'] ?? '#' }}" target="_blank" rel="noreferrer" class="rounded-[1.25rem] border border-slate-200 bg-slate-50 p-4 transition hover:border-blue-300 dark:border-slate-700 dark:bg-slate-950 dark:hover:border-blue-500">
                                        <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500 dark:text-slate-400">{{ $resource['type'] ?? 'Docs' }}</p>
                                        <p class="mt-2 font-extrabold">{{ $resource['title'] ?? 'Resource' }}</p>
                                        <p class="mt-2 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $resource['topic'] ?? 'General' }}</p>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <div class="rounded-[2rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                            <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">Projects</p>
                            <div class="mt-5 space-y-4">
                                @foreach ($milestones as $milestone)
                                    <div class="rounded-[1.25rem] bg-slate-50 p-4 dark:bg-slate-950">
                                        <p class="font-extrabold">{{ $milestone['title'] ?? 'Project milestone' }}</p>
                                        <p class="mt-2 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $milestone['description'] ?? '' }}</p>
                                        <p class="mt-3 rounded-2xl bg-white px-4 py-3 text-sm font-semibold text-slate-700 dark:bg-slate-900 dark:text-slate-200">Deliverable: {{ $milestone['deliverable'] ?? '' }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="rounded-[2rem] bg-slate-950 p-5 text-white shadow-sm dark:bg-slate-800 sm:p-6">
                            <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-400">Mentor notes</p>
                            <div class="mt-4 space-y-3">
                                @foreach ($mentorNotes as $note)
                                    <p class="text-sm font-semibold leading-6 text-slate-200">{{ $note }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
            @endunless
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
