<?php

namespace App\Livewire\Pages;

use App\Models\Comment;
use App\Models\Notification;
use App\Models\Post;
use App\Models\PostLike;
use App\Models\PostView;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PostShow extends Component
{
    public int $postId;

    public ?Post $post = null;

    public int $likesCount = 0;

    public int $viewsCount = 0;

    public int $commentsCount = 0;

    public bool $liked = false;

    public function mount(int $postId): void
    {
        $this->postId = $postId;
        $this->loadPost();
        $this->recordUniqueView();
    }

    private function loadPost(): void
    {
        $this->post = Post::with('user')->withCount(['likes', 'views', 'comments'])->findOrFail($this->postId);
        $this->likesCount = (int) ($this->post->likes_count ?? 0);
        $this->viewsCount = (int) ($this->post->views_count ?? 0);
        $this->commentsCount = (int) ($this->post->comments_count ?? 0);
        $this->liked = Auth::check() && PostLike::where('post_id', $this->postId)->where('user_id', Auth::id())->exists();
    }

    public function toggleLike(): void
    {
        if (! Auth::check()) {
            return;
        }

        $like = PostLike::where('post_id', $this->postId)->where('user_id', Auth::id())->first();

        if ($like) {
            $like->delete();
            $this->liked = false;
            if ($this->post) {
                Notification::where('type', 'post_like')
                    ->where('post_id', $this->postId)
                    ->where('actor_id', Auth::id())
                    ->where('user_id', $this->post->user_id)
                    ->delete();
            }
        } else {
            PostLike::create([
                'post_id' => $this->postId,
                'user_id' => Auth::id(),
            ]);
            $this->liked = true;

            if ($this->post && $this->post->user_id !== Auth::id()) {
                Notification::create([
                    'user_id' => $this->post->user_id,
                    'actor_id' => Auth::id(),
                    'type' => 'post_like',
                    'post_id' => $this->postId,
                ]);
            }
        }

        $this->refreshCounts();
    }

    private function recordUniqueView(): void
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
                ['post_id' => $this->postId, 'user_id' => $userId],
                ['viewer_hash' => null]
            );
        } else {
            if ($viewerHash) {
                PostView::firstOrCreate(
                    ['post_id' => $this->postId, 'viewer_hash' => $viewerHash],
                    ['user_id' => null]
                );
            }
        }

        $this->refreshCounts();
    }

    private function refreshCounts(): void
    {
        $this->likesCount = PostLike::where('post_id', $this->postId)->count();
        $this->viewsCount = PostView::where('post_id', $this->postId)->count();
        $this->commentsCount = Comment::where('post_id', $this->postId)->count();
    }

    public function deletePost(): void
    {
        if (! Auth::check()) {
            return;
        }
        $post = Post::find($this->postId);
        if (! $post || $post->user_id !== Auth::id()) {
            return;
        }
        $post->delete();
        $this->redirect(url('/dashboard'), navigate: true);
    }

    public function render()
    {
        return view('pages.forum.post-show');
    }
}
