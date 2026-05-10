@props([
    'title',
    'description' => null,
    'icon' => null,
    'selected' => false,
    'disabled' => false,
    'featured' => false,
])

<label class="choice cursor-pointer">
    <input
        type="radio"
        {{ $attributes->whereStartsWith('name') }}
        {{ $attributes->whereStartsWith('value') }}
        data-summary="goal"
        class="sr-only"
        :checked="$selected"
        :disabled="$disabled"
    >
    <span class="grid h-full rounded-2xl md:rounded-[1.75rem] border border-slate-200 bg-white p-5 md:p-6 shadow-sm md:shadow-xl md:shadow-slate-200/60 transition-all duration-300">
        <div class="flex items-start gap-3">
            @if ($icon)
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-2xl bg-slate-950 text-white">
                    {{ $icon }}
                </div>
            @endif
            <div class="min-w-0 flex-1">
                <span class="block text-lg font-bold">{{ $title }}</span>
                @if ($description)
                    <span class="mt-1 block text-sm font-semibold leading-6 text-slate-500">{{ $description }}</span>
                @endif
            </div>
        </div>
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

    .choice input:checked + span {
        border-color: #2563eb;
        background: #eff6ff;
        color: #1d4ed8;
        box-shadow: 0 16px 34px rgba(37, 99, 235, 0.16);
        transform: translateY(-2px) scale(1.01);
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
