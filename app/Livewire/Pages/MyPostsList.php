<?php

namespace App\Livewire\Pages;

use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MyPostsList extends Component
{
    use WithPagination;

    public function render()
    {
        $posts = Post::with('user')
            ->withCount(['likes', 'views', 'comments'])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);

        $likedPostIds = [];
        if (Auth::check()) {
            $ids = $posts->getCollection()->pluck('id');
            $likedPostIds = PostLike::where('user_id', Auth::id())
                ->whereIn('post_id', $ids)
                ->pluck('post_id')
                ->all();
        }

        return view('pages.forum.my-list', [
            'posts' => $posts,
            'likedPostIds' => $likedPostIds,
        ]);
    }
}
