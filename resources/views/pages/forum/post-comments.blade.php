<div x-data="{ replyModalOpen: false, replyParentId: null, replyLabel: '', replyText: '' }"
     x-on:open-reply-modal.window="replyModalOpen = true; replyParentId = $event.detail.parentId; replyLabel = $event.detail.label; replyText = ''"
     x-on:comment-replied.window="replyModalOpen = false">
    <div class="mt-8 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-5">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Comments</h2>

        @auth
            <div class="mb-6">
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Add a comment</label>
                <div class="flex items-start gap-3">
                    <textarea dir="auto" wire:model.defer="newComment" rows="3" class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" placeholder="Write a comment..."></textarea>
                    <button wire:click="addRoot" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Comment</button>
                </div>
                @error('newComment') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        @endauth

        <div class="relative h-1 mb-3" wire:loading.flex wire:target="loadMore,addRoot,addReply,deleteComment">
            <div class="h-1 w-full bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                <div class="h-full w-1/2 animate-pulse bg-blue-600 rounded-full"></div>
            </div>
        </div>

        <div class="space-y-5">
            @forelse($roots as $comment)
                @include('pages.forum.post-comments-item', ['comment' => $comment, 'postId' => $postId ?? $comment->post_id])
            @empty
                <p class="text-sm text-gray-600 dark:text-gray-400">No comments yet. Be the first to comment.</p>
            @endforelse
        </div>

        <div class="mt-6 flex justify-center">
            <div id="comments-sentinel" class="h-6" data-has-more="{{ $hasMore ? '1' : '0' }}"></div>
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
    </div>

    <div x-cloak x-show="replyModalOpen" class="fixed inset-0 z-50 md:hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/50" @click="replyModalOpen=false"></div>
        <div class="relative w-full max-w-md rounded-2xl bg-white dark:bg-gray-900 p-4 border border-gray-200 dark:border-gray-700 shadow-lg">
            <div class="flex items-center justify-between mb-3">
                <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="replyLabel"></div>
                <button class="text-gray-500 dark:text-gray-400" @click="replyModalOpen=false">Close</button>
            </div>
            <div class="flex items-start gap-2">
                <textarea dir="auto" x-model="replyText" rows="4" class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100" placeholder="Write a reply..."></textarea>
                <button @click="$wire.set('replyText.'+replyParentId, replyText); $wire.addReply(replyParentId);" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">Reply</button>
            </div>
        </div>
    </div>

    <script>
        function initCommentsInfiniteScroll() {
            const sentinel = document.getElementById('comments-sentinel');
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
        document.addEventListener('DOMContentLoaded', initCommentsInfiniteScroll);
        document.addEventListener('livewire:navigated', initCommentsInfiniteScroll);
    </script>
</div>
