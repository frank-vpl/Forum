<div class="max-w-3xl">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ $this->postId ? 'Edit Post' : 'New Post' }}</h1>
        <p class="text-gray-600 dark:text-gray-400">{{ $this->postId ? 'Update your post' : 'Share something with the community' }}</p>
    </div>

    <form wire:submit.prevent="save" class="space-y-5">
        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
            <input type="text" wire:model.defer="title" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="Enter a clear title">
            @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Category</label>
            <input type="text" wire:model.defer="category" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="e.g. General, Help, Ideas">
            @error('category') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Content (Markdown)</label>
            <input type="hidden" id="content-hidden" wire:model.defer="content" value="{{ $content }}">
            <div wire:ignore>
                <textarea id="markdown-editor"></textarea>
            </div>
            @error('content') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="rounded-lg bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">{{ $this->postId ? 'Update' : 'Publish' }}</button>
            <a href="{{ url('/dashboard') }}" class="text-gray-600 hover:underline dark:text-gray-300">Cancel</a>
        </div>
    </form>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dompurify@3.0.6/dist/purify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <style>
        .editor-preview, .editor-preview-side { font-size: 0.95rem; line-height: 1.75; color: rgb(55 65 81); }
        .dark .editor-preview, .dark .editor-preview-side { color: rgb(229 231 235); background-color: #1f2937; }
        .editor-preview [dir="rtl"], .editor-preview-side [dir="rtl"] { text-align: right; }
        .editor-preview .emoji-flag, .editor-preview-side .emoji-flag { display: inline !important; height: 1em; width: auto; vertical-align: -0.2em; margin: 0 !important; padding: 0 !important; border: 0 !important; border-radius: 0 !important; }
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
        .dark .editor-preview blockquote, .dark .editor-preview-side blockquote { border-color: rgb(75 85 99); color: rgb(156 163 175); }
        .editor-preview code, .editor-preview-side code { background: rgba(17,24,39,0.05); padding: 0.15rem 0.35rem; border-radius: 0.25rem; }
        .dark .editor-preview code, .dark .editor-preview-side code { background: rgba(255,255,255,0.06); }
        .editor-preview pre, .editor-preview-side pre { background: rgba(17,24,39,0.05); padding: 0.75rem; border-radius: 0.5rem; overflow: auto; }
        .dark .editor-preview pre, .dark .editor-preview-side pre { background: rgba(255,255,255,0.06); }
        .editor-preview img, .editor-preview-side img { max-width: 100%; height: auto; border-radius: 0.375rem; }
        .editor-preview table, .editor-preview-side table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
        .editor-preview th, .editor-preview td, .editor-preview-side th, .editor-preview-side td { border: 1px solid rgb(229 231 235); padding: 0.5rem; }
        .dark .editor-preview th, .dark .editor-preview td, .dark .editor-preview-side th, .dark .editor-preview-side td { border-color: rgb(75 85 99); }
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
                function detectDirFromText(s) {
                    if (typeof s !== 'string') return 'ltr';
                    const t = s.trim();
                    for (let i = 0; i < t.length; i++) {
                        const ch = t[i];
                        if (/\s/.test(ch)) continue;
                        return rtlRegex.test(ch) ? 'rtl' : 'ltr';
                    }
                    return 'ltr';
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
                        return DOMPurify.sanitize(marked.parse(dirty));
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
            const hidden = document.getElementById('content-hidden');
            if (hidden && hidden.value) {
                mde.value(hidden.value);
                // ensure Livewire model sees the initial value as touched
                hidden.value = mde.value();
                hidden.dispatchEvent(new Event('input', { bubbles: true }));
            }
            mde.codemirror.on('change', () => {
                const hidden = document.getElementById('content-hidden');
                if (!hidden) return;
                hidden.value = mde.value();
                hidden.dispatchEvent(new Event('input', { bubbles: true }));
            });
        }
    </script>
</div>
