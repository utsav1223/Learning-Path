<div class="fixed inset-x-0 top-0 z-50">
    <div class="border-b border-brand-400/20 bg-slate-950 text-white">
        <div class="mx-auto flex min-h-[3.25rem] max-w-7xl items-center justify-center gap-3 px-4 py-3 text-center text-[11px] font-bold uppercase tracking-[0.24em] sm:px-6 sm:text-xs lg:px-8">
            <span class="inline-flex h-2 w-2 rounded-full bg-emerald-400 pulse-ring"></span>
            <span>New adaptive quiz engine live</span>
            <span class="hidden text-slate-500 sm:inline">|</span>
            <span class="hidden sm:inline">Early access for frontend, data, and DSA tracks</span>
        </div>
    </div>

    <nav class="border-b border-slate-200/80 bg-white/88 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="#home" class="flex items-center gap-3">
                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-brand-600 text-lg font-extrabold text-white shadow-lg shadow-brand-600/20">
                    S
                </span>
                <span>
                    <span class="block text-base font-extrabold tracking-tight text-slate-950 sm:text-lg">SkillWeave</span>
                    <span class="block text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Adaptive Learning</span>
                </span>
            </a>

            <div class="hidden items-center gap-8 text-sm font-semibold text-slate-600 lg:flex">
                <a href="#home" class="transition hover:text-brand-600">Home</a>
                <a href="#how-it-works" class="transition hover:text-brand-600">How it Works</a>
                <a href="#features" class="transition hover:text-brand-600">Features</a>
                <a href="#faqs" class="transition hover:text-brand-600">FAQs</a>
                <a href="#about" class="transition hover:text-brand-600">About</a>
            </div>

            <div class="hidden items-center gap-3 sm:flex">
                @guest
                    <a href="{{ url('/login') }}" class="hidden rounded-full border border-slate-300 px-5 py-2.5 text-sm font-bold text-slate-700 transition hover:border-brand-200 hover:bg-brand-50 hover:text-brand-700 sm:inline-flex">
                        Login
                    </a>

                    <a href="{{ route('get.started') }}" class="inline-flex items-center rounded-full bg-brand-600 px-4 py-2.5 text-sm font-extrabold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700 sm:px-5">
                        Get Started
                    </a>
                @endguest

                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center rounded-full bg-brand-600 px-4 py-2.5 text-sm font-extrabold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700 sm:px-5">
                        Dashboard
                    </a>
                @endauth
            </div>

            <button
                type="button"
                class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-brand-200 hover:text-brand-700 sm:hidden"
                aria-expanded="false"
                aria-controls="mobile-menu"
                data-mobile-menu-button
            >
                <span class="sr-only">Open menu</span>
                <svg class="h-5 w-5 menu-open-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M4 7h16M4 12h16M4 17h16" stroke-linecap="round"></path>
                </svg>
                <svg class="hidden h-5 w-5 menu-close-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="m6 6 12 12M18 6 6 18" stroke-linecap="round"></path>
                </svg>
            </button>
        </div>

        <div id="mobile-menu" class="hidden border-t border-slate-200 bg-white/95 px-4 pb-4 pt-3 shadow-lg shadow-slate-200/40 sm:hidden" data-mobile-menu>
            <div class="rounded-[1.75rem] border border-slate-200 bg-slate-50 p-3">
                <div class="grid gap-2">
                    <a href="#home" class="rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-white hover:text-brand-700" data-mobile-menu-link>Home</a>
                    <a href="#how-it-works" class="rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-white hover:text-brand-700" data-mobile-menu-link>How it Works</a>
                    <a href="#features" class="rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-white hover:text-brand-700" data-mobile-menu-link>Features</a>
                    <a href="#faqs" class="rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-white hover:text-brand-700" data-mobile-menu-link>FAQs</a>
                    <a href="#about" class="rounded-2xl px-4 py-3 text-sm font-bold text-slate-700 transition hover:bg-white hover:text-brand-700" data-mobile-menu-link>About</a>
                </div>

                <div class="mt-4 grid gap-3 border-t border-slate-200 pt-4">
                    @guest
                        <a href="{{ url('/login') }}" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:border-brand-200 hover:text-brand-700" data-mobile-menu-link>
                            Login
                        </a>

                        <a href="{{ route('get.started') }}" class="inline-flex items-center justify-center rounded-full bg-brand-600 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700" data-mobile-menu-link>
                            Get Started
                        </a>
                    @endguest

                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center rounded-full bg-brand-600 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700" data-mobile-menu-link>
                            Dashboard
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</div>
