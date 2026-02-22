<?php

namespace App\Livewire\Pages;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UsersList extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function render()
    {
        $usersQuery = User::query()
            ->when(!(Auth::user()?->isAdmin()), fn ($q) => $q->where('status', '!=', 'banned'))
            ->orderBy('status', 'desc') // Order by status: admin, verified, user, banned
            ->orderBy('name', 'asc'); // Then by name alphabetically

        if (! empty($this->search)) {
            $usersQuery->where(function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        $users = $usersQuery->paginate(12); // 12 users per page

        return view('pages.users.users-list', [
            'users' => $users,
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
