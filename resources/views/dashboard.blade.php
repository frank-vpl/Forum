<x-layouts::app.sidebar :title="__('Forum')">
    <flux:main>
        @php
            $flashStatus = session('status');
            $color = (string) (session('status_color') ?? 'blue');
            $map = [
                'blue' => ['bg' => 'bg-blue-50 dark:bg-blue-900/20', 'text' => 'text-blue-700 dark:text-blue-300', 'border' => 'border-blue-200 dark:border-blue-800'],
                'green' => ['bg' => 'bg-green-50 dark:bg-green-900/20', 'text' => 'text-green-700 dark:text-green-300', 'border' => 'border-green-200 dark:border-green-800'],
                'red' => ['bg' => 'bg-red-50 dark:bg-red-900/20', 'text' => 'text-red-700 dark:text-red-300', 'border' => 'border-red-200 dark:border-red-800'],
                'yellow' => ['bg' => 'bg-yellow-50 dark:bg-yellow-900/20', 'text' => 'text-yellow-700 dark:text-yellow-300', 'border' => 'border-yellow-200 dark:border-yellow-800'],
                'orange' => ['bg' => 'bg-orange-50 dark:bg-orange-900/20', 'text' => 'text-orange-700 dark:text-orange-300', 'border' => 'border-orange-200 dark:border-orange-800'],
                'purple' => ['bg' => 'bg-purple-50 dark:bg-purple-900/20', 'text' => 'text-purple-700 dark:text-purple-300', 'border' => 'border-purple-200 dark:border-purple-800'],
                'indigo' => ['bg' => 'bg-indigo-50 dark:bg-indigo-900/20', 'text' => 'text-indigo-700 dark:text-indigo-300', 'border' => 'border-indigo-200 dark:border-indigo-800'],
                'gray' => ['bg' => 'bg-gray-50 dark:bg-gray-900/20', 'text' => 'text-gray-700 dark:text-gray-300', 'border' => 'border-gray-200 dark:border-gray-800'],
            ];
            $classes = $map[$color] ?? $map['blue'];
            $message = $flashStatus ? ucwords(str_replace('-', ' ', (string) $flashStatus)) : null;
        @endphp
        @if($message)
            <div class="mb-4">
                <div class="flex items-center gap-3 rounded-lg border px-4 py-3 {{ $classes['bg'] }} {{ $classes['border'] }}">
                    <flux:icon name="information-circle" class="h-5 w-5 {{ $classes['text'] }}" />
                    <div class="text-sm font-medium {{ $classes['text'] }}">{{ $message }}</div>
                </div>
            </div>
        @endif
        <livewire:pages.posts-list />
    </flux:main>
</x-layouts::app.sidebar>
