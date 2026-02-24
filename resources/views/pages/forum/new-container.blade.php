@php($editId = request()->get('id'))
<x-layouts.home :title="$editId ? __('Edit Post') : __('New Post')">
    <flux:main>
        <livewire:pages.post-create :postId="$editId" />
    </flux:main>
</x-layouts.home>
