@props([
    'label',
    'value',
    'sublabel' => null,
    'background' => 'bg-slate-50',
    'rounded' => 'rounded-2xl',
    'dark' => false,
])

<div {{ $attributes->class([
    'px-4 py-3',
    $rounded,
    $dark ? 'bg-slate-950 text-white' : $background,
]) }}>
    <p class="text-xs font-bold uppercase tracking-[0.2em]" :class="$dark ? 'text-slate-400' : 'text-slate-400'">
        {{ $label }}
    </p>
    <p class="mt-1 text-2xl font-extrabold" :class="$dark ? 'text-white' : 'text-slate-900'">
        {{ $value }}
    </p>
    @if ($sublabel)
        <p class="mt-2 text-sm font-semibold" :class="$dark ? 'text-slate-400' : 'text-slate-500'">
            {{ $sublabel }}
        </p>
    @endif
</div>
