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
            $post = Post::where('id', $this->postId)->where('user_id', Auth::id())->first();
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
            $post = Post::where('id', $this->postId)->where('user_id', Auth::id())->firstOrFail();
            $post->update([
                'title' => trim($this->title),
                'category' => trim($this->category),
                'content' => $this->content,
            ]);
        } else {
            $post = Post::create([
                'user_id' => Auth::id(),
                'title' => trim($this->title),
                'category' => trim($this->category),
                'content' => $this->content,
            ]);
        }

        $this->redirect(url('/forum/'.$post->id), navigate: true);
    }

    public function render()
    {
        return view('pages.forum.post-create');
    }
}
