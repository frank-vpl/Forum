<?php

namespace App\Livewire\Pages;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PostCreate extends Component
{
    public ?int $postId = null;

    public string $title = '';

    public string $category = '';

    public string $content = '';

    public function mount(?int $postId = null): void
    {
        $this->postId = $postId;
        if ($this->postId) {
            $query = Post::where('id', $this->postId);
            if (! (Auth::user()?->isAdmin())) {
                $query->where('user_id', Auth::id());
            }
            $post = $query->first();
            if (! $post) {
                $this->redirect(url('/forum/'.$this->postId), navigate: true);

                return;
            }
            $this->title = $post->title;
            $this->category = $post->category;
            $this->content = $post->content;
        }
    }

    public function save(): void
    {
        $this->validate([
            'title' => ['required', 'string', 'max:60'],
            'category' => ['required', 'string', 'max:20'],
            'content' => ['required', 'string'],
        ]);

        if ($this->postId) {
            $query = Post::where('id', $this->postId);
            if (! (Auth::user()?->isAdmin())) {
                $query->where('user_id', Auth::id());
            }
            $post = $query->firstOrFail();
            $post->update([
                'title' => trim($this->title),
                'category' => trim($this->category),
                'content' => $this->content,
            ]);
            if ((Auth::user()?->isAdmin()) && $post->user_id !== Auth::id()) {
                \App\Models\Notification::create([
                    'user_id' => $post->user_id,
                    'actor_id' => Auth::id(),
                    'type' => 'post_admin_edited',
                    'post_id' => $post->id,
                ]);
            }
        } else {
            $trimTitle = trim($this->title);
            $trimCategory = trim($this->category);
            $existing = Post::where('user_id', Auth::id())
                ->where('title', $trimTitle)
                ->where('category', $trimCategory)
                ->where('content', $this->content)
                ->where('created_at', '>=', now()->subMinute())
                ->first();
            if ($existing) {
                $post = $existing;
            } else {
                $post = Post::create([
                    'user_id' => Auth::id(),
                    'title' => $trimTitle,
                    'category' => $trimCategory,
                    'content' => $this->content,
                ]);
            }
        }

        $this->redirect(url('/forum/'.$post->id), navigate: true);
    }

    public function render()
    {
        return view('pages.forum.post-create');
    }
}
