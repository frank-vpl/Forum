<?php

namespace App\Livewire\Pages;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UsersList extends Component
{
    use WithPagination;

    public string $filter = 'default';

    public $search = '';

    protected $queryString = [
        'search',
        'filter' => ['except' => 'default'],
    ];

    public function mount(): void
    {
        $this->filter = Auth::user()?->users_filter ?? 'default';
    }

    public function updatedFilter(string $value): void
    {
        if (Auth::check()) {
            User::whereKey(Auth::id())->update(['users_filter' => $value]);
        }
        $this->resetPage();
    }

    public function render()
    {
        $usersQuery = User::query()
            ->when(! (Auth::user()?->isAdmin()), fn ($q) => $q->where('status', '!=', 'banned'))
            ->when(Auth::check(), function ($q) {
                $currentId = Auth::id();
                $q->whereDoesntHave('blockedBy', fn ($qq) => $qq->whereKey($currentId))
                    ->whereDoesntHave('blocks', fn ($qq) => $qq->whereKey($currentId));
            });

        // Apply filter constraints (subset selection)
        switch ($this->filter) {
            case 'following':
                if (Auth::check()) {
                    $currentId = Auth::id();
                    $usersQuery->whereHas('followers', fn ($q) => $q->whereKey($currentId));
                } else {
                    $usersQuery->whereRaw('1 = 0');
                }
                break;
            case 'verified':
                $usersQuery->whereIn('status', ['admin', 'verified']);
                break;
            case 'official':
                $usersQuery->where('status', 'admin');
                break;
            case 'latest':
            case 'name':
            case 'default':
            default:
                // No subset restriction beyond base constraints
                break;
        }

        // Apply search constraints (name/email), if provided
        if (! empty($this->search)) {
            $usersQuery->where(function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        // Apply ordering based on active filter (even when searching)
        switch ($this->filter) {
            case 'latest':
                $usersQuery->orderBy('created_at', 'desc');
                break;
            case 'following':
                $usersQuery->orderBy('name', 'asc');
                break;
            case 'name':
                $usersQuery->orderBy('name', 'asc');
                break;
            case 'verified':
                $usersQuery->orderBy('name', 'asc');
                break;
            case 'official':
                $usersQuery->orderBy('name', 'asc');
                break;
            case 'default':
            default:
                $usersQuery->orderBy('status', 'desc')->orderBy('name', 'asc');
        }

        $users = $usersQuery->paginate(12); // 12 users per page
        $users->appends(array_filter([
            'search' => $this->search ?: null,
            'filter' => $this->filter !== 'default' ? $this->filter : null,
        ]));

        return view('pages.users.users-list', [
            'users' => $users,
            'filter' => $this->filter,
        ]);
    }

    /**
     * Get the display status for a user
     */
    public function getDisplayStatus($status)
    {
        return match ($status) {
            'admin' => 'Admin',
            'verified' => 'Verified',
            'user' => 'User',
            'banned' => 'Banned',
            default => 'User'
        };
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Admin-only: Update a user's status
     */
    public function updateStatus(int $userId, string $status): void
    {
        if (! Auth::check() || ! Auth::user()->isAdmin()) {
            return;
        }

        if (! in_array($status, ['user', 'verified', 'banned', 'admin'], true)) {
            return;
        }

        $target = User::find($userId);
        if (! $target) {
            return;
        }

        // Admins cannot edit other admins' status
        if ($target->isAdmin()) {
            return;
        }

        $old = $target->status;
        if ($old === $status) {
            return;
        }

        $target->update(['status' => $status]);

        // Create system notification for the user about status change
        $type = match ($status) {
            'verified' => 'status_to_verified',
            'banned' => 'status_to_banned',
            'admin' => 'status_to_admin',
            'user' => $old === 'banned' ? 'status_unbanned' : 'status_to_user',
            default => 'status_to_user',
        };

        \App\Models\Notification::create([
            'user_id' => $target->id,
            'actor_id' => Auth::id(),
            'type' => $type,
            'post_id' => null,
            'comment_id' => null,
        ]);

        $this->resetPage();
    }
}
