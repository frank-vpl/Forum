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
                @php($isSystem = in_array($n->type, ['status_to_verified','status_to_user','status_to_banned','status_unbanned','status_to_admin','post_admin_edited','post_admin_deleted','email_change_requested','email_changed','email_change_canceled','email_change_expired'], true))
                @if($isSystem)
                    <img src="{{ asset('logo.svg') }}" alt="System" class="w-12 h-12 rounded-full object-contain border border-gray-200 dark:border-gray-700 p-2 bg-white dark:bg-gray-800">
                @elseif($actor?->profile_image_url)
                    <a href="{{ route('user.show', ['id' => $actor->id]) }}" wire:navigate>
                        <img src="{{ $actor->profile_image_url }}" alt="{{ $actor->name }}" class="w-12 h-12 rounded-full object-cover border border-gray-200 dark:border-gray-700">
                    </a>
                @else
                    @if($actor)
                        <a href="{{ route('user.show', ['id' => $actor->id]) }}" wire:navigate>
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900 dark:to-purple-900 flex items-center justify-center text-sm font-semibold text-gray-800 dark:text-white border border-gray-200 dark:border-gray-700">
                                {{ $actor->initials() }}
                            </div>
                        </a>
                    @else
                        <img src="{{ asset('logo.svg') }}" alt="System" class="w-12 h-12 rounded-full object-contain border border-gray-200 dark:border-gray-700 p-2 bg-white dark:bg-gray-800">
                    @endif
                @endif
                <div class="flex-1">
                    <div class="flex items-center gap-2 text-base">
                        @if($isSystem)
                            <span class="font-semibold text-gray-900 dark:text-white">System</span>
                        @elseif($actor)
                            <a href="{{ route('user.show', ['id' => $actor->id]) }}" wire:navigate class="font-semibold text-gray-900 dark:text-white">
                                {{ $actor->name }}
                            </a>
                        @else
                            <span class="font-semibold text-gray-900 dark:text-white">System</span>
                        @endif
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
                        @elseif($n->type === 'status_to_verified')
                            <span class="font-medium">Your account status changed to Verified</span>
                        @elseif($n->type === 'status_to_user')
                            <span class="font-medium">Your account status changed to User</span>
                        @elseif($n->type === 'status_to_banned')
                            <span class="font-medium text-red-600 dark:text-red-400">Your account has been banned</span>
                        @elseif($n->type === 'status_unbanned')
                            <span class="font-medium">Your account has been unbanned</span>
                        @elseif($n->type === 'status_to_admin')
                            <span class="font-medium">You have been granted Admin</span>
                        @elseif($n->type === 'post_admin_edited')
                            <span class="font-medium">Your post was updated by Admin</span>
                        @elseif($n->type === 'post_admin_deleted')
                            <span class="font-medium">Your post was removed by Admin</span>
                            @if(!$n->post && $n->post_id)
                                <span class="text-gray-600 dark:text-gray-400">(Post #{{ $n->post_id }})</span>
                            @endif
                        @elseif($n->type === 'user_follow')
                            <span class="font-medium">started following you</span>
                        @elseif($n->type === 'user_follow_back')
                            <span class="font-medium">followed you back</span>
                        @elseif($n->type === 'email_change_requested')
                            <span class="font-medium text-yellow-600 dark:text-yellow-400">Email change requested</span>
                            <span class="text-gray-600 dark:text-gray-400">Please confirm via the link sent to your new email.</span>
                        @elseif($n->type === 'email_changed')
                            <span class="font-medium text-green-600 dark:text-green-400">Email address updated</span>
                        @elseif($n->type === 'email_change_canceled')
                            <span class="font-medium text-blue-600 dark:text-blue-400">Email change canceled</span>
                        @elseif($n->type === 'email_change_expired')
                            <span class="font-medium text-red-600 dark:text-red-400">Email change expired</span>
                            <span class="text-gray-600 dark:text-gray-400">Your pending email change link has expired.</span>
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
