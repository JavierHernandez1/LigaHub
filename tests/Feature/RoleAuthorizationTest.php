<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_fan_cannot_access_admin_area(): void
    {
        $user = User::factory()->create(['role' => 'fan']);

        $this->actingAs($user)
            ->get('/admin')
            ->assertForbidden();
    }

    public function test_admin_can_access_admin_area(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
            ->get('/admin')
            ->assertOk();
    }

    public function test_guest_is_redirected_to_login_from_admin_area(): void
    {
        $this->get('/admin')
            ->assertRedirect('/login');
    }

    public function test_competition_policy_restricts_creation(): void
    {
        $fan = User::factory()->create(['role' => 'fan']);
        $organizer = User::factory()->create(['role' => 'organizer']);

        $this->assertFalse($fan->can('create', \App\Models\Competition::class));
        $this->assertTrue($organizer->can('create', \App\Models\Competition::class));
    }

    public function test_fixture_policy_allows_referees_to_manage_fixtures(): void
    {
        $referee = User::factory()->create(['role' => 'referee']);

        $this->assertTrue($referee->can('create', \App\Models\Fixture::class));
    }
}