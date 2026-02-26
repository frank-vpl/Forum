<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-neutral-950 antialiased">

<div class="grid min-h-screen lg:grid-cols-2">

    <!-- LEFT SIDE (Brand / Background) -->
    <div class="relative hidden lg:flex items-center justify-center bg-gradient-to-br from-neutral-900 to-neutral-800 text-white p-12">

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/40"></div>

        <div class="relative z-10 max-w-md space-y-8">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-4" wire:navigate>
                <img src="{{ asset('iranguard.png') }}" 
                     class="w-14 h-14 rounded-lg shadow-lg" 
                     alt="{{ config('app.name', 'App Logo') }}">
                     
                <h1 class="text-3xl font-bold tracking-wide">
                    {{ config('app.name', 'App Logo') }}
                </h1>
            </a>

            <!-- Quote -->
            @php
                [$message, $author] = str(Illuminate\Foundation\Inspiring::quotes()->random())->explode('-');
            @endphp

            <blockquote class="space-y-4 border-l-4 border-white/40 pl-6">
                <p class="text-xl font-light leading-relaxed">
                    “{{ trim($message) }}”
                </p>
                <footer class="text-sm text-neutral-300">
                    — {{ trim($author) }}
                </footer>
            </blockquote>

        </div>
    </div>


    <!-- RIGHT SIDE (BIG CENTER DIALOG) -->
    <div class="flex items-center justify-center bg-neutral-50 dark:bg-neutral-900 p-6">

        <div class="w-full max-w-md">

            <!-- Big Dialog Card -->
            <div class="rounded-2xl bg-white dark:bg-neutral-950 
                        shadow-2xl ring-1 ring-black/5 dark:ring-white/10
                        p-10 space-y-6">

                <!-- Mobile Logo -->
                <a href="{{ route('home') }}" class="flex flex-col items-center gap-3 lg:hidden" wire:navigate>
                    <img src="{{ asset('iranguard.png') }}" 
                         class="w-16 h-16 rounded-xl shadow-md" 
                         alt="{{ config('app.name', 'App Logo') }}">
                </a>

                <!-- Slot Content (Login/Register Form) -->
                <div>
                    {{ $slot }}
                </div>

            </div>

        </div>

    </div>

</div>

@fluxScripts
</body>
</html>