@props([
    'color' => 'blue',
    'variant' => 'light', // 'light' or 'dark'
    'size' => 'sm',
])

@php
$colorClasses = [
    'blue' => [
        'light' => 'bg-blue-50 text-blue-700',
        'dark' => 'bg-blue-600 text-white',
    ],
    'emerald' => [
        'light' => 'bg-emerald-50 text-emerald-700',
        'dark' => 'bg-emerald-600 text-white',
    ],
    'slate' => [
        'light' => 'bg-slate-100 text-slate-700',
        'dark' => 'bg-slate-700 text-white',
    ],
];

$sizeClasses = [
    'sm' => 'px-3 py-1 text-sm font-bold',
    'md' => 'px-4 py-2 text-base font-bold',
    'lg' => 'px-5 py-3 text-lg font-bold',
];
@endphp

<span {{ $attributes->class([
    'inline-flex items-center rounded-full',
    $colorClasses[$color][$variant] ?? $colorClasses['blue']['light'],
    $sizeClasses[$size] ?? $sizeClasses['sm'],
]) }}>
    {{ $slot }}
</span>
