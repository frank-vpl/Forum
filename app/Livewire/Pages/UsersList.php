<?php

namespace App\Livewire\Pages;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersList extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = ['search'];

    public function render()
    {
        $usersQuery = User::where('status', '!=', 'banned')
            ->orderBy('status', 'desc') // Order by status: admin, verified, user
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
            default => 'User'
        };
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }
}
