<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class FakePostsSeeder extends Seeder
{
    public function run(): void
    {
        $ids = User::pluck('id')->all();
        if (count($ids) === 0) {
            $ids = User::factory()->count(10)->create()->pluck('id')->all();
        }
        $categories = ['General', 'Help', 'Ideas', 'News', 'Discussion'];
        for ($i = 0; $i < 30; $i++) {
            $uid = Arr::random($ids);
            Post::factory()->create([
                'user_id' => $uid,
                'title' => Str::limit(fake()->sentence(random_int(3, 10)), 60, ''),
                'category' => Arr::random($categories),
                'content' => fake()->paragraphs(random_int(2, 6), true),
            ]);
        }
    }
}
