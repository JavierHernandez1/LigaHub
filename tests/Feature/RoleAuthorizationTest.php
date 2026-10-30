<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_only_admin_can_access_admin_area(): void
    {
        foreach (User::ROLES as $role) {
            $user = User::factory()->create(['role' => $role]);

            $response = $this->actingAs($user)->get(route('admin.dashboard'));

            if ($role === 'admin') {
                $response->assertOk();
            } else {
                $response->assertForbidden();
            }
        }
    }

    public function test_guest_is_redirected_to_login_from_admin_area(): void
    {
        $this->get(route('admin.dashboard'))
            ->assertRedirect('/login');
    }

    public function test_guest_can_access_public_homepage(): void
    {
        $this->get('/')
            ->assertOk();
    }

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect('/login');
    }

    public function test_competition_policy_restricts_creation(): void
    {
        $fan = User::factory()->create(['role' => 'fan']);
        $organizer = User::factory()->create(['role' => 'organizer']);
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertFalse($fan->can('create', \App\Models\Competition::class));
        $this->assertTrue($organizer->can('create', \App\Models\Competition::class));
        $this->assertTrue($admin->can('create', \App\Models\Competition::class));
    }

    public function test_fixture_policy_allows_referees_to_manage_fixtures(): void
    {
        $referee = User::factory()->create(['role' => 'referee']);
        $fan = User::factory()->create(['role' => 'fan']);

        $this->assertTrue($referee->can('create', \App\Models\Fixture::class));
        $this->assertFalse($fan->can('create', \App\Models\Fixture::class));
    }
}
