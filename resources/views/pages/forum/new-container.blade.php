@php($editId = request()->get('id'))
<x-layouts::app.sidebar :title="$editId ? __('Edit Post') : __('New Post')">
    <flux:main>
        <livewire:pages.post-create :postId="$editId" />
    </flux:main>
</x-layouts::app.sidebar>
