<?php

namespace App\Livewire\Pages;

use App\Models\Notification;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostView;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PostsList extends Component
{
    use WithPagination;

    public string $filter = 'news';
    public string $search = '';
    public int $page = 1;
    public int $perPage = 12;
    public bool $loadingMore = false;
    public ?string $loadError = null;

    public function mount(): void
    {
        $this->filter = Auth::user()?->post_filter ?? 'news';
    }

    public function updatedFilter(string $value): void
    {
        if (Auth::check()) {
            User::whereKey(Auth::id())->update(['post_filter' => $value]);
        }
        $this->resetPage();
    }

    protected $queryString = [
        'search',
        'filter' => ['except' => 'news'],
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
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
        $query = Post::with('user')
            ->whereHas('user', fn ($q) => $q->where('status', '!=', 'banned'))
            ->withCount(['likes', 'views', 'comments']);
        if (Auth::check()) {
            $currentId = Auth::id();
            $query->whereDoesntHave('user', function ($q) use ($currentId) {
                $q->whereHas('blocks', fn ($qq) => $qq->whereKey($currentId))
                  ->orWhereHas('blockedBy', fn ($qq) => $qq->whereKey($currentId));
            });
        }

        // Subset restriction by filter
        switch ($this->filter) {
            case 'verified_users':
                $query->whereHas('user', fn ($q) => $q->whereIn('status', ['admin', 'verified']));
                break;
            case 'admin_posts':
                $query->whereHas('user', fn ($q) => $q->where('status', 'admin'));
                break;
            case 'most_likes':
            case 'most_views':
            case 'news':
            default:
                // no subset beyond base
                break;
        }

        // Search by title or user name
        if (! empty($this->search)) {
            $s = $this->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', '%'.$s.'%')
                  ->orWhereHas('user', fn ($qq) => $qq->where('name', 'like', '%'.$s.'%'));
            });
        }

        // Ordering by filter (applies even when searching)
        switch ($this->filter) {
            case 'most_likes':
                $query->orderByDesc('likes_count');
                break;
            case 'most_views':
                $query->orderByDesc('views_count');
                break;
            case 'verified_users':
                $query->orderByDesc('created_at');
                break;
            case 'admin_posts':
                $query->orderByDesc('created_at');
                break;
            case 'news':
            default:
                $query->orderByDesc('created_at');
        }

        $total = (clone $query)->count();
        $posts = $query->limit($this->page * $this->perPage)->get();
        $hasMore = ($this->page * $this->perPage) < $total;

        $likedPostIds = [];
        if (Auth::check()) {
            $ids = $posts->pluck('id');
            $likedPostIds = PostLike::where('user_id', Auth::id())
                ->whereIn('post_id', $ids)
                ->pluck('post_id')
                ->all();
        }

        return view('pages.forum.posts-list', [
            'posts' => $posts,
            'likedPostIds' => $likedPostIds,
            'filter' => $this->filter,
            'search' => $this->search,
            'hasMore' => $hasMore,
            'loadingMore' => $this->loadingMore,
            'loadError' => $this->loadError,
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
