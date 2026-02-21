<x-layouts::app.sidebar :title="__('Post')">
    <flux:main>
        <livewire:pages.post-show :postId="$id" />
    </flux:main>
</x-layouts::app.sidebar>