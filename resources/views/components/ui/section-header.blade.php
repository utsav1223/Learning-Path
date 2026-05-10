@props([
    'label',
    'title',
    'description' => null,
    'action' => null,
    'actionLabel' => 'View all',
])

<div {{ $attributes->class('flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between') }}>
    <div>
        @if ($label)
            <p class="text-sm font-extrabold uppercase tracking-[0.22em] text-blue-600">
                {{ $label }}
            </p>
        @endif
        <h2 class="mt-2 text-2xl font-extrabold">
            {{ $title }}
        </h2>
        @if ($description)
            <p class="mt-2 text-sm font-semibold text-slate-500">
                {{ $description }}
            </p>
        @endif
    </div>
    @if ($action)
        <a href="{{ $action }}" class="text-sm font-extrabold text-blue-700 hover:text-blue-800">
            {{ $actionLabel }}
        </a>
    @endif
</div>
