@php($p = \App\Models\Post::find($id))
<x-layouts::app.sidebar :title="$p?->title ?? __('Post')">
    <flux:main>
        <livewire:pages.post-show :postId="$id" />
    </flux:main>
</x-layouts::app.sidebar>
