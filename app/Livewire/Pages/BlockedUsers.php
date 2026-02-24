<?php

namespace App\Livewire\Pages;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BlockedUsers extends Component
{
    public int $page = 1;

    public int $perPage = 20;

    public bool $loadingMore = false;

    public ?string $loadError = null;

    public string $search = '';

    protected $queryString = ['search'];

    public function updatedSearch(): void
    {
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
        $me = Auth::user();
        $query = $me->blocks()
            ->when(! empty($this->search), function ($q) {
                $q->where(function ($qq) {
                    $qq->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('user_blocks.created_at', 'desc');

        $total = (clone $query)->count();
        $users = $query->limit($this->page * $this->perPage)->get();
        $hasMore = ($this->page * $this->perPage) < $total;

        return view('pages.users.blocked-users', [
            'users' => $users,
            'hasMore' => $hasMore,
        ]);
    }
}
