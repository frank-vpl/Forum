<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BannedUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_banned_user_cannot_access_dashboard(): void
    {
        $bannedUser = User::factory()->create([
            'status' => 'banned',
        ]);

        $response = $this->actingAs($bannedUser)
            ->get(route('dashboard'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email' => 'Your account has been banned.']);
    }

    public function test_regular_user_can_access_dashboard(): void
    {
        $regularUser = User::factory()->create([
            'status' => 'user',
        ]);

        $response = $this->actingAs($regularUser)
            ->get(route('dashboard'));

        $response->assertOk();
    }

    public function test_verified_user_can_access_dashboard(): void
    {
        $verifiedUser = User::factory()->create([
            'status' => 'verified',
        ]);

        $response = $this->actingAs($verifiedUser)
            ->get(route('dashboard'));

        $response->assertOk();
    }

    public function test_admin_user_can_access_dashboard(): void
    {
        $adminUser = User::factory()->create([
            'status' => 'admin',
        ]);

        $response = $this->actingAs($adminUser)
            ->get(route('dashboard'));

        $response->assertOk();
    }

    public function test_banned_user_cannot_access_settings(): void
    {
        $bannedUser = User::factory()->create([
            'status' => 'banned',
        ]);

        $response = $this->actingAs($bannedUser)
            ->get('/settings/profile');

        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email' => 'Your account has been banned.']);
    }

    public function test_banned_user_is_logged_out_when_accessing_protected_routes(): void
    {
        $bannedUser = User::factory()->create([
            'status' => 'banned',
        ]);

        // Authenticate the user first
        $this->actingAs($bannedUser);

        // Verify the user is authenticated
        $this->assertAuthenticatedAs($bannedUser);

        // Access a protected route
        $response = $this->get(route('dashboard'));

        // The user should be logged out and redirected
        $this->assertGuest();
        $response->assertRedirect(route('login'));
        $response->assertSessionHasErrors(['email' => 'Your account has been banned.']);
    }
}
