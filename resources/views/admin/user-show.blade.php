<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->name }} | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Manrope', sans-serif; }</style>
</head>
<body class="bg-slate-100 text-slate-950">
    <main class="min-h-screen px-4 py-5 sm:px-6 lg:px-8">
        <div class="mx-auto max-w-[1400px]">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-extrabold text-slate-700 shadow-sm">Back to admin</a>
                <form method="POST" action="{{ route('admin.users.delete', $user) }}" onsubmit="return confirm('Delete this learner and all related data?')">
                    @csrf
                    @method('DELETE')
                    <button class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-extrabold text-rose-700 shadow-sm">Delete learner</button>
                </form>
            </div>

            <section class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm">
                <div class="grid gap-0 xl:grid-cols-[1fr_24rem]">
                    <div class="p-5 sm:p-6 lg:p-8">
                        <p class="text-xs font-extrabold uppercase tracking-[0.24em] text-blue-600">Learner detail</p>
                        <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">{{ $user->name }}</h1>
                        <p class="mt-3 break-all text-sm font-semibold text-slate-500">{{ $user->email }}</p>
                        <div class="mt-5 flex flex-wrap gap-2">
                            <span class="rounded-full bg-blue-50 px-3 py-2 text-xs font-extrabold text-blue-700">{{ $user->profile?->learning_goal ?? $user->goal ?? 'No goal' }}</span>
                            <span class="rounded-full bg-slate-100 px-3 py-2 text-xs font-extrabold text-slate-700">Joined {{ $user->created_at->format('M d, Y') }}</span>
                            <span class="rounded-full {{ $user->email_verified_at ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }} px-3 py-2 text-xs font-extrabold">{{ $user->email_verified_at ? 'Verified' : 'Unverified' }}</span>
                        </div>
                    </div>
                    <div class="border-t border-slate-200 bg-slate-950 p-5 text-white xl:border-l xl:border-t-0">
                        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400">Assessment</p>
                        <p class="mt-3 text-4xl font-extrabold">{{ $attempt?->completed_at ? ($attempt->percentage . '%') : 'Pending' }}</p>
                        <p class="mt-2 text-sm font-semibold text-slate-400">{{ $attempt?->score ?? 0 }}/{{ $attempt?->total_questions ?? 25 }} correct</p>
                    </div>
                </div>
            </section>

            <section class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                <div class="rounded-[1.25rem] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500">Reports</p>
                    <p class="mt-3 text-4xl font-extrabold">{{ $user->supportTickets->count() }}</p>
                </div>
                <div class="rounded-[1.25rem] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500">Wrong answers</p>
                    <p class="mt-3 text-4xl font-extrabold">{{ $wrongAnswers->count() }}</p>
                </div>
                <div class="rounded-[1.25rem] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500">Roadmap</p>
                    <p class="mt-3 text-2xl font-extrabold">{{ $attempt?->roadmap_generated_at ? 'Generated' : 'Not ready' }}</p>
                </div>
                <div class="rounded-[1.25rem] border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500">Proficiency</p>
                    <p class="mt-3 text-4xl font-extrabold">{{ (int) ($user->proficiency ?? 0) }}%</p>
                </div>
            </section>

            <section class="mt-5 grid gap-5 xl:grid-cols-[0.9fr_1.1fr]">
                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-rose-600">Admin signals</p>
                    <h2 class="mt-2 text-2xl font-extrabold">Review before action</h2>
                    <div class="mt-5 grid gap-3">
                        @foreach ($signals as $signal)
                            <div class="rounded-xl border {{ $signal['active'] ? 'border-rose-200 bg-rose-50' : 'border-slate-200 bg-slate-50' }} p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-extrabold">{{ $signal['label'] }}</p>
                                        <p class="mt-1 text-sm font-semibold leading-6 text-slate-600">{{ $signal['detail'] }}</p>
                                    </div>
                                    <span class="rounded-full {{ $signal['active'] ? 'bg-rose-600 text-white' : 'bg-slate-200 text-slate-700' }} px-3 py-1 text-xs font-extrabold">{{ $signal['active'] ? 'Active' : 'Clear' }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600">Topic analytics</p>
                    <h2 class="mt-2 text-2xl font-extrabold">Assessment breakdown</h2>
                    <div class="mt-5 grid gap-3">
                        @forelse ($topicBreakdown as $topic)
                            <div>
                                <div class="flex justify-between text-sm font-extrabold">
                                    <span>{{ $topic['topic'] ?? 'Topic' }}</span>
                                    <span>{{ $topic['score'] ?? 0 }}%</span>
                                </div>
                                <div class="mt-2 h-3 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full {{ ($topic['score'] ?? 0) >= 70 ? 'bg-emerald-500' : (($topic['score'] ?? 0) >= 45 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width: {{ $topic['score'] ?? 0 }}%"></div>
                                </div>
                            </div>
                        @empty
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 text-sm font-semibold text-slate-500">No assessment breakdown yet.</div>
                        @endforelse
                    </div>
                </div>
            </section>

            <section class="mt-5 grid gap-5 xl:grid-cols-2">
                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-indigo-600">Profile</p>
                    <h2 class="mt-2 text-2xl font-extrabold">Onboarding data</h2>
                    <dl class="mt-5 grid gap-3 sm:grid-cols-2">
                        @foreach ([
                            'Skill level' => $user->profile?->skill_level,
                            'Target role' => $user->profile?->target_role,
                            'Study time' => $user->profile?->daily_learning_time ? $user->profile->daily_learning_time . ' min/day' : null,
                            'Weekly days' => $user->profile?->weekly_days,
                            'Pace' => $user->learning_pace,
                            'Format' => $user->learning_format,
                        ] as $label => $value)
                            <div class="rounded-xl bg-slate-50 p-4">
                                <dt class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500">{{ $label }}</dt>
                                <dd class="mt-2 text-sm font-extrabold">{{ $value ?? 'Not set' }}</dd>
                            </div>
                        @endforeach
                    </dl>
                </div>

                <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-indigo-600">Reports</p>
                    <h2 class="mt-2 text-2xl font-extrabold">User tickets</h2>
                    <div class="mt-5 grid gap-3">
                        @forelse ($user->supportTickets as $ticket)
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex flex-wrap gap-2">
                                    <span class="rounded-full bg-white px-3 py-1 text-xs font-extrabold text-slate-700 ring-1 ring-slate-200">{{ $ticket->category }}</span>
                                    <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-extrabold text-indigo-700">{{ $ticket->status }}</span>
                                </div>
                                <p class="mt-3 font-extrabold">{{ $ticket->subject }}</p>
                                <p class="mt-2 text-sm font-semibold leading-6 text-slate-600">{{ $ticket->message }}</p>
                            </div>
                        @empty
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-5 text-sm font-semibold text-slate-500">No tickets from this user.</div>
                        @endforelse
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>
</html>
