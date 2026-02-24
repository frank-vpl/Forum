<div class="max-w-3xl">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ $this->postId ? 'Edit Post' : 'New Post' }}</h1>
        <p class="text-gray-600 dark:text-gray-400">{{ $this->postId ? 'Update your post' : 'Share something with the community' }}</p>
    </div>

    <form
        id="post-form"
        wire:submit.prevent="save"
        x-data="{ titleLocal: @js($title ?? ''), categoryLocal: @js($category ?? ''), contentLen: @js(mb_strlen($content ?? '')) }"
        class="space-y-5"
    >
        <div>
            <flux:input
                dir="auto"
                wire:model.defer="title"
                :label="__('Title')"
                placeholder="Enter a clear title"
                required
                maxlength="60"
                x-model="titleLocal"
                x-on:input="titleLocal = ($event.target.value || '').slice(0, 60)"
            />
            <div class="mt-1 flex justify-end">
                <span class="text-xs text-zinc-500 dark:text-zinc-400" x-text="(titleLocal || '').length + '/60'"></span>
            </div>
        </div>

        <div>
            <flux:input
                dir="auto"
                wire:model.defer="category"
                :label="__('Category')"
                placeholder="e.g. General, Help, Ideas"
                required
                maxlength="20"
                x-model="categoryLocal"
                x-on:input="categoryLocal = ($event.target.value || '').slice(0, 20)"
            />
            <div class="mt-1 flex justify-end">
                <span class="text-xs text-zinc-500 dark:text-zinc-400" x-text="(categoryLocal || '').length + '/20'"></span>
            </div>
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Content (Markdown)</label>
            <input type="hidden" id="content-hidden" wire:model.defer="content" value="{{ $content }}">
            <div wire:ignore>
                <textarea dir="auto" id="markdown-editor"></textarea>
            </div>
            <div class="mt-1 flex justify-end">
                <span id="content-count" class="text-xs text-zinc-500 dark:text-zinc-400" x-text="contentLen"></span>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <flux:button
                id="post-submit"
                variant="primary"
                type="submit"
                wire:loading.attr="disabled"
                wire:target="save"
            >
                <span wire:loading.remove wire:target="save">{{ $this->postId ? 'Update' : 'Publish' }}</span>
                <span wire:loading wire:target="save" class="inline-flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-4 w-4 animate-spin" fill="none">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" opacity="0.25"></circle>
                        <path d="M4 12a8 8 0 018-8" stroke="currentColor" stroke-width="4" stroke-linecap="round"></path>
                    </svg>
                    {{ $this->postId ? 'Updating…' : 'Publishing…' }}
                </span>
            </flux:button>
            <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:underline dark:text-gray-300">Cancel</a>
        </div>
    </form>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.6/dist/purify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <style>
        .editor-preview, .editor-preview-side { font-size: 0.95rem; line-height: 1.75; color: rgb(55 65 81); text-align: start; }
        .dark .editor-preview, .dark .editor-preview-side { color: rgb(229 231 235); background-color: #1f2937; }
        .editor-preview [dir="rtl"], .editor-preview-side [dir="rtl"] { text-align: right; }
        .editor-preview .emoji-flag, .editor-preview-side .emoji-flag { display: inline !important; height: 1em; width: auto; vertical-align: -0.2em; margin: 0 !important; padding: 0 !important; border: 0 !important; border-radius: 0 !important; }
        .editor-preview strong, .editor-preview b, .editor-preview em, .editor-preview i,
        .editor-preview-side strong, .editor-preview-side b, .editor-preview-side em, .editor-preview-side i { unicode-bidi: plaintext; }
        .editor-preview p, .editor-preview-side p,
        .editor-preview li, .editor-preview-side li,
        .editor-preview blockquote, .editor-preview-side blockquote,
        .editor-preview h1, .editor-preview-side h1,
        .editor-preview h2, .editor-preview-side h2,
        .editor-preview h3, .editor-preview-side h3,
        .editor-preview h4, .editor-preview-side h4,
        .editor-preview h5, .editor-preview-side h5,
        .editor-preview h6, .editor-preview-side h6 { unicode-bidi: plaintext; }
        /* Dark mode for CodeMirror editor */
        .dark .CodeMirror { background-color: #111827; color: #e5e7eb; border-color: #374151; }
        .dark .CodeMirror-cursor { border-left: 1px solid #e5e7eb; }
        .dark .CodeMirror-gutters { background-color: #111827; border-right: 1px solid #374151; color: #9ca3af; }
        .dark .editor-toolbar { background-color: #1f2937; border-color: #374151; }
        .dark .editor-toolbar a { color: #e5e7eb; }
        .dark .editor-toolbar a.active, .dark .editor-toolbar a:hover { background: #374151; }
        .editor-preview h1, .editor-preview-side h1 { font-size: 1.875rem; line-height: 2.25rem; font-weight: 700; margin: 1.5rem 0 1rem; }
        .editor-preview h2, .editor-preview-side h2 { font-size: 1.5rem; line-height: 2rem; font-weight: 700; margin: 1.25rem 0 0.75rem; }
        .editor-preview h3, .editor-preview-side h3 { font-size: 1.25rem; line-height: 1.75rem; font-weight: 600; margin: 1rem 0 0.5rem; }
        .editor-preview p, .editor-preview-side p { margin: 0.75rem 0; }
        .editor-preview ul, .editor-preview-side ul { list-style-type: disc; list-style-position: outside; margin: 0.75rem 0; padding-left: 1.25rem; }
        .editor-preview ol, .editor-preview-side ol { list-style-type: decimal; list-style-position: outside; margin: 0.75rem 0; padding-left: 1.25rem; }
        .editor-preview li, .editor-preview-side li { margin: 0.25rem 0; }
        .editor-preview li > ul, .editor-preview li > ol, .editor-preview-side li > ul, .editor-preview-side li > ol { margin-top: 0.25rem; margin-bottom: 0.25rem; }
        .editor-preview a, .editor-preview-side a { color: rgb(37 99 235); text-decoration: underline; }
        .dark .editor-preview a, .dark .editor-preview-side a { color: rgb(96 165 250); }
        .editor-preview blockquote, .editor-preview-side blockquote { border-left: 4px solid rgb(209 213 219); padding-left: 1rem; color: rgb(75 85 99); }
        .editor-preview blockquote[dir="rtl"], .editor-preview-side blockquote[dir="rtl"] { border-left: 0; border-right: 4px solid rgb(209 213 219); padding-left: 0; padding-right: 1rem; }
        .dark .editor-preview blockquote, .dark .editor-preview-side blockquote { border-color: rgb(75 85 99); color: rgb(156 163 175); }
        .dark .editor-preview blockquote[dir="rtl"], .dark .editor-preview-side blockquote[dir="rtl"] { border-right-color: rgb(75 85 99); }
        .editor-preview code, .editor-preview-side code { background: rgba(17,24,39,0.05); padding: 0.15rem 0.35rem; border-radius: 0.25rem; }
        .dark .editor-preview code, .dark .editor-preview-side code { background: rgba(255,255,255,0.06); }
        .editor-preview pre, .editor-preview-side pre { background: rgba(17,24,39,0.05); padding: 0.75rem; border-radius: 0.5rem; overflow: auto; }
        .dark .editor-preview pre, .dark .editor-preview-side pre { background: rgba(255,255,255,0.06); }
        .editor-preview img, .editor-preview-side img { max-width: 100%; height: auto; border-radius: 0.375rem; }
        .editor-preview table, .editor-preview-side table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
        .editor-preview th, .editor-preview td, .editor-preview-side th, .editor-preview-side td { border: 1px solid rgb(229 231 235); padding: 0.5rem; }
        .dark .editor-preview th, .dark .editor-preview td, .dark .editor-preview-side th, .dark .editor-preview-side td { border-color: rgb(75 85 99); }
        .editor-preview th, .editor-preview td, .editor-preview-side th, .editor-preview-side td { text-align: start; }
        .editor-preview table[dir="rtl"], .editor-preview-side table[dir="rtl"] { direction: rtl; }
        .editor-preview .md-table-scroll, .editor-preview-side .md-table-scroll { overflow-x: auto; -webkit-overflow-scrolling: touch; margin: 1rem 0; }
        .editor-preview .md-table-scroll table, .editor-preview-side .md-table-scroll table { min-width: max-content; width: auto; }
        .CodeMirror pre { unicode-bidi: plaintext; text-align: start; }
    </style>
    <script>
        document.addEventListener('livewire:navigated', () => {
            initMDE();
        });
        document.addEventListener('DOMContentLoaded', () => {
            initMDE();
        });
        document.addEventListener('livewire:load', () => {
            initMDE();
        });
        function initMDE() {
            const ta = document.getElementById('markdown-editor');
            if (!ta) return;
            if (ta._mde) return;
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
                const _link = renderer.link;
                renderer.link = function(href, title, text) {
                    const html = _link.call(this, href, title, text);
                    return html.replace('<a ', '<a target="_blank" rel="noopener noreferrer" ');
                };
                marked.setOptions({ gfm: true, breaks: true, renderer });
            }
            const mde = new EasyMDE({
                element: ta,
                autoDownloadFontAwesome: true,
                spellChecker: false,
                status: false,
                previewRender: function(text) {
                    try {
                        const dirty = String(text ?? '').replaceAll(FLAG, IMG_HTML);
                        const sanitized = DOMPurify.sanitize(marked.parse(dirty));
                        const tmp = document.createElement('div');
                        tmp.innerHTML = sanitized;
                        if (typeof detectDirFromText === 'function') {
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
                            const els = tmp.querySelectorAll('strong,b,em,i');
                            els.forEach(el => el.setAttribute('dir', detectDirFromTextLocal(el.textContent || '')));
                            const blocks = tmp.querySelectorAll('p,li,blockquote,h1,h2,h3,h4,h5,h6');
                            blocks.forEach(el => {
                                if (!el.hasAttribute('dir')) {
                                    el.setAttribute('dir', detectDirFromTextLocal(el.textContent || ''));
                                }
                                if (el.innerHTML && /<br\s*\/?>/i.test(el.innerHTML)) {
                                    const parts = el.innerHTML.split(/<br\s*\/?>/i);
                                    const wrapped = parts.map(seg => {
                                        const d = detectDirFromTextLocal(String(seg).replace(/<[^>]*>/g, ' '));
                                        return `<span dir="${d}">${seg}</span>`;
                                    }).join('<br>');
                                    el.innerHTML = wrapped;
                                }
                            });
                            tmp.querySelectorAll('table').forEach(t => {
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
                        return tmp.innerHTML;
                    } catch (e) {
                        return text;
                    }
                },
                toolbar: [
                    'bold', 'italic', '|',
                    'unordered-list', 'ordered-list', 'quote', '|',
                    'link', 'image', '|',
                    'guide', 'preview', 'side-by-side', 'fullscreen'
                ],
                placeholder: 'Write your post in Markdown...',
            });
            ta._mde = mde;
            const wrapper = mde.codemirror.getWrapperElement && mde.codemirror.getWrapperElement();
            if (wrapper) wrapper.setAttribute('dir', 'auto');
            const hidden = document.getElementById('content-hidden');
            if (hidden && hidden.value) {
                mde.value(hidden.value);
                // ensure Livewire model sees the initial value as touched
                hidden.value = mde.value();
                hidden.dispatchEvent(new Event('input', { bubbles: true }));
                const cnt = document.getElementById('content-count');
                if (cnt) cnt.textContent = String(mde.value()?.length ?? 0);
            }
            mde.codemirror.on('change', () => {
                const hidden = document.getElementById('content-hidden');
                if (!hidden) return;
                hidden.value = mde.value();
                hidden.dispatchEvent(new Event('input', { bubbles: true }));
                const cnt = document.getElementById('content-count');
                if (cnt) cnt.textContent = String(mde.value()?.length ?? 0);
            });
        }
        document.addEventListener('livewire:load', () => {
            if (window.Livewire && Livewire.hook) {
                const reset = () => {
                    const btn = document.getElementById('post-submit');
                    if (btn) btn.disabled = false;
                };
                Livewire.hook('message.processed', reset);
                Livewire.hook('message.failed', reset);
            }
        });
    </script>
</div>
