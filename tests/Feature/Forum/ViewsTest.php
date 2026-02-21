<?php

namespace Tests\Feature\Forum;

use App\Livewire\Pages\PostShow;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ViewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_logged_in_user_only_counts_once(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create();

        $this->actingAs($user);

        Livewire::test(PostShow::class, ['postId' => $post->id])->call('$refresh');
        Livewire::test(PostShow::class, ['postId' => $post->id])->call('$refresh');
        Livewire::test(PostShow::class, ['postId' => $post->id])->call('$refresh');

        $this->assertDatabaseCount('post_views', 1);
    }
}
