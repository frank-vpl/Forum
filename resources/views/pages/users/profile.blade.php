<div>
    <div class="mb-6 space-y-4">
        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                @if($user->profile_image_url)
                    <img src="{{ $user->profile_image_url }}" alt="{{ $user->name }}" class="w-16 h-16 rounded-full object-cover border border-gray-200 dark:border-gray-700">
                @else
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900 dark:to-purple-900 flex items-center justify-center text-lg font-semibold text-gray-800 dark:text-white border border-gray-200 dark:border-gray-700">
                        {{ $user->initials() }}
                    </div>
                @endif
                <div>
                    <div class="flex items-center gap-2">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
                        @if($user->getBadgeIconPath())
                            <img src="{{ $user->getBadgeIconPath() }}" alt="{{ $user->getBadgeTooltip() }}" class="w-5 h-5" title="{{ $user->getBadgeTooltip() }}">
                        @endif
                        <button type="button" class="hidden md:inline-flex items-center gap-1 rounded-lg border border-gray-300 dark:border-gray-700 px-2.5 py-1 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
                            onclick="const u=`${location.origin}/user/{{ $user->id }}`; navigator.clipboard.writeText(u).then(()=>{ this.dataset.label=this.innerText; this.innerText='Copied'; setTimeout(()=>{ this.innerText=this.dataset.label||'Copy link'; },1500); });">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6h2.25A2.25 2.25 0 0 1 18 8.25v7.5A2.25 2.25 0 0 1 15.75 18h-2.25M10.5 6H8.25A2.25 2.25 0 0 0 6 8.25v7.5A2.25 2.25 0 0 0 8.25 18h2.25M8.25 12h7.5" />
                            </svg>
                            Copy link
                        </button>
                    </div>
                    <div class="mt-1 flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($user->status === 'admin')
                                bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100
                            @elseif($user->status === 'verified')
                                bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100
                            @else
                                bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                            @endif
                        ">
                            {{ $user->status === 'admin' ? 'Admin' : ($user->status === 'verified' ? 'Verified' : 'User') }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="hidden md:flex items-center gap-3">
                <div class="rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 px-4 py-2 flex items-center gap-3 text-sm">
                    <span class="inline-flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h7M4 5h16a1 1 0 0 1 1 1v12l-3-3H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1z" />
                        </svg>
                        {{ $commentsTotal }}
                    </span>
                    <span class="inline-flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s4.5-7.5 9.75-7.5S21.75 12 21.75 12s-4.5 7.5-9.75 7.5S2.25 12 2.25 12z" />
                            <circle cx="12" cy="12" r="3.25" />
                        </svg>
                        {{ $viewsTotal }}
                    </span>
                </div>
                @auth
                    @if(auth()->id() === $user->id)
                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a1.5 1.5 0 0 1 2.121 2.121l-9.9 9.9L6 16l.492-3.083 10.37-9.43zM19 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h6" />
                            </svg>
                            Edit Profile
                        </a>
                    @endif
                @endauth
            </div>
        </div>
        <div class="md:hidden grid grid-cols-3 gap-2">
            <div class="rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-3 text-center">
                <div class="text-xs text-gray-500 dark:text-gray-400">Comments</div>
                <div class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ $commentsTotal }}</div>
            </div>
            <div class="rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-3 text-center">
                <div class="text-xs text-gray-500 dark:text-gray-400">Views</div>
                <div class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ $viewsTotal }}</div>
            </div>
            <div class="rounded-xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-3 text-center">
                <div class="text-xs text-gray-500 dark:text-gray-400">Posts</div>
                <div class="mt-1 text-base font-semibold text-gray-900 dark:text-white">{{ $postsCount }}</div>
            </div>
        </div>
        <div class="md:hidden mt-3">
            <button type="button" class="inline-flex items-center gap-2 rounded-lg border border-gray-300 dark:border-gray-700 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 w-full justify-center hover:bg-gray-50 dark:hover:bg-gray-700"
                onclick="const u=`${location.origin}/user/{{ $user->id }}`; navigator.clipboard.writeText(u).then(()=>{ this.dataset.label=this.innerText; this.innerText='Copied'; setTimeout(()=>{ this.innerText=this.dataset.label||'Copy link'; },1500); });">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6h2.25A2.25 2.25 0 0 1 18 8.25v7.5A2.25 2.25 0 0 1 15.75 18h-2.25M10.5 6H8.25A2.25 2.25 0 0 0 6 8.25v7.5A2.25 2.25 0 0 0 8.25 18h2.25M8.25 12h7.5" />
                </svg>
                Copy link
            </button>
        </div>
        @auth
            @if(auth()->id() === $user->id)
                <div class="md:hidden">
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 w-full justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a1.5 1.5 0 0 1 2.121 2.121l-9.9 9.9L6 16l.492-3.083 10.37-9.43zM19 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h6" />
                        </svg>
                        Edit Profile
                    </a>
                </div>
            @endif
        @endauth
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
        @forelse($posts as $post)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 transition-all duration-200 hover:shadow-lg">
                <div class="p-5">
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
                                    {{ $u?->name ?? 'Unknown' }}
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
                        {{ \Illuminate\Support\Str::limit($post->title, 60) }}
                    </a>
                    <p dir="auto" class="text-sm text-gray-600 dark:text-gray-300 mb-4">
                        {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 45) }}
                    </p>

                    <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-300">
                        <div class="flex items-center gap-4">
                            @php($liked = in_array($post->id, $likedPostIds ?? []))
                            <span class="inline-flex items-center gap-1 {{ $liked ? 'text-red-600' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="{{ $liked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21c-4.5-3-9-6.5-9-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 4.5-4.5 8-9 11z" />
                                </svg>
                                {{ $post->likes_count }}
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s4.5-7.5 9.75-7.5S21.75 12 21.75 12s-4.5 7.5-9.75 7.5S2.25 12 2.25 12z" />
                                    <circle cx="12" cy="12" r="3.25" />
                                </svg>
                                {{ $post->views_count }}
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h7M4 5h16a1 1 0 0 1 1 1v12l-3-3H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1z" />
                                </svg>
                                {{ $post->comments_count }}
                            </span>
                        </div>
                        <a href="{{ url('/forum/'.$post->id) }}" class="inline-flex items-center gap-1 text-blue-600 hover:underline">Open</a>
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

    @if($posts->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $posts->links('vendor.pagination.tailwind') }}
        </div>
    @endif
</div>
