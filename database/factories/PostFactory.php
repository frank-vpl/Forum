<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(6),
            'category' => $this->faker->randomElement(['General', 'Help', 'Ideas']),
            'content' => $this->faker->paragraph(4),
        ];
    }
}
