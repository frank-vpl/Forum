<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800 pb-16 lg:pb-0">
        <flux:sidebar sticky collapsible="mobile" class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="lg:hidden" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')" class="grid">
                    <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Forum') }}
                    </flux:sidebar.item>
                    @auth
                    <flux:sidebar.item
                        icon="user"
                        :href="route('user.show', ['id' => auth()->id()])"
                        :current="(request()->routeIs('user.show') && ((int) request()->route('id') === (int) auth()->id()))"
                        wire:navigate
                    >
                        {{ __('Profile') }}
                    </flux:sidebar.item>
                    @else
                    <flux:sidebar.item icon="user" :href="route('login', ['redirect' => ltrim(route('dashboard', absolute: false), '/')])" wire:navigate>
                        {{ __('Profile') }}
                    </flux:sidebar.item>
                    @endauth
                    @auth
                    <flux:sidebar.item icon="bell" :href="route('notifications.index')" :current="request()->routeIs('notifications.index')" wire:navigate>
                        {{ __('Notifications') }}
                        @php($notifCount = \App\Models\Notification::where('user_id', auth()->id())->whereNull('seen_at')->count())
                        @if($notifCount > 0)
                            <span class="ms-2 inline-flex items-center justify-center rounded-full bg-red-600 text-white text-[10px] px-1.5 min-w-[18px] h-[18px]">{{ $notifCount >= 100 ? '+99' : $notifCount }}</span>
                        @endif
                    </flux:sidebar.item>
                                        <flux:sidebar.item icon="users" :href="route('users.index')" :current="request()->routeIs('users.index')" wire:navigate>
                        {{ __('Users') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="star" :href="route('premium.index')" :current="request()->routeIs('premium.index')" wire:navigate>
                        {{ __('Premium') }}
                    </flux:sidebar.item>
                    @else
                    <flux:sidebar.item icon="bell" :href="route('login', ['redirect' => ltrim(route('notifications.index', absolute: false), '/')])" wire:navigate>
                        {{ __('Notifications') }}
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="star" :href="route('premium.index')" wire:navigate class="rounded-md bg-yellow-100 text-yellow-900 dark:bg-yellow-900 dark:text-yellow-100">
                        {{ __('Premium') }}
                    </flux:sidebar.item>
                    @endauth
                    <div class="px-3 pt-3">
                        <div class="mb-1 text-[11px] font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">
                            {{ __('Appearance') }}
                        </div>
                        <div x-data>
                            <select
                                x-model="$flux.appearance"
                                class="w-full rounded-lg border border-zinc-200 bg-white px-2.5 py-2 text-sm text-zinc-900 shadow-sm dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-100"
                            >
                                <option value="light">{{ __('Light') }}</option>
                                <option value="dark">{{ __('Dark') }}</option>
                                <option value="system">{{ __('System') }}</option>
                            </select>
                        </div>
                    </div>
                </flux:sidebar.group>
                
                <div class="px-3 pt-3">
                    <details class="group">
                        <summary class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-zinc-900 hover:bg-zinc-100 dark:text-white dark:hover:bg-zinc-800 cursor-pointer">
                            <span class="inline-flex items-center gap-2">
                                <flux:icon name="scale" class="w-5 h-5 text-zinc-600 dark:text-zinc-300" />
                                {{ __('Legal') }}
                            </span>
                            <flux:icon name="chevron-down" class="w-5 h-5 text-zinc-600 dark:text-zinc-300 transition-transform group-open:rotate-180" />
                        </summary>
                        <div class="mt-2 space-y-1">
                            <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2 text-sm {{ (request()->routeIs('home') || request()->is('home')) ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800' }}">
                                Home
                            </a>
                            <a href="{{ url('/about') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->is('about') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800' }}">
                                About
                            </a>
                            <a href="{{ url('/terms') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->is('terms') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800' }}">
                                Terms
                            </a>
                            <a href="{{ url('/privacy') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->is('privacy') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800' }}">
                                Privacy
                            </a>
                            <a href="{{ url('/faq') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->is('faq') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800' }}">
                                FAQ
                            </a>
                        </div>
                    </details>
                </div>
            </flux:sidebar.nav>

            <flux:spacer />

            @auth
                <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
            @else
                <div class="hidden lg:flex items-center p-4">
                    <a href="{{ !request()->route() ? route('login', ['redirect' => ltrim(route('dashboard', absolute: false), '/')]) : (trim(request()->getPathInfo(), '/') === '' ? route('login') : route('login', ['redirect' => ltrim(request()->getRequestUri(), '/')])) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3.5 py-2 text-white text-sm hover:bg-blue-700">
                        {{ __('Log in') }}
                    </a>
                </div>
            @endauth
        </flux:sidebar>


        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            @auth
            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    :src="auth()->user()->profile_image_url ?? null"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
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
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                                @if(!in_array(auth()->user()->status, ['admin','verified']))
                                    <flux:menu.item :href="route('premium.index')" icon="star" wire:navigate>
                                        Buy Premium
                                    </flux:menu.item>
                                    <flux:menu.separator />
                                @endif
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Settings') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

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
                </flux:menu>
            </flux:dropdown>
            @else
            <a href="{{ !request()->route() ? route('login', ['redirect' => ltrim(route('dashboard', absolute: false), '/')]) : (trim(request()->getPathInfo(), '/') === '' ? route('login') : route('login', ['redirect' => ltrim(request()->getRequestUri(), '/')])) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3.5 py-2 text-white text-sm hover:bg-blue-700">
                {{ __('Log in') }}
            </a>
            @endauth
        </flux:header>

        {{ $slot }}

        <nav class="fixed bottom-0 inset-x-0 z-50 border-t border-zinc-200 bg-white/90 dark:border-zinc-700 dark:bg-zinc-900/90 backdrop-blur supports-[backdrop-filter]:bg-white/60 dark:supports-[backdrop-filter]:bg-zinc-900/60 lg:hidden">
            <div class="mx-auto max-w-xl">
                <div class="grid grid-cols-5">
                    <a
                        href="{{ route('dashboard') }}"
                        wire:navigate
                        class="flex flex-col items-center justify-center gap-1 py-2 {{ request()->routeIs('dashboard') ? 'text-blue-600 dark:text-blue-400' : 'text-zinc-700 dark:text-zinc-200' }}"
                    >
                        <flux:icon name="home" class="w-6 h-6" />
                        <span class="text-[11px] leading-tight">Forum</span>
                    </a>
                    <a
                        href="{{ route('users.index') }}"
                        wire:navigate
                        class="flex flex-col items-center justify-center gap-1 py-2 {{ request()->routeIs('users.index') ? 'text-blue-600 dark:text-blue-400' : 'text-zinc-700 dark:text-zinc-200' }}"
                    >
                        <flux:icon name="users" class="w-6 h-6" />
                        <span class="text-[11px] leading-tight">Users</span>
                    </a>
                    @auth
                    <a
                        href="{{ route('forum.new') }}"
                        class="flex items-center justify-center py-2"
                    >
                        <span class="relative -top-3 inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-600 text-white shadow-lg">
                            <flux:icon name="plus" class="w-6 h-6" />
                        </span>
                    </a>
                    @else
                    <a
                        href="{{ route('login', ['redirect' => ltrim(route('forum.new', absolute: false), '/')]) }}"
                        class="flex items-center justify-center py-2"
                    >
                        <span class="relative -top-3 inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-600 text-white shadow-lg">
                            <flux:icon name="plus" class="w-6 h-6" />
                        </span>
                    </a>
                    @endauth
                    @auth
                    <a
                        href="{{ route('notifications.index') }}"
                        wire:navigate
                        class="flex flex-col items-center justify-center gap-1 py-2 {{ request()->routeIs('notifications.index') ? 'text-blue-600 dark:text-blue-400' : 'text-zinc-700 dark:text-zinc-200' }}"
                    >
                        <div class="relative">
                            <flux:icon name="bell" class="w-6 h-6" />
                            @php($notifCount = \App\Models\Notification::where('user_id', auth()->id())->whereNull('seen_at')->count())
                            @if($notifCount > 0)
                                <span class="absolute -top-1 -right-1 inline-flex items-center justify-center rounded-full bg-red-600 text-white text-[10px] px-1 min-w-[16px] h-[16px]">{{ $notifCount >= 100 ? '+99' : $notifCount }}</span>
                            @endif
                        </div>
                        <span class="text-[11px] leading-tight">Notifications</span>
                    </a>
                    <a
                        href="{{ route('user.show', ['id' => auth()->id()]) }}"
                        wire:navigate
                        class="flex flex-col items-center justify-center gap-1 py-2 {{
                            (request()->routeIs('user.show') && ((int) request()->route('id') === (int) auth()->id()))
                                ? 'text-blue-600 dark:text-blue-400'
                                : 'text-zinc-700 dark:text-zinc-200'
                        }}"
                    >
                        <flux:icon name="user" class="w-6 h-6" />
                        <span class="text-[11px] leading-tight">Profile</span>
                    </a>
                    @else
                    <a
                        href="{{ route('login', ['redirect' => ltrim(route('notifications.index', absolute: false), '/')]) }}"
                        class="flex flex-col items-center justify-center gap-1 py-2 text-zinc-700 dark:text-zinc-200"
                    >
                        <flux:icon name="bell" class="w-6 h-6" />
                        <span class="text-[11px] leading-tight">Notifications</span>
                    </a>
                    <a
                        href="{{ route('login', ['redirect' => ltrim(route('dashboard', absolute: false), '/')]) }}"
                        class="flex flex-col items-center justify-center gap-1 py-2 text-zinc-700 dark:text-zinc-200"
                    >
                        <flux:icon name="user" class="w-6 h-6" />
                        <span class="text-[11px] leading-tight">Profile</span>
                    </a>
                    @endauth
                </div>
            </div>
        </nav>

        <style>
        .emoji-flag{display:inline !important;height:1em;width:auto;vertical-align:-0.2em;margin:0 !important;padding:0 !important;border:0 !important;border-radius:0 !important}
        </style>
        <script>
        (() => {
            const FLAG = '🇮🇷';
            const IMG_URL = '{{ asset('iran.png') }}';
            const SKIP_TAGS = new Set(['SCRIPT','STYLE','CODE','PRE','NOSCRIPT','TEXTAREA']);
            let scheduled = false;
            function replaceInNode(node) {
                const text = node.nodeValue;
                if (!text || text.indexOf(FLAG) === -1) return;
                const parts = text.split(FLAG);
                if (parts.length === 1) return;
                const frag = document.createDocumentFragment();
                for (let i = 0; i < parts.length; i++) {
                    if (parts[i]) frag.appendChild(document.createTextNode(parts[i]));
                    if (i < parts.length - 1) {
                        const img = document.createElement('img');
                        img.src = IMG_URL;
                        img.alt = FLAG;
                        img.style.height = '1.2em';
                        img.style.width = 'auto';
                        img.style.verticalAlign = '-0.2em';
                        img.className = 'emoji-flag emoji-flag-ir';
                        frag.appendChild(img);
                    }
                }
                node.parentNode && node.parentNode.replaceChild(frag, node);
            }
            function run(root) {
                if (!root) return;
                const walker = document.createTreeWalker(root, NodeFilter.SHOW_TEXT, {
                    acceptNode(n) {
                        const p = n.parentNode;
                        if (!p || SKIP_TAGS.has(p.nodeName)) return NodeFilter.FILTER_REJECT;
                        return n.nodeValue && n.nodeValue.indexOf(FLAG) !== -1
                            ? NodeFilter.FILTER_ACCEPT
                            : NodeFilter.FILTER_SKIP;
                    }
                });
                let node;
                const targets = [];
                while ((node = walker.nextNode())) targets.push(node);
                for (const n of targets) replaceInNode(n);
            }
            const init = () => run(document.body);
            document.addEventListener('DOMContentLoaded', init);
            document.addEventListener('livewire:navigated', init);
            const obs = new MutationObserver(() => {
                if (scheduled) return;
                scheduled = true;
                requestAnimationFrame(() => {
                    scheduled = false;
                    run(document.body);
                });
            });
            obs.observe(document.documentElement, { childList: true, subtree: true });
        })();
        </script>

        @fluxScripts
    </body>
</html>
