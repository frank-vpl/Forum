<?php

namespace App\Livewire\Pages;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PostComments extends Component
{
    public int $postId;

    public int $page = 1;

    public int $perPage = 10;

    public array $visibleChildren = []; // per comment id, how many replies are visible

    public string $newComment = '';

    public array $replyText = [];

    public bool $loadingMore = false;

    public ?string $loadError = null;

    public function mount(int $postId): void
    {
        $this->postId = $postId;
    }

    public function addRoot(): void
    {
        $this->validate([
            'newComment' => ['required', 'string', 'max:2000'],
        ]);

        if (! Auth::check()) {
            return;
        }

        Comment::create([
            'post_id' => $this->postId,
            'user_id' => Auth::id(),
            'text' => trim($this->newComment),
        ]);

        $post = Post::find($this->postId);
        if ($post && $post->user_id !== Auth::id()) {
            Notification::create([
                'user_id' => $post->user_id,
                'actor_id' => Auth::id(),
                'type' => 'post_comment',
                'post_id' => $this->postId,
            ]);
        }

        $this->newComment = '';
    }

    public function addReply(int $parentId): void
    {
        $text = $this->replyText[$parentId] ?? '';
        $this->validate([
            "replyText.$parentId" => ['required', 'string', 'max:2000'],
        ]);

        if (! Auth::check()) {
            return;
        }

        $parent = Comment::find($parentId);
        if (! $parent) {
            return;
        }

        $rootId = $parent->parent_id ?: $parent->id;

        Comment::create([
            'post_id' => $this->postId,
            'user_id' => Auth::id(),
            'parent_id' => $rootId,
            'reply_to_comment_id' => $parentId,
            'text' => trim($text),
        ]);

        if ($parent && $parent->user_id !== Auth::id()) {
            Notification::create([
                'user_id' => $parent->user_id,
                'actor_id' => Auth::id(),
                'type' => 'comment_reply',
                'post_id' => $this->postId,
                'comment_id' => $parentId,
            ]);
        }

        $this->replyText[$parentId] = '';
        $this->dispatch('comment-replied', parentId: $parentId);
    }

    public function deleteComment(int $commentId): void
    {
        if (! Auth::check()) {
            return;
        }

        $comment = Comment::find($commentId);
        if ($comment && $comment->user_id === Auth::id()) {
            $comment->delete();

            if ($comment->parent_id === null) {
                Notification::where('type', 'post_comment')
                    ->where('post_id', $comment->post_id)
                    ->where('actor_id', $comment->user_id)
                    ->delete();
            } else {
                Notification::where('type', 'comment_reply')
                    ->where('post_id', $comment->post_id)
                    ->where('actor_id', $comment->user_id)
                    ->when($comment->reply_to_comment_id, fn ($q) => $q->where('comment_id', $comment->reply_to_comment_id))
                    ->delete();
            }
        }
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

    public function showMore(int $parentId): void
    {
        $current = $this->visibleChildren[$parentId] ?? 2;
        $this->visibleChildren[$parentId] = $current + 2;
    }

    public function showLess(int $parentId): void
    {
        $this->visibleChildren[$parentId] = 2;
    }

    public function render()
    {
        $query = Comment::with(['user'])
            ->where('post_id', $this->postId)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc');

        $total = (clone $query)->count();

        $roots = $query->limit($this->page * $this->perPage)->get();

        $hasMore = ($this->page * $this->perPage) < $total;

        return view('pages.forum.post-comments', [
            'roots' => $roots,
            'hasMore' => $hasMore,
        ]);
    }
}
