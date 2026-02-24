<div>
    <div class="mb-6 flex items-center justify-between gap-3">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">Forum Posts</h1>
            <p class="text-gray-600 dark:text-gray-400">Latest discussions from the community</p>
            <div class="mt-2 flex items-center gap-2 flex-wrap">
                <span class="text-sm text-gray-600 dark:text-gray-400">Filter:</span>
                <select wire:model.live="filter" class="rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-white px-2.5 py-1.5 sm:hidden">
                    <option value="news">Latest</option>
                    <option value="most_likes">Popular</option>
                    <option value="most_views">Trending</option>
                    <option value="verified_users">Verified</option>
                    <option value="admin_posts">Official</option>
                </select>
                <div class="hidden sm:inline-flex rounded-lg border border-gray-300 dark:border-gray-700 overflow-hidden">
                    <button type="button" wire:click="$set('filter','news')"
                        class="px-3 py-1.5 text-sm {{ ($filter ?? 'news') === 'news' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                        Latest
                    </button>
                    <button type="button" wire:click="$set('filter','most_likes')"
                        class="px-3 py-1.5 text-sm {{ ($filter ?? 'news') === 'most_likes' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                        Popular
                    </button>
                    <button type="button" wire:click="$set('filter','most_views')"
                        class="px-3 py-1.5 text-sm {{ ($filter ?? 'news') === 'most_views' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                        Trending
                    </button>
                    <button type="button" wire:click="$set('filter','verified_users')"
                        class="px-3 py-1.5 text-sm {{ ($filter ?? 'news') === 'verified_users' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                        Verified
                    </button>
                    <button type="button" wire:click="$set('filter','admin_posts')"
                        class="px-3 py-1.5 text-sm {{ ($filter ?? 'news') === 'admin_posts' ? 'bg-blue-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                        Official
                    </button>
                </div>
            </div>
        </div>
        <a href="{{ url('/new') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
            </svg>
            New
        </a>
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

    @if($posts->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $posts->links('vendor.pagination.tailwind') }}
        </div>
    @endif
</div>
