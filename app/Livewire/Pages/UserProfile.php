<?php

namespace App\Livewire\Pages;

use App\Models\Comment;
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
        $posts = Post::with('user')
            ->withCount(['likes', 'views', 'comments'])
            ->where('user_id', $this->userId)
            ->orderByDesc('created_at')
            ->paginate(10);
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
        ]);
    }

    public function toggleLike(int $postId): void
    {
        if (! Auth::check()) {
            return;
        }
        if (config('auth.require_email_verification') && ! Auth::user()->hasVerifiedEmail()) {
            $this->redirect(
                route('verification.notice', ['redirect' => ltrim(route('forum.show', ['id' => $postId], absolute: false), '/')]),
                navigate: true
            );
            return;
        }

        $like = PostLike::where('post_id', $postId)->where('user_id', Auth::id())->first();
        if ($like) {
            $like->delete();
        } else {
            PostLike::create(['post_id' => $postId, 'user_id' => Auth::id()]);
        }

        $this->resetPage();
    }
}
