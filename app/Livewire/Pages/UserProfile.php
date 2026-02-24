<?php

namespace App\Livewire\Pages;

use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostView;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class UserProfile extends Component
{
    use WithPagination;

    public int $userId;

    public ?User $user = null;

    public int $postsCount = 0;

    public int $viewsTotal = 0;

    public int $commentsTotal = 0;

    public function mount(int $userId): void
    {
        $this->userId = $userId;
        $this->user = User::findOrFail($this->userId);
        $this->postsCount = Post::where('user_id', $this->userId)->count();
        $this->viewsTotal = PostView::whereHas('post', fn ($q) => $q->where('user_id', $this->userId))->count();
        $this->commentsTotal = Comment::whereHas('post', fn ($q) => $q->where('user_id', $this->userId))->count();
    }

    public function render()
    {
        $blockedBy = Auth::check() && Auth::user()->isBlockedById($this->userId);
        $hasBlocked = Auth::check() && Auth::user()->hasBlockedId($this->userId);
        $postsQuery = Post::with('user')
            ->withCount(['likes', 'views', 'comments'])
            ->where('user_id', $this->userId)
            ->orderByDesc('created_at');
        if ($blockedBy || $hasBlocked) {
            $postsQuery->whereRaw('1 = 0');
        }
        $posts = $postsQuery->paginate(10);
        $posts->withPath('/user/'.$this->userId);

        $likedPostIds = [];
        if (Auth::check()) {
            $ids = $posts->getCollection()->pluck('id');
            $likedPostIds = PostLike::where('user_id', Auth::id())
                ->whereIn('post_id', $ids)
                ->pluck('post_id')
                ->all();
        }

        return view('pages.users.profile', [
            'user' => $this->user,
            'posts' => $posts,
            'postsCount' => $this->postsCount,
            'viewsTotal' => $this->viewsTotal,
            'commentsTotal' => $this->commentsTotal,
            'likedPostIds' => $likedPostIds,
            'blockedBy' => $blockedBy,
            'hasBlocked' => $hasBlocked,
        ]);
    }

    public function toggleLike(int $postId): void
    {
        $this->recordUniqueViewFor($postId);
        if (! Auth::check()) {
            return;
        }
        if (config('auth.require_email_verification') && ! Auth::user()->hasVerifiedEmail()) {
            $path = route('forum.show', ['id' => $postId], absolute: false);
            $this->redirect(route('verification.notice', ['redirect' => ltrim($path, '/')]), navigate: true);

            return;
        }

        $post = Post::with('user')->find($postId);
        if (! $post || $post->user?->isBanned()) {
            return;
        }
        if (Auth::check() && ($post->user_id) && (Auth::user()->hasBlockedId($post->user_id) || Auth::user()->isBlockedById($post->user_id))) {
            return;
        }

        $existing = PostLike::where('post_id', $postId)->where('user_id', Auth::id())->first();
        if ($existing) {
            $existing->delete();
            Notification::where('type', 'post_like')
                ->where('post_id', $postId)
                ->where('actor_id', Auth::id())
                ->where('user_id', $post->user_id)
                ->delete();
        } else {
            PostLike::create([
                'post_id' => $postId,
                'user_id' => Auth::id(),
            ]);
            if ($post->user_id !== Auth::id()) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'actor_id' => Auth::id(),
                    'type' => 'post_like',
                    'post_id' => $postId,
                ]);
            }
        }
        // Livewire will re-render; counts and liked state refresh automatically
    }

    private function recordUniqueViewFor(int $postId): void
    {
        $userId = Auth::id();
        $viewerHash = null;

        if (! $userId) {
            $ip = request()->ip() ?? '';
            $ua = request()->userAgent() ?? '';
            $viewerHash = hash('sha256', $ip.'|'.$ua);
        }

        if ($userId) {
            PostView::firstOrCreate(
                ['post_id' => $postId, 'user_id' => $userId],
                ['viewer_hash' => null]
            );
        } else {
            if ($viewerHash) {
                PostView::firstOrCreate(
                    ['post_id' => $postId, 'viewer_hash' => $viewerHash],
                    ['user_id' => null]
                );
            }
        }
    }
}
