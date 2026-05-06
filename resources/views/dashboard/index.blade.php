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
    <div class="min-h-screen lg:grid lg:grid-cols-[18rem_1fr]">
        <aside class="sticky top-0 z-20 bg-slate-950 px-4 py-4 text-white shadow-lg shadow-slate-950/10 sm:px-5 lg:min-h-screen lg:py-6">
            <div class="flex items-center justify-between gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-3">
                    <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-600 text-lg font-extrabold">S</span>
                    <span>
                        <span class="block text-lg font-extrabold">SkillWeave</span>
                        <span class="block text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Learner desk</span>
                    </span>
                </a>
                <a href="{{ route('onboarding') }}" class="rounded-full bg-white/10 px-3 py-2 text-xs font-extrabold text-slate-200 lg:hidden">Edit</a>
            </div>

            <nav class="mt-5 flex gap-2 overflow-x-auto text-sm font-bold lg:mt-10 lg:grid lg:overflow-visible">
                <a href="#" class="rounded-2xl bg-white/10 px-4 py-3 text-white">Dashboard</a>
                <a href="#path" class="rounded-2xl px-4 py-3 text-slate-300 hover:bg-white/10 hover:text-white">Learning Path</a>
                <a href="#resources" class="rounded-2xl px-4 py-3 text-slate-300 hover:bg-white/10 hover:text-white">Resources</a>
                <a href="{{ route('onboarding') }}" class="rounded-2xl px-4 py-3 text-slate-300 hover:bg-white/10 hover:text-white">Edit Profile</a>
            </nav>

            <div class="mt-6 hidden rounded-3xl bg-white/10 p-5 lg:block">
                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400">Current goal</p>
                <p class="mt-3 text-xl font-extrabold">{{ $profile?->learning_goal ?? $user->goal ?? 'Skill growth' }}</p>
                <p class="mt-3 text-sm leading-6 text-slate-300">{{ $dailyMinutes }} minutes per day, {{ $user->learning_pace ?? 'steady' }} pace.</p>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-8 hidden lg:block">
                @csrf
                <button class="w-full rounded-2xl border border-white/10 px-4 py-3 text-sm font-extrabold text-slate-200 hover:bg-white/10">Logout</button>
            </form>
        </aside>

        <main class="px-4 py-5 sm:px-6 lg:px-8">
            <header class="flex flex-col gap-4 rounded-[1.75rem] bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-6">
                <div>
                    <p class="text-sm font-extrabold uppercase tracking-[0.22em] text-blue-600">Welcome back</p>
                    <h1 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl">{{ $user->name }}</h1>
                    <p class="mt-2 text-sm font-semibold text-slate-500">Your path is tuned from your onboarding answers and progress signals.</p>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:flex sm:items-center">
                    <div class="rounded-2xl bg-slate-50 px-4 py-3">
                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400">Pace</p>
                        <p class="mt-1 text-sm font-extrabold">{{ $user->learning_pace ?? 'Steady' }}</p>
                    </div>
                    <div class="rounded-2xl bg-slate-950 px-5 py-4 text-white">
                    <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400">Readiness</p>
                    <p class="mt-1 text-3xl font-extrabold">{{ $progress }}%</p>
                    </div>
                </div>
            </header>

            @if (session('status'))
                <div class="mt-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-bold text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <section class="mt-6 grid gap-4 md:grid-cols-3">
                <div class="rounded-[1.5rem] bg-white p-5 shadow-sm">
                    <p class="text-sm font-bold text-slate-500">Skill level</p>
                    <p class="mt-3 text-2xl font-extrabold">{{ $profile?->skill_level ?? 'Beginner' }}</p>
                    <div class="mt-5 h-2 rounded-full bg-slate-100">
                        <div class="h-2 rounded-full bg-blue-600" style="width: {{ min($progress, 100) }}%"></div>
                    </div>
                </div>
                <div class="rounded-[1.5rem] bg-white p-5 shadow-sm">
                    <p class="text-sm font-bold text-slate-500">Focus areas</p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach ($interests as $interest)
                            <span class="rounded-full bg-blue-50 px-3 py-1 text-sm font-extrabold text-blue-700">{{ $interest }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="rounded-[1.5rem] bg-white p-5 shadow-sm">
                    <p class="text-sm font-bold text-slate-500">Today plan</p>
                    <p class="mt-3 text-2xl font-extrabold">{{ $dailyMinutes }} minutes</p>
                    <p class="mt-2 text-sm font-semibold text-slate-500">{{ $user->learning_format ?? 'Videos + quizzes' }}</p>
                </div>
            </section>

            <section id="path" class="mt-6 grid gap-5 xl:grid-cols-[1.2fr_0.8fr]">
                <div class="rounded-[1.75rem] bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-extrabold uppercase tracking-[0.22em] text-blue-600">Adaptive path</p>
                            <h2 class="mt-2 text-2xl font-extrabold">Next milestones</h2>
                        </div>
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-bold text-slate-600">Updated now</span>
                    </div>

                    <div class="mt-6 grid gap-4">
                        @foreach ($modules as $module)
                            <article class="rounded-3xl border border-slate-200 p-4 transition hover:border-blue-200 hover:shadow-sm sm:p-5">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-sm font-extrabold text-blue-700">{{ $module['status'] }}</p>
                                        <h3 class="mt-1 text-xl font-extrabold">{{ $module['title'] }}</h3>
                                        <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">{{ $module['description'] }}</p>
                                    </div>
                                    <div class="shrink-0 rounded-2xl bg-slate-50 px-4 py-3 text-center">
                                        <p class="text-2xl font-extrabold">{{ $module['match'] }}%</p>
                                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">match</p>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-[1.75rem] bg-slate-950 p-5 text-white shadow-sm sm:p-6">
                    <p class="text-sm font-extrabold uppercase tracking-[0.22em] text-blue-200">Skill graph</p>
                    <h2 class="mt-2 text-2xl font-extrabold">Your route</h2>

                    <div class="mt-8 space-y-5">
                        @foreach (['Foundation', 'Practice', 'Checkpoint', 'Project'] as $index => $node)
                            <div class="flex items-center gap-4">
                                <span class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $index <= 1 ? 'bg-blue-600' : 'bg-white/10' }} text-sm font-extrabold">{{ $index + 1 }}</span>
                                <div>
                                    <p class="font-extrabold">{{ $node }}</p>
                                    <p class="text-sm font-semibold text-slate-400">{{ $index <= 1 ? 'Active in your path' : 'Unlocks after quiz' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            <section id="resources" class="mt-6 rounded-[1.75rem] bg-white p-5 shadow-sm sm:p-6">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="text-sm font-extrabold uppercase tracking-[0.22em] text-blue-600">Recommended resources</p>
                        <h2 class="mt-2 text-2xl font-extrabold">Start here today</h2>
                    </div>
                    <a href="{{ route('onboarding') }}" class="text-sm font-extrabold text-blue-700 hover:text-blue-800">Refine preferences</a>
                </div>

                <div class="mt-6 grid gap-4 lg:grid-cols-3">
                    @foreach ($resources as $resource)
                        <article class="rounded-3xl border border-slate-200 bg-slate-50 p-5">
                            <p class="text-sm font-extrabold text-blue-700">{{ $resource['type'] }}</p>
                            <h3 class="mt-2 text-lg font-extrabold">{{ $resource['title'] }}</h3>
                            <p class="mt-4 text-sm font-bold text-slate-500">{{ $resource['time'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <form method="POST" action="{{ route('logout') }}" class="mt-6 lg:hidden">
                @csrf
                <button class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm font-extrabold text-slate-700 shadow-sm">Logout</button>
            </form>
        </main>
    </div>
</body>
</html>
