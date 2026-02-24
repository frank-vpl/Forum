<?php

namespace App\Livewire\Pages;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UserConnections extends Component
{
    use WithPagination;

    public int $userId;

    public string $mode = 'followers';

    public string $q = '';

    public array $keepVisible = [];

    protected $queryString = [
        'q' => ['except' => ''],
    ];

    public function mount(int $userId, string $mode = 'followers'): void
    {
        $this->userId = $userId;
        $this->mode = in_array($mode, ['followers', 'following'], true) ? $mode : 'followers';
    }

    public function updatedQ(): void
    {
        $this->resetPage();
    }

    public function followUser(int $targetId): void
    {
        if (! Auth::check() || Auth::id() === $targetId) {
            return;
        }
        $me = Auth::user();
        if ($me->hasBlockedId($targetId) || $me->isBlockedById($targetId)) {
            return;
        }
        if (! $me->hasFollowedId($targetId)) {
            $isReciprocal = User::whereKey($targetId)->first()?->hasFollowedId(Auth::id()) ?? false;
            $me->follows()->attach($targetId);
            if ($isReciprocal) {
                Notification::create([
                    'user_id' => $targetId,
                    'actor_id' => Auth::id(),
                    'type' => 'user_follow_back',
                    'post_id' => null,
                    'comment_id' => null,
                ]);
            } else {
                Notification::create([
                    'user_id' => $targetId,
                    'actor_id' => Auth::id(),
                    'type' => 'user_follow',
                    'post_id' => null,
                    'comment_id' => null,
                ]);
            }
        }
    }

    public function unfollowUser(int $targetId): void
    {
        if (! Auth::check() || Auth::id() === $targetId) {
            return;
        }
        $me = Auth::user();
        if ($me->hasFollowedId($targetId)) {
            $me->follows()->detach($targetId);
            Notification::whereIn('type', ['user_follow', 'user_follow_back'])
                ->where('user_id', $targetId)
                ->where('actor_id', Auth::id())
                ->delete();
            $this->keepVisible[$targetId] = true;
        }
    }

    public function nextPage(): void
    {
        $this->setPage($this->page + 1);
    }

    public function render()
    {
        $base = User::query();

        if ($this->mode === 'followers') {
            $base = User::whereHas('follows', fn ($q) => $q->where('followed_id', $this->userId));
        } else {
            $idsToKeep = array_keys($this->keepVisible);
            $base = User::query()->where(function ($q) use ($idsToKeep) {
                $q->whereHas('followers', fn ($q2) => $q2->where('follower_id', $this->userId));
                if (! empty($idsToKeep)) {
                    $q->orWhereIn('users.id', $idsToKeep);
                }
            });
        }

        $ownerId = $this->userId;
        $base->whereDoesntHave('blocks', fn ($q) => $q->where('users.id', $ownerId));
        $base->whereDoesntHave('blockedBy', fn ($q) => $q->where('users.id', $ownerId));

        if ($this->q !== '') {
            $base->where('name', 'like', '%'.$this->q.'%');
        }

        $users = $base->orderBy('name')->paginate(15);

        return view('pages.users.connections', [
            'users' => $users,
            'userId' => $this->userId,
            'mode' => $this->mode,
        ]);
    }
}
