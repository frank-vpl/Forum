<div class="max-w-2xl mx-auto">
    <div class="relative overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-6 md:p-8">
        <div class="absolute inset-0 pointer-events-none opacity-60 dark:opacity-40" style="background: radial-gradient(800px 200px at 10% 0%, rgba(59,130,246,0.08), transparent), radial-gradient(600px 200px at 90% 0%, rgba(168,85,247,0.08), transparent);"></div>
        <div class="relative">
            @auth
                @if(auth()->user()->isVerified() || auth()->user()->isAdmin())
                    <div class="mb-6 flex items-center gap-2 rounded-xl border border-yellow-200 bg-yellow-50 px-4 py-3 text-yellow-900 dark:border-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-100">
                        <img src="{{ asset('images/user/verified-badge.svg') }}" alt="Premium" class="w-5 h-5">
                        <span>You already have Premium ({{ ucfirst(auth()->user()->status) }})</span>
                    </div>
                @endif
            @endauth
            <div class="flex items-center gap-3 mb-4">
                <img src="{{ asset('images/user/verified-badge.svg') }}" alt="Blue Badge" class="w-8 h-8">
                <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">Premium Blue Badge</h1>
            </div>
            <p class="text-gray-600 dark:text-gray-300 mb-6">Lifetime verification for only <span class="font-semibold">$2</span>. Pay with crypto and get premium visibility.</p>

            <div class="grid md:grid-cols-2 gap-6 mb-8">
                                <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-5 bg-white dark:bg-gray-800">
                    <div class="font-semibold text-gray-900 dark:text-white mb-2">Includes</div>
                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        <li class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Blue verification badge
                        </li>
                        <li class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Featured on top in Users Directory
                        </li>
                        <li class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Verified posts highlighted on Forum
                        </li>
                    </ul>
                </div>
                <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-5 bg-white dark:bg-gray-800">
                    <div class="text-3xl font-bold text-gray-900 dark:text-white">$2</div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">Lifetime</div>
                    <div class="mt-4">
                        <div class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-2">Buy via</div>
                        <div class="grid grid-cols-1 gap-2">
                            <a href="https://t.me/h3dev" class="inline-flex items-center justify-center gap-2 rounded-lg bg-sky-600 px-4 py-2 text-white hover:bg-sky-700">
                                Telegram
                            </a>
                            <a href="mailto:h3dev.pira@gmail.com" class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700">
                                Email
                            </a>
                            <a href="https://x.com/albert_com32388" class="inline-flex items-center justify-center gap-2 rounded-lg bg-black px-4 py-2 text-white hover:bg-zinc-900">
                                X (Twitter)
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 dark:border-gray-700 p-5 bg-white dark:bg-gray-800">
                <div class="font-semibold text-gray-900 dark:text-white mb-2">Contact</div>
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    <a href="https://t.me/h3dev" class="text-blue-600 hover:underline">Telegram</a>
                    &nbsp;•&nbsp;
                    <a href="mailto:h3dev.pira@gmail.com" class="text-blue-600 hover:underline">Email</a>
                    &nbsp;•&nbsp;
                    <a href="https://x.com/albert_com32388" class="text-blue-600 hover:underline">X (Twitter)</a>
                </div>
            </div>
        </div>
    </div>
</div>
