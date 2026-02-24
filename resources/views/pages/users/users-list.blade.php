<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Users Directory</h1>
        <p class="text-gray-600 dark:text-gray-400">Browse all active members in our community</p>
    </div>

    <!-- Search Bar -->
    <div class="mb-6 max-w-md">
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <flux:icon name="magnifying-glass" class="w-4 h-4 text-gray-500" />
            </div>
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                placeholder="Search users by name or email..."
            >
        </div>
    </div>

    <!-- Users Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($users as $user)
            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 transition-all duration-200 hover:shadow-lg">
                @auth
                    @if(auth()->user()->isAdmin() && auth()->id() !== $user->id)
                        <div class="absolute top-3 right-3 z-30">
                            @if($user->status === 'admin')
                                <span class="inline-flex items-center rounded-md bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100 text-xs px-2 py-1">Admin</span>
                            @else
                                <select
                                    class="text-xs rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 px-2 py-1 shadow-sm"
                                    data-prev="{{ $user->status }}"
                                    onchange="(function(sel){const prev=sel.dataset.prev; const val=sel.value; let msg='Change status to '+val.charAt(0).toUpperCase()+val.slice(1)+'?'; if(val==='banned'){ msg='Change status to Banned? The user will be logged out and blocked.';} if(val==='admin'){ msg='Grant Admin role to this user?'; } if(!confirm(msg)){ sel.value=prev; return;} const compEl=sel.closest('[wire\\:id]'); if(compEl&&window.Livewire){ const comp=window.Livewire.find(compEl.getAttribute('wire:id')); comp && comp.call('updateStatus', {{ $user->id }}, val); sel.dataset.prev=val; }})(this)"
                                    aria-label="Change user status"
                                >
                                    <option value="user" {{ $user->status === 'user' ? 'selected' : '' }}>User</option>
                                    <option value="verified" {{ $user->status === 'verified' ? 'selected' : '' }}>Verified</option>
                                    <option value="banned" {{ $user->status === 'banned' ? 'selected' : '' }}>Banned</option>
                                    <option value="admin" {{ $user->status === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            @endif
                        </div>
                    @endif
                @endauth
                <div class="p-6">
                    <div class="flex flex-col items-center text-center">
                        <!-- Avatar with profile image or initials -->
                        <a href="{{ url('/user/'.$user->id) }}" wire:navigate class="relative mb-4 block">
                            @if($user->profile_image_url)
                                <img 
                                    src="{{ $user->profile_image_url }}" 
                                    alt="{{ $user->name }}" 
                                    class="w-16 h-16 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600"
                                >
                            @else
                                <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900 dark:to-purple-900 flex items-center justify-center text-lg font-semibold text-gray-800 dark:text-white border-2 border-gray-200 dark:border-gray-600">
                                    {{ $user->initials() }}
                                </div>
                            @endif
                            @if($user->getBadgeIconPath())
                                <img 
                                    src="{{ $user->getBadgeIconPath() }}" 
                                    alt="{{ $user->getBadgeTooltip() }}" 
                                    class="absolute -bottom-1 -right-1 w-6 h-6 bg-white dark:bg-gray-800 rounded-full p-0.5 border border-gray-200 dark:border-gray-700"
                                    title="{{ $user->getBadgeTooltip() }}"
                                >
                            @endif
                        </a>

                        <!-- User Info -->
                        <div class="w-full">
                            <a href="{{ url('/user/'.$user->id) }}" wire:navigate class="font-semibold text-gray-900 dark:text-white text-lg mb-1 flex items-center justify-center gap-1">
                                <span>{{ $user->name }}</span>
                            </a>
                            @if(auth()->user()?->isAdmin())
                                <div class="mt-1 mb-1 flex items-center justify-center">
                                    <flux:button
                                        icon="clipboard-document-list"
                                        variant="outline"
                                        size="xs"
                                        x-on:click="navigator.clipboard.writeText('{{ $user->email }}').then(()=>alert('Email copied'))"
                                        title="{{ __('Copy email') }}"
                                    />
                                </div>
                            @endif
                            
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($user->status === 'admin')
                                    bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100
                                @elseif($user->status === 'verified')
                                    bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100
                                @else
                                    bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                @endif
                            ">
                                {{ $this->getDisplayStatus($user->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <flux:icon name="user" class="mx-auto h-12 w-12 text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No users found</h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Try adjusting your search query.
                </p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $users->links('vendor.pagination.tailwind') }}
        </div>
    @endif
</div>
