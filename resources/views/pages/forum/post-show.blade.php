<div class="max-w-3xl">
    @if($post)
        @php $u = $post->user; @endphp
        <div class="mb-6 relative overflow-hidden rounded-2xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="absolute inset-0 pointer-events-none opacity-60 dark:opacity-40" style="background: radial-gradient(1200px 200px at 10% 0%, rgba(59,130,246,0.08), transparent), radial-gradient(1000px 200px at 90% 0%, rgba(168,85,247,0.08), transparent);"></div>
            <div class="relative p-6 md:p-8">
                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                    <h1 dir="auto" class="text-3xl md:text-4xl font-extrabold tracking-tight text-gray-900 dark:text-white">{{ $post->title }}</h1>
                    <div class="flex items-center gap-2 md:mt-1 shrink-0">
                        @auth
                            @if(auth()->id() === ($u?->id))
                                <a href="{{ url('/new?id='.$post->id) }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3.5 py-2 text-white text-sm hover:bg-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 3.487a1.5 1.5 0 0 1 2.121 2.121l-9.9 9.9L6 16l.492-3.083 10.37-9.43zM19 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h6" />
                                    </svg>
                                    Edit
                                </a>
                                <button type="button" class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-3.5 py-2 text-white text-sm hover:bg-red-700"
                                    onclick="if(confirm('Delete this post? This cannot be undone.')){ const idEl=this.closest('[wire\\:id]'); if(idEl){ window.Livewire.find(idEl.getAttribute('wire:id')).call('deletePost'); } }">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 7h12M9 7v10m6-10v10M4 7l1 12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2l1-12M9 7V5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v2" />
                                    </svg>
                                    Delete
                                </button>
                            @endif
                        @endauth
                        <button type="button" class="inline-flex items-center gap-1 rounded-lg border border-gray-300 dark:border-gray-700 px-2.5 py-1 text-xs text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700"
                            onclick="const u=`${location.origin}/forum/{{ $post->id }}`; navigator.clipboard.writeText(u).then(()=>{ this.dataset.label=this.innerText; this.innerText='Copied'; setTimeout(()=>{ this.innerText=this.dataset.label||'Copy link'; },1500); });">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6h2.25A2.25 2.25 0 0 1 18 8.25v7.5A2.25 2.25 0 0 1 15.75 18h-2.25M10.5 6H8.25A2.25 2.25 0 0 0 6 8.25v7.5A2.25 2.25 0 0 0 8.25 18h2.25M8.25 12h7.5" />
                            </svg>
                            Copy link
                        </button>
                    </div>
                </div>
                <div class="mt-3 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        @if($u?->profile_image_url)
                            <a href="{{ url('/user/'.($u->id ?? '')) }}" wire:navigate>
                                <img src="{{ $u->profile_image_url }}" alt="{{ $u->name }}" class="w-12 h-12 rounded-full object-cover border border-gray-200 dark:border-gray-700">
                            </a>
                        @else
                            <a href="{{ url('/user/'.($u->id ?? '')) }}" wire:navigate>
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900 dark:to-purple-900 flex items-center justify-center text-base font-semibold text-gray-800 dark:text-white border border-gray-200 dark:border-gray-700">
                                    {{ $u?->initials() }}
                                </div>
                            </a>
                        @endif
                        <div class="min-w-0">
                            <div class="flex items-center gap-1">
                                <a href="{{ url('/user/'.($u->id ?? '')) }}" wire:navigate class="text-base font-semibold text-gray-900 dark:text-white truncate">
                                    <span>{{ $u?->name ?? 'Unknown' }}</span>
                                </a>
                                @if($u?->getBadgeIconPath())
                                    <img src="{{ $u->getBadgeIconPath() }}" alt="{{ $u->getBadgeTooltip() }}" class="w-4 h-4" title="{{ $u->getBadgeTooltip() }}">
                                @endif
                            </div>
                            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                                <span class="rounded bg-gray-100 px-2 py-1 text-[11px] text-gray-700 dark:bg-gray-700 dark:text-gray-200">{{ $post->category }}</span>
                                <span>{{ $post->created_at?->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 rounded-full border border-gray-200 dark:border-gray-700 px-3 py-1.5 text-xs {{ $liked ? 'text-red-600' : 'text-gray-700 dark:text-gray-300' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="{{ $liked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21c-4.5-3-9-6.5-9-11a5 5 0 0 1 9-3 5 5 0 0 1 9 3c0 4.5-4.5 8-9 11z" />
                            </svg>
                            {{ $likesCount }}
                        </span>
                        <span class="inline-flex items-center gap-1 rounded-full border border-gray-200 dark:border-gray-700 px-3 py-1.5 text-xs text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s4.5-7.5 9.75-7.5S21.75 12 21.75 12s-4.5 7.5-9.75 7.5S2.25 12 2.25 12z" />
                                <circle cx="12" cy="12" r="3.25" />
                            </svg>
                            {{ $viewsCount }}
                        </span>
                        <span class="inline-flex items-center gap-1 rounded-full border border-gray-200 dark:border-gray-700 px-3 py-1.5 text-xs text-gray-700 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 8h10M7 12h7M4 5h16a1 1 0 0 1 1 1v12l-3-3H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1z" />
                            </svg>
                            {{ $commentsCount }}
                        </span>
                    </div>
                </div>
                
            </div>
        </div>

        <div class="max-w-none">
            <div id="post-content" wire:ignore class="rounded-xl border border-gray-200 bg-white p-5 dark:border-gray-700 dark:bg-gray-800">
                <pre class="whitespace-pre-wrap text-sm text-gray-800 dark:text-gray-200">{{ $post->content }}</pre>
            </div>
        </div>

        <div class="mt-6 flex items-center gap-3">
            @auth
                <button wire:click="toggleLike" class="inline-flex items-center gap-2 rounded-lg border px-4 py-2 text-sm
                    {{ $liked ? 'border-red-600 bg-red-50 text-red-700 dark:bg-red-900/30' : 'border-gray-300 text-gray-700 dark:border-gray-700 dark:text-gray-300' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="{{ $liked ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.1-4.5-4.688-4.5-1.862 0-3.48 1.124-4.312 2.744C11.168 4.874 9.55 3.75 7.688 3.75 5.1 3.75 3 5.765 3 8.25c0 7.125 9 12 9 12s9-4.875 9-12z" />
                    </svg>
                </button>
            @else
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline">Log in to like</a>
            @endauth
            <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:underline dark:text-gray-300">Back to Forum</a>
        </div>

        <livewire:pages.post-comments :postId="$post->id" />

        <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.6/dist/purify.min.js"></script>
        <style>
            #post-content [dir="rtl"] { text-align: right; }
            #post-content { font-size: 0.95rem; line-height: 1.75; color: rgb(55 65 81); text-align: start; }
            .dark #post-content { color: rgb(229 231 235); }
            #post-content h1 { font-size: 1.875rem; line-height: 2.25rem; font-weight: 700; margin: 1.5rem 0 1rem; }
            #post-content h2 { font-size: 1.5rem; line-height: 2rem; font-weight: 700; margin: 1.25rem 0 0.75rem; }
            #post-content h3 { font-size: 1.25rem; line-height: 1.75rem; font-weight: 600; margin: 1rem 0 0.5rem; }
            #post-content p { margin: 0.75rem 0; }
            #post-content ul { list-style-type: disc; list-style-position: outside; margin: 0.75rem 0; padding-left: 1.25rem; }
            #post-content ol { list-style-type: decimal; list-style-position: outside; margin: 0.75rem 0; padding-left: 1.25rem; }
            #post-content li { margin: 0.25rem 0; }
            #post-content li > ul, #post-content li > ol { margin-top: 0.25rem; margin-bottom: 0.25rem; }
            #post-content a { color: rgb(37 99 235); text-decoration: underline; }
            .dark #post-content a { color: rgb(96 165 250); }
            #post-content blockquote { border-left: 4px solid rgb(209 213 219); padding-left: 1rem; color: rgb(75 85 99); }
            #post-content blockquote[dir="rtl"] { border-left: 0; border-right: 4px solid rgb(209 213 219); padding-left: 0; padding-right: 1rem; }
            .dark #post-content blockquote { border-color: rgb(75 85 99); color: rgb(156 163 175); }
            .dark #post-content blockquote[dir="rtl"] { border-right-color: rgb(75 85 99); }
            #post-content code { background: rgba(17,24,39,0.05); padding: 0.15rem 0.35rem; border-radius: 0.25rem; }
            .dark #post-content code { background: rgba(255,255,255,0.06); }
            #post-content pre { background: rgba(17,24,39,0.05); padding: 0.75rem; border-radius: 0.5rem; overflow: auto; }
            .dark #post-content pre { background: rgba(255,255,255,0.06); }
            #post-content img { max-width: 100%; height: auto; border-radius: 0.375rem; display: block; margin: 0.75rem 0; }
            #post-content table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
            #post-content th, #post-content td { border: 1px solid rgb(229 231 235); padding: 0.5rem; }
            .dark #post-content th, .dark #post-content td { border-color: rgb(75 85 99); }
            #post-content .emoji-flag { display: inline !important; margin: 0 !important; border: 0 !important; border-radius: 0 !important; }
            #post-content strong, #post-content b, #post-content em, #post-content i { unicode-bidi: plaintext; }
            #post-content th, #post-content td { text-align: start; }
                    #post-content table[dir="rtl"] { direction: rtl; }
                    #post-content .md-table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; margin: 1rem 0; }
                    #post-content .md-table-scroll table { min-width: max-content; width: auto; }
        </style>
        <script>
            function renderMarkdown() {
                const el = document.getElementById('post-content');
                if (!el) return;
                const FLAG = '🇮🇷';
                const IMG_URL = '{{ asset('iran.png') }}';
                const IMG_HTML = `<img src="${IMG_URL}" alt="${FLAG}" style="height:1.2em;width:auto;vertical-align:-0.2em" class="emoji-flag emoji-flag-ir">`;
                if (window.marked) {
                    const renderer = new marked.Renderer();
                    const rtlRegex = /[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF\uFB50-\uFDFF\uFE70-\uFEFF]/;
                    const skipRegex = /[\s0-9!@#$%^&*()\-_=+\[\]{};:'",.<>/?\\|`~\u200E\u200F\u061C\u200C\u200D]/;
                    function detectDirFromText(s) {
                        if (typeof s !== 'string') return 'ltr';
                        const t = s.replace(/<[^>]*>/g, ' ').replace(/&[#A-Za-z0-9]+;/g, ' ').trim();
                        for (let i = 0; i < t.length; i++) {
                            const ch = t[i];
                            if (skipRegex.test(ch)) continue;
                            return rtlRegex.test(ch) ? 'rtl' : 'ltr';
                        }
                        return 'ltr';
                    }
                    function applyInlineDir(root) {
                        if (!root) return;
                        const els = root.querySelectorAll('strong,b,em,i');
                        els.forEach(el => {
                            const dir = detectDirFromText(el.textContent || '');
                            el.setAttribute('dir', dir);
                        });
                        const blocks = root.querySelectorAll('p,li,blockquote,h1,h2,h3,h4,h5,h6');
                        blocks.forEach(el => {
                            if (!el.hasAttribute('dir')) {
                                el.setAttribute('dir', detectDirFromText(el.textContent || ''));
                            }
                        });
                        root.querySelectorAll('table').forEach(t => {
                            if (!t.hasAttribute('dir')) t.setAttribute('dir', detectDirFromText(t.textContent || ''));
                            t.querySelectorAll('th,td').forEach(cell => {
                                cell.setAttribute('dir', detectDirFromText(cell.textContent || ''));
                            });
                            if (!t.parentElement || !t.parentElement.classList.contains('md-table-scroll')) {
                                const wrap = document.createElement('div');
                                wrap.className = 'md-table-scroll';
                                t.parentNode.insertBefore(wrap, t);
                                wrap.appendChild(t);
                            }
                        });
                    }
                    function extractText(arg) {
                        if (typeof arg === 'string') return arg;
                        if (arg && typeof arg === 'object') {
                            if (typeof arg.text === 'string') return arg.text;
                            if (typeof arg.raw === 'string') return arg.raw;
                        }
                        try { return String(arg ?? ''); } catch { return ''; }
                    }
                    const _paragraph = renderer.paragraph?.bind(renderer);
                    renderer.paragraph = function(...args) {
                        const text = extractText(args[0]);
                        const html = _paragraph ? _paragraph(...args) : `<p>${text}</p>`;
                        const dir = detectDirFromText(text);
                        return html.replace(/^<p\b/, `<p dir="${dir}"`);
                    };
                    const _heading = renderer.heading?.bind(renderer);
                    renderer.heading = function(...args) {
                        const text = extractText(args[0]);
                        const html = _heading ? _heading(...args) : `<h1>${text}</h1>`;
                        const dir = detectDirFromText(text);
                        return html.replace(/^<h(\d)\b/, (m, lvl) => `<h${lvl} dir="${dir}"`);
                    };
                    const _listitem = renderer.listitem?.bind(renderer);
                    renderer.listitem = function(...args) {
                        const text = extractText(args[0]);
                        const html = _listitem ? _listitem(...args) : `<li>${text}</li>`;
                        const dir = detectDirFromText(text);
                        return html.replace(/^<li\b/, `<li dir="${dir}"`);
                    };
                    const _blockquote = renderer.blockquote?.bind(renderer);
                    renderer.blockquote = function(...args) {
                        const text = extractText(args[0]);
                        const html = _blockquote ? _blockquote(...args) : `<blockquote>${text}</blockquote>`;
                        const dir = detectDirFromText(text);
                        return html.replace(/^<blockquote\b/, `<blockquote dir="${dir}"`);
                    };
                    const _strong = renderer.strong?.bind(renderer);
                    renderer.strong = function(text) {
                        const dir = detectDirFromText(text);
                        const html = _strong ? _strong(text) : `<strong>${text}</strong>`;
                        return html.replace(/^<strong\b/, `<strong dir="${dir}"`);
                    };
                    const _em = renderer.em?.bind(renderer);
                    renderer.em = function(text) {
                        const dir = detectDirFromText(text);
                        const html = _em ? _em(text) : `<em>${text}</em>`;
                        return html.replace(/^<em\b/, `<em dir="${dir}"`);
                    };
                    const _table = renderer.table?.bind(renderer);
                    renderer.table = function(header, body) {
                        const raw = `${header || ''} ${body || ''}`;
                        const dir = detectDirFromText(raw);
                        const html = _table ? _table(header, body) : `<table><thead>${header}</thead><tbody>${body}</tbody></table>`;
                        return html.replace(/^<table\b/, `<table dir="${dir}"`);
                    };
                    const _tablerow = renderer.tablerow?.bind(renderer);
                    renderer.tablerow = function(content) {
                        const html = _tablerow ? _tablerow(content) : `<tr>${content}</tr>`;
                        return html;
                    };
                    const _tablecell = renderer.tablecell?.bind(renderer);
                    renderer.tablecell = function(content, flags) {
                        const text = extractText(content);
                        const dir = detectDirFromText(text);
                        const tag = flags && flags.header ? 'th' : 'td';
                        const align = flags && flags.align ? ` style="text-align:${flags.align}"` : '';
                        const htmlContent = typeof content === 'string' ? content : (content && typeof content === 'object' ? (content.raw ?? content.text ?? '') : String(content ?? ''));
                        return `<${tag} dir="${dir}"${align}>${htmlContent}</${tag}>`;
                    };
                    const _link = renderer.link;
                    renderer.link = function(href, title, text) {
                        const html = _link.call(this, href, title, text);
                        return html.replace('<a ', '<a target=\"_blank\" rel=\"noopener noreferrer\" ');
                    };
                    marked.setOptions({ gfm: true, breaks: true, renderer });
                }
                const raw = @json($post->content);
                const dirty = String(raw ?? '').replaceAll(FLAG, IMG_HTML);
                const html = DOMPurify.sanitize(marked.parse(dirty));
                el.innerHTML = html;
                if (typeof applyInlineDir === 'function') {
                    applyInlineDir(el);
                } else {
                    const rtlRegex = /[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF\uFB50-\uFDFF\uFE70-\uFEFF]/;
                    const skipRegex = /[\s0-9!@#$%^&*()\-_=+\[\]{};:'",.<>/?\\|`~\u200E\u200F\u061C\u200C\u200D]/;
                    function detectDirFromTextLocal(s) {
                        if (typeof s !== 'string') return 'ltr';
                        const t = s.replace(/<[^>]*>/g, ' ').replace(/&[#A-Za-z0-9]+;/g, ' ').trim();
                        for (let i = 0; i < t.length; i++) {
                            const ch = t[i];
                            if (skipRegex.test(ch)) continue;
                            return rtlRegex.test(ch) ? 'rtl' : 'ltr';
                        }
                        return 'ltr';
                    }
                    el.querySelectorAll('strong,b,em,i').forEach(elm => {
                        elm.setAttribute('dir', detectDirFromTextLocal(elm.textContent || ''));
                    });
                    el.querySelectorAll('p,li,blockquote,h1,h2,h3,h4,h5,h6').forEach(elm => {
                        if (!elm.hasAttribute('dir')) {
                            elm.setAttribute('dir', detectDirFromTextLocal(elm.textContent || ''));
                        }
                    });
                    el.querySelectorAll('table').forEach(t => {
                        if (!t.hasAttribute('dir')) t.setAttribute('dir', detectDirFromTextLocal(t.textContent || ''));
                        t.querySelectorAll('th,td').forEach(cell => {
                            cell.setAttribute('dir', detectDirFromTextLocal(cell.textContent || ''));
                        });
                        if (!t.parentElement || !t.parentElement.classList.contains('md-table-scroll')) {
                            const wrap = document.createElement('div');
                            wrap.className = 'md-table-scroll';
                            t.parentNode.insertBefore(wrap, t);
                            wrap.appendChild(t);
                        }
                    });
                }
            }

            document.addEventListener('DOMContentLoaded', renderMarkdown);
            document.addEventListener('livewire:navigated', renderMarkdown);
        </script>
    @endif
</div>
