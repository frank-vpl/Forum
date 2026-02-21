<?php

namespace App\Livewire\Pages;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Notifications extends Component
{
    public int $page = 1;

    public int $perPage = 10;

    public bool $loadingMore = false;

    public ?string $loadError = null;

    public function mount(): void
    {
        //
    }

    public function clearAll(): void
    {
        Notification::where('user_id', Auth::id())->delete();
        $this->page = 1;
    }

    public function loadMore(): void
    {
        $this->loadingMore = true;
        $this->loadError = null;
        try {
            $this->page++;
        } catch (\Throwable $e) {
            $this->page--;
            $this->loadError = 'load-error';
        } finally {
            $this->loadingMore = false;
        }
    }

    public function render()
    {
        $query = Notification::with(['actor', 'post', 'comment'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc');

        $total = (clone $query)->count();

        $items = $query->limit($this->page * $this->perPage)->get();

        $idsToMark = $items->filter(fn ($n) => $n->seen_at === null)->pluck('id');
        if ($idsToMark->isNotEmpty()) {
            Notification::whereIn('id', $idsToMark)->update(['seen_at' => now()]);
        }

        $hasMore = ($this->page * $this->perPage) < $total;

        return view('pages.notifications', [
            'items' => $items,
            'hasMore' => $hasMore,
        ]);
    }
}
