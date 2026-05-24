<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings | SkillWeave</title>
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
                <p class="text-sm font-extrabold">Settings</p>
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
            :dailyMinutes="$profile?->daily_learning_time ?? 45"
            :pace="$user->learning_pace ?? 'Steady'"
        />

        <main class="px-4 py-5 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-6xl">
                <section class="overflow-hidden rounded-[1.5rem] border border-slate-200 bg-white shadow-sm dark:border-slate-800 dark:bg-slate-900">
                    <div class="grid gap-0 lg:grid-cols-[1fr_20rem]">
                        <div class="p-5 sm:p-6 lg:p-8">
                            <p class="text-xs font-extrabold uppercase tracking-[0.24em] text-blue-600 dark:text-blue-400">Account center</p>
                            <h1 class="mt-3 text-3xl font-extrabold tracking-tight sm:text-4xl">Manage your profile and security</h1>
                            <p class="mt-4 max-w-3xl text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">Update your account name, jump back into your learning profile, change your password, and review the login details connected to your SkillWeave account.</p>
                        </div>
                        <div class="border-t border-slate-200 bg-slate-950 p-5 text-white dark:border-slate-800 lg:border-l lg:border-t-0">
                            <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400">Signed in as</p>
                            <p class="mt-3 text-xl font-extrabold">{{ $user->name }}</p>
                            <p class="mt-2 break-all text-sm font-semibold text-slate-300">{{ $user->email }}</p>
                            <div class="mt-5 rounded-xl bg-white/10 px-4 py-3 text-sm font-extrabold text-slate-200">
                                {{ $user->email_verified_at ? 'Email verified' : 'Email verification pending' }}
                            </div>
                        </div>
                    </div>
                </section>

                @if (session('status'))
                    <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700 dark:border-emerald-900 dark:bg-emerald-950/30 dark:text-emerald-300">
                        {{ session('status') }}
                    </div>
                @endif

                <section class="mt-5 grid gap-5 xl:grid-cols-[1fr_0.9fr]">
                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                        <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">Account details</p>
                        <h2 class="mt-2 text-2xl font-extrabold">Basic information</h2>
                        <form method="POST" action="{{ route('settings.account.update') }}" class="mt-6 grid gap-5">
                            @csrf
                            @method('PATCH')

                            <label class="grid gap-2">
                                <span class="text-sm font-extrabold text-slate-700 dark:text-slate-200">Name</span>
                                <input name="name" value="{{ old('name', $user->name) }}" required class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold outline-none transition focus:border-blue-500 focus:bg-white dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-900">
                                @error('name')
                                    <span class="text-sm font-bold text-rose-600">{{ $message }}</span>
                                @enderror
                            </label>

                            <label class="grid gap-2">
                                <span class="text-sm font-extrabold text-slate-700 dark:text-slate-200">Email</span>
                                <input value="{{ $user->email }}" disabled class="rounded-xl border border-slate-200 bg-slate-100 px-4 py-3 text-sm font-bold text-slate-500 dark:border-slate-700 dark:bg-slate-950 dark:text-slate-400">
                                <span class="text-xs font-bold text-slate-500 dark:text-slate-400">Email changes are kept separate from profile edits so your verification status stays clear.</span>
                            </label>

                            <button class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-blue-700 sm:w-auto">Save account details</button>
                        </form>
                    </div>

                    <div class="rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                        <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">Learning profile</p>
                        <h2 class="mt-2 text-2xl font-extrabold">Manage profile</h2>
                        <div class="mt-6 grid gap-3">
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950">
                                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500">Goal</p>
                                <p class="mt-2 font-extrabold">{{ $profile?->learning_goal ?? $user->goal ?? 'Not set' }}</p>
                            </div>
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4 dark:border-slate-700 dark:bg-slate-950">
                                <p class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500">Study plan</p>
                                <p class="mt-2 font-extrabold">{{ $profile?->daily_learning_time ?? 45 }} min/day, {{ $profile?->weekly_days ?? 5 }} days/week</p>
                            </div>
                        </div>
                        <a href="{{ route('onboarding') }}" class="mt-6 inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-slate-50 px-5 py-3 text-sm font-extrabold text-slate-700 transition hover:bg-slate-100 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-100 dark:hover:bg-slate-700">Edit learning profile</a>
                    </div>
                </section>

                <section id="password" class="mt-5 rounded-[1.5rem] border border-slate-200 bg-white p-5 shadow-sm dark:border-slate-800 dark:bg-slate-900 sm:p-6">
                    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                        <div>
                            <p class="text-xs font-extrabold uppercase tracking-[0.22em] text-blue-600 dark:text-blue-400">Security</p>
                            <h2 class="mt-2 text-2xl font-extrabold">Change password</h2>
                            <p class="mt-3 max-w-2xl text-sm font-semibold leading-6 text-slate-500 dark:text-slate-300">Use a strong password with at least 8 characters, uppercase and lowercase letters, and a number.</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('settings.password.update') }}" class="mt-6 grid gap-5 lg:grid-cols-3">
                        @csrf
                        @method('PATCH')

                        <label class="grid gap-2">
                            <span class="text-sm font-extrabold text-slate-700 dark:text-slate-200">Current password</span>
                            <input type="password" name="current_password" autocomplete="current-password" required class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold outline-none transition focus:border-blue-500 focus:bg-white dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-900">
                            @error('current_password')
                                <span class="text-sm font-bold text-rose-600">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="grid gap-2">
                            <span class="text-sm font-extrabold text-slate-700 dark:text-slate-200">New password</span>
                            <input type="password" name="password" autocomplete="new-password" required class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold outline-none transition focus:border-blue-500 focus:bg-white dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-900">
                            @error('password')
                                <span class="text-sm font-bold text-rose-600">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="grid gap-2">
                            <span class="text-sm font-extrabold text-slate-700 dark:text-slate-200">Confirm password</span>
                            <input type="password" name="password_confirmation" autocomplete="new-password" required class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold outline-none transition focus:border-blue-500 focus:bg-white dark:border-slate-700 dark:bg-slate-950 dark:focus:bg-slate-900">
                        </label>

                        <div class="lg:col-span-3">
                            <button class="inline-flex w-full items-center justify-center rounded-xl bg-slate-950 px-5 py-3 text-sm font-extrabold text-white transition hover:bg-slate-800 dark:bg-blue-600 dark:hover:bg-blue-500 sm:w-auto">Update password</button>
                        </div>
                    </form>
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
                if (window.innerWidth >= 1024) setDrawerState(false);
            });
        });
    </script>
</body>
</html>
