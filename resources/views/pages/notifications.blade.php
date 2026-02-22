<div>
    <div class="mb-4 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Notifications</h2>
        <button type="button" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 dark:border-gray-700 px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
            onclick="if(confirm('Clear all notifications?')){ const idEl=this.closest('[wire\\:id]'); if(idEl){ window.Livewire.find(idEl.getAttribute('wire:id')).call('clearAll'); } }">
            Clear all
        </button>
    </div>
    <div class="space-y-0">
        @forelse($items as $n)
            @php($actor = $n->actor)
            <div class="flex items-start gap-4 py-3">
                @if($actor?->profile_image_url)
                    <img src="{{ $actor->profile_image_url }}" alt="{{ $actor->name }}" class="w-12 h-12 rounded-full object-cover border border-gray-200 dark:border-gray-700">
                @else
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900 dark:to-purple-900 flex items-center justify-center text-sm font-semibold text-gray-800 dark:text-white border border-gray-200 dark:border-gray-700">
                        {{ $actor?->initials() }}
                    </div>
                @endif
                <div class="flex-1">
                    <div class="flex items-center gap-2 text-base">
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $actor?->name ?? 'User' }}</span>
                        <span class="text-gray-500 dark:text-gray-400">•</span>
                        <span class="text-gray-500 dark:text-gray-400 text-sm">{{ $n->created_at?->diffForHumans() }}</span>
                    </div>
                    <div class="mt-1 text-base text-gray-800 dark:text-gray-200">
                        @if($n->type === 'post_like')
                            <span class="font-medium">liked your post</span>
                        @elseif($n->type === 'post_comment')
                            <span class="font-medium">commented on your post</span>
                        @elseif($n->type === 'comment_reply')
                            <span class="font-medium">replied to your comment</span>
                        @else
                            <span class="font-medium">activity</span>
                        @endif
                        @if($n->post)
                            <a href="{{ route('forum.show', ['id' => $n->post->id]) }}" class="text-blue-600 hover:underline font-semibold">
                                {{ $n->post->title }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
            @if(!$loop->last)
                <hr class="border-t border-gray-200 dark:border-gray-700">
            @endif
        @empty
            <div class="text-sm text-gray-600 dark:text-gray-300">No notifications</div>
        @endforelse
    </div>

    <div class="mt-6 flex justify-center">
        <div id="notifications-sentinel" class="h-6" data-has-more="{{ $hasMore ? '1' : '0' }}"></div>
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
        function initNotificationsInfiniteScroll() {
            const sentinel = document.getElementById('notifications-sentinel');
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
        document.addEventListener('DOMContentLoaded', initNotificationsInfiniteScroll);
        document.addEventListener('livewire:navigated', initNotificationsInfiniteScroll);
    </script>
</div>
