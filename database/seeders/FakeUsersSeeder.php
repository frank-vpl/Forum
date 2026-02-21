<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FakeUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 30 fake users with different statuses
        $statuses = ['user', 'user', 'user', 'user', 'verified', 'admin'];

        for ($i = 1; $i <= 30; $i++) {
            // Distribute statuses: mostly users, some verified, few admins
            if ($i <= 25) {
                $status = $statuses[array_rand($statuses)];
            } else {
                $status = 'user'; // Last 5 users are regular users
            }

            User::factory()->create([
                'name' => fake()->name(),
                'email' => fake()->unique()->safeEmail(),
                'password' => Hash::make('password'),
                'status' => $status,
                'email_verified_at' => now(),
            ]);
        }
    }
}
