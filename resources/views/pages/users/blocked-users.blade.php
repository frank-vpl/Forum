<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Blocked Users</h1>
        <p class="text-gray-600 dark:text-gray-400">Manage users you've blocked</p>
    </div>

    <div class="mb-6 max-w-md">
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <flux:icon name="magnifying-glass" class="w-4 h-4 text-gray-500" />
            </div>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Search blocked users by name or email..."
            >
        </div>
    </div>

    <div class="space-y-0">
        @forelse($users as $user)
            <div class="flex items-center gap-4 py-3">
                @if($user->profile_image_url)
                    <a href="{{ route('user.show', ['id' => $user->id]) }}" wire:navigate>
                        <img src="{{ $user->profile_image_url }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover border border-gray-200 dark:border-gray-700">
                    </a>
                @else
                    <a href="{{ route('user.show', ['id' => $user->id]) }}" wire:navigate>
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900 dark:to-purple-900 flex items-center justify-center text-sm font-semibold text-gray-800 dark:text-white border border-gray-200 dark:border-gray-700">
                            {{ $user->initials() }}
                        </div>
                    </a>
                @endif
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('user.show', ['id' => $user->id]) }}" wire:navigate class="font-semibold text-gray-900 dark:text-white truncate">
                            {{ $user->name }}
                        </a>
                        @if($user->getBadgeIconPath())
                            <img src="{{ $user->getBadgeIconPath() }}" alt="{{ $user->getBadgeTooltip() }}" class="w-4 h-4" title="{{ $user->getBadgeTooltip() }}">
                        @endif
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Blocked {{ $user->pivot?->created_at?->diffForHumans() }}
                    </div>
                </div>
                <form action="{{ route('user.unblock', ['id' => $user->id]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <flux:button
                        icon="no-symbol"
                        variant="outline"
                        size="sm"
                        title="Unblock"
                        type="submit"
                    >
                        Unblock
                    </flux:button>
                </form>
            </div>
            @if(!$loop->last)
                <hr class="border-t border-gray-200 dark:border-gray-700">
            @endif
        @empty
            <div class="text-sm text-gray-600 dark:text-gray-300">No blocked users</div>
        @endforelse
    </div>

    <div class="mt-6 flex justify-center">
        <div id="blocks-sentinel" class="h-6" data-has-more="{{ $hasMore ? '1' : '0' }}"></div>
    </div>

    @if($loadError && $hasMore)
        <div class="mt-4 flex justify-center">
            <button wire:click="loadMore" class="rounded-lg border border-gray-300 dark:border-gray-700 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">Load more</button>
        </div>
    @endif

    <div class="mt-4 space-y-3" wire:loading.delay.short wire:target="loadMore">
        <div class="h-16 rounded-lg bg-gray-100 dark:bg-gray-700/50 animate-pulse"></div>
        <div class="h-16 rounded-lg bg-gray-100 dark:bg-gray-700/50 animate-pulse"></div>
        <div class="h-16 rounded-lg bg-gray-100 dark:bg-gray-700/50 animate-pulse"></div>
    </div>

    <script>
        function initBlocksInfiniteScroll() {
            const sentinel = document.getElementById('blocks-sentinel');
            if (!sentinel) return;
            if (sentinel._observer) return;
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (!entry.isIntersecting) return;
                    if (sentinel.dataset.hasMore !== '1') return;
                    const compEl = sentinel.closest('[wire\\:id]');
                    if (compEl && window.Livewire) {
                        const comp = window.Livewire.find(compEl.getAttribute('wire:id'));
                        comp && comp.call('loadMore');
                    }
                });
            }, { threshold: 0.1 });
            observer.observe(sentinel);
            sentinel._observer = observer;
        }
        document.addEventListener('DOMContentLoaded', initBlocksInfiniteScroll);
        document.addEventListener('livewire:navigated', initBlocksInfiniteScroll);
    </script>
</div>
