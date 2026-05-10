{{--
Component: skill-level-card
Purpose: Skill level selection with visual progress indicator
Category: Onboarding

Props:
  - level (string): Level name ('Beginner', 'Intermediate', 'Advanced')
  - description (string): Descriptive hint text
  - fillCount (int): 1-3, determines progress bar fill count
  - selected (bool): Whether this option is currently selected

Attributes (pass-through):
  - name: Form input name attribute (radio group)
  - value: Form input value attribute

Slot: icon SVG content (pass icon SVG here)

Responsive Behavior:
  - Mobile: rounded-2xl, p-4, shadow-sm
  - Tablet (md+): rounded-[1.75rem], p-6, shadow-xl
  - Automatically encapsulated - no parent control needed

Interactive States:
  - Hover: translateY(-3px) scale(1.015) with gradient background
  - Focus: Blue outline with 3px offset
  - Checked: Blue border-left, gradient background, enhanced shadow

Example:
  <x-onboarding.skill-level-card
      level="Beginner"
      description="Starting from fundamentals"
      fillCount="1"
      :selected="true"
      name="skill_level"
      value="Beginner"
  >
      <svg class="h-5 w-5">...</svg>
  </x-onboarding.skill-level-card>

Notes:
  - Uses .choice and .level-choice CSS classes (must be in parent stylesheet)
  - Level bar always shows 3 segments
  - SVG icons must be passed as slot content for flexibility
--}}

@props([
    'level',
    'description',
    'fillCount' => 1,
    'selected' => false,
])

<label class="choice level-choice cursor-pointer">
    <input
        type="radio"
        {{ $attributes->whereStartsWith('name') }}
        {{ $attributes->whereStartsWith('value') }}
        data-summary="skill"
        class="sr-only"
        {{ $selected ? 'checked' : '' }}
    >
    <span class="block h-full rounded-2xl md:rounded-[1.75rem] border border-l-4 border-slate-200 border-l-slate-200 bg-white p-5 md:p-6 shadow-sm md:shadow-xl md:shadow-slate-200/60 transition-all duration-300">
        <span class="flex items-start gap-3">
            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-slate-950 text-white">
                {{ $slot }}
            </span>
            <span class="min-w-0 flex-1">
                <span class="block text-lg font-bold">{{ $level }}</span>
                <span class="mt-1 block text-sm font-semibold leading-6 text-slate-500">{{ $description }}</span>
            </span>
        </span>
        <span class="mt-4 flex gap-1.5" data-level-bar>
            @for ($i = 1; $i <= 3; $i++)
                <span class="h-2 flex-1 rounded-full {{ $i <= $fillCount ? 'bg-slate-800' : 'bg-slate-200' }}"></span>
            @endfor
        </span>
    </span>
</label>

<style>
    .choice > span {
        transition: transform 0.25s cubic-bezier(0.22, 1, 0.36, 1),
                    box-shadow 0.25s cubic-bezier(0.22, 1, 0.36, 1),
                    border-color 0.25s cubic-bezier(0.22, 1, 0.36, 1),
                    background-color 0.25s cubic-bezier(0.22, 1, 0.36, 1);
    }

    @media (hover: hover) {
        .choice:hover > span {
            transform: translateY(-2px);
        }
    }

    .level-choice input:checked + span {
        border-left-color: #2563eb;
        background: linear-gradient(135deg, #eff6ff 0%, #ffffff 72%);
        box-shadow: 0 20px 42px rgba(37, 99, 235, 0.18);
        transform: translateY(-3px) scale(1.015);
    }

    .level-choice input:checked + span [data-level-bar] span {
        background: #2563eb;
    }

    .choice input:focus-visible + span {
        outline: 3px solid rgba(37, 99, 235, 0.28);
        outline-offset: 3px;
    }

    .choice input:disabled + span {
        cursor: not-allowed;
        opacity: 0.48;
    }
</style>
