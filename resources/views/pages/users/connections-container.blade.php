@php($u = \App\Models\User::find($id))
<x-layouts::app.sidebar :title="($mode === 'followers' ? 'Followers' : 'Following').' · '.($u?->name ?? '')">
    <flux:main>
        <livewire:pages.user-connections :userId="$id" mode="{{ $mode }}" />
    </flux:main>
</x-layouts::app.sidebar>
