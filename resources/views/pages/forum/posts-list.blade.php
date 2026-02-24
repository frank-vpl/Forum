<div>
    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">Forum Posts</h1>
            <p class="text-gray-600 dark:text-gray-400">Latest discussions from the community</p>
            <div class="mt-2 flex items-center gap-2 flex-wrap">
                <span class="text-sm text-gray-600 dark:text-gray-400">Filter:</span>
                <div class="hidden sm:block">
                    <flux:radio.group variant="segmented" wire:model.live="filter">
                        <flux:radio value="news">Latest</flux:radio>
                        <flux:radio value="following">Following</flux:radio>
                        <flux:radio value="most_likes">Popular</flux:radio>
                        <flux:radio value="most_views">Trending</flux:radio>
                        <flux:radio value="verified_users">Verified</flux:radio>
                        <flux:radio value="admin_posts">Official</flux:radio>
                    </flux:radio.group>
                </div>
                <div class="sm:hidden">
                    <flux:dropdown>
                        <flux:button variant="primary" icon:trailing="chevron-down">
                            {{ $filter === 'news' ? 'Latest' : ($filter === 'following' ? 'Following' : ($filter === 'most_likes' ? 'Popular' : ($filter === 'most_views' ? 'Trending' : ($filter === 'verified_users' ? 'Verified' : 'Official')))) }}
                        </flux:button>
                        <flux:menu>
                            <flux:menu.radio.group wire:model.live="filter">
                                <flux:menu.radio value="news">Latest</flux:menu.radio>
                                <flux:menu.radio value="following">Following</flux:menu.radio>
                                <flux:menu.radio value="most_likes">Popular</flux:menu.radio>
                                <flux:menu.radio value="most_views">Trending</flux:menu.radio>
                                <flux:menu.radio value="verified_users">Verified</flux:menu.radio>
                                <flux:menu.radio value="admin_posts">Official</flux:menu.radio>
                            </flux:menu.radio.group>
                        </flux:menu>
                    </flux:dropdown>
                </div>
            </div>
        </div>
        <a href="{{ url('/new') }}" class="hidden sm:inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            New
        </a>
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
                placeholder="Search posts by title or user name..."
            >
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse($posts as $post)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 transition-all duration-200 hover:shadow-lg h-full">
                <div class="p-5 flex flex-col h-full">
                    <div class="flex items-center gap-3 mb-3">
                        @php $u = $post->user; @endphp
                        @if($u?->profile_image_url)
                            <a href="{{ url('/user/'.($u->id ?? '')) }}" wire:navigate>
                                <img src="{{ $u->profile_image_url }}" alt="{{ $u->name }}" class="w-9 h-9 rounded-full object-cover border border-gray-200 dark:border-gray-700">
                            </a>
                        @else
                            <a href="{{ url('/user/'.($u->id ?? '')) }}" wire:navigate>
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900 dark:to-purple-900 flex items-center justify-center text-xs font-semibold text-gray-800 dark:text-white border border-gray-200 dark:border-gray-700">
                                    {{ $u?->initials() }}
                                </div>
                            </a>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-1">
                                <a href="{{ url('/user/'.($u->id ?? '')) }}" wire:navigate class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                    {!! \App\Support\TextFilters::flagify($u?->name ?? 'Unknown') !!}
                                </a>
                                @if($u?->getBadgeIconPath())
                                    <img src="{{ $u->getBadgeIconPath() }}" alt="{{ $u->getBadgeTooltip() }}" class="w-4 h-4" title="{{ $u->getBadgeTooltip() }}">
                                @endif
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ $post->created_at?->diffForHumans() }}</span>
                        </div>
                        <span class="rounded bg-gray-100 px-2 py-0.5 text-xs text-gray-700 dark:bg-gray-700 dark:text-gray-200">{{ $post->category }}</span>
                    </div>

                    <a dir="auto" href="{{ url('/forum/'.$post->id) }}" class="block text-lg font-semibold text-gray-900 dark:text-white hover:underline mb-1">
                        {!! \App\Support\TextFilters::flagify(\Illuminate\Support\Str::limit($post->title, 60)) !!}
                    </a>
                    <a dir="auto" href="{{ url('/forum/'.$post->id) }}" class="block text-sm text-gray-600 dark:text-gray-300 mb-4 hover:underline">
                        {!! \App\Support\TextFilters::flagify(\Illuminate\Support\Str::limit(strip_tags($post->content), 45)) !!}
                    </a>

                    <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-300 mt-auto">
                        <div class="flex items-center gap-3">
                            @php($liked = in_array($post->id, $likedPostIds ?? []))
                            @php($blockedLike = auth()->check() && ($post->user_id) && (auth()->user()->hasBlockedId($post->user_id) || auth()->user()->isBlockedById($post->user_id)))
                            @if(! $blockedLike)
                                <button
                                    type="button"
                                    wire:click="toggleLike({{ $post->id }})"
                                    wire:loading.class="opacity-50"
                                    wire:target="toggleLike"
                                    class="inline-flex items-center gap-1 {{ $liked ? 'text-red-600' : 'hover:text-red-600' }}"
                                    title="{{ $liked ? 'Unlike' : 'Like' }}"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform" viewBox="0 0 24 24" fill="{{ $liked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21c-4.5-3-9-6.5-9-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 4.5-4.5 8-9 11z" />
                                    </svg>
                                    @format_count($post->likes_count)
                                </button>
                            @else
                                <span class="inline-flex items-center gap-1 text-gray-400 dark:text-gray-500" title="Likes disabled">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 21c-4.5-3-9-6.5-9-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 4.5-4.5 8-9 11z" />
                                    </svg>
                                    @format_count($post->likes_count)
                                </span>
                            @endif
                        </div>
                        <div class="flex items-center gap-4">
                            <a href="{{ url('/forum/'.$post->id) }}" class="inline-flex items-center gap-1 hover:text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s4.5-7.5 9.75-7.5S21.75 12 21.75 12s-4.5 7.5-9.75 7.5S2.25 12 2.25 12z" />
                                    <circle cx="12" cy="12" r="3.25" />
                                </svg>
                                @format_count($post->views_count)
                            </a>
                            <a href="{{ url('/forum/'.$post->id) }}" class="inline-flex items-center gap-1 hover:text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h7M4 5h16a1 1 0 0 1 1 1v12l-3-3H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1z" />
                                </svg>
                                @format_count($post->comments_count)
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <flux:icon name="chat-bubble-left-right" class="mx-auto h-12 w-12 text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No posts yet</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Be the first to <a href="{{ url('/new') }}" class="text-blue-600 hover:underline">create one</a>.
                </p>
            </div>
        @endforelse
    </div>

    @if($hasMore)
        <div
            x-data="{ busy: false }"
            x-init="(function(){ const fn = () => { if (busy) return; const near = window.innerHeight + window.scrollY >= document.body.offsetHeight - 200; if (near) { busy = true; $wire.loadMore().then(() => { busy = false; }).catch(() => { $wire.reportLoadError(); busy = false; }); } }; window.addEventListener('scroll', fn); })()"
            class="mt-6"
        >
            @if($loadingMore)
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                        <flux:skeleton.group animate="shimmer">
                            <flux:skeleton.line class="mb-2 w-1/4" />
                            <flux:skeleton.line />
                            <flux:skeleton.line />
                            <flux:skeleton.line class="w-3/4" />
                        </flux:skeleton.group>
                        <div class="mt-4">
                            <flux:skeleton.group animate="shimmer" class="flex items-center gap-4">
                                <flux:skeleton class="size-10 rounded-full" />
                                <div class="flex-1">
                                    <flux:skeleton.line />
                                    <flux:skeleton.line class="w-1/2" />
                                </div>
                            </flux:skeleton.group>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                        <flux:skeleton.group animate="shimmer">
                            <flux:skeleton.line class="mb-2 w-1/4" />
                            <flux:skeleton.line />
                            <flux:skeleton.line />
                            <flux:skeleton.line class="w-3/4" />
                        </flux:skeleton.group>
                        <div class="mt-4">
                            <flux:skeleton.group animate="shimmer" class="flex items-center gap-4">
                                <flux:skeleton class="size-10 rounded-full" />
                                <div class="flex-1">
                                    <flux:skeleton.line />
                                    <flux:skeleton.line class="w-1/2" />
                                </div>
                            </flux:skeleton.group>
                        </div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5">
                        <flux:skeleton.group animate="shimmer">
                            <flux:skeleton.line class="mb-2 w-1/4" />
                            <flux:skeleton.line />
                            <flux:skeleton.line />
                            <flux:skeleton.line class="w-3/4" />
                        </flux:skeleton.group>
                        <div class="mt-4">
                            <flux:skeleton.group animate="shimmer" class="flex items-center gap-4">
                                <flux:skeleton class="size-10 rounded-full" />
                                <div class="flex-1">
                                    <flux:skeleton.line />
                                    <flux:skeleton.line class="w-1/2" />
                                </div>
                            </flux:skeleton.group>
                        </div>
                    </div>
                </div>
            @elseif($loadError)
                <div class="mt-6 flex justify-center">
                    <flux:button variant="outline" icon="arrow-path" wire:click="loadMore">
                        Retry loading next page
                    </flux:button>
                </div>
            @endif
        </div>
    @endif
</div>
