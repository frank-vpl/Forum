<div>
    <div class="mb-8">
        <!-- Desktop: centered IG-like header -->
        <div class="hidden md:flex flex-col items-center text-center gap-3">
            @if($user->profile_image_url)
                <img src="{{ $user->profile_image_url }}" alt="{{ $user->name }}" class="w-32 h-32 rounded-full object-cover border border-gray-200 dark:border-gray-700">
            @else
                <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900 dark:to-purple-900 flex items-center justify-center text-3xl font-semibold text-gray-800 dark:text-white border border-gray-200 dark:border-gray-700">
                    {{ $user->initials() }}
                </div>
            @endif

            <div class="flex items-center gap-2">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                @if($user->getBadgeIconPath())
                    <img src="{{ $user->getBadgeIconPath() }}" alt="{{ $user->getBadgeTooltip() }}" class="w-5 h-5" title="{{ $user->getBadgeTooltip() }}">
                @endif
                @if($user->isBanned())
                    <span class="ms-2 inline-flex items-center rounded-md bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100 text-xs px-2 py-1">Banned</span>
                @endif
            </div>

            <div class="flex items-center gap-6 text-sm text-gray-600 dark:text-gray-300">
                <span><span class="font-semibold text-gray-900 dark:text-white">@format_count($commentsTotal)</span> Comments</span>
                <span><span class="font-semibold text-gray-900 dark:text-white">@format_count($viewsTotal)</span> Views</span>
                <span><span class="font-semibold text-gray-900 dark:text-white">@format_count($postsCount)</span> Posts</span>
            </div>

            @if($user->bio)
                <p dir="auto" class="text-sm text-gray-700 dark:text-gray-300">{{ $user->bio }}</p>
            @endif
            @if($user->profile_url)
                <a href="{{ $user->profile_url }}" target="_blank" rel="nofollow noopener" class="inline-flex items-center text-sm text-blue-600 hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5h6v6M20 4 12 12M16.5 7.5A6 6 0 1 1 7.5 16.5" />
                    </svg>
                    {{ $user->profile_link_title ?: (parse_url($user->profile_url, PHP_URL_HOST) ?: $user->profile_url) }}
                </a>
            @endif

            @if(!$user->isBanned())
            <div class="mt-3 flex items-center gap-2">
                @auth
                    @if(auth()->id() === $user->id)
                        <flux:dropdown>
                            <flux:button icon:trailing="chevron-down" variant="outline" size="sm">Manage</flux:button>
                            <flux:menu>
                                <flux:menu.item icon="pencil-square" href="{{ route('profile.edit') }}" wire:navigate>Edit Profile</flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item icon="key" href="{{ route('user-password.edit') }}" wire:navigate>Edit Password</flux:menu.item>
                                <flux:menu.separator />
                                <flux:menu.item icon="paint-brush" href="{{ route('appearance.edit') }}" wire:navigate>Appearance</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                        <flux:button
                            icon="clipboard-document-list"
                            variant="outline"
                            size="sm"
                            x-on:click="const u=`${location.origin}/user/{{ $user->id }}`; navigator.clipboard.writeText(u).then(()=>alert('Link copied'))"
                            title="{{ __('Copy link') }}"
                        >
                            {{ __('Copy Link') }}
                        </flux:button>
                    @else
                        <flux:button
                            icon="flag"
                            variant="outline"
                            size="sm"
                            x-on:click="alert('Report coming soon')"
                            title="{{ __('Report') }}"
                        >
                            {{ __('Report') }}
                        </flux:button>
                        <flux:button
                            icon="clipboard-document-list"
                            variant="outline"
                            size="sm"
                            x-on:click="const u=`${location.origin}/user/{{ $user->id }}`; navigator.clipboard.writeText(u).then(()=>alert('Link copied'))"
                            title="{{ __('Copy link') }}"
                        >
                            {{ __('Copy Link') }}
                        </flux:button>
                    @endif
                @else
                    <flux:button
                        icon="clipboard-document-list"
                        variant="outline"
                        size="sm"
                        x-on:click="const u=`${location.origin}/user/{{ $user->id }}`; navigator.clipboard.writeText(u).then(()=>alert('Link copied'))"
                        title="{{ __('Copy link') }}"
                    >
                        {{ __('Copy Link') }}
                    </flux:button>
                    <a href="{{ route('login', ['redirect' => ltrim(url()->current(), '/')]) }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-red-200 bg-red-50 px-4 py-2 text-red-700 hover:bg-red-100 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-7.38 12.78A2 2 0 0 0 4.53 20h14.94a2 2 0 0 0 1.73-3.36L13.82 3.86a2 2 0 0 0-3.53 0z" />
                        </svg>
                        Report
                    </a>
                @endauth
            </div>
            @endif
        </div>

        <!-- Mobile: compact header -->
        <div class="md:hidden flex items-start gap-4">
            @if($user->profile_image_url)
                <img src="{{ $user->profile_image_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover border border-gray-200 dark:border-gray-700">
            @else
                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900 dark:to-purple-900 flex items-center justify-center text-lg font-semibold text-gray-800 dark:text-white border border-gray-200 dark:border-gray-700">
                    {{ $user->initials() }}
                </div>
            @endif
            <div class="flex-1">
                <div class="flex items-center gap-2">
                    <h1 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                    @if($user->getBadgeIconPath())
                        <img src="{{ $user->getBadgeIconPath() }}" alt="{{ $user->getBadgeTooltip() }}" class="w-4 h-4" title="{{ $user->getBadgeTooltip() }}">
                    @endif
                </div>
                <div class="mt-1 flex items-center gap-4 text-xs text-gray-600 dark:text-gray-300">
                    <span><span class="font-semibold text-gray-900 dark:text-white">@format_count($commentsTotal)</span> Comments</span>
                    <span><span class="font-semibold text-gray-900 dark:text-white">@format_count($viewsTotal)</span> Views</span>
                    <span><span class="font-semibold text-gray-900 dark:text-white">@format_count($postsCount)</span> Posts</span>
                </div>
                @if($user->bio)
                    <p dir="auto" class="mt-2 text-sm text-gray-700 dark:text-gray-300">{{ $user->bio }}</p>
                @endif
                @if($user->profile_url)
                    <a href="{{ $user->profile_url }}" target="_blank" rel="nofollow noopener" class="mt-1 inline-flex items-center text-sm text-blue-600 hover:underline">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 me-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5h6v6M20 4 12 12M16.5 7.5A6 6 0 1 1 7.5 16.5" />
                        </svg>
                        {{ $user->profile_link_title ?: (parse_url($user->profile_url, PHP_URL_HOST) ?: $user->profile_url) }}
                    </a>
                @endif
                @if(!$user->isBanned())
                <div class="mt-3 flex items-center gap-2">
                    @auth
                        @if(auth()->id() === $user->id)
                            <flux:dropdown>
                                <flux:button icon:trailing="chevron-down" variant="outline" size="sm">Manage</flux:button>
                                <flux:menu>
                                    <flux:menu.item icon="pencil-square" href="{{ route('profile.edit') }}" wire:navigate>Edit Profile</flux:menu.item>
                                    <flux:menu.separator />
                                    <flux:menu.item icon="key" href="{{ route('user-password.edit') }}" wire:navigate>Edit Password</flux:menu.item>
                                    <flux:menu.separator />
                                    <flux:menu.item icon="paint-brush" href="{{ route('appearance.edit') }}" wire:navigate>Appearance</flux:menu.item>
                                </flux:menu>
                            </flux:dropdown>
                            <flux:button
                                icon="clipboard-document-list"
                                variant="outline"
                                size="sm"
                                x-on:click="const u=`${location.origin}/user/{{ $user->id }}`; navigator.clipboard.writeText(u).then(()=>alert('Link copied'))"
                                title="{{ __('Copy link') }}"
                            >
                                {{ __('Copy Link') }}
                            </flux:button>
                        @else
                            <flux:button
                                icon="flag"
                                variant="outline"
                                size="sm"
                                x-on:click="alert('Report coming soon')"
                                title="{{ __('Report') }}"
                            >
                                {{ __('Report') }}
                            </flux:button>
                            <flux:button
                                icon="clipboard-document-list"
                                variant="outline"
                                size="sm"
                                x-on:click="const u=`${location.origin}/user/{{ $user->id }}`; navigator.clipboard.writeText(u).then(()=>alert('Link copied'))"
                                title="{{ __('Copy link') }}"
                            >
                                {{ __('Copy Link') }}
                            </flux:button>
                        @endif
                    @else
                        <flux:button
                            icon="clipboard-document-list"
                            variant="outline"
                            size="sm"
                            x-on:click="const u=`${location.origin}/user/{{ $user->id }}`; navigator.clipboard.writeText(u).then(()=>alert('Link copied'))"
                            title="{{ __('Copy link') }}"
                        >
                            {{ __('Copy Link') }}
                        </flux:button>
                        <a href="{{ route('login', ['redirect' => ltrim(url()->current(), '/')]) }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-red-700 hover:bg-red-100 dark:border-red-800 dark:bg-red-900/30 dark:text-red-300 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86l-7.38 12.78A2 2 0 0 0 4.53 20h14.94a2 2 0 0 0 1.73-3.36L13.82 3.86a2 2 0 0 0-3.53 0z" />
                            </svg>
                            Report
                        </a>
                    @endauth
                </div>
                @endif
            </div>
        </div>
    </div>

    @if($user->isBanned())
        <div class="rounded-lg border border-red-200 bg-red-50 dark:border-red-800/60 dark:bg-red-900/20 p-4 text-red-700 dark:text-red-300">
            Posts are hidden because this account is banned.
        </div>
    @else
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
                <flux:icon name="user" class="mx-auto h-12 w-12 text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No posts yet</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    No posts by this user.
                </p>
            </div>
        @endforelse
    </div>
    @endif

    @if($posts->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $posts->links('vendor.pagination.tailwind') }}
        </div>
    @endif
</div>
