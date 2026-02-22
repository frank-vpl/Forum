<?php

namespace App\Livewire\Pages;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class PostsList extends Component
{
    use WithPagination;

    public string $filter = 'news';

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

    public function render()
    {
        $query = Post::with('user')
            ->whereHas('user', fn ($q) => $q->where('status', '!=', 'banned'))
            ->withCount(['likes', 'views', 'comments']);

        switch ($this->filter) {
            case 'most_likes':
                $query->orderByDesc('likes_count');
                break;
            case 'most_views':
                $query->orderByDesc('views_count');
                break;
            case 'verified_users':
                $query->whereHas('user', fn ($q) => $q->whereIn('status', ['admin', 'verified']))
                    ->orderByDesc('created_at');
                break;
            case 'admin_posts':
                $query->whereHas('user', fn ($q) => $q->where('status', 'admin'))
                    ->orderByDesc('created_at');
                break;
            case 'news':
            default:
                $query->orderByDesc('created_at');
        }

        $posts = $query->paginate(12);

        $likedPostIds = [];
        if (Auth::check()) {
            $ids = $posts->getCollection()->pluck('id');
            $likedPostIds = PostLike::where('user_id', Auth::id())
                ->whereIn('post_id', $ids)
                ->pluck('post_id')
                ->all();
        }

        return view('pages.forum.posts-list', [
            'posts' => $posts,
            'likedPostIds' => $likedPostIds,
            'filter' => $this->filter,
        ]);
    }
}
