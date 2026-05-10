@props([
    'percentage' => 0,
    'color' => 'bg-blue-600',
    'showLabel' => false,
])

<div {{ $attributes->class('h-2 rounded-full bg-slate-100 overflow-hidden') }}>
    <div
        class="h-full rounded-full transition-all duration-300 {{ $color }}"
        style="width: {{ min($percentage, 100) }}%"
    ></div>
</div>

@if ($showLabel)
    <p class="mt-2 text-xs font-bold text-slate-600">{{ $percentage }}%</p>
@endif
