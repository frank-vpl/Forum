<x-layouts.home :title="__('Home')">
    <section class="min-h-screen bg-white dark:bg-zinc-900">
        <div class="mx-auto max-w-6xl px-6 py-16 lg:py-24">
            <div class="flex items-center justify-center">
                <img src="{{ asset('logo.svg') }}" alt="{{ config('app.name') }}" class="h-12 w-auto dark:brightness-90">
            </div>
            <div class="mt-8 text-center">
                <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold tracking-tight text-zinc-900 dark:text-white">
                    Freedom community for Iran
                </h1>
                <p class="mt-4 text-base sm:text-lg text-zinc-600 dark:text-zinc-300">
                    For Pahlavi supporters, patriots, and monarchists — Javid Shah.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                    @guest
                        <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-white hover:bg-blue-700">
                            Enter Forum
                        </a>
                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 dark:border-zinc-700 px-5 py-2.5 text-zinc-900 dark:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800">
                            Log in
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-lg border border-zinc-300 dark:border-zinc-700 px-5 py-2.5 text-zinc-900 dark:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800">
                                Register
                            </a>
                        @endif
                    @endguest
                </div>
            </div>
            <div class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="flex items-center gap-3">
                        <flux:icon name="home" class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Forum</h3>
                    </div>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">Latest discussions from the community with mobile-first UI.</p>
                </div>
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="flex items-center gap-3">
                        <flux:icon name="users" class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Users</h3>
                    </div>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">Public profiles with posts, views, comments, and badges.</p>
                </div>
                <div class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="flex items-center gap-3">
                        <flux:icon name="cog" class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                        <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">Settings</h3>
                    </div>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">Profile, password, two-factor, and appearance preferences.</p>
                </div>
            </div>
            <div class="mt-12 flex justify-center">
                <a href="{{ route('dashboard') }}" wire:navigate class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-5 py-2.5 text-white hover:bg-blue-700">
                    Explore the Forum
                </a>
            </div>
        </div>
    </section>
</x-layouts.home>
