@php($u = $comment->user)
<div class="flex items-start gap-3" x-data="{ open: false }" x-on:comment-replied.window="if ($event.detail && $event.detail.parentId === {{ $comment->id }}) open = false">
    @if($u?->profile_image_url)
        <img src="{{ $u->profile_image_url }}" alt="{{ $u->name }}" class="w-9 h-9 rounded-full object-cover border border-gray-200 dark:border-gray-700">
    @else
        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900 dark:to-purple-900 flex items-center justify-center text-xs font-semibold text-gray-800 dark:text-white border border-gray-200 dark:border-gray-700">
            {{ $u?->initials() }}
        </div>
    @endif
    <div class="flex-1">
        <div class="flex items-center gap-2 text-sm">
            <span class="font-medium text-gray-900 dark:text-white">{{ $u?->name ?? 'Unknown' }}</span>
            @if($u?->getBadgeIconPath())
                <img src="{{ $u->getBadgeIconPath() }}" alt="{{ $u->getBadgeTooltip() }}" class="w-4 h-4" title="{{ $u->getBadgeTooltip() }}">
            @endif
            <span class="text-gray-500 dark:text-gray-400">•</span>
            <span class="text-gray-500 dark:text-gray-400">{{ $comment->created_at?->diffForHumans() }}</span>
            <span class="text-blue-600 dark:text-blue-400 underline">
                to {{ $comment->parent_id ? ($comment->replyTo?->user?->name ?? 'user') : 'Post' }}
            </span>
        </div>
        <div dir="auto" class="mt-1 text-sm text-gray-800 dark:text-gray-200 whitespace-pre-wrap">{{ $comment->text }}</div>
        <div class="mt-2 flex items-center gap-3">
            @auth
                <button
                    data-label="Reply to {{ e($u?->name ?? 'user') }}"
                    @click.prevent="if (window.innerWidth < 768) { window.dispatchEvent(new CustomEvent('open-reply-modal', { detail: { parentId: {{ $comment->id }}, label: $el.dataset.label } })); } else { open = !open }"
                    class="text-xs text-blue-600 hover:underline">Reply</button>
                @if(auth()->id() === $comment->user_id)
                    <button type="button" class="text-xs text-red-600 hover:underline"
                        onclick="if(confirm('Delete this comment?')){ const idEl=this.closest('[wire\\:id]'); if(idEl){ window.Livewire.find(idEl.getAttribute('wire:id')).call('deleteComment', {{ $comment->id }}); } }">
                        Delete
                    </button>
                @endif
            @endauth
        </div>
        @auth
            <div x-show="open" x-cloak class="mt-2 hidden md:block">
                <div class="flex items-start gap-2">
                    <textarea dir="auto" wire:model.defer="replyText.{{ $comment->id }}" rows="2" class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100" placeholder="Write a reply..."></textarea>
                    <button wire:click="addReply({{ $comment->id }})" class="rounded-lg bg-blue-600 px-3 py-2 text-white hover:bg-blue-700 text-sm">Reply</button>
                </div>
                @error('replyText.'.$comment->id) <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        @endauth

        @if(!$comment->parent_id)
            @php($children = $comment->children()->with(['user','replyTo.user'])->orderBy('created_at')->get())
            @if($children->isNotEmpty())
                @php($limit = $this->visibleChildren[$comment->id] ?? 2)
                <div class="mt-3 space-y-3 ps-6 border-l border-gray-200 dark:border-gray-700">
                    @foreach($children->take($limit) as $child)
                        @include('pages.forum.post-comments-item', ['comment' => $child, 'postId' => $postId])
                    @endforeach
                    <div class="mt-2">
                        @if($children->count() > $limit)
                            <button wire:click="showMore({{ $comment->id }})" wire:loading.attr="disabled" class="text-xs text-blue-600 hover:underline">Show more</button>
                        @endif
                        @if($limit > 2)
                            <button wire:click="showLess({{ $comment->id }})" wire:loading.attr="disabled" class="text-xs text-gray-600 dark:text-gray-300 hover:underline ms-3">Show less</button>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
