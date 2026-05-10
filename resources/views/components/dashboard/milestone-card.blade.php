@props([
    'status',
    'title',
    'description',
    'match' => null,
])

<x-ui.card hover rounded="rounded-3xl" shadow="shadow-sm" padding="p-4 sm:p-5">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <p class="text-sm font-extrabold text-blue-700">{{ $status }}</p>
            <h3 class="mt-1 text-xl font-extrabold">{{ $title }}</h3>
            <p class="mt-2 text-sm font-semibold leading-6 text-slate-500">{{ $description }}</p>
        </div>
        @if ($match !== null)
            <div class="shrink-0 rounded-2xl bg-slate-50 px-4 py-3 text-center">
                <p class="text-2xl font-extrabold">{{ $match }}%</p>
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-400">match</p>
            </div>
        @endif
    </div>
</x-ui.card>
