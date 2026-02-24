<div>
    <div class="mb-4 flex items-center gap-3">
        <flux:input placeholder="Search by name..." wire:model.live.debounce.300ms="q" class="w-full" />
    </div>

    <div class="mb-6 flex items-center gap-2">
        <a href="{{ route('user.followers', ['id' => $userId]) }}" wire:navigate>
            <flux:button variant="{{ ($mode ?? 'followers') === 'followers' ? 'primary' : 'outline' }}" size="sm">Followers</flux:button>
        </a>
        <a href="{{ route('user.following', ['id' => $userId]) }}" wire:navigate>
            <flux:button variant="{{ ($mode ?? 'followers') === 'following' ? 'primary' : 'outline' }}" size="sm">Following</flux:button>
        </a>
    </div>

    @if($users->count() === 0)
        <div class="flex flex-col items-center justify-center text-center py-16">
            <div class="rounded-full p-4 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900 dark:to-purple-900 border border-gray-200 dark:border-gray-700">
                <flux:icon name="users" class="h-10 w-10 text-blue-600 dark:text-blue-300" />
            </div>
            <div class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No users found</div>
            <div class="text-sm text-gray-600 dark:text-gray-300">Try adjusting your search or check back later.</div>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($users as $u)
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4 flex items-center gap-3">
                    @if($u->profile_image_url)
                        <a href="{{ url('/user/'.$u->id) }}" wire:navigate>
                            <img src="{{ $u->profile_image_url }}" alt="{{ $u->name }}" class="w-12 h-12 rounded-full object-cover border border-gray-200 dark:border-gray-700">
                        </a>
                    @else
                        <a href="{{ url('/user/'.$u->id) }}" wire:navigate>
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900 dark:to-purple-900 flex items-center justify-center text-sm font-semibold text-gray-800 dark:text-white border border-gray-200 dark:border-gray-700">
                                {{ $u->initials() }}
                            </div>
                        </a>
                    @endif
                    <div class="flex-1">
                        <div class="flex items-center gap-2">
                            <a href="{{ url('/user/'.$u->id) }}" wire:navigate class="font-medium text-gray-900 dark:text-white hover:underline">
                                {{ $u->name }}
                            </a>
                            @if($u->getBadgeIconPath())
                                <img src="{{ $u->getBadgeIconPath() }}" alt="{{ $u->getBadgeTooltip() }}" class="w-4 h-4" title="{{ $u->getBadgeTooltip() }}">
                            @endif
                        </div>
                        <div class="text-xs text-gray-600 dark:text-gray-300">
                            <span class="font-semibold text-gray-900 dark:text-white">@format_count($u->followers()->count())</span> followers
                        </div>
                    </div>
                    @auth
                        @php
                            $isFollowing = auth()->user()->hasFollowedId($u->id);
                            $theyFollowMe = auth()->user()->isFollowedById($u->id);
                        @endphp
                        @if($isFollowing)
                            <flux:button variant="outline" size="sm" wire:click="unfollowUser({{ $u->id }})">Following</flux:button>
                        @elseif(auth()->id() !== $u->id)
                            <flux:button variant="primary" size="sm" wire:click="followUser({{ $u->id }})">{{ $theyFollowMe ? 'Follow Back' : 'Follow' }}</flux:button>
                        @endif
                    @endauth
                </div>
            @endforeach
        </div>

        @if($hasMore)
            <div
                x-data
                x-intersect="$wire.nextPage()"
                class="flex items-center justify-center p-6 text-sm text-gray-500 dark:text-gray-400"
            >
                Loading more...
            </div>
        @endif
    @endif
</div>
