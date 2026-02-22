@php
$code = $code ?? 404;
$style = (int) (request('style') ?? ($style ?? 1));
$heading = $heading ?? (string) $code;
if (!isset($title)) {
    $title = match ($code) {
        403 => __('Access denied'),
        500 => __('Server error'),
        503 => __('Service unavailable'),
        default => __('Page not found'),
    };
}
if (!isset($desc)) {
    $desc = match ($code) {
        403 => __('You do not have permission to view this page.'),
        500 => __('Something went wrong on our side. Please try again later.'),
        503 => __('The service is temporarily unavailable. Please try again later.'),
        default => __('The page you’re looking for doesn’t exist or has been moved.'),
    };
}
$icon = $code === 403 ? 'lock-closed' : ($code === 503 ? 'clock' : 'exclamation-triangle');
@endphp

<x-layouts::app.sidebar :title="__('Error').' '.$heading">
    <flux:main>
        @if($style === 2)
            <div class="flex min-h-[60vh] items-center justify-center px-4">
                <div class="w-full max-w-2xl overflow-hidden rounded-3xl border border-zinc-200 bg-gradient-to-br from-white to-zinc-50 p-0 shadow-lg dark:border-zinc-700 dark:from-zinc-800 dark:to-zinc-900">
                    <div class="relative h-40 w-full bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 dark:from-blue-700 dark:via-indigo-700 dark:to-violet-700"></div>
                    <div class="-mt-12 px-8 pb-10 text-center">
                        <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-2xl bg-white text-blue-600 shadow-md ring-1 ring-zinc-200 dark:bg-zinc-800 dark:text-blue-400 dark:ring-zinc-700">
                            <flux:icon name="{{ $icon }}" class="h-10 w-10" />
                        </div>
                        <div class="text-7xl font-extrabold tracking-tight text-zinc-900 dark:text-white">{{ $heading }}</div>
                        <div class="mt-2 text-xl font-semibold text-zinc-900 dark:text-white">{{ $title }}</div>
                        <p class="mx-auto mt-3 max-w-lg text-sm text-zinc-600 dark:text-zinc-400">{{ $desc }}</p>
                        <div class="mt-8 flex items-center justify-center gap-3">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                                <flux:icon name="home" class="h-4 w-4" />
                                <span>{{ __('Back to Platform') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @elseif($style === 3)
            <div class="flex min-h-[60vh] items-center justify-center px-4">
                <div class="w-full max-w-2xl rounded-3xl border border-zinc-200 bg-white p-10 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="grid gap-8 sm:grid-cols-2 sm:gap-10">
                        <div class="flex items-center justify-center">
                            <div class="relative">
                                <div class="absolute inset-0 blur-2xl opacity-30 bg-gradient-to-br from-blue-600 via-indigo-600 to-violet-600 rounded-full w-52 h-52"></div>
                                <div class="relative flex h-52 w-52 items-center justify-center rounded-2xl bg-zinc-50 text-indigo-600 ring-1 ring-zinc-200 dark:bg-zinc-900 dark:text-indigo-400 dark:ring-zinc-700">
                                    <span class="text-8xl font-black">{{ $heading }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col justify-center text-center sm:text-left">
                            <div class="text-2xl font-semibold text-zinc-900 dark:text-white">{{ $title }}</div>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">{{ $desc }}</p>
                            <div class="mt-6 flex items-center justify-center sm:justify-start">
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                                    <flux:icon name="home" class="h-4 w-4" />
                                    <span>{{ __('Back to Platform') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="flex min-h-[60vh] items-center justify-center px-4">
                <div class="w-full max-w-xl rounded-2xl border border-zinc-200 bg-white p-8 text-center shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-full bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-300">
                        <flux:icon name="{{ $icon }}" class="h-8 w-8" />
                    </div>
                    <div class="mb-2 text-7xl font-extrabold tracking-tight text-zinc-900 dark:text-white">{{ $heading }}</div>
                    <div class="mb-3 text-xl font-semibold text-zinc-900 dark:text-white">{{ $title }}</div>
                    <p class="mb-8 text-sm text-zinc-600 dark:text-zinc-400">{{ $desc }}</p>
                    <div class="flex items-center justify-center gap-3">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                            <flux:icon name="home" class="h-4 w-4" />
                            <span>{{ __('Back to Platform') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </flux:main>
</x-layouts::app.sidebar>
