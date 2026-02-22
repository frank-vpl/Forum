<flux:dropdown position="bottom" align="start">
    <flux:sidebar.profile
        :name="auth()->user()->name"
        :initials="auth()->user()->initials()"
        :src="auth()->user()->profile_image_url ?? null"
        icon:trailing="chevrons-up-down"
        data-test="sidebar-menu-button"
    />

    <flux:menu>
        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
            <flux:avatar
                :name="auth()->user()->name"
                :initials="auth()->user()->initials()"
                :src="auth()->user()->profile_image_url ?? null"
            />
            <div class="grid flex-1 text-start text-sm leading-tight">
                <flux:heading class="truncate flex items-center gap-1">
                    {{ auth()->user()->name }}
                    @if(auth()->user()->getBadgeIconPath())
                        <img 
                            src="{{ auth()->user()->getBadgeIconPath() }}" 
                            alt="{{ auth()->user()->getBadgeTooltip() }}" 
                            class="w-4 h-4" 
                            title="{{ auth()->user()->getBadgeTooltip() }}"
                        >
                    @endif
                </flux:heading>
                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
            </div>
        </div>
        <flux:menu.separator />
        <flux:menu.radio.group>
            @if(in_array(auth()->user()->status, ['admin','verified']))
                <flux:menu.item icon="star" class="rounded-md bg-yellow-100 text-yellow-900 dark:bg-yellow-900 dark:text-yellow-100 cursor-default">
                    You are Premium
                </flux:menu.item>
            @else
                <flux:menu.item :href="route('premium.index')" icon="star" wire:navigate class="rounded-md bg-yellow-100 text-yellow-900 dark:bg-yellow-900 dark:text-yellow-100">
                    Buy Premium
                </flux:menu.item>
            @endif
            <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                {{ __('Settings') }}
            </flux:menu.item>
            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <flux:menu.item
                    as="button"
                    type="submit"
                    icon="arrow-right-start-on-rectangle"
                    class="w-full cursor-pointer"
                    onclick="if(!confirm('Are you sure you want to log out?')){ event.preventDefault(); return false; }"
                    data-test="logout-button"
                >
                    {{ __('Log Out') }}
                </flux:menu.item>
            </form>
        </flux:menu.radio.group>
    </flux:menu>
</flux:dropdown>
