@props([
    'userName',
    'pace' => 'Steady',
    'readiness' => 0,
    'description' => 'Your path is tuned from your onboarding answers and progress signals.',
])

<header class="flex flex-col gap-4 rounded-[1.75rem] bg-white p-5 shadow-sm sm:flex-row sm:items-center sm:justify-between sm:p-6">
    <div>
        <p class="text-sm font-extrabold uppercase tracking-[0.22em] text-blue-600">Welcome back</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight sm:text-4xl">{{ $userName }}</h1>
        <p class="mt-2 text-sm font-semibold text-slate-500">{{ $description }}</p>
    </div>
    <div class="grid grid-cols-2 gap-3 sm:flex sm:items-center">
        <x-ui.metric
            label="Pace"
            value="{{ $pace }}"
            background="bg-slate-50"
        />
        <x-ui.metric
            label="Readiness"
            value="{{ $readiness }}%"
            background="bg-slate-950"
            dark
        />
    </div>
</header>
