@extends('layouts.app')

@section('content')
<section id="home" class="relative overflow-hidden pt-[11.5rem] sm:pt-[12rem] lg:pt-44">
    <div class="absolute inset-x-0 top-0 -z-10 h-[38rem] bg-[radial-gradient(circle_at_top_right,rgba(49,94,251,0.16),transparent_32%),radial-gradient(circle_at_left,rgba(56,189,248,0.12),transparent_26%)]"></div>

    <div class="mx-auto grid max-w-7xl gap-12 px-4 pb-16 pt-8 sm:px-6 sm:pb-20 sm:pt-10 lg:grid-cols-[1.05fr_0.95fr] lg:gap-16 lg:px-8 lg:pb-28">
        <div class="max-w-2xl reveal">
            <div class="inline-flex items-center gap-2 rounded-full border border-brand-100 bg-white/90 px-4 py-2 text-sm font-semibold text-brand-700 shadow-sm">
                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                Personalized paths for focused learners
            </div>

            <h1 class="mt-8 text-4xl font-extrabold leading-[1.05] tracking-tight text-slate-950 sm:text-6xl lg:text-7xl">
                Learn Smarter.
                <span class="text-brand-600">Not Harder.</span>
            </h1>

            <p class="mt-6 max-w-xl text-base leading-8 text-slate-600 sm:text-xl">
                SkillWeave builds a personalised learning path just for you, adapting as you grow so every course, quiz, and milestone actually moves you forward.
            </p>

            <div class="mt-10 flex flex-col gap-4 sm:flex-row">
                <a href="{{ route('get.started') }}" class="inline-flex items-center justify-center rounded-full bg-brand-600 px-7 py-4 text-base font-extrabold text-white shadow-lg shadow-brand-600/20 transition hover:bg-brand-700">
                    Get Started Free
                </a>
                <a href="#how-it-works" class="inline-flex items-center justify-center rounded-full border border-slate-300 bg-white px-7 py-4 text-base font-bold text-slate-700 transition hover:border-slate-400 hover:bg-slate-50">
                    See How It Works
                </a>
            </div>

            <p class="mt-4 text-sm font-semibold text-slate-500">
                Free to join &middot; No credit card
            </p>

            <div class="mt-12 grid max-w-xl gap-4 sm:grid-cols-3">
                <div class="rounded-2xl border border-white/80 bg-white/80 p-4 shadow-sm backdrop-blur reveal reveal-delay-1">
                    <p class="text-2xl font-extrabold text-slate-950">10k+</p>
                    <p class="mt-1 text-sm text-slate-500">Active learners building momentum</p>
                </div>
                <div class="rounded-2xl border border-white/80 bg-white/80 p-4 shadow-sm backdrop-blur reveal reveal-delay-2">
                    <p class="text-2xl font-extrabold text-slate-950">92%</p>
                    <p class="mt-1 text-sm text-slate-500">Finish their weekly goals on time</p>
                </div>
                <div class="rounded-2xl border border-white/80 bg-white/80 p-4 shadow-sm backdrop-blur reveal reveal-delay-3">
                    <p class="text-2xl font-extrabold text-slate-950">4.9/5</p>
                    <p class="mt-1 text-sm text-slate-500">Average student satisfaction</p>
                </div>
            </div>
        </div>

        <div class="relative reveal reveal-delay-1">
            <div class="absolute -right-10 top-8 -z-10 h-40 w-40 rounded-full bg-cyan-200/60 blur-3xl"></div>
            <div class="absolute -left-6 bottom-16 -z-10 h-48 w-48 rounded-full bg-brand-200/70 blur-3xl"></div>

            <div class="floating-panel overflow-hidden rounded-[2rem] border border-slate-200 bg-white shadow-soft">
                <div class="flex items-center justify-between border-b border-slate-100 px-4 py-4 sm:px-5">
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-rose-400"></span>
                        <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                        <span class="h-3 w-3 rounded-full bg-emerald-400"></span>
                    </div>
                    <p class="text-xs font-semibold text-slate-500 sm:text-sm">SkillWeave Dashboard Preview</p>
                </div>

                <div class="grid gap-5 bg-slate-50 p-4 sm:p-5">
                    <div class="rounded-3xl bg-slate-950 p-5 text-white sm:p-6">
                        <div class="flex flex-col items-start justify-between gap-4 sm:flex-row">
                            <div>
                                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Current path</p>
                                <h2 class="mt-3 text-xl font-extrabold sm:text-2xl">Frontend Engineer Track</h2>
                                <p class="mt-2 max-w-sm text-sm leading-7 text-slate-300">Adaptive milestones update after each quiz so you always know the next best concept to learn.</p>
                            </div>
                            <div class="rounded-2xl bg-white/10 px-4 py-3 text-left sm:text-right">
                                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Progress</p>
                                <p class="mt-1 text-2xl font-extrabold">68%</p>
                            </div>
                        </div>

                        <div class="mt-8">
                            <div class="flex items-center justify-between text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">
                                <span>Path graph</span>
                                <span>Updated today</span>
                            </div>
                            <div class="mt-4 rounded-3xl border border-white/10 bg-white/5 p-5">
                                <div class="grid gap-4 lg:grid-cols-[1fr_auto_1fr_auto_1fr] lg:items-center">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-500/20 text-emerald-300">1</span>
                                        <div>
                                            <p class="font-bold text-white">HTML Foundations</p>
                                            <p class="text-sm text-slate-400">Completed</p>
                                        </div>
                                    </div>
                                    <div class="hidden h-px w-12 bg-white/20 lg:block"></div>
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-brand-500/20 text-brand-100">2</span>
                                        <div>
                                            <p class="font-bold text-white">Responsive CSS</p>
                                            <p class="text-sm text-slate-400">In progress</p>
                                        </div>
                                    </div>
                                    <div class="hidden h-px w-12 bg-white/20 lg:block"></div>
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/10 text-slate-300">3</span>
                                        <div>
                                            <p class="font-bold text-white">React Components</p>
                                            <p class="text-sm text-slate-400">Up next</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-5 md:grid-cols-[1.05fr_0.95fr]">
                        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-100">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-500">Weekly study rhythm</p>
                                    <p class="mt-1 text-lg font-extrabold text-slate-950 sm:text-xl">13.5 hours focused</p>
                                </div>
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-sm font-bold text-emerald-600">+18%</span>
                            </div>
                            <div class="mt-5 flex items-end gap-2 overflow-hidden">
                                <div class="h-20 w-7 rounded-t-2xl bg-slate-200 sm:w-8"></div>
                                <div class="h-28 w-7 rounded-t-2xl bg-brand-200 sm:w-8"></div>
                                <div class="h-24 w-7 rounded-t-2xl bg-brand-300 sm:w-8"></div>
                                <div class="h-36 w-7 rounded-t-2xl bg-brand-500 sm:w-8"></div>
                                <div class="h-32 w-7 rounded-t-2xl bg-cyan-300 sm:w-8"></div>
                                <div class="h-40 w-7 rounded-t-2xl bg-slate-950 sm:w-8"></div>
                            </div>
                        </div>

                        <div class="rounded-3xl bg-white p-5 shadow-sm ring-1 ring-slate-100">
                            <p class="text-sm font-semibold text-slate-500">Next recommendation</p>
                            <div class="mt-4 rounded-2xl bg-slate-50 p-4">
                                <p class="text-sm font-semibold text-brand-700">Smart Quiz</p>
                                <h3 class="mt-2 text-lg font-extrabold text-slate-950">Flexbox and grid challenge</h3>
                                <p class="mt-2 text-sm leading-7 text-slate-600">A short adaptive quiz to decide whether you should move straight to React layouts.</p>
                            </div>
                            <div class="mt-4 h-2 rounded-full bg-slate-100">
                                <div class="h-2 w-2/3 rounded-full bg-brand-600"></div>
                            </div>
                            <p class="mt-3 text-sm text-slate-500">2 of 3 readiness checks completed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="border-y border-slate-200 bg-white/90">
    <div class="mx-auto grid max-w-7xl gap-4 px-4 py-8 sm:grid-cols-2 sm:gap-6 sm:px-6 lg:grid-cols-4 lg:px-8">
        <div class="rounded-2xl border border-slate-100 bg-slate-50 px-5 py-4 reveal">
            <p class="text-2xl font-extrabold text-slate-950">10,000+</p>
            <p class="mt-1 text-sm font-medium text-slate-500">Students enrolled</p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-slate-50 px-5 py-4 reveal reveal-delay-1">
            <p class="text-2xl font-extrabold text-slate-950">50+</p>
            <p class="mt-1 text-sm font-medium text-slate-500">Courses available</p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-slate-50 px-5 py-4 reveal reveal-delay-2">
            <p class="text-2xl font-extrabold text-slate-950">4.9 / 5</p>
            <p class="mt-1 text-sm font-medium text-slate-500">Average rating</p>
        </div>
        <div class="rounded-2xl border border-slate-100 bg-slate-50 px-5 py-4 reveal reveal-delay-3">
            <p class="text-2xl font-extrabold text-slate-950">95%</p>
            <p class="mt-1 text-sm font-medium text-slate-500">Completion rate</p>
        </div>
    </div>
</section>

<section class="overflow-hidden py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="reveal flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between lg:gap-6">
            <div>
                <p class="text-sm font-extrabold uppercase tracking-[0.25em] text-brand-600">Learning snapshots</p>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-4xl">A moving look at the student experience.</h2>
            </div>
            <p class="hidden max-w-md text-sm leading-7 text-slate-500 lg:block">A scrolling visual strip makes the page feel more alive and gives visitors quick context about the platform in motion.</p>
        </div>
    </div>

    <div class="mt-8 overflow-hidden">
        <div class="marquee-track gap-4 px-4 sm:gap-5 sm:px-6 lg:px-8">
            @for ($i = 0; $i < 2; $i++)
                <article class="w-[17rem] shrink-0 overflow-hidden rounded-[1.6rem] border border-slate-200 bg-white shadow-sm sm:w-[20rem]">
                    <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=900&q=80" alt="Student study setup" class="h-48 w-full object-cover">
                    <div class="p-5">
                        <p class="text-sm font-semibold text-brand-700">Live roadmap updates</p>
                        <h3 class="mt-2 text-lg font-extrabold text-slate-950">Dynamic milestones based on quiz results</h3>
                    </div>
                </article>
                <article class="w-[17rem] shrink-0 overflow-hidden rounded-[1.6rem] border border-slate-200 bg-white shadow-sm sm:w-[20rem]">
                    <img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=900&q=80" alt="Developer workspace" class="h-48 w-full object-cover">
                    <div class="p-5">
                        <p class="text-sm font-semibold text-brand-700">Skill tracking</p>
                        <h3 class="mt-2 text-lg font-extrabold text-slate-950">Clear visibility into strengths and gaps</h3>
                    </div>
                </article>
                <article class="w-[17rem] shrink-0 overflow-hidden rounded-[1.6rem] border border-slate-200 bg-white shadow-sm sm:w-[20rem]">
                    <img src="https://images.unsplash.com/photo-1516321497487-e288fb19713f?auto=format&fit=crop&w=900&q=80" alt="Team learning session" class="h-48 w-full object-cover">
                    <div class="p-5">
                        <p class="text-sm font-semibold text-brand-700">Goal based planning</p>
                        <h3 class="mt-2 text-lg font-extrabold text-slate-950">Each module connects to a real outcome</h3>
                    </div>
                </article>
                <article class="w-[17rem] shrink-0 overflow-hidden rounded-[1.6rem] border border-slate-200 bg-white shadow-sm sm:w-[20rem]">
                    <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=900&q=80" alt="Students collaborating" class="h-48 w-full object-cover">
                    <div class="p-5">
                        <p class="text-sm font-semibold text-brand-700">Faster feedback loops</p>
                        <h3 class="mt-2 text-lg font-extrabold text-slate-950">Short quizzes that redirect the next lesson</h3>
                    </div>
                </article>
            @endfor
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 sm:py-24 lg:px-8">
    <div class="max-w-2xl reveal">
        <p class="text-sm font-extrabold uppercase tracking-[0.25em] text-brand-600">The problem</p>
        <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">Traditional learning platforms fail students.</h2>
        <p class="mt-5 text-base leading-8 text-slate-600 sm:text-lg">Most platforms dump content on everyone the same way. SkillWeave starts from your current level and builds a path that actually matches how you learn.</p>
    </div>

    <div class="mt-14 grid gap-6 lg:grid-cols-3">
        <article class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm reveal">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-rose-50 text-rose-500">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M6 6h12M6 12h12M6 18h8" stroke-linecap="round"/>
                    <path d="m17 16 4 4M21 16l-4 4" stroke-linecap="round"/>
                </svg>
            </div>
            <h3 class="mt-6 text-2xl font-extrabold text-slate-950">Generic content</h3>
            <p class="mt-4 text-base leading-8 text-slate-600">The same course is shown to everyone, even when two learners have completely different starting points.</p>
        </article>

        <article class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm reveal reveal-delay-1">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 text-amber-500">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path d="M12 3a7 7 0 0 1 7 7c0 4-3 5.5-3 8h-8c0-2.5-3-4-3-8a7 7 0 0 1 7-7Z" stroke-linejoin="round"/>
                    <path d="M9 21h6M10 18h4" stroke-linecap="round"/>
                </svg>
            </div>
            <h3 class="mt-6 text-2xl font-extrabold text-slate-950">No adaptation</h3>
            <p class="mt-4 text-base leading-8 text-slate-600">When you struggle or move faster than expected, the platform stays static and leaves you to figure out the next step alone.</p>
        </article>

        <article class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm reveal reveal-delay-2">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-cyan-50 text-cyan-600">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <circle cx="12" cy="12" r="8"></circle>
                    <path d="M12 8v5l3 2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h3 class="mt-6 text-2xl font-extrabold text-slate-950">Wasted time</h3>
            <p class="mt-4 text-base leading-8 text-slate-600">You spend hours repeating topics you already know instead of closing the gaps that actually matter for your goal.</p>
        </article>
    </div>
</section>

<section id="how-it-works" class="bg-slate-950 py-20 text-white sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="max-w-2xl reveal">
            <p class="text-sm font-extrabold uppercase tracking-[0.25em] text-brand-200">How it works</p>
            <h2 class="mt-4 text-3xl font-extrabold tracking-tight sm:text-5xl">A clear path in three simple steps.</h2>
            <p class="mt-5 text-base leading-8 text-slate-300 sm:text-lg">Visitors should understand your product in seconds. SkillWeave keeps the process simple: sign up, get assessed, and start learning with direction.</p>
        </div>

        <div class="mt-14 grid gap-6 lg:grid-cols-3">
            <article class="rounded-3xl border border-white/10 bg-white/5 p-8 reveal">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 text-xl font-extrabold text-brand-100">1</div>
                <h3 class="mt-6 text-2xl font-extrabold">Sign Up</h3>
                <p class="mt-4 text-sm font-semibold uppercase tracking-[0.2em] text-brand-200">Create your account in 30 seconds</p>
                <p class="mt-4 text-base leading-8 text-slate-300">Set your goal, choose your focus area, and tell us what kind of role or subject you are aiming for.</p>
            </article>

            <article class="rounded-3xl border border-white/10 bg-white/5 p-8 reveal reveal-delay-1">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 text-xl font-extrabold text-brand-100">2</div>
                <h3 class="mt-6 text-2xl font-extrabold">Take Skill Quiz</h3>
                <p class="mt-4 text-sm font-semibold uppercase tracking-[0.2em] text-brand-200">We assess your current knowledge level</p>
                <p class="mt-4 text-base leading-8 text-slate-300">Adaptive quizzes identify what you already know, where you are struggling, and which concepts unlock the biggest gains.</p>
            </article>

            <article class="rounded-3xl border border-white/10 bg-white/5 p-8 reveal reveal-delay-2">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white/10 text-xl font-extrabold text-brand-100">3</div>
                <h3 class="mt-6 text-2xl font-extrabold">Get Your Path</h3>
                <p class="mt-4 text-sm font-semibold uppercase tracking-[0.2em] text-brand-200">Your personalised roadmap is ready instantly</p>
                <p class="mt-4 text-base leading-8 text-slate-300">The platform turns your results into a guided sequence of resources, quizzes, milestones, and next-best actions.</p>
            </article>
        </div>
    </div>
</section>

<section id="features" class="mx-auto max-w-7xl px-4 py-20 sm:px-6 sm:py-24 lg:px-8">
    <div class="max-w-2xl reveal">
        <p class="text-sm font-extrabold uppercase tracking-[0.25em] text-brand-600">Features</p>
        <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">Everything a focused learner needs to keep moving.</h2>
        <p class="mt-5 text-base leading-8 text-slate-600 sm:text-lg">Each section is built to answer a different question from potential students: can it adapt, can I track progress, and will it keep me motivated?</p>
    </div>

    <div class="mt-14 grid gap-6 md:grid-cols-2 xl:grid-cols-3">
        @php
            $features = [
                ['Adaptive Learning Path', 'Your path updates in real time as you progress.', 'M12 5v14m7-7H5'],
                ['Skill Tracking', 'See exactly what you know and what gaps remain.', 'M4 19h16M7 16V8m5 8V5m5 11v-6'],
                ['Smart Quizzes', 'Quizzes adjust difficulty based on your answers.', 'M9 12h6M12 9v6M5 5h14v14H5z'],
                ['Progress Dashboard', 'A visual overview of your entire learning journey.', 'M5 12h3v7H5zm5-5h3v12h-3zm5 3h3v9h-3z'],
                ['Resource Library', 'Videos, articles, and exercises in one place.', 'M7 6.5A2.5 2.5 0 0 1 9.5 4H19v15H9.5A2.5 2.5 0 0 0 7 21m0-14a2.5 2.5 0 0 1 2.5-2.5M7 7v14m0-14H5v14h2'],
                ['Goal-Based Learning', 'Everything aligns to your specific career goal.', 'm12 3 7 4v5c0 5-3.5 9-7 11-3.5-2-7-6-7-11V7l7-4Z'],
            ];
        @endphp

        @foreach ($features as [$title, $description, $path])
            <article class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm transition hover:-translate-y-1 hover:shadow-lg reveal">
                <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-50 text-brand-600">
                    <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path d="{{ $path }}" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </div>
                <h3 class="mt-6 text-2xl font-extrabold text-slate-950">{{ $title }}</h3>
                <p class="mt-4 text-base leading-8 text-slate-600">{{ $description }}</p>
            </article>
        @endforeach
    </div>
</section>

<section class="bg-white py-20 sm:py-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-[2rem] border border-slate-200 bg-slate-50 p-6 shadow-soft sm:p-8 reveal">
            <div class="max-w-2xl">
                <p class="text-sm font-extrabold uppercase tracking-[0.25em] text-brand-600">Platform preview</p>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">See the product before you commit.</h2>
                <p class="mt-5 text-base leading-8 text-slate-600 sm:text-lg">A dashboard preview removes the fear of the unknown. Even as a mockup, it helps visitors understand the structure of the experience.</p>
            </div>

            <div class="mt-10 overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white">
                <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                    <div class="flex items-center gap-2">
                        <span class="h-3 w-3 rounded-full bg-rose-400"></span>
                        <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                        <span class="h-3 w-3 rounded-full bg-emerald-400"></span>
                    </div>
                    <p class="text-sm font-semibold text-slate-500">Student dashboard</p>
                </div>

                <div class="grid gap-6 p-4 sm:p-6 lg:grid-cols-[0.72fr_1.28fr]">
                    <aside class="rounded-3xl bg-slate-950 p-6 text-white">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-400">Journey overview</p>
                        <h3 class="mt-3 text-2xl font-extrabold">Your next milestone is already queued.</h3>
                        <div class="mt-8 space-y-4">
                            <div class="rounded-2xl bg-white/5 p-4">
                                <p class="text-sm font-semibold text-slate-300">Current streak</p>
                                <p class="mt-2 text-3xl font-extrabold">21 days</p>
                            </div>
                            <div class="rounded-2xl bg-white/5 p-4">
                                <p class="text-sm font-semibold text-slate-300">Mastered topics</p>
                                <p class="mt-2 text-3xl font-extrabold">34</p>
                            </div>
                            <div class="rounded-2xl bg-white/5 p-4">
                                <p class="text-sm font-semibold text-slate-300">Career goal</p>
                                <p class="mt-2 text-lg font-extrabold">Frontend developer</p>
                            </div>
                        </div>
                    </aside>

                    <div class="space-y-6">
                        <div class="grid gap-6 md:grid-cols-3">
                            <div class="rounded-3xl bg-slate-50 p-5">
                                <p class="text-sm font-semibold text-slate-500">Node 01</p>
                                <p class="mt-3 text-lg font-extrabold text-slate-950">HTML Semantics</p>
                                <p class="mt-2 text-sm text-emerald-600">Completed</p>
                            </div>
                            <div class="rounded-3xl bg-brand-600 p-5 text-white">
                                <p class="text-sm font-semibold text-brand-100">Node 02</p>
                                <p class="mt-3 text-lg font-extrabold">Responsive Layouts</p>
                                <p class="mt-2 text-sm text-brand-100">Current focus</p>
                            </div>
                            <div class="rounded-3xl bg-slate-50 p-5">
                                <p class="text-sm font-semibold text-slate-500">Node 03</p>
                                <p class="mt-3 text-lg font-extrabold text-slate-950">React State</p>
                                <p class="mt-2 text-sm text-slate-500">Locked until quiz completion</p>
                            </div>
                        </div>

                        <div class="rounded-3xl border border-slate-200 p-6">
                            <div class="flex items-center justify-between">
                                <h4 class="text-xl font-extrabold text-slate-950">Path graph</h4>
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-sm font-semibold text-slate-500">Live updates</span>
                            </div>
                            <div class="mt-8 grid gap-5 lg:flex lg:flex-row lg:items-center lg:justify-between">
                                <div class="flex items-center gap-3">
                                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-lg font-extrabold text-emerald-600">A</span>
                                    <div>
                                        <p class="font-extrabold text-slate-950">Foundation</p>
                                        <p class="text-sm text-slate-500">Strong enough to move ahead</p>
                                    </div>
                                </div>
                                <div class="hidden h-px flex-1 bg-slate-200 lg:block"></div>
                                <div class="flex items-center gap-3">
                                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-50 text-lg font-extrabold text-brand-600">B</span>
                                    <div>
                                        <p class="font-extrabold text-slate-950">Application</p>
                                        <p class="text-sm text-slate-500">Current learning node</p>
                                    </div>
                                </div>
                                <div class="hidden h-px flex-1 bg-slate-200 lg:block"></div>
                                <div class="flex items-center gap-3">
                                    <span class="flex h-14 w-14 items-center justify-center rounded-2xl bg-slate-100 text-lg font-extrabold text-slate-500">C</span>
                                    <div>
                                        <p class="font-extrabold text-slate-950">Interview Readiness</p>
                                        <p class="text-sm text-slate-500">Unlocked after next checkpoint</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-3xl bg-slate-50 p-6">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm font-semibold text-slate-500">Recommended course</p>
                                    <h4 class="mt-2 text-xl font-extrabold text-slate-950">Build responsive components with Tailwind</h4>
                                </div>
                                <span class="rounded-full bg-white px-3 py-1 text-sm font-bold text-brand-700 shadow-sm">82% match</span>
                            </div>
                            <div class="mt-5 h-2 rounded-full bg-white">
                                <div class="h-2 w-4/5 rounded-full bg-brand-600"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="mx-auto max-w-7xl px-4 py-20 sm:px-6 sm:py-24 lg:px-8">
    <div class="max-w-2xl reveal">
        <p class="text-sm font-extrabold uppercase tracking-[0.25em] text-brand-600">Testimonials</p>
        <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">Real stories from students who needed structure.</h2>
        <p class="mt-5 text-base leading-8 text-slate-600 sm:text-lg">Testimonials turn product claims into believable outcomes. These cards give visitors a sense of what progress can actually feel like.</p>
    </div>

    <div class="mt-14 grid gap-6 lg:grid-cols-3">
        @php
            $testimonials = [
                ['Priya S.', 'Became a frontend developer in 4 months', 'I had tried five different platforms before SkillWeave. This was the first one that actually understood what I needed to learn next and what I could skip.'],
                ['Arjun R.', 'Shifted from tutorials to job-ready projects', 'The adaptive quizzes were the turning point for me. I stopped guessing where my gaps were and finally had a roadmap that felt honest and practical.'],
                ['Meera K.', 'Built consistency while preparing for placements', 'SkillWeave made the process less overwhelming. I always knew my next step, and that clarity helped me stay consistent every week.'],
            ];
        @endphp

        @foreach ($testimonials as [$name, $goal, $quote])
            <article class="rounded-3xl border border-slate-200 bg-white p-8 shadow-sm reveal">
                <div class="flex items-center gap-4">
                    <span class="flex h-14 w-14 items-center justify-center rounded-full bg-brand-50 text-lg font-extrabold text-brand-700">
                        {{ strtoupper(substr($name, 0, 1)) }}
                    </span>
                    <div>
                        <h3 class="text-lg font-extrabold text-slate-950">{{ $name }}</h3>
                        <p class="text-sm text-slate-500">{{ $goal }}</p>
                    </div>
                </div>
                <p class="mt-6 text-sm font-bold uppercase tracking-[0.25em] text-amber-500">Five star review</p>
                <p class="mt-5 text-base leading-8 text-slate-600">"{{ $quote }}"</p>
            </article>
        @endforeach
    </div>
</section>

<section id="faqs" class="mx-auto max-w-5xl px-4 py-20 sm:px-6 sm:py-24 lg:px-8">
    <div class="text-center reveal">
        <p class="text-sm font-extrabold uppercase tracking-[0.25em] text-brand-600">FAQs</p>
        <h2 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-950 sm:text-5xl">Questions students usually ask before joining.</h2>
        <p class="mx-auto mt-5 max-w-2xl text-base leading-8 text-slate-600 sm:text-lg">This section removes friction at the bottom of the page and helps the final CTA feel earned rather than abrupt.</p>
    </div>

    <div class="mt-14 space-y-4">
        <details class="faq-item reveal rounded-3xl border border-slate-200 bg-white p-6 shadow-sm" open>
            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-left">
                <span class="text-lg font-extrabold text-slate-950">How does SkillWeave decide what I should learn next?</span>
                <svg class="h-5 w-5 shrink-0 text-slate-400 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </summary>
            <p class="mt-4 max-w-3xl text-base leading-8 text-slate-600">The platform combines your selected goal, quiz performance, and recent progress to decide the next lesson, quiz, or practice block that will have the highest payoff.</p>
        </details>

        <details class="faq-item reveal reveal-delay-1 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-left">
                <span class="text-lg font-extrabold text-slate-950">Can beginners use the platform, or is it only for advanced students?</span>
                <svg class="h-5 w-5 shrink-0 text-slate-400 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </summary>
            <p class="mt-4 max-w-3xl text-base leading-8 text-slate-600">It works for both. Beginners get a structured foundation, while advanced learners get shorter paths that skip material they have already mastered.</p>
        </details>

        <details class="faq-item reveal reveal-delay-2 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-left">
                <span class="text-lg font-extrabold text-slate-950">Do I need to pay before I can see my learning path?</span>
                <svg class="h-5 w-5 shrink-0 text-slate-400 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </summary>
            <p class="mt-4 max-w-3xl text-base leading-8 text-slate-600">No. The entry experience is designed to be low friction, so users can start, get assessed, and understand the value of the roadmap before making a larger commitment.</p>
        </details>

        <details class="faq-item reveal reveal-delay-3 rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-left">
                <span class="text-lg font-extrabold text-slate-950">What kinds of goals can I build a path for?</span>
                <svg class="h-5 w-5 shrink-0 text-slate-400 transition-transform" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="m6 9 6 6 6-6" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </summary>
            <p class="mt-4 max-w-3xl text-base leading-8 text-slate-600">Common goals include frontend development, data structures, interview preparation, backend engineering, and skill-gap closure for internships or placements.</p>
        </details>
    </div>
</section>

<section id="final-cta" class="pb-20 sm:pb-24">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-[2rem] bg-[linear-gradient(135deg,#1f3fc0_0%,#315efb_45%,#38bdf8_100%)] px-6 py-12 text-white shadow-soft sm:px-12 sm:py-14 reveal">
            <div class="max-w-3xl">
                <p class="text-sm font-extrabold uppercase tracking-[0.25em] text-brand-100">Ready when you are</p>
                <h2 class="mt-4 text-3xl font-extrabold tracking-tight sm:text-5xl">Ready to learn the smart way?</h2>
                <p class="mt-5 text-base leading-8 text-brand-50 sm:text-lg">Join thousands of students already on their personalised path and start with a roadmap that responds to your real progress.</p>
            </div>

            <div class="mt-10 flex flex-col gap-4 sm:flex-row sm:items-center">
                <a href="{{ route('get.started') }}" class="inline-flex items-center justify-center rounded-full bg-white px-7 py-4 text-base font-extrabold text-brand-700 transition hover:bg-slate-100">
                    Start for Free
                </a>
                <p class="text-sm font-semibold text-brand-100">No credit card required</p>
            </div>
        </div>
    </div>
</section>
@endsection
