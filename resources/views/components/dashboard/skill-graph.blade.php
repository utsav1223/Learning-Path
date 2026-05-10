@props([
    'nodes' => ['Foundation', 'Practice', 'Checkpoint', 'Project'],
    'completedUpTo' => 1,
])

<x-ui.card background="bg-slate-950 text-white" padding="p-5 sm:p-6" rounded="rounded-[1.75rem]">
    <p class="text-sm font-extrabold uppercase tracking-[0.22em] text-blue-200">Skill graph</p>
    <h2 class="mt-2 text-2xl font-extrabold">Your route</h2>

    <div class="mt-8 space-y-5">
        @foreach ($nodes as $index => $node)
            <div class="flex items-center gap-4">
                <span class="flex h-12 w-12 items-center justify-center rounded-2xl font-extrabold text-sm" 
                    :class="$index <= $completedUpTo ? 'bg-blue-600 text-white' : 'bg-white/10 text-slate-400'">
                    {{ $index + 1 }}
                </span>
                <div>
                    <p class="font-extrabold">{{ $node }}</p>
                    <p class="text-sm font-semibold text-slate-400">
                        {{ $index <= $completedUpTo ? 'Active in your path' : 'Unlocks after quiz' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
</x-ui.card>
