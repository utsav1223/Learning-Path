{{--
Component: step-navigation
Purpose: Tablet/desktop step indicator navigation for multi-step onboarding
Category: Onboarding

Props:
  - steps (array): Array of step names ['Basics', 'Level', 'Topics', 'Rhythm']
  - currentStep (int): Zero-indexed current step (0-3)

Responsive Behavior:
  - Mobile: hidden
  - Tablet (md): 4-column segmented grid nav
  - Desktop (lg): hidden (left sidebar used instead)

Visual Design:
  - Grid layout: grid-cols-4 on tablet
  - Rounded: rounded-2xl container
  - Glass effect: bg-white/80 backdrop-blur-xl
  - Spacing: px-2 py-2 md:px-3 md:py-3
  - Shadow: shadow-sm ring-1 ring-slate-200

Interactive States:
  - Active: Blue dot indicator, blue text
  - Inactive: Gray dot indicator, gray text
  - Buttons: data-step-jump="N" for JS click handlers

Example:
  <x-onboarding.step-navigation
      :steps="['Basics', 'Level', 'Topics', 'Rhythm']"
      :currentStep="0"
  />

JavaScript Integration:
  - Each button has data-step-jump attribute (0-3)
  - Expected to trigger step changes in parent controller
  - Requires external JS to manage active state

Notes:
  - Automatically encapsulates all tablet-specific styling
  - Parent page should NOT apply md: or lg: visibility classes
  - Component self-manages responsive display
--}}

@props([
    'currentStep' => 0,
    'steps' => ['Basics', 'Level', 'Topics', 'Rhythm'],
])

<nav {{ $attributes->class('hidden md:block xl:hidden') }} aria-label="Onboarding steps">
    <div class="grid grid-cols-4 gap-2 rounded-2xl bg-white/80 backdrop-blur-xl px-2 py-2 shadow-sm ring-1 ring-slate-200 md:px-3 md:py-3">
        @foreach ($steps as $index => $step)
            <button
                type="button"
                data-step-jump="{{ $index }}"
                class="roadmap-step min-h-[48px] shrink-0 rounded-lg border border-transparent px-2 py-2 text-left text-xs sm:text-sm font-semibold transition-all duration-300"
                :class="$index === $currentStep ? 'is-active' : ''"
            >
                <span class="inline-flex flex-col items-center justify-center gap-1 w-full">
                    <span class="h-2 w-2 rounded-full" :class="$index === $currentStep ? 'bg-blue-600' : 'bg-slate-300'"></span>
                    <span class="text-center">{{ $step }}</span>
                </span>
            </button>
        @endforeach
    </div>
</nav>

<style>
    .roadmap-step.is-active {
        border-color: #2563eb;
        background: #eff6ff;
        color: #1d4ed8;
    }

    .roadmap-step.is-complete {
        border-color: #10b981;
        background: #ecfdf5;
        color: #047857;
    }
</style>
