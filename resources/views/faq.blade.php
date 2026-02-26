<x-layouts.home :title="__('FAQ')">
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-blue-50 via-white to-white dark:from-zinc-800 dark:via-zinc-900 dark:to-zinc-900"></div>
        <div class="mx-auto max-w-7xl px-6 py-16 lg:py-24">
            <header class="fixed left-0 right-0 top-0 z-40">
                <div class="mx-auto max-w-7xl px-6 pt-4">
                    <div class="flex items-center justify-between rounded-full border border-zinc-200 bg-white/80 px-4 py-2 shadow-sm backdrop-blur supports-[backdrop-filter]:bg-white/60 dark:border-zinc-700 dark:bg-zinc-900/60">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('home') }}" class="flex items-center gap-2">
                                <img src="{{ asset('iranguard.png') }}" alt="{{ config('app.name') }}" class="w-8 dark:brightness-90">
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
                            <a href="{{ route('about') }}" class="{{ request()->is('about') ? 'text-blue-600 dark:text-blue-400 font-semibold' : 'text-zinc-700 hover:text-zinc-900 dark:text-zinc-200 dark:hover:text-white' }}">About</a>
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
                                    <a href="{{ route('users.index') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('users.index') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800' }}">Users Directory</a>
                                    <a href="{{ url('/terms') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->is('terms') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800' }}">Terms</a>
                                    <a href="{{ url('/privacy') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->is('privacy') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800' }}">Privacy</a>
                                    <a href="{{ route('about') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('about') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800' }}">About</a>
                                    <a href="{{ route('faq') }}" class="block rounded-lg px-3 py-2 text-sm {{ request()->routeIs('faq') ? 'bg-zinc-100 text-zinc-900 dark:bg-zinc-800 dark:text-white font-medium' : 'text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800' }}">FAQ</a>
                                    <div class="my-2 h-px bg-zinc-200 dark:bg-zinc-700"></div>
                                    @guest
                                        <a href="{{ route('login') }}" class="block rounded-lg px-3 py-2 text-sm text-blue-700 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-950/40">Log in</a>
                                    @else
                                        <a href="{{ route('dashboard') }}" class="block rounded-lg px-3 py-2 text-sm text-zinc-900 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-zinc-800">Open Forum</a>
                                    @endguest
                                </div>
                            </details>
                        </div>
                    </div>
                </div>
            </header>

            <div class="mt-24">
                <div class="rounded-2xl border border-zinc-200 bg-white p-8 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                    <h2 class="text-2xl font-semibold text-zinc-900 dark:text-white">Frequently Asked Questions</h2>
                    <div class="mt-4 divide-y divide-zinc-200 dark:divide-zinc-700">
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                How do I create a post?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Register or log in, then click New to open the editor. Choose a category, write your content, and publish.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                How do notifications work?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                You’ll receive notifications when your posts get likes or comments. Open the bell menu to view and mark them as read.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Is my account secure?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Email verification and two‑factor authentication are supported. Google login keeps you remembered across sessions.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                How do I verify my email?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                After registration, we email a verification link. Click it to unlock posting and other features. You can resend the link from the Settings page.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                How do I enable two‑factor authentication (2FA)?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Open Settings and enable two‑factor. Scan the QR with an authenticator app and store recovery codes safely.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Can I change my name or email later?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Yes. Go to Settings to update profile details. Changing email may require re‑verification.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                How do I upload or change my profile image?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                From your profile Settings, upload a square image for best results. We store and serve it from secure storage.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                What categories can I post in?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Choose a category that fits your topic when creating a post. We highlight official and verified content separately.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                How do I report abuse or spam?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Use Report controls on profiles or posts where available. Moderators escalate repeated or severe violations.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Can I edit or delete my posts and comments?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                You can delete your own posts and comments. Edit options may vary by release; if not available, delete and repost your corrected content.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Are external links allowed in posts or profiles?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Yes, but keep them relevant and safe. Suspicious links can be removed by moderators to protect the community.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                What do the verified and admin badges mean?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Verified signals trusted identity; Admin designates staff. Badges display beside names on posts and profiles.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                How do I become verified?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Verification requires a $2 upgrade via the Premium page. Visit
                                <a href="{{ route('premium.index') }}" class="text-blue-600 dark:text-blue-400 hover:underline">Premium</a>
                                to upgrade.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                What data do you collect about me?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Only what’s needed for authentication and content (email, profile details, activity). We avoid invasive tracking and analytics by default.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Do you support dark mode?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Yes. The interface adapts to your system preference and can be adjusted in Settings where available.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Are Persian posts supported?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Yes. The platform supports right‑to‑left scripts and Unicode content; Persian and English posts are welcome.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                How do I reset my password?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Use “Forgot password” on the login page to receive a reset link via email. Follow the link to set a new password.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Can I change my notification preferences?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Manage how you’re notified about likes and comments from Settings. You can mute or clear notifications anytime.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                How are views and likes counted?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Likes are unique per user. Views deduplicate per user or device/browser, providing a more accurate reach estimate.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Can I deactivate or delete my account?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                You can delete your account from your Profile page. We remove personal data while preserving public discussion integrity where required.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Do you offer a premium plan?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Premium features are showcased on the Premium page. Upgrades help support development and hosting costs.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Does Google sign‑in keep me logged in?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Yes. We remember your session after Google login. If you enable 2FA, you’ll complete a one‑time challenge.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                How do I format my posts?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Use the editor’s basic formatting tools. Keep paragraphs short; links and media should be relevant and safe for all audiences.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Can I follow specific users or categories?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                You can browse by categories and profiles. Following and feed personalization are planned improvements as the community grows.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Do you support real‑time updates?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                The interface is optimized for quick refreshes and snappy interactions. Real‑time streaming is on the roadmap.
                            </p>
                        </details>

                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Can I export my data?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Data export tools are planned. Meanwhile, you can download your posts manually from your profile.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                How do I appeal a ban?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                If banned, you may contact support to request a review. Provide context and links to relevant posts.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                How do I report a bug?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Share steps to reproduce, screenshots, and device info in the Issues section or via support.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Can I schedule posts?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Scheduling is planned. For now, create drafts and publish manually when ready.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Do you support tags or hashtags?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Categories are available today. Tags and hashtags are under consideration for future releases.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Can I embed images or video?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Image uploads are supported where allowed. Video embedding may be limited; link to sources when needed.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Is there an API?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Public APIs are planned. For now, the site focuses on a stable, user‑friendly experience.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                How do mentions work?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Mention users by name where supported. They may receive a notification depending on settings.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Can I save drafts?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Draft support varies. If unavailable, compose locally and paste when ready to publish.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Why was my post hidden?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Posts may be hidden for policy violations or low quality signals. Improve content and repost respectfully.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                What are rate limits?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                To prevent abuse, actions may be limited over short windows. Normal activity is unaffected for typical users.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                How do I contact support?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Use the Support link where available or email the team. Include screenshots and URLs to speed up help.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Do you accept donations?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Upgrades and contributions help offset hosting and development. See the Premium page for options.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                How do I request a feature?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Share ideas in the forum or via support. Clear use‑cases help prioritize improvements.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Can I change my timezone or locale?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Localization options expand over time. Dates and times respect your device settings where possible.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Do you use analytics?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                We avoid invasive tracking. Minimal telemetry helps improve stability and performance.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                How do I manage email frequency?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Adjust email preferences from Settings or unsubscribe links. Notifications remain available in‑app.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Does search support filters?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Filtered search is planned. Use categories and users to narrow content today.
                            </p>
                        </details>
                        <details class="group py-4">
                            <summary class="flex cursor-pointer items-center justify-between gap-2 text-sm text-zinc-900 dark:text-white">
                                Can I bookmark posts?
                                <span class="text-zinc-500 transition group-open:rotate-180">⌄</span>
                            </summary>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                                Bookmarking and saved lists are planned. For now, use browser bookmarks or share links to revisit later.
                            </p>
                        </details>
                    </div>
                </div>
            </div>

            <footer class="mt-16">
                <div class="rounded-2xl border border-zinc-200 bg-white p-8 shadow-sm dark:border-zinc-700 dark:bg-zinc-800">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 text-sm">
                        <div>
                            <div class="flex items-center gap-2">
                                <img src="{{ asset('iranguard.png') }}" alt="{{ config('app.name') }}" class="w-8 dark:brightness-90">
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
