<?php

namespace Tests\Feature\Forum;

use App\Livewire\Pages\PostCreate;
use App\Livewire\Pages\PostShow;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ForumTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_post(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Livewire::test(PostCreate::class)
            ->set('title', 'Hello World')
            ->set('category', 'General')
            ->set('content', 'This is **markdown**')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('posts', [
            'title' => 'Hello World',
            'category' => 'General',
            'user_id' => $user->id,
        ]);
    }

    public function test_like_is_unique_per_user(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $this->actingAs($user);

        Livewire::test(PostShow::class, ['postId' => $post->id])
            ->call('toggleLike')
            ->call('toggleLike');

        $this->assertDatabaseCount('post_likes', 0);

        Livewire::test(PostShow::class, ['postId' => $post->id])
            ->call('toggleLike');

        $this->assertDatabaseCount('post_likes', 1);
    }
}
