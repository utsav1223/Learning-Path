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
<body class="bg-slate-100 text-slate-950 dark:bg-slate-950 dark:text-slate-100">
    @php
        $metrics = collect($roadmap['metrics'] ?? [])->take(4);
        $studyTracks = collect($roadmap['study_tracks'] ?? [])->take(4);
        $weeklyFocus = collect($roadmap['weekly_focus'] ?? [])->take(4);
        $todoSections = collect($roadmap['todo_sections'] ?? [])->take(3);
        $resources = collect($roadmap['resource_stack'] ?? [])->take(10);
        $milestones = collect($roadmap['project_milestones'] ?? [])->take(4);
        $priorityActions = collect($roadmap['priority_actions'] ?? [])->take(5);
        $mentorNotes = collect($roadmap['mentor_notes'] ?? [])->take(4);
        $youtubePreviewIds = [
            'freecodecamp' => 'pQN-pnXPaVg',
            'freecodecamp.org' => 'pQN-pnXPaVg',
            'traversy media' => 'w7ejDZ8SWv8',
            'fireship' => 'DHjqpvDnNGE',
            'the net ninja' => 'iWOYAxlnaww',
            'net ninja' => 'iWOYAxlnaww',
            'kevin powell' => 'rg7Fvvl3taU',
            'corey schafer' => 'ZDa-Z5JzLYM',
            'statquest' => 'qBigTkBLU6g',
        ];
        $resolveYoutubeVideoId = function (string $url, string $title = '') use ($youtubePreviewIds) {
            if (!str_contains($url, 'youtube.com') && !str_contains($url, 'youtu.be')) {
                return null;
            }

            $path = parse_url($url, PHP_URL_PATH) ?: '';
            parse_str(parse_url($url, PHP_URL_QUERY) ?: '', $query);
            $videoId = $query['v'] ?? null;

            if (!$videoId && str_contains($url, 'youtu.be')) {
                $videoId = trim($path, '/');
            }

            if (!$videoId && preg_match('~/embed/([^/?]+)~', $path, $matches)) {
                $videoId = $matches[1];
            }

            if (!$videoId && preg_match('~/shorts/([^/?]+)~', $path, $matches)) {
                $videoId = $matches[1];
            }

            if (!$videoId) {
                $lookup = strtolower($title . ' ' . $url);
                foreach ($youtubePreviewIds as $needle => $previewId) {
                    if (str_contains($lookup, $needle)) {
                        return $previewId;
                    }
                }
            }

            return $videoId;
        };
        $resourceCards = $resources->map(function ($resource) use ($resolveYoutubeVideoId) {
            $url = $resource['url'] ?? '#';
            $host = parse_url($url, PHP_URL_HOST) ?: 'resource';
            $isYoutube = str_contains($url, 'youtube.com');
            $isMdn = str_contains($url, 'developer.mozilla.org');
            $youtubeVideoId = $isYoutube ? $resolveYoutubeVideoId($url, $resource['title'] ?? '') : null;

            return array_merge($resource, [
                'host' => str_replace('www.', '', $host),
                'badge' => $isYoutube ? 'Video' : ($isMdn ? 'MDN Docs' : ($resource['type'] ?? 'Resource')),
                'tone' => $isYoutube ? 'red' : ($isMdn ? 'blue' : 'slate'),
                'youtube_video_id' => $youtubeVideoId,
                'youtube_embed_url' => $youtubeVideoId ? 'https://www.youtube.com/embed/' . $youtubeVideoId : null,
                'youtube_thumbnail_url' => $youtubeVideoId ? 'https://i.ytimg.com/vi/' . $youtubeVideoId . '/hqdefault.jpg' : null,
            ]);
        });
        $videoResourceCards = $resourceCards
            ->filter(fn ($resource) => ($resource['tone'] ?? 'slate') === 'red')
            ->values();
        $referenceResourceCards = $resourceCards
            ->reject(fn ($resource) => ($resource['tone'] ?? 'slate') === 'red')
            ->values();
        $youtubeChannels = collect($roadmap['resource_stack'] ?? [])
            ->filter(fn ($resource) => strcasecmp($resource['type'] ?? '', 'Video') === 0 && str_contains($resource['url'] ?? '', 'youtube.com'))
            ->take(6)
            ->map(function ($resource) use ($resolveYoutubeVideoId) {
                $url = $resource['url'] ?? '#';
                $videoId = $resolveYoutubeVideoId($url, $resource['title'] ?? '');

                return array_merge($resource, [
                    'youtube_video_id' => $videoId,
                    'youtube_embed_url' => $videoId ? 'https://www.youtube.com/embed/' . $videoId : null,
                    'youtube_thumbnail_url' => $videoId ? 'https://i.ytimg.com/vi/' . $videoId . '/hqdefault.jpg' : null,
                ]);
            })
            ->values();
        if ($youtubeChannels->isEmpty()) {
            $youtubeChannels = collect([
                ['title' => 'freeCodeCamp.org', 'type' => 'Video', 'url' => 'https://www.youtube.com/@freecodecamp', 'topic' => 'Full lessons', 'why' => 'Complete beginner-to-advanced courses and project walkthroughs.'],
                ['title' => 'Traversy Media', 'type' => 'Video', 'url' => 'https://www.youtube.com/@TraversyMedia', 'topic' => 'Web projects', 'why' => 'Practical tutorials for frontend, backend, and full-stack builds.'],
                ['title' => 'Fireship', 'type' => 'Video', 'url' => 'https://www.youtube.com/@Fireship', 'topic' => 'Fast revision', 'why' => 'Short explainers that help you revise concepts quickly.'],
                ['title' => 'The Net Ninja', 'type' => 'Video', 'url' => 'https://www.youtube.com/@NetNinja', 'topic' => 'Structured playlists', 'why' => 'Clear playlist-based learning for web development topics.'],
            ])->map(function ($resource) use ($resolveYoutubeVideoId) {
                $videoId = $resolveYoutubeVideoId($resource['url'] ?? '#', $resource['title'] ?? '');

                return array_merge($resource, [
                    'youtube_video_id' => $videoId,
                    'youtube_thumbnail_url' => $videoId ? 'https://i.ytimg.com/vi/' . $videoId . '/hqdefault.jpg' : null,
                ]);
            });
        }
        $videoResourceCards = $videoResourceCards->isNotEmpty() ? $videoResourceCards : $youtubeChannels;
        $hasRoadmap = $weeklyFocus->isNotEmpty();
        $tabs = [
            'overview' => 'Overview',
            'priority' => 'Priority',
            'weak' => 'Weak areas',
            'weekly' => 'Weekly plan',
            'todos' => 'Todo board',
            'resources' => 'Resources',
            'projects' => 'Projects',
        ];
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

        <main class="px-4 py-5 sm:px-6 lg:col-start-2 lg:px-8">
            <div class="mx-auto max-w-[1500px]">
                @if (session('status'))
                    <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/30 dark:text-emerald-300">
                        {{ session('status') }}
                    </div>
                @endif

                <section class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="grid gap-0 xl:grid-cols-[1fr_24rem]">
                        <div class="p-5 sm:p-6 lg:p-8">
                            <p class="text-xs font-extrabold uppercase tracking-[0.24em] text-blue-600 dark:text-blue-400">AI learning roadmap</p>
                            <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">{{ $roadmap['headline'] ?? 'Generate your personalized roadmap' }}</h1>
                            <p class="mt-4 max-w-3xl text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $roadmap['summary'] ?? 'Create a detailed, assessment-aware roadmap with weekly focus blocks, practical tasks, resources, and milestones.' }}</p>
                        </div>
                        <div class="border-t border-slate-200 bg-slate-950 p-5 text-white dark:border-slate-800 xl:border-l xl:border-t-0">
                            <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400">Roadmap controls</p>
                            <div class="mt-5 grid gap-3">
                                <form method="POST" action="{{ route('roadmap.generate') }}" data-roadmap-generate-form>
                                    @csrf
                                    <button class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-500" data-roadmap-generate-button>
                                        <span data-ai-icon class="hidden h-5 w-5 items-center justify-center rounded-full bg-white/20 text-xs">AI</span>
                                        <span data-ai-label>{{ $hasRoadmap ? 'Regenerate roadmap' : 'Generate roadmap' }}</span>
                                    </button>
                                </form>
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-xl border border-white/10 bg-white/10 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-white/15">Back to dashboard</a>
                            </div>
                            <p class="mt-5 text-sm font-semibold leading-6 text-slate-300">{{ $profile?->weekly_days ?? 5 }} study days/week, {{ $dailyMinutes }} minutes per day.</p>
                        </div>
                    </div>
                </section>

                @unless ($hasRoadmap)
                    <section class="mt-5 rounded-[1.5rem] border border-slate-200 bg-white p-6 text-center shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">No roadmap yet</p>
                        <h2 class="mt-3 text-2xl font-extrabold">Generate your roadmap from the latest assessment</h2>
                        <p class="mx-auto mt-3 max-w-2xl text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">The generated plan will organize priority actions, weekly tasks, resources, video channels, projects, and checkpoints into a tabbed workspace.</p>
                    </section>
                @else
                    <section class="mt-5 rounded-[1.5rem] border border-slate-200 bg-white p-2 shadow-sm dark:border-slate-800 dark:bg-slate-900">
                        <div class="flex gap-2 overflow-x-auto" data-roadmap-tabs>
                            @foreach ($tabs as $tabId => $tabLabel)
                                <button type="button" data-roadmap-tab="{{ $tabId }}" class="shrink-0 rounded-xl px-4 py-3 text-sm font-extrabold transition">{{ $tabLabel }}</button>
                            @endforeach
                        </div>
                    </section>

                    <section class="mt-5 hidden rounded-[1.5rem] border border-emerald-200 bg-emerald-50 p-5 shadow-sm dark:border-emerald-950 dark:bg-emerald-950/20 sm:p-6" data-roadmap-complete-message>
                        <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                            <div>
                                <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-emerald-700 dark:text-emerald-300">Congratulations</p>
                                <h2 class="mt-2 text-2xl font-extrabold text-slate-950 dark:text-slate-100">You have completed this roadmap.</h2>
                                <p class="mt-3 max-w-3xl text-sm font-semibold leading-6 text-emerald-800 dark:text-emerald-200">Now edit your onboarding profile to create another assessment-aligned roadmap with a new goal, pace, or focus area.</p>
                            </div>
                            <a href="{{ route('onboarding') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-700 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-emerald-800">Edit onboarding</a>
                        </div>
                    </section>

                    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/70 px-4 backdrop-blur-md" data-roadmap-celebration>
                        <div class="w-full max-w-lg rounded-2xl border border-emerald-200 bg-white p-6 text-center shadow-2xl dark:border-emerald-950 dark:bg-slate-900">
                            <div class="mx-auto grid h-20 w-20 place-items-center rounded-2xl bg-emerald-600 text-2xl font-extrabold text-white shadow-lg shadow-emerald-600/30">OK</div>
                            <h2 class="mt-5 text-3xl font-extrabold">Congratulations</h2>
                            <p class="mt-3 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">You completed all priority actions, weekly plan tasks, and todo board items. Edit your onboarding profile to create another roadmap when you are ready.</p>
                            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                                <a href="{{ route('onboarding') }}" class="inline-flex items-center justify-center rounded-xl bg-emerald-700 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-emerald-800">Edit onboarding</a>
                                <button type="button" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm font-extrabold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" data-roadmap-celebration-close>Stay here</button>
                            </div>
                        </div>
                    </div>

                    <section class="mt-5" data-roadmap-panel="overview">
                        <div class="grid gap-5 xl:grid-cols-[1fr_0.85fr]">
                            <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                                <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">Overview</p>
                                <h2 class="mt-2 text-2xl font-extrabold">Plan summary</h2>
                                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                    @foreach ($metrics as $metric)
                                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950">
                                            <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">{{ $metric['label'] ?? 'Metric' }}</p>
                                            <p class="mt-3 text-2xl font-extrabold">{{ $metric['value'] ?? '--' }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="rounded-[1.5rem] bg-slate-950 p-5 text-white shadow-sm dark:bg-slate-900 sm:p-6">
                                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400">Mentor notes</p>
                                <div class="mt-5 grid gap-3">
                                    @foreach ($mentorNotes as $note)
                                        <p class="rounded-xl bg-white/5 p-4 text-sm font-semibold leading-6 text-slate-200">{{ $note }}</p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="mt-5" data-roadmap-panel="priority">
                        <div class="grid gap-5 xl:grid-cols-[0.9fr_1.1fr]">
                            <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                    <div>
                                        <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-cyan-700 dark:text-cyan-300">Priority actions</p>
                                        <h2 class="mt-2 text-2xl font-extrabold">Start here</h2>
                                    </div>
                                    <button type="button" class="rounded-xl bg-cyan-700 px-4 py-3 text-sm font-extrabold text-white hover:bg-cyan-800" data-roadmap-mark-scope="priority">Mark all</button>
                                </div>
                                <div class="mt-6 grid gap-3">
                                    @foreach ($priorityActions as $index => $action)
                                        <label class="flex cursor-pointer gap-3 rounded-xl border border-cyan-100 bg-cyan-50 p-4 transition hover:border-cyan-300 dark:border-cyan-900 dark:bg-cyan-950/20 dark:hover:border-cyan-500">
                                            <input type="checkbox" class="mt-1 h-5 w-5 shrink-0 rounded border-slate-300 text-cyan-700 focus:ring-cyan-600" data-roadmap-check data-roadmap-scope="priority" data-roadmap-key="priority-{{ $index }}">
                                            <span class="text-sm font-semibold leading-6 text-slate-800 dark:text-slate-100"><span class="mr-2 font-extrabold text-cyan-700 dark:text-cyan-300">{{ $index + 1 }}.</span>{{ $action }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                                <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">Skill tracks</p>
                                <h2 class="mt-2 text-2xl font-extrabold">Learning lanes</h2>
                                <div class="mt-6 grid gap-4 md:grid-cols-2">
                                    @foreach ($studyTracks as $track)
                                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950">
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
                    </section>

                    <section class="mt-5" data-roadmap-panel="weak">
                        <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                                <div>
                                    <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-rose-600 dark:text-rose-300">Assessment weak areas</p>
                                    <h2 class="mt-2 text-2xl font-extrabold">Practice map by topic</h2>
                                    <p class="mt-3 max-w-3xl text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">This breaks each weak area into smaller skills, so HTML, CSS, React, APIs, DSA, or AI topics become clear practice actions instead of vague labels.</p>
                                </div>
                                <span class="rounded-xl bg-slate-100 px-4 py-3 text-sm font-extrabold text-slate-700 dark:bg-slate-800 dark:text-slate-200">{{ count($weakAreaPracticePlan) }} assessment topics</span>
                            </div>

                            <div class="mt-6 grid gap-4 lg:grid-cols-2">
                                @foreach ($weakAreaPracticePlan as $area)
                                    <article class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950">
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-lg font-extrabold">{{ $area['topic'] }}</p>
                                                <p class="mt-1 text-sm font-semibold text-slate-500 dark:text-slate-400">{{ $area['wrong'] }} missed, {{ $area['correct'] }} correct in assessment</p>
                                            </div>
                                            <span class="rounded-full {{ $area['score'] >= 70 ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-300' : ($area['score'] >= 45 ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/30 dark:text-amber-300' : 'bg-rose-50 text-rose-700 dark:bg-rose-950/30 dark:text-rose-300') }} px-3 py-1 text-xs font-extrabold">{{ $area['score'] }}%</span>
                                        </div>
                                        <div class="mt-4 grid gap-2 sm:grid-cols-2">
                                            @foreach ($area['focus_items'] as $focusItem)
                                                <div class="rounded-xl bg-white px-3 py-3 text-sm font-extrabold text-slate-700 ring-1 ring-slate-200 dark:bg-slate-900 dark:text-slate-200 dark:ring-slate-700">{{ $focusItem }}</div>
                                            @endforeach
                                        </div>
                                        <p class="mt-4 rounded-xl bg-white p-3 text-sm font-semibold leading-6 text-slate-600 dark:bg-slate-900 dark:text-slate-300">{{ $area['practice_goal'] }}</p>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section id="path" class="mt-5" data-roadmap-panel="weekly">
                        <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                            <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                                <div>
                                    <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">Weekly plan</p>
                                    <h2 class="mt-2 text-2xl font-extrabold">Detailed study timeline</h2>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <span class="rounded-full bg-indigo-50 px-4 py-2 text-sm font-extrabold text-indigo-700 dark:bg-indigo-950/30 dark:text-indigo-300">{{ $profile?->weekly_days ?? 5 }} study days/week</span>
                                    <button type="button" class="rounded-full bg-indigo-700 px-4 py-2 text-sm font-extrabold text-white hover:bg-indigo-800" data-roadmap-mark-scope="weekly">Mark weekly done</button>
                                </div>
                            </div>

                            <div class="mt-6 grid gap-4">
                                @foreach ($weeklyFocus as $weekIndex => $week)
                                    <article class="rounded-xl border border-indigo-100 bg-indigo-50/60 p-4 dark:border-indigo-950 dark:bg-indigo-950/20" data-week-card="{{ $weekIndex }}">
                                        <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_17rem]">
                                            <div>
                                                <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-blue-600 dark:text-blue-400">{{ $week['week'] ?? 'Week' }}</p>
                                                <h3 class="mt-2 text-xl font-extrabold">{{ $week['title'] ?? 'Focus block' }}</h3>
                                                <p class="mt-3 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $week['goal'] ?? '' }}</p>
                                            </div>
                                            <div class="rounded-xl bg-white p-4 text-sm font-semibold leading-6 text-slate-700 dark:bg-slate-900 dark:text-slate-200">
                                                <span class="block text-xs font-extrabold uppercase tracking-[0.16em] text-slate-400">Deliverable</span>
                                                <span class="mt-2 block">{{ $week['deliverable'] ?? '' }}</span>
                                            </div>
                                        </div>

                                        <div class="mt-5">
                                            <div class="flex items-center justify-between gap-3">
                                                <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-indigo-700 dark:text-indigo-300">Week progress</span>
                                                <span class="text-xs font-extrabold text-indigo-700 dark:text-indigo-300" data-week-progress-label="{{ $weekIndex }}">0%</span>
                                            </div>
                                            <div class="mt-2 h-2.5 overflow-hidden rounded-full bg-white dark:bg-slate-900">
                                                <div class="h-full rounded-full bg-indigo-600 transition-all duration-300" style="width: 0%" data-week-progress-bar="{{ $weekIndex }}"></div>
                                            </div>
                                        </div>

                                        <div class="mt-5 grid gap-4 xl:grid-cols-[1fr_0.8fr]">
                                            <div class="grid gap-3">
                                                @foreach (($week['tasks'] ?? []) as $task)
                                                    <label class="block cursor-pointer rounded-xl bg-white p-4 transition hover:ring-2 hover:ring-indigo-200 dark:bg-slate-900 dark:hover:ring-indigo-900">
                                                        <div class="flex gap-3">
                                                            <input type="checkbox" class="mt-1 h-5 w-5 shrink-0 rounded border-slate-300 text-indigo-700 focus:ring-indigo-600" data-roadmap-check data-roadmap-scope="weekly" data-roadmap-week="{{ $weekIndex }}" data-roadmap-key="weekly-{{ $weekIndex }}-{{ $loop->index }}">
                                                            <span class="min-w-0 flex-1">
                                                                <span class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                                                                    <span class="text-sm font-extrabold">{{ $task['title'] ?? 'Task' }}</span>
                                                                    <span class="flex flex-wrap gap-2">
                                                                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-extrabold text-blue-700 dark:bg-blue-950/40 dark:text-blue-300">{{ $task['priority'] ?? 'Medium' }}</span>
                                                                        <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $task['effort'] ?? '30 min' }}</span>
                                                                    </span>
                                                                </span>
                                                                <span class="mt-2 block text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $task['detail'] ?? '' }}</span>
                                                            </span>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>

                                            <div class="grid content-start gap-3">
                                                @foreach (($week['resources'] ?? []) as $resource)
                                                    <a href="{{ $resource['url'] ?? '#' }}" target="_blank" rel="noreferrer" class="block rounded-xl border border-slate-200 bg-white p-4 transition hover:border-blue-300 dark:border-slate-700 dark:bg-slate-900 dark:hover:border-blue-500">
                                                        <p class="text-xs font-extrabold uppercase tracking-[0.14em] text-slate-500 dark:text-slate-400">{{ $resource['type'] ?? 'Docs' }}</p>
                                                        <p class="mt-2 text-sm font-extrabold">{{ $resource['title'] ?? 'Resource' }}</p>
                                                        <p class="mt-2 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $resource['why'] ?? '' }}</p>
                                                    </a>
                                                @endforeach
                                                <div class="rounded-xl bg-slate-950 p-4 text-white dark:bg-slate-800">
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

                    <section class="mt-5" data-roadmap-panel="todos">
                        <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-violet-700 dark:text-violet-300">Todo board</p>
                                    <h2 class="mt-2 text-2xl font-extrabold">Work queue</h2>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <button type="button" class="rounded-xl bg-violet-700 px-4 py-3 text-sm font-extrabold text-white hover:bg-violet-800" data-roadmap-mark-scope="todos">Mark all todos</button>
                                    <button type="button" class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-extrabold text-slate-700 hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100" data-roadmap-clear-scope="todos">Clear</button>
                                </div>
                            </div>
                            <div class="mt-6 grid gap-4 lg:grid-cols-3">
                                @foreach ($todoSections as $section)
                                    <div class="rounded-xl border border-violet-100 bg-violet-50/70 p-4 dark:border-violet-950 dark:bg-violet-950/20">
                                        <p class="font-extrabold">{{ $section['title'] ?? 'Next actions' }}</p>
                                        <p class="mt-2 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $section['summary'] ?? '' }}</p>
                                        <div class="mt-4 grid gap-3">
                                            @foreach (($section['items'] ?? []) as $item)
                                                <label class="flex cursor-pointer gap-3 rounded-xl bg-white p-4 dark:bg-slate-900">
                                                    <input type="checkbox" class="mt-1 h-5 w-5 shrink-0 rounded border-slate-300 text-violet-700 focus:ring-violet-600" data-roadmap-check data-roadmap-scope="todos" data-roadmap-key="todo-{{ $loop->parent->index }}-{{ $loop->index }}">
                                                    <span>
                                                        <span class="block text-sm font-extrabold">{{ $item['task'] ?? 'Task' }}</span>
                                                        <span class="mt-2 block text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $item['outcome'] ?? '' }}</span>
                                                        <span class="mt-3 inline-flex rounded-full bg-slate-100 px-3 py-1 text-xs font-extrabold text-slate-600 dark:bg-slate-800 dark:text-slate-300">{{ $item['priority'] ?? 'Medium' }} / {{ $item['effort'] ?? '30 min' }}</span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section id="resources" class="mt-5" data-roadmap-panel="resources">
                        <div class="grid gap-5">
                            <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                                <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                                    <div>
                                        <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-red-700 dark:text-red-300">Video resources</p>
                                        <h2 class="mt-2 text-2xl font-extrabold">Watch and practice</h2>
                                        <p class="mt-3 max-w-3xl text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">These cards show clear video thumbnails. Open the video or channel in YouTube when you want guided walkthroughs.</p>
                                    </div>
                                    <span class="rounded-xl bg-red-50 px-4 py-3 text-sm font-extrabold text-red-700 dark:bg-red-950/30 dark:text-red-300">{{ $videoResourceCards->count() }} video picks</span>
                                </div>

                                <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                                    @foreach ($videoResourceCards as $resource)
                                        <div class="group overflow-hidden rounded-xl border border-slate-200 bg-slate-50 transition hover:-translate-y-0.5 hover:border-blue-300 hover:bg-white hover:shadow-lg dark:border-slate-700 dark:bg-slate-950 dark:hover:border-blue-500 dark:hover:bg-slate-900">
                                            <div class="relative aspect-video bg-slate-950">
                                                @if ($resource['youtube_thumbnail_url'] ?? null)
                                                    <a href="{{ $resource['url'] ?? '#' }}" target="_blank" rel="noreferrer" class="block h-full w-full">
                                                        <img src="{{ $resource['youtube_thumbnail_url'] }}" alt="{{ $resource['title'] ?? 'YouTube video thumbnail' }}" class="h-full w-full object-cover">
                                                        <span class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-slate-950/10 to-transparent"></span>
                                                        <span class="absolute left-1/2 top-1/2 grid h-16 w-16 -translate-x-1/2 -translate-y-1/2 place-items-center rounded-full bg-white text-sm font-extrabold text-red-600 shadow-xl">Play</span>
                                                        <span class="absolute bottom-4 left-4 right-4 text-sm font-extrabold text-white">{{ $resource['title'] ?? 'Watch on YouTube' }}</span>
                                                    </a>
                                                @else
                                                    <a href="{{ $resource['url'] ?? '#' }}" target="_blank" rel="noreferrer" class="flex h-full items-center justify-center bg-red-600 p-5 text-white">
                                                        <div class="text-center">
                                                            <span class="mx-auto grid h-16 w-16 place-items-center rounded-full bg-white text-lg font-extrabold text-red-600">Play</span>
                                                            <p class="mt-4 text-xs font-extrabold uppercase tracking-[0.18em] text-red-100">YouTube channel</p>
                                                            <p class="mt-2 text-lg font-extrabold">{{ $resource['title'] ?? 'Open video support' }}</p>
                                                        </div>
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="p-4">
                                                <p class="font-extrabold text-slate-950 dark:text-slate-100">{{ $resource['title'] ?? 'Resource' }}</p>
                                                <p class="mt-2 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $resource['why'] ?? 'Use this resource for focused practice.' }}</p>
                                                <div class="mt-4 flex flex-wrap gap-2">
                                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-extrabold text-slate-600 ring-1 ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700">{{ $resource['topic'] ?? 'General' }}</span>
                                                    <a href="{{ $resource['url'] ?? '#' }}" target="_blank" rel="noreferrer" class="rounded-full bg-blue-50 px-3 py-1 text-xs font-extrabold text-blue-700 dark:bg-blue-950/40 dark:text-blue-300">{{ ($resource['tone'] ?? 'slate') === 'red' ? 'Open on YouTube' : 'Open resource' }}</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                                <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                                    <div>
                                        <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">Docs and practice</p>
                                        <h2 class="mt-2 text-2xl font-extrabold">Reference stack</h2>
                                        <p class="mt-3 max-w-3xl text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">Use these resources for reading, drills, documentation, and project support. They are separated from video cards for easier scanning.</p>
                                    </div>
                                    <span class="rounded-xl bg-blue-50 px-4 py-3 text-sm font-extrabold text-blue-700 dark:bg-blue-950/30 dark:text-blue-300">{{ $referenceResourceCards->count() }} references</span>
                                </div>

                                <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                                    @forelse ($referenceResourceCards as $resource)
                                        <a href="{{ $resource['url'] ?? '#' }}" target="_blank" rel="noreferrer" class="group block rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:-translate-y-0.5 hover:border-blue-300 hover:bg-white hover:shadow-lg dark:border-slate-700 dark:bg-slate-950 dark:hover:border-blue-500 dark:hover:bg-slate-900">
                                            <div class="flex items-start justify-between gap-4">
                                                <div class="min-w-0">
                                                    <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-blue-600 dark:text-blue-300">{{ $resource['badge'] ?? 'Resource' }}</p>
                                                    <p class="mt-2 font-extrabold text-slate-950 dark:text-slate-100">{{ $resource['title'] ?? 'Resource' }}</p>
                                                    <p class="mt-1 text-xs font-bold text-slate-400">{{ $resource['host'] ?? 'resource' }}</p>
                                                </div>
                                                <span class="{{ ($resource['tone'] ?? 'slate') === 'blue' ? 'bg-blue-600' : 'bg-slate-800' }} grid h-11 w-11 shrink-0 place-items-center rounded-xl text-xs font-extrabold text-white">{{ ($resource['tone'] ?? 'slate') === 'blue' ? 'MDN' : 'GO' }}</span>
                                            </div>
                                            <p class="mt-4 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $resource['why'] ?? 'Use this resource for focused practice.' }}</p>
                                            <div class="mt-4 flex flex-wrap gap-2">
                                                <span class="rounded-full bg-white px-3 py-1 text-xs font-extrabold text-slate-600 ring-1 ring-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700">{{ $resource['topic'] ?? 'General' }}</span>
                                                <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-extrabold text-blue-700 dark:bg-blue-950/40 dark:text-blue-300">Open</span>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 text-sm font-semibold text-slate-500 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-400">No reference resources yet. Regenerate your roadmap to add docs and practice links.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="mt-5" data-roadmap-panel="projects">
                        <div class="grid gap-5 xl:grid-cols-[1fr_0.75fr]">
                            <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                                <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">Projects</p>
                                <h2 class="mt-2 text-2xl font-extrabold">Milestones</h2>
                                <div class="mt-6 grid gap-4 md:grid-cols-2">
                                    @foreach ($milestones as $milestone)
                                        <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950">
                                            <p class="font-extrabold">{{ $milestone['title'] ?? 'Project milestone' }}</p>
                                            <p class="mt-2 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">{{ $milestone['description'] ?? '' }}</p>
                                            <p class="mt-3 rounded-xl bg-white px-4 py-3 text-sm font-semibold text-slate-700 dark:bg-slate-900 dark:text-slate-200">Deliverable: {{ $milestone['deliverable'] ?? '' }}</p>
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @foreach (($milestone['skills'] ?? []) as $skill)
                                                    <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-extrabold text-blue-700 dark:bg-blue-950/40 dark:text-blue-300">{{ $skill }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="rounded-[1.5rem] bg-slate-950 p-5 text-white shadow-sm dark:bg-slate-900 sm:p-6">
                                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400">Completion signal</p>
                                <h2 class="mt-2 text-2xl font-extrabold">What good looks like</h2>
                                <div class="mt-6 grid gap-3">
                                    <div class="rounded-xl bg-white/5 p-4 text-sm font-semibold leading-6 text-slate-200">A small finished project beats a large unfinished plan.</div>
                                    <div class="rounded-xl bg-white/5 p-4 text-sm font-semibold leading-6 text-slate-200">Every milestone should produce code, notes, tests, or a visible demo.</div>
                                    <div class="rounded-xl bg-white/5 p-4 text-sm font-semibold leading-6 text-slate-200">Use the weekly checkpoint to decide whether to move forward or repeat a weak topic.</div>
                                </div>
                            </div>
                        </div>
                    </section>
                @endunless
            </div>
        </main>
    </div>

    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/70 px-4 backdrop-blur-md" data-roadmap-overlay>
        <div class="w-full max-w-md rounded-2xl border border-white/10 bg-white p-6 text-center shadow-2xl dark:bg-slate-900">
            <div class="mx-auto grid h-20 w-20 place-items-center rounded-2xl bg-blue-600 text-xl font-extrabold text-white shadow-lg shadow-blue-600/30" data-ai-overlay-icon>G</div>
            <h2 class="mt-5 text-2xl font-extrabold">Gemini 2.5 Pro assistant is generating</h2>
            <p class="mt-3 text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300" data-ai-overlay-status>Analyzing your assessment, onboarding goal, and weak areas...</p>
            <div class="mt-6 h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800">
                <div class="h-full w-1/2 animate-pulse rounded-full bg-blue-600"></div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('[data-dashboard-sidebar]');
            const openButton = document.querySelector('[data-dashboard-sidebar-button]');
            const closeButton = document.querySelector('[data-dashboard-sidebar-close]');
            const overlay = document.querySelector('[data-dashboard-sidebar-overlay]');
            const tabButtons = Array.from(document.querySelectorAll('[data-roadmap-tab]'));
            const tabPanels = Array.from(document.querySelectorAll('[data-roadmap-panel]'));

            const setActiveRoadmapTab = (activeTab) => {
                tabButtons.forEach((button) => {
                    const isActive = button.dataset.roadmapTab === activeTab;
                    button.className = `shrink-0 rounded-xl px-4 py-3 text-sm font-extrabold transition ${isActive
                        ? 'bg-slate-950 text-white dark:bg-blue-600'
                        : 'text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800'}`;
                });

                tabPanels.forEach((panel) => {
                    panel.classList.toggle('hidden', panel.dataset.roadmapPanel !== activeTab);
                });
            };

            if (tabButtons.length > 0) {
                tabButtons.forEach((button) => {
                    button.addEventListener('click', () => setActiveRoadmapTab(button.dataset.roadmapTab));
                });
                setActiveRoadmapTab('overview');
            }

            const roadmapStoragePrefix = `skillweave-roadmap-user-{{ $user->id }}-attempt-{{ $attempt?->id ?? 'none' }}-`;
            const roadmapChecks = Array.from(document.querySelectorAll('[data-roadmap-check]'));
            const completionMessage = document.querySelector('[data-roadmap-complete-message]');
            const celebrationModal = document.querySelector('[data-roadmap-celebration]');
            const celebrationClose = document.querySelector('[data-roadmap-celebration-close]');
            const celebrationKey = roadmapStoragePrefix + 'celebration-shown';

            const updateRoadmapCompletion = () => {
                const requiredScopes = ['priority', 'weekly', 'todos'];
                const scopedChecks = roadmapChecks.filter((check) => requiredScopes.includes(check.dataset.roadmapScope));
                const allComplete = scopedChecks.length > 0 && scopedChecks.every((check) => check.checked);

                completionMessage?.classList.toggle('hidden', !allComplete);
                const weekIndexes = [...new Set(roadmapChecks
                    .filter((check) => check.dataset.roadmapScope === 'weekly' && check.dataset.roadmapWeek !== undefined)
                    .map((check) => check.dataset.roadmapWeek))];

                weekIndexes.forEach((weekIndex) => {
                    const weekChecks = roadmapChecks.filter((check) => check.dataset.roadmapWeek === weekIndex);
                    const completed = weekChecks.filter((check) => check.checked).length;
                    const percent = weekChecks.length > 0 ? Math.round((completed / weekChecks.length) * 100) : 0;
                    const bar = document.querySelector(`[data-week-progress-bar="${weekIndex}"]`);
                    const label = document.querySelector(`[data-week-progress-label="${weekIndex}"]`);
                    const card = document.querySelector(`[data-week-card="${weekIndex}"]`);

                    if (bar) bar.style.width = `${percent}%`;
                    if (label) label.textContent = `${percent}%`;
                    card?.classList.toggle('ring-2', percent === 100);
                    card?.classList.toggle('ring-emerald-200', percent === 100);
                    card?.classList.toggle('dark:ring-emerald-900', percent === 100);
                });

                if (allComplete && localStorage.getItem(celebrationKey) !== '1') {
                    celebrationModal?.classList.remove('hidden');
                    celebrationModal?.classList.add('flex');
                    document.body.classList.add('overflow-hidden');
                    localStorage.setItem(celebrationKey, '1');
                }

                if (!allComplete) {
                    localStorage.removeItem(celebrationKey);
                    celebrationModal?.classList.add('hidden');
                    celebrationModal?.classList.remove('flex');
                    document.body.classList.remove('overflow-hidden');
                }

                scopedChecks.forEach((check) => {
                    localStorage.setItem(roadmapStoragePrefix + check.dataset.roadmapKey, check.checked ? '1' : '0');
                    const card = check.closest('label');
                    card?.classList.toggle('opacity-70', check.checked);
                    card?.classList.toggle('ring-2', check.checked);
                    card?.classList.toggle('ring-emerald-200', check.checked);
                    card?.classList.toggle('dark:ring-emerald-900', check.checked);
                });
            };

            celebrationClose?.addEventListener('click', () => {
                celebrationModal?.classList.add('hidden');
                celebrationModal?.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            });

            roadmapChecks.forEach((check) => {
                check.checked = localStorage.getItem(roadmapStoragePrefix + check.dataset.roadmapKey) === '1';
                check.addEventListener('change', updateRoadmapCompletion);
            });

            document.querySelectorAll('[data-roadmap-mark-scope]').forEach((button) => {
                button.addEventListener('click', () => {
                    roadmapChecks
                        .filter((check) => check.dataset.roadmapScope === button.dataset.roadmapMarkScope)
                        .forEach((check) => { check.checked = true; });
                    updateRoadmapCompletion();
                });
            });

            document.querySelectorAll('[data-roadmap-clear-scope]').forEach((button) => {
                button.addEventListener('click', () => {
                    roadmapChecks
                        .filter((check) => check.dataset.roadmapScope === button.dataset.roadmapClearScope)
                        .forEach((check) => { check.checked = false; });
                    updateRoadmapCompletion();
                });
            });

            updateRoadmapCompletion();

            document.querySelectorAll('[data-roadmap-generate-form]').forEach((generateForm) => {
                generateForm.addEventListener('submit', () => {
                    const button = generateForm.querySelector('[data-roadmap-generate-button]');
                    const icon = generateForm.querySelector('[data-ai-icon]');
                    const label = generateForm.querySelector('[data-ai-label]');
                    const roadmapOverlay = document.querySelector('[data-roadmap-overlay]');
                    const overlayIcon = document.querySelector('[data-ai-overlay-icon]');
                    const overlayStatus = document.querySelector('[data-ai-overlay-status]');
                    const frames = ['G', '2.5', 'AI', '{}', 'OK'];
                    const statuses = [
                        'Analyzing your assessment, onboarding goal, and weak areas...',
                        'Finding what to practice inside each topic...',
                        'Building weekly focus blocks with Gemini 2.5 Pro...',
                        'Adding MDN, YouTube, and practice resources...',
                        'Preparing project milestones and checkpoints...'
                    ];
                    let frameIndex = 0;

                    button.disabled = true;
                    button.classList.add('opacity-80', 'cursor-wait');
                    icon.classList.remove('hidden');
                    icon.classList.add('inline-flex', 'animate-pulse');
                    label.textContent = 'AI is generating...';
                    roadmapOverlay?.classList.remove('hidden');
                    roadmapOverlay?.classList.add('flex');
                    document.body.classList.add('overflow-hidden');

                    window.setInterval(() => {
                        const nextFrame = frames[frameIndex % frames.length];
                        icon.textContent = nextFrame;
                        if (overlayIcon) overlayIcon.textContent = nextFrame;
                        if (overlayStatus) overlayStatus.textContent = statuses[frameIndex % statuses.length];
                        frameIndex += 1;
                    }, 450);
                });
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
