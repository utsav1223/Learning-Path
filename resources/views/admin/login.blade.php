<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | SkillWeave</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Manrope', sans-serif; }</style>
</head>
<body class="min-h-screen bg-slate-950 text-slate-100">
    <main class="grid min-h-screen lg:grid-cols-[1fr_30rem]">
        <section class="hidden overflow-hidden bg-slate-900 lg:block">
            <div class="relative flex h-full items-center px-14">
                <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?auto=format&fit=crop&w=1400&q=80" alt="Admin workspace" class="absolute inset-0 h-full w-full object-cover opacity-30">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-700/70 via-slate-950/80 to-slate-950"></div>
                <div class="relative max-w-2xl">
                    <p class="text-xs font-extrabold uppercase tracking-[0.28em] text-blue-200">SkillWeave Admin</p>
                    <h1 class="mt-5 text-5xl font-extrabold tracking-tight text-white">Manage users, issues, and platform health.</h1>
                    <p class="mt-5 text-lg font-semibold leading-8 text-slate-300">Review registered learners, track open support tickets, and remove invalid user accounts from one focused admin workspace.</p>
                </div>
            </div>
        </section>

        <section class="flex items-center justify-center px-5 py-10 sm:px-8">
            <div class="w-full max-w-md">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3">
                    <span class="grid h-12 w-12 place-items-center rounded-2xl bg-blue-600 text-lg font-extrabold text-white">S</span>
                    <span>
                        <span class="block text-xl font-extrabold text-white">SkillWeave</span>
                        <span class="block text-xs font-bold uppercase tracking-[0.22em] text-slate-500">Admin login</span>
                    </span>
                </a>

                <div class="mt-10 rounded-2xl border border-white/10 bg-white p-6 text-slate-950 shadow-2xl shadow-blue-950/40">
                    <h2 class="text-2xl font-extrabold">Admin access</h2>
                    <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">Use an account where `is_admin` is enabled.</p>

                    @if (session('admin_login_status'))
                        <div class="mt-5 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm font-bold text-blue-700">
                            {{ session('admin_login_status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.post') }}" class="mt-6 grid gap-5">
                        @csrf
                        <label class="grid gap-2">
                            <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500">Username or email</span>
                            <input type="text" name="login" value="{{ old('login') }}" placeholder="admin" class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold outline-none transition focus:border-blue-600 focus:ring-4 focus:ring-blue-100">
                            @error('login')
                                <span class="text-sm font-bold text-rose-600">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="grid gap-2">
                            <span class="text-xs font-extrabold uppercase tracking-[0.16em] text-slate-500">Password</span>
                            <input type="password" name="password" class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-bold outline-none transition focus:border-blue-600 focus:ring-4 focus:ring-blue-100">
                            @error('password')
                                <span class="text-sm font-bold text-rose-600">{{ $message }}</span>
                            @enderror
                        </label>

                        <label class="flex items-center gap-3 text-sm font-bold text-slate-600">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            Keep admin session active
                        </label>

                        <button class="rounded-xl bg-blue-600 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-blue-200 transition hover:bg-blue-700">Sign in as admin</button>
                    </form>
                </div>

                <p class="mt-6 text-sm font-semibold text-slate-500">Regular learners should use <a href="{{ route('login') }}" class="font-extrabold text-blue-300 hover:text-blue-200">user login</a>.</p>
            </div>
        </section>
    </main>
</body>
</html>
