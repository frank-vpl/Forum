<x-layouts::app.sidebar :title="__('Profile')">
    <flux:main>
        <livewire:pages.user-profile :userId="$id" />
    </flux:main>
</x-layouts::app.sidebar>
