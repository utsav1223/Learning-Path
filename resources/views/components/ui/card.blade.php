@props([
    'rounded' => 'rounded-[1.75rem]',
    'shadow' => 'shadow-sm',
    'padding' => 'p-5 sm:p-6',
    'border' => true,
    'background' => 'bg-white',
    'hover' => false,
])

<div
    {{ $attributes->class([
        $rounded,
        $shadow,
        $padding,
        'border border-slate-200' => $border,
        $background,
        'transition hover:border-blue-200 hover:shadow-sm' => $hover,
    ]) }}
>
    {{ $slot }}
</div>
