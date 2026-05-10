@props([
    'user',
    'profile' => null,
])

<div class="min-h-screen lg:grid lg:grid-cols-[18rem_1fr]">
    <x-dashboard.sidebar :user="$user" :profile="$profile" />
    
    <main class="px-4 py-5 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>
</div>
