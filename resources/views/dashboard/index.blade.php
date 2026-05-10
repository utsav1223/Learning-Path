<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | SkillWeave</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body class="bg-slate-100 text-slate-900">
    <div class="fixed inset-0 z-30 bg-slate-950/50 opacity-0 pointer-events-none transition-opacity duration-300 lg:hidden" data-dashboard-sidebar-overlay></div>

    <div class="min-h-screen lg:grid lg:grid-cols-[18rem_1fr]">
        <div class="sticky top-0 z-30 flex items-center justify-between border-b border-slate-200 bg-white/90 px-4 py-3 shadow-sm backdrop-blur-xl lg:hidden">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-slate-400">SkillWeave</p>
                <p class="text-sm font-extrabold text-slate-900">Dashboard</p>
            </div>
            <button
                type="button"
                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-900"
                aria-label="Open navigation menu"
                aria-expanded="false"
                aria-controls="dashboard-sidebar"
                data-dashboard-sidebar-button
            >
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"></path>
                </svg>
            </button>
        </div>

        <x-dashboard.sidebar 
            :user="$user" 
            :profile="$profile"
            :currentGoal="$profile?->learning_goal ?? $user->goal ?? 'Skill growth'"
            :dailyMinutes="$dailyMinutes ?? 45"
            :pace="$user->learning_pace ?? 'steady'"
        />
        
        <main class="px-4 py-5 sm:px-6 lg:px-8">
            <x-dashboard.welcome-header
                :userName="$user->name"
                :pace="$user->learning_pace ?? 'Steady'"
                :readiness="$progress ?? 0"
                description="Your path is tuned from your onboarding answers and progress signals."
            />

            @if (session('status'))
                <x-ui.card padding="p-5 md:p-6" rounded="rounded-2xl md:rounded-[1.75rem]" background="bg-emerald-50" border="border-emerald-200" class="mt-5 text-sm font-semibold text-emerald-700 md:shadow-xl md:shadow-emerald-200/40">
                    {{ session('status') }}
                </x-ui.card>
            @endif

            <section class="mt-6 grid gap-4 md:grid-cols-3">
                <x-ui.card padding="p-5" rounded="rounded-[1.5rem]">
                    <p class="text-sm font-bold text-slate-500">Skill level</p>
                    <p class="mt-3 text-2xl font-extrabold">{{ $profile?->skill_level ?? 'Beginner' }}</p>
                    <x-ui.progress-bar class="mt-5" :percentage="min($progress, 100)" />
                </x-ui.card>

                <x-ui.card padding="p-5" rounded="rounded-[1.5rem]">
                    <p class="text-sm font-bold text-slate-500">Focus areas</p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($interests as $interest)
                            <x-ui.badge color="blue" size="sm">{{ $interest }}</x-ui.badge>
                        @endforeach
                    </div>
                </x-ui.card>

                <x-ui.card padding="p-5" rounded="rounded-[1.5rem]">
                    <p class="text-sm font-bold text-slate-500">Today plan</p>
                    <p class="mt-3 text-2xl font-extrabold">{{ $dailyMinutes ?? 45 }} minutes</p>
                    <p class="mt-2 text-sm font-semibold text-slate-500">{{ $user->learning_format ?? 'Videos + quizzes' }}</p>
                </x-ui.card>
            </section>

            <section id="path" class="mt-6 grid gap-5 xl:grid-cols-[1.2fr_0.8fr]">
                <x-ui.card padding="p-5 sm:p-6" rounded="rounded-[1.75rem]">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-extrabold uppercase tracking-[0.22em] text-blue-600">Adaptive path</p>
                            <h2 class="mt-2 text-2xl font-extrabold">Next milestones</h2>
                        </div>
                        <x-ui.badge color="slate" size="sm" variant="light" class="shrink-0">Updated now</x-ui.badge>
                    </div>

                    <div class="mt-6 grid gap-4">
                        @foreach ($modules as $module)
                            <x-dashboard.milestone-card
                                :status="$module['status']"
                                :title="$module['title']"
                                :description="$module['description']"
                                :match="$module['match']"
                            />
                        @endforeach
                    </div>
                </x-ui.card>

                <x-dashboard.skill-graph 
                    :nodes="['Foundation', 'Practice', 'Checkpoint', 'Project']"
                    :completedUpTo="1"
                />
            </section>

            <section id="resources" class="mt-6">
                <x-ui.card padding="p-5 sm:p-6" rounded="rounded-[1.75rem]">
                    <x-ui.section-header
                        label="Recommended resources"
                        title="Start here today"
                        :action="route('onboarding')"
                        actionLabel="Refine preferences"
                    />

                    <div class="mt-6 grid gap-4 lg:grid-cols-3">
                        @foreach ($resources as $resource)
                            <x-dashboard.resource-card
                                :type="$resource['type']"
                                :title="$resource['title']"
                                :time="$resource['time']"
                            />
                        @endforeach
                    </div>
                </x-ui.card>
            </section>

            <form method="POST" action="{{ route('logout') }}" class="mt-6 lg:hidden">
                @csrf
                <button class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-extrabold text-slate-700 shadow-sm">Logout</button>
            </form>
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

            openButton.addEventListener('click', function () {
                const isOpen = openButton.getAttribute('aria-expanded') === 'true';
                setDrawerState(!isOpen);
            });

            if (closeButton) {
                closeButton.addEventListener('click', function () {
                    setDrawerState(false);
                });
            }

            overlay.addEventListener('click', function () {
                setDrawerState(false);
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024) {
                    setDrawerState(false);
                }
            });
        });
    </script>
</body>
</html>

