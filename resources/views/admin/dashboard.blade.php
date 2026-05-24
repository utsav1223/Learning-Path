<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | SkillWeave</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Manrope', sans-serif; }</style>
</head>
<body class="bg-slate-100 text-slate-950">
    <div class="min-h-screen lg:grid lg:grid-cols-[18rem_1fr]">
        <aside class="border-b border-slate-200 bg-slate-950 p-5 text-white lg:fixed lg:inset-y-0 lg:left-0 lg:w-[18rem] lg:border-b-0">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <span class="grid h-12 w-12 place-items-center rounded-2xl bg-blue-600 text-lg font-extrabold">S</span>
                <span>
                    <span class="block text-xl font-extrabold">SkillWeave</span>
                    <span class="block text-xs font-bold uppercase tracking-[0.22em] text-slate-500">Admin panel</span>
                </span>
            </a>

            <nav class="mt-10 grid gap-2 text-sm font-extrabold">
                <a href="#overview" class="rounded-xl bg-white/10 px-4 py-3 text-white">Overview</a>
                <a href="#analytics" class="rounded-xl px-4 py-3 text-slate-300 transition hover:bg-white/10 hover:text-white">Analytics</a>
                <a href="#users" class="rounded-xl px-4 py-3 text-slate-300 transition hover:bg-white/10 hover:text-white">Learners</a>
                <a href="#tickets" class="rounded-xl px-4 py-3 text-slate-300 transition hover:bg-white/10 hover:text-white">Reports</a>
            </nav>

            <div class="mt-8 rounded-2xl bg-white/10 p-4">
                <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400">Policy</p>
                <p class="mt-3 text-sm font-semibold leading-6 text-slate-300">Delete users only for verified account-deletion requests or confirmed platform misuse.</p>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-8">
                @csrf
                <button class="w-full rounded-xl border border-white/10 px-4 py-3 text-sm font-extrabold text-slate-200 transition hover:bg-white/10">Logout</button>
            </form>
        </aside>

        <main class="px-4 py-5 sm:px-6 lg:col-start-2 lg:px-8">
            <div class="mx-auto max-w-[1500px]">
                <section id="overview" class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm">
                    <div class="grid gap-0 xl:grid-cols-[1fr_24rem]">
                        <div class="p-5 sm:p-6 lg:p-8">
                            <p class="text-xs font-extrabold uppercase tracking-[0.24em] text-blue-600">Admin command center</p>
                            <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Learner operations dashboard</h1>
                            <p class="mt-4 max-w-3xl text-sm font-semibold leading-6 text-slate-500">Monitor real learners, inspect individual profiles, resolve account requests, and act on confirmed misuse reports from one responsive workspace.</p>
                        </div>
                        <div class="border-t border-slate-200 bg-slate-950 p-5 text-white xl:border-l xl:border-t-0">
                            <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400">Signed in admin</p>
                            <p class="mt-3 text-2xl font-extrabold">{{ auth()->user()->name }}</p>
                            <p class="mt-2 break-all text-sm font-semibold text-slate-400">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                </section>

                @if (session('admin_status'))
                    <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-bold text-emerald-700">
                        {{ session('admin_status') }}
                    </div>
                @endif

                <section class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-5">
                    <div class="rounded-[1.25rem] border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500">Learners</p>
                        <p class="mt-3 text-4xl font-extrabold">{{ $stats['total_users'] }}</p>
                        <p class="mt-2 text-sm font-bold text-slate-500">Admin accounts hidden</p>
                    </div>
                    <div class="rounded-[1.25rem] border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500">Admins</p>
                        <p class="mt-3 text-4xl font-extrabold">{{ $stats['admins'] }}</p>
                    </div>
                    <div class="rounded-[1.25rem] border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500">Completion</p>
                        <p class="mt-3 text-4xl font-extrabold">{{ $stats['completion_rate'] }}%</p>
                        <p class="mt-2 text-sm font-bold text-slate-500">{{ $stats['completed_assessments'] }} completed</p>
                    </div>
                    <div class="rounded-[1.25rem] border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500">Avg score</p>
                        <p class="mt-3 text-4xl font-extrabold">{{ $stats['average_score'] }}%</p>
                    </div>
                    <div class="rounded-[1.25rem] border border-slate-200 bg-white p-5 shadow-sm">
                        <p class="text-xs font-extrabold uppercase tracking-[0.18em] text-slate-500">Open reports</p>
                        <p class="mt-3 text-4xl font-extrabold">{{ $stats['open_tickets'] }}</p>
                    </div>
                </section>

                <section id="analytics" class="mt-5 grid gap-5 xl:grid-cols-[1.1fr_0.9fr]">
                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600">Analytics</p>
                        <h2 class="mt-2 text-2xl font-extrabold">Registrations this week</h2>
                        <div class="mt-6 flex h-64 items-end gap-3 rounded-2xl bg-slate-50 p-4">
                            @forelse ($analytics['registration_trend'] as $day => $total)
                                <div class="flex min-w-0 flex-1 flex-col items-center gap-2">
                                    <div class="flex w-full items-end rounded-t-xl bg-blue-100" style="height: {{ max(10, round(($total / $analytics['max_registration_count']) * 100)) }}%;">
                                        <div class="h-full w-full rounded-t-xl bg-blue-600"></div>
                                    </div>
                                    <span class="text-xs font-extrabold text-slate-500">{{ \Carbon\Carbon::parse($day)->format('D') }}</span>
                                    <span class="text-xs font-bold text-slate-400">{{ $total }}</span>
                                </div>
                            @empty
                                <div class="grid h-full w-full place-items-center text-sm font-semibold text-slate-500">No registrations this week.</div>
                            @endforelse
                        </div>
                    </div>

                    <div class="grid gap-5">
                        <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                            <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-indigo-600">Ticket status</p>
                            <div class="mt-5 grid gap-3">
                                @foreach (['Open', 'In Progress', 'Resolved'] as $status)
                                    @php
                                        $count = (int) ($analytics['ticket_counts'][$status] ?? 0);
                                        $maxTicketCount = max(1, (int) $analytics['ticket_counts']->max());
                                    @endphp
                                    <div>
                                        <div class="flex justify-between text-sm font-extrabold">
                                            <span>{{ $status }}</span>
                                            <span>{{ $count }}</span>
                                        </div>
                                        <div class="mt-2 h-3 overflow-hidden rounded-full bg-slate-100">
                                            <div class="h-full rounded-full {{ $status === 'Resolved' ? 'bg-emerald-500' : ($status === 'In Progress' ? 'bg-amber-500' : 'bg-indigo-600') }}" style="width: {{ round(($count / $maxTicketCount) * 100) }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                            <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-rose-600">Report categories</p>
                            <div class="mt-5 flex flex-wrap gap-2">
                                @forelse ($analytics['category_counts'] as $category => $count)
                                    <span class="rounded-full bg-slate-100 px-3 py-2 text-xs font-extrabold text-slate-700">{{ $category }}: {{ $count }}</span>
                                @empty
                                    <span class="text-sm font-semibold text-slate-500">No reports yet.</span>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </section>

                <section id="users" class="mt-5 rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600">Learners</p>
                            <h2 class="mt-2 text-2xl font-extrabold">Manage registered learners</h2>
                            <p class="mt-2 text-sm font-semibold text-slate-500">Admin accounts are removed from this table.</p>
                        </div>
                        <span class="rounded-xl bg-blue-50 px-4 py-3 text-sm font-extrabold text-blue-700">{{ $stats['total_users'] }} learners</span>
                    </div>

                    <div class="mt-6 grid gap-4 xl:grid-cols-2">
                        @foreach ($users as $user)
                            <article class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                    <div class="min-w-0">
                                        <p class="truncate text-lg font-extrabold">{{ $user->name }}</p>
                                        <p class="mt-1 break-all text-sm font-semibold text-slate-500">{{ $user->email }}</p>
                                        <div class="mt-3 flex flex-wrap gap-2">
                                            <span class="rounded-full bg-white px-3 py-1 text-xs font-extrabold text-slate-700 ring-1 ring-slate-200">{{ $user->profile?->learning_goal ?? $user->goal ?? 'No goal' }}</span>
                                            <span class="rounded-full bg-white px-3 py-1 text-xs font-extrabold text-slate-700 ring-1 ring-slate-200">{{ $user->support_tickets_count }} reports</span>
                                            @if ($user->assessmentAttempt?->completed_at)
                                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-extrabold text-emerald-700">{{ $user->assessmentAttempt->percentage }}% score</span>
                                            @else
                                                <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-extrabold text-amber-700">Assessment pending</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="grid shrink-0 gap-2 sm:w-36">
                                        <a href="{{ route('admin.users.show', $user) }}" class="rounded-xl bg-slate-950 px-4 py-2 text-center text-xs font-extrabold text-white transition hover:bg-slate-800">View details</a>
                                        <form method="POST" action="{{ route('admin.users.delete', $user) }}" onsubmit="return confirm('Delete this learner and all related data?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="w-full rounded-xl border border-rose-200 bg-rose-50 px-4 py-2 text-xs font-extrabold text-rose-700 transition hover:bg-rose-100">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="mt-5">
                        {{ $users->links() }}
                    </div>
                </section>

                <section id="tickets" class="mt-5 rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-indigo-600">Reports</p>
                            <h2 class="mt-2 text-2xl font-extrabold">Support, deletion, and misuse queue</h2>
                        </div>
                        <span class="rounded-xl bg-indigo-50 px-4 py-3 text-sm font-extrabold text-indigo-700">{{ $stats['open_tickets'] }} open</span>
                    </div>

                    <div class="mt-6 grid gap-4">
                        @forelse ($tickets as $ticket)
                            <article class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap gap-2">
                                            <span class="rounded-full bg-white px-3 py-1 text-xs font-extrabold text-slate-700 ring-1 ring-slate-200">{{ $ticket->category }}</span>
                                            <span class="rounded-full {{ $ticket->priority === 'High' ? 'bg-rose-50 text-rose-700' : ($ticket->priority === 'Medium' ? 'bg-amber-50 text-amber-700' : 'bg-emerald-50 text-emerald-700') }} px-3 py-1 text-xs font-extrabold">{{ $ticket->priority }}</span>
                                            <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-extrabold text-indigo-700">{{ $ticket->status }}</span>
                                        </div>
                                        <h3 class="mt-3 text-lg font-extrabold">{{ $ticket->subject }}</h3>
                                        <p class="mt-2 text-sm font-semibold leading-6 text-slate-600">{{ $ticket->message }}</p>
                                        <p class="mt-3 text-xs font-bold text-slate-500">By {{ $ticket->user?->name ?? 'Deleted user' }} - {{ $ticket->created_at->diffForHumans() }}</p>
                                        @if ($ticket->user && !$ticket->user->is_admin)
                                            <a href="{{ route('admin.users.show', $ticket->user) }}" class="mt-3 inline-flex rounded-xl bg-white px-4 py-2 text-xs font-extrabold text-slate-700 ring-1 ring-slate-200">Open learner profile</a>
                                        @endif
                                    </div>

                                    <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}" class="grid w-full gap-3 lg:w-96">
                                        @csrf
                                        @method('PATCH')
                                        <select name="status" class="rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100">
                                            @foreach (['Open', 'In Progress', 'Resolved'] as $status)
                                                <option value="{{ $status }}" @selected($ticket->status === $status)>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                        <textarea name="admin_notes" rows="3" placeholder="Admin note shown to user..." class="resize-y rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-bold leading-6 outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100">{{ $ticket->admin_notes }}</textarea>
                                        <button class="rounded-xl bg-indigo-600 px-4 py-3 text-sm font-extrabold text-white transition hover:bg-indigo-700">Update report</button>
                                    </form>
                                </div>
                            </article>
                        @empty
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-6 text-center text-sm font-semibold text-slate-500">No reports yet.</div>
                        @endforelse
                    </div>

                    <div class="mt-5">
                        {{ $tickets->links() }}
                    </div>
                </section>
            </div>
        </main>
    </div>
</body>
</html>
