@props([
    'type',
    'title',
    'time',
])

<x-ui.card rounded="rounded-3xl" background="bg-slate-50" border="false" shadow="shadow-none" padding="p-5">
    <p class="text-sm font-extrabold text-blue-700">{{ $type }}</p>
    <h3 class="mt-2 text-lg font-extrabold">{{ $title }}</h3>
    <p class="mt-4 text-sm font-bold text-slate-500">{{ $time }}</p>
</x-ui.card>
