{{--
Component: sidebar
Purpose: Dashboard navigation sidebar with user context
Category: Dashboard

Props:
  - user (User): Authenticated user object (required)
  - profile (Profile|null): User's profile (optional)
  - currentGoal (string): User's current learning goal
  - dailyMinutes (int): Daily learning commitment in minutes
  - pace (string): Learning pace ('steady', 'fast track', 'weekend focused')

Responsive Behavior:
  - Mobile: Sticky top, full width, compact design
  - Tablet: Sticky top, full width
  - Desktop (lg): Sticky left sidebar, min-h-screen, full-height nav

Visual Design:
  - Background: bg-slate-950 (dark)
  - Text: text-white
  - Spacing: px-4 py-4 sm:px-5 lg:py-6
  - Navigation: flex gap-2 overflow-x-auto lg:grid lg:overflow-visible
  - Goal summary: Hidden on mobile/tablet, visible on desktop only

Interactive States:
  - Active nav link: bg-white/10 text-white
  - Inactive nav links: text-slate-300 hover:bg-white/10
  - "Edit" button: Mobile-only, hidden on desktop

Section: Goal Summary
  - Visibility: Desktop only (hidden: md:hidden, shown: lg:block)
  - Contains: Current goal, daily minutes, learning pace
  - Styling: rounded-3xl bg-white/10 p-5

Section: Logout
  - Mobile: Form at bottom of main content
  - Desktop: Form in sidebar (mt-8)
  - Button: Full width, border-white/10

Example:
  <x-dashboard.sidebar
      :user="$user"
      :profile="$profile"
      currentGoal="Frontend Developer"
      :dailyMinutes="45"
      pace="steady"
  />

Notes:
  - Component includes all desktop vs. mobile responsive logic
  - Parent should NOT add md: or lg: visibility classes
  - Navigation links are hardcoded (future: could be configurable)
  - Goal summary is read-only display
--}}

@props([
    'user',
    'profile' => null,
    'currentGoal' => 'Skill growth',
    'dailyMinutes' => 45,
    'pace' => 'steady',
])

<aside
  id="dashboard-sidebar"
  class="fixed inset-y-0 left-0 z-40 w-[82vw] max-w-sm -translate-x-full overflow-y-auto bg-slate-950 px-4 py-4 text-white shadow-2xl shadow-slate-950/30 transition-transform duration-300 ease-out sm:w-[22rem] sm:px-5 lg:sticky lg:top-0 lg:z-20 lg:w-auto lg:max-w-none lg:translate-x-0 lg:overflow-visible lg:shadow-lg lg:shadow-slate-950/10 lg:min-h-screen lg:py-6"
  data-dashboard-sidebar
>
  <div class="flex items-center justify-between gap-4 lg:justify-start">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-blue-600 text-lg font-extrabold">S</span>
            <span>
                <span class="block text-lg font-extrabold">SkillWeave</span>
                <span class="block text-xs font-bold uppercase tracking-[0.2em] text-slate-400">Learner desk</span>
            </span>
        </a>
    <div class="flex items-center gap-2 lg:hidden">
      <a href="{{ route('onboarding') }}" class="rounded-full bg-white/10 px-3 py-2 text-xs font-extrabold text-slate-200">Edit</a>
      <button
        type="button"
        class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white transition hover:bg-white/15"
        aria-label="Close navigation menu"
        data-dashboard-sidebar-close
      >
        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
          <path d="m6 6 12 12M18 6 6 18" stroke-linecap="round"></path>
        </svg>
      </button>
    </div>
    </div>

    <nav class="mt-5 grid gap-2 text-sm font-bold lg:mt-10 lg:grid lg:overflow-visible">
      <a href="{{ route('dashboard') }}"
         class="{{ request()->routeIs('dashboard') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-extrabold transition">
          Dashboard
      </a>

      <a href="{{ route('assessment.show') }}"
         class="{{ request()->routeIs('assessment.*') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-extrabold transition">
          Assessment
      </a>

      <a href="{{ route('roadmap.show') }}"
         class="{{ request()->routeIs('roadmap.*') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-extrabold transition">
          Roadmap
      </a>

      <a href="{{ route('roadmap.show') }}#path"
         class="text-slate-300 hover:bg-white/5 hover:text-white flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-extrabold transition">
          Learning Path
      </a>

      <a href="{{ route('roadmap.show') }}#resources"
         class="text-slate-300 hover:bg-white/5 hover:text-white flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-extrabold transition">
          Resources
      </a>

      <a href="{{ route('onboarding') }}"
         class="{{ request()->routeIs('onboarding') ? 'bg-white/10 text-white' : 'text-slate-300 hover:bg-white/5 hover:text-white' }} flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-extrabold transition">
          Edit Profile
      </a>
    </nav>

    <div class="mt-6 hidden rounded-3xl bg-white/10 p-5 lg:block">
        <p class="text-xs font-extrabold uppercase tracking-[0.2em] text-slate-400">Current goal</p>
        <p class="mt-3 text-xl font-extrabold">{{ $currentGoal }}</p>
        <p class="mt-3 text-sm leading-6 text-slate-300">{{ $dailyMinutes }} minutes per day, {{ $pace }} pace.</p>
    </div>

    <form method="POST" action="{{ route('logout') }}" class="mt-8 hidden lg:block">
        @csrf
        <button class="w-full rounded-2xl border border-white/10 px-4 py-3 text-sm font-extrabold text-slate-200 hover:bg-white/10">Logout</button>
    </form>
</aside>
