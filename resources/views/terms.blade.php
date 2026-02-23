<x-layouts.home :title="__('Terms')">
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-blue-50 via-white to-white dark:from-zinc-800 dark:via-zinc-900 dark:to-zinc-900"></div>
        <div class="mx-auto max-w-7xl px-6 py-16 lg:py-24">
            <header class="fixed left-0 right-0 top-0 z-40">
                <div class="mx-auto max-w-7xl px-6 pt-4">
                    <div class="flex items-center justify-between rounded-full border border-zinc-200 bg-white/80 px-4 py-2 shadow-sm backdrop-blur supports-[backdrop-filter]:bg-white/60 dark:border-zinc-700 dark:bg-zinc-900/60">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('home') }}" class="flex items-center gap-2">
                                <img src="{{ asset('logo.svg') }}" alt="{{ config('app.name') }}" class="h-7 w-auto dark:brightness-90">
                                <span class="sr-only">{{ config('app.name') }}</span>
                            </a>
                            <div class="relative" x-data>
                            <details x-ref="appearanceMenu" class="relative group">
                                <summary class="list-none inline-flex items-center gap-2 h-7 rounded-full border border-zinc-300 px-2 py-0 text-sm text-zinc-900 hover:bg-zinc-100 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 cursor-pointer self-center">
                                    <template x-if="$flux.appearance === 'dark'">
                                        <flux:icon name="moon" class="h-5 w-5" />
                                    </template>
                                    <template x-if="$flux.appearance === 'light'">
                                        <flux:icon name="sun" class="h-5 w-5" />
                                    </template>
                                    <template x-if="$flux.appearance === 'system'">
                                        <flux:icon name="computer-desktop" class="h-5 w-5" />
                                    </template>
                                </summary>
                                <div class="absolute left-0 mt-2 w-56 origin-top-left rounded-xl border border-zinc-200 bg-white p-2 shadow-lg ring-1 ring-black/5 dark:border-zinc-700 dark:bg-zinc-900">
                                    <div class="px-3 pb-2 text-[11px] font-semibold uppercase tracking-wide text-zinc-500 dark:text-zinc-400">Appearance</div>
                                    <a href="#" x-on:click.prevent="$flux.appearance = 'light'; $refs.appearanceMenu.open = false" class="block rounded-lg px-3 py-2 text-sm" :class="$flux.appearance === 'light' ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800'">Light</a>
                                    <a href="#" x-on:click.prevent="$flux.appearance = 'dark'; $refs.appearanceMenu.open = false" class="block rounded-lg px-3 py-2 text-sm" :class="$flux.appearance === 'dark' ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800'">Dark</a>
                                    <a href="#" x-on:click.prevent="$flux.appearance = 'system'; $refs.appearanceMenu.open = false" class="block rounded-lg px-3 py-2 text-sm" :class="$flux.appearance === 'system' ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800'">System</a>
                                </div>
                            </details>
                            </div>
                        </div>
                        <nav class="hidden md:flex items-center gap-6 text-sm">
                            <a href="{{ route('home') }}" class="{{ (request()->routeIs('home') || request()->is('home')) ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-zinc-700 hover:text-zinc-900 dark:text-zinc-200 dark:hover:text-white' }}">Home</a>
                            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-zinc-700 hover:text-zinc-900 dark:text-zinc-200 dark:hover:text-white' }}">Forum</a>
                            <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.index') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-zinc-700 hover:text-zinc-900 dark:text-zinc-200 dark:hover:text-white' }}">Users Directory</a>
                            <a href="{{ url('/terms') }}" class="{{ request()->is('terms') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-zinc-700 hover:text-zinc-900 dark:text-zinc-200 dark:hover:text-white' }}">Terms</a>
                            <a href="{{ url('/privacy') }}" class="{{ request()->is('privacy') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-zinc-700 hover:text-zinc-900 dark:text-zinc-200 dark:hover:text-white' }}">Privacy</a>
                            <a href="{{ url('/about') }}" class="{{ request()->is('about') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-zinc-700 hover:text-zinc-900 dark:text-zinc-200 dark:hover:text-white' }}">About</a>
                            <a href="{{ route('faq') }}" class="{{ request()->routeIs('faq') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-zinc-700 hover:text-zinc-900 dark:text-zinc-200 dark:hover:text-white' }}">FAQ</a>
                        </nav>
                        <div class="hidden md:block">
                            @guest
                                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 rounded-full bg-blue-600 px-4 py-2 text-white text-sm hover:bg-blue-700">
                                    Log in
                                </a>
                            @else
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-full border border-zinc-300 px-4 py-2 text-sm text-zinc-900 hover:bg-zinc-100 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-700/50">
                                    Open Forum
                                </a>
                            @endguest
                        </div>
                        <div class="md:hidden">
                            <details class="relative group">
                                <summary class="list-none inline-flex items-center gap-2 rounded-full border border-zinc-300 px-3 py-1.5 text-sm text-zinc-900 hover:bg-zinc-100 dark:border-zinc-700 dark:text-white dark:hover:bg-zinc-800 cursor-pointer">
                                    <flux:icon name="bars-2" class="h-5 w-5" />
                                    Menu
                                </summary>
                                <div class="absolute right-0 mt-2 w-56 origin-top-right rounded-xl border border-zinc-200 bg-white p-2 shadow-lg ring-1 ring-black/5 dark:border-zinc-700 dark:bg-zinc-900">
                                    <a href="{{ route('home') }}" class="block rounded-lg px-3 py-2 text-sm {{ (request()->routeIs('home') || request()->is('home')) ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800' }}">Home</a>
                                    <a href="{{ route('dashboard') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('dashboard') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800' }}">Forum</a>
                                    <a href="{{ route('users.index') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('users.index') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 pipeline dark:hover:bg-zinc-800' }}">Users Directory</a>
                                    <a href="{{ url('/terms') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->is('terms') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800' }}">Terms</a>
                                    <a href="{{ url('/privacy') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->is('privacy') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800' }}">Privacy</a>
                                    <a href="{{ url('/about') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->is('about') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800' }}">About</a>
                                    <a href="{{ route('faq') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('faq') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800' }}">FAQ</a>
                                    <div class="my-2 h-px bg-zinc-200 dark:bg-zinc-700"></div>
                                    @guest
                                        <a href="{{ route('login') }}" class="block rounded-lg px-3 py-2 text-sm text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800">Log in</a>
                                    @else
                                        <a href="{{ route('dashboard') }}" class="block rounded-lg px-3 py-2 text-sm text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800">Open Forum</a>
                                    @endguest
                                </div>
                            </details>
                        </div>
                    </div>
                </div>
            </header>

            <div class="mt-24 sm:mt-28">
                <div class="text-center">
                    <h1 class="text-4xl md:text-6xl font-extrabold tracking-tight text-zinc-900 dark:text-white">
                        Terms of Use
                    </h1>
                    <p class="mx-auto mt-5 max-w-3xl text-base sm:text-lg text-zinc-600 dark:text-zinc-300">
                        Please read these terms carefully. By using {{ config('app.name') }}, you agree to these rules of respectful, lawful participation.
                    </p>
                </div>

                <div class="mx-auto mt-10 max-w-4xl space-y-10 text-zinc-800 dark:text-zinc-200">
                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">1. Acceptance</h2>
                        <p class="mt-3 text-xl leading-9">
                            Using the platform means you accept these Terms of Use and our Privacy Policy. If you do not agree, do not use the service.
                        </p>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">2. Purpose</h2>
                        <p class="mt-3 text-xl leading-9">
                            {{ config('app.name') }} exists to support free, respectful discussion for patriots who stand with the Pahlavi vision and a free, democratic Iran. Content should remain civil, constructive, and focused on truth.
                        </p>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">3. Accounts</h2>
                        <p class="mt-3 text-xl leading-9">
                            You must provide accurate information. Keep credentials secure. Verification and optional two‑factor authentication help protect your account. We may suspend or ban accounts that violate these terms or community guidelines.
                        </p>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">4. Your Content</h2>
                        <p class="mt-3 text-xl leading-9">
                            You own your content. By posting, you grant us a non‑exclusive license to display and host it in connection with the service. You are responsible for ensuring content is lawful and safe. You may delete your posts; caching or backups may persist briefly.
                        </p>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">5. Prohibited Conduct</h2>
                        <p class="mt-3 text-xl leading-9">
                            Do not post illegal content, threats, harassment, hate speech, spam, or coordinated disinformation. Do not attempt to hack, scrape, or disrupt the service. Violations may result in removal of content, account sanctions, or bans.
                        </p>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">6. Moderation</h2>
                        <p class="mt-3 text-xl leading-9">
                            We reserve the right to moderate content and behavior to protect users and maintain civil discourse. Decisions may consider repeated violations, safety risks, and legal obligations. Appeals may be considered at our discretion.
                        </p>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">7. Notices and Takedowns</h2>
                        <p class="mt-3 text-xl leading-9">
                            If you believe content violates law or rights, report it through community channels. We will review good‑faith notices and act as appropriate under applicable law and our policies.
                        </p>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">8. Service Changes</h2>
                        <p class="mt-3 text-xl leading-9">
                            Features may change or be removed. We strive for uptime and performance but do not guarantee uninterrupted availability. We may update these terms as the platform evolves.
                        </p>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">9. Disclaimers</h2>
                        <p class="mt-3 text-xl leading-9">
                            The service is provided “as is” without warranties. We are not liable for user content, third‑party links, or any reliance on information posted by users. Use caution and verify sources.
                        </p>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">10. Limitation of Liability</h2>
                        <p class="mt-3 text-xl leading-9">
                            To the fullest extent permitted by law, {{ config('app.name') }} and contributors are not liable for indirect, incidental, or consequential damages arising from your use of the service.
                        </p>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">11. Termination</h2>
                        <p class="mt-3 text-xl leading-9">
                            You may stop using the service at any time. We may suspend or terminate access for violations of these terms or legal obligations. Upon termination, certain sections may continue to apply.
                        </p>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">12. Governing Law</h2>
                        <p class="mt-3 text-xl leading-9">
                            These terms are governed by applicable laws where the service is operated and hosted. Disputes will be resolved under those laws and venues.
                        </p>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-zinc-900 dark:text-white">13. Contact</h2>
                        <p class="mt-3 text-xl leading-9">
                            For questions about these terms, reach out via community channels or open an issue on our GitHub repository.
                        </p>
                    </div>
                </div>

                <div class="mt-16 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 p-8 text-center text-white">
                    <h3 class="text-2xl font-semibold">Ready to join the conversation?</h3>
                    <p class="mt-2 text-sm opacity-90">Create an account in seconds and start posting.</p>
                    <div class="mt-4 flex flex-col sm:flex-row items-center justify-center gap-3">
                        @guest
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-lg bg-white px-6 py-3 text-blue-700 hover:bg-blue-50">
                                Create account
                            </a>
                            <a href="{{ url('/auth/google').('?redirect='.urlencode(ltrim(route('dashboard', absolute: false), '/'))) }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-white px-5 py-2.5 text-blue-700 hover:bg-blue-50">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" class="h-4 w-4">
                                    <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12   c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.611,8.337,6.306,14.691z"/>
                                    <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,16.087,18.961,14,24,14c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657   C34.046,6.053,29.268,4,24,4C16.318,4,9.611,8.337,6.306,14.691z"/>
                                    <path fill="#4CAF50" d="M24,44c5.164,0,9.86-1.977,13.409-5.195l-6.19-5.238C29.297,35.091,26.784,36,24,36   c-5.189,0-9.607-3.313-11.267-7.946l-6.51,5.016C9.484,39.556,16.227,44,24,44z"/>
                                    <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.794,2.241-2.231,4.166-4.094,5.569   c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.865,40.031,44,35,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                                </svg>
                                Continue with Google
                            </a>
                        @else
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center rounded-lg bg-white px-6 py-3 text-blue-700 hover:bg-blue-50">
                                Open Forum
                            </a>
                        @endguest
                    </div>
                </div>
            </div>

            <footer class="mt-16">
                <div class="rounded-2xl border border-zinc-200 bg-white p-8 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 text-sm">
                        <div>
                            <div class="flex items-center gap-2">
                                <img src="{{ asset('logo.svg') }}" alt="{{ config('app.name') }}" class="h-6 w-auto dark:brightness-90">
                                <span class="font-semibold text-zinc-900 dark:text-white">{{ config('app.name') }}</span>
                            </div>
                            <p class="mt-3 text-zinc-600 dark:text-zinc-300">
                                Freedom community forum for Iran — simple, fast, and privacy‑minded.
                            </p>
                            <div class="mt-4">
                                <a href="https://github.com/frank-vpl/Forum" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-lg bg-zinc-900 px-4 py-2 text-white hover:bg-zinc-800 dark:bg-zinc-700 dark:hover:bg-zinc-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 .5a12 12 0 0 0-3.79 23.4c.6.1.82-.27.82-.6v-2.2c-3.34.72-4.04-1.61-4.04-1.61-.55-1.4-1.34-1.77-1.34-1.77-1.1-.75.08-.74.08-.74 1.22.09 1.86 1.26 1.86 1.26 1.08 1.85 2.83 1.32 3.52 1 .1-.78.42-1.32.76-1.62-2.66-.3-5.47-1.34-5.47-5.98 0-1.32.47-2.4 1.24-3.25-.12-.3-.54-1.52.12-3.18 0 0 1.02-.33 3.34 1.24a11.5 11.5 0 0 1 6.08 0c2.32-1.57 3.34-1.24 3.34-1.24.66 1.66.24 2.88.12 3.18.77.85 1.24 1.93 1.24 3.25 0 4.65-2.81 5.67-5.49 5.97.43.37.81 1.1.81 2.22v3.29c0 .33.22.71.83.6A12 12 0 0 0 12 .5z"/>
                                    </svg>
                                    View on GitHub
                                </a>
                            </div>
                        </div>
                        <div>
                            <h5 class="font-semibold text-zinc-900 dark:text-white">Explore</h5>
                            <ul class="mt-3 space-y-2">
                                <li>
                                    <a href="{{ route('dashboard') }}" class="group inline-flex items-center gap-2 rounded-lg px-1 py-1 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-700/50 dark:hover:text-white">
                                        <flux:icon name="home" class="h-4 w-4 opacity-70 group-hover:opacity-100" />
                                        Forum
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('users.index') }}" class="group inline-flex items-center gap-2 rounded-lg px-1 py-1 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-700/50 dark:hover:text-white">
                                        <flux:icon name="users" class="h-4 w-4 opacity-70 group-hover:opacity-100" />
                                        Users Directory
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('forum.new') }}" class="group inline-flex items-center gap-2 rounded-lg px-1 py-1 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-700/50 dark:hover:text-white">
                                        <flux:icon name="sparkles" class="h-4 w-4 opacity-70 group-hover:opacity-100" />
                                        New Post
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('faq') }}" class="group inline-flex items-center gap-2 rounded-lg px-1 py-1 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-700/50 dark:hover:text-white">
                                        <flux:icon name="question-mark-circle" class="h-4 w-4 opacity-70 group-hover:opacity-100" />
                                        FAQ
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h5 class="font-semibold text-zinc-900 dark:text-white">Account</h5>
                            <ul class="mt-3 space-y-2">
                                @guest
                                    <li>
                                        <a href="{{ route('login') }}" class="group inline-flex items-center gap-2 rounded-lg px-1 py-1 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-700/50 dark:hover:text-white">
                                            <flux:icon name="arrow-right-start-on-rectangle" class="h-4 w-4 opacity-70 group-hover:opacity-100" />
                                            Log in
                                        </a>
                                    </li>
                                    @if (Route::has('register'))
                                    <li>
                                        <a href="{{ route('register') }}" class="group inline-flex items-center gap-2 rounded-lg px-1 py-1 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-700/50 dark:hover:text-white">
                                            <flux:icon name="user-plus" class="h-4 w-4 opacity-70 group-hover:opacity-100" />
                                            Register
                                        </a>
                                    </li>
                                    @endif
                                @else
                                    <li>
                                        <a href="{{ route('dashboard') }}" class="group inline-flex items-center gap-2 rounded-lg px-1 py-1 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-700/50 dark:hover:text-white">
                                            <flux:icon name="arrow-uturn-right" class="h-4 w-4 opacity-70 group-hover:opacity-100" />
                                            Go to Dashboard
                                        </a>
                                    </li>
                                @endguest
                            </ul>
                        </div>
                        <div>
                            <h5 class="font-semibold text-zinc-900 dark:text-white">Project</h5>
                            <ul class="mt-3 space-y-2">
                                <li>
                                    <a href="{{ url('/terms') }}" class="group inline-flex items-center gap-2 rounded-lg px-1 py-1 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-700/50 dark:hover:text-white">
                                        <flux:icon name="document-text" class="h-4 w-4 opacity-70 group-hover:opacity-100" />
                                        Terms
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('/privacy') }}" class="group inline-flex items-center gap-2 rounded-lg px-1 py-1 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-700/50 dark:hover:text-white">
                                        <flux:icon name="shield-check" class="h-4 w-4 opacity-70 group-hover:opacity-100" />
                                        Privacy
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('/about') }}" class="group inline-flex items-center gap-2 rounded-lg px-1 py-1 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-700/50 dark:hover:text-white">
                                        <flux:icon name="information-circle" class="h-4 w-4 opacity-70 group-hover:opacity-100" />
                                        About
                                    </a>
                                </li>
                                <li>
                                    <a href="https://github.com/frank-vpl/Forum" target="_blank" rel="noopener" class="group inline-flex items-center gap-2 rounded-lg px-1 py-1 text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-zinc-700/50 dark:hover:text-white">
                                        <flux:icon name="code-bracket" class="h-4 w-4 opacity-70 group-hover:opacity-100" />
                                        GitHub Repo
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-3 border-t border-zinc-200 pt-6 dark:border-zinc-700">
                        <div class="text-xs text-zinc-500 dark:text-zinc-400">
                            © {{ date('Y') }} {{ config('app.name') }} • Open source (GPL‑3.0)
                        </div>
                        <div class="flex items-center gap-4 text-xs">
                            <a href="{{ url('/terms') }}" class="text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white">Terms</a>
                            <span class="text-zinc-400">•</span>
                            <a href="{{ url('/privacy') }}" class="text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white">Privacy</a>
                            <span class="text-zinc-400">•</span>
                            <a href="{{ url('/about') }}" class="text-zinc-600 hover:text-zinc-900 dark:text-zinc-300 dark:hover:text-white">About</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </section>
</x-layouts.home>
