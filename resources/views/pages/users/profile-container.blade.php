@php($u = \App\Models\User::find($id))
<x-layouts::app.sidebar :title="$u?->name ?? __('Profile')">
    <flux:main>
        <livewire:pages.user-profile :userId="$id" />
    </flux:main>
</x-layouts::app.sidebar>
