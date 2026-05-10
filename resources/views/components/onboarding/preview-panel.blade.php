@props([
    'title' => 'Your path',
    'items' => [],
    'dark' => false,
    'collapsible' => false,
])

@php
$bgClass = $dark ? 'bg-slate-950 text-white' : 'bg-white';
$textColor = $dark ? 'text-white' : 'text-slate-900';
@endphp

<div {{ $attributes->class([
    'rounded-2xl border border-slate-200 p-4 shadow-sm',
    $bgClass,
    'xl:sticky xl:top-6 xl:self-start' => !$collapsible,
    'md:col-span-2 md:block lg:hidden' => $collapsible,
]) }}>
    @if ($collapsible)
        <details>
            <summary class="cursor-pointer text-sm font-bold text-slate-700">{{ $title }}</summary>
            <div class="mt-3 grid gap-3 md:grid-cols-2 xl:grid-cols-4">
                {{ $slot }}
            </div>
        </details>
    @else
        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-blue-600">Live preview</p>
        <h2 class="mt-2 text-2xl font-bold">{{ $title }}</h2>

        <div class="mt-4 space-y-2">
            {{ $slot }}
        </div>
    @endif
</div>
