<?php

namespace Tests\Feature;

use App\Models\Competition;
use App\Models\Fixture;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PolicyCoverageTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_roles_can_view_competitions(): void
    {
        foreach (User::ROLES as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->assertTrue($user->can('viewAny', Competition::class));
        }
    }

    public function test_all_roles_can_view_a_competition(): void
    {
        $competition = Competition::factory()->create();

        foreach (User::ROLES as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->assertTrue($user->can('view', $competition));
        }
    }

    public function test_only_admins_and_organizers_can_create_competitions(): void
    {
        foreach (User::ROLES as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->assertSame(
                in_array($role, ['admin', 'organizer'], true),
                $user->can('create', Competition::class),
                "Permiso de creación de competición incorrecto para el rol {$role}."
            );
        }
    }

    public function test_only_admins_and_organizers_can_update_competitions(): void
    {
        $competition = Competition::factory()->create();

        foreach (User::ROLES as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->assertSame(
                in_array($role, ['admin', 'organizer'], true),
                $user->can('update', $competition),
                "Permiso de modificación de competición incorrecto para el rol {$role}."
            );
        }
    }

    public function test_only_admins_can_delete_competitions(): void
    {
        $competition = Competition::factory()->create();

        foreach (User::ROLES as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->assertSame(
                $role === 'admin',
                $user->can('delete', $competition),
                "Permiso de eliminación de competición incorrecto para el rol {$role}."
            );
        }
    }

    public function test_all_roles_can_view_fixtures(): void
    {
        $fixture = Fixture::factory()->create();

        foreach (User::ROLES as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->assertTrue($user->can('viewAny', Fixture::class));
            $this->assertTrue($user->can('view', $fixture));
        }
    }

    public function test_only_admins_organizers_and_referees_can_manage_fixtures(): void
    {
        $fixture = Fixture::factory()->create();

        foreach (User::ROLES as $role) {
            $user = User::factory()->create(['role' => $role]);

            $canManage = in_array($role, ['admin', 'organizer', 'referee'], true);

            $this->assertSame(
                $canManage,
                $user->can('create', Fixture::class),
                "Permiso de creación de partido incorrecto para el rol {$role}."
            );

            $this->assertSame(
                $canManage,
                $user->can('update', $fixture),
                "Permiso de modificación de partido incorrecto para el rol {$role}."
            );
        }
    }

    public function test_only_admins_and_organizers_can_delete_fixtures(): void
    {
        $fixture = Fixture::factory()->create();

        foreach (User::ROLES as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->assertSame(
                in_array($role, ['admin', 'organizer'], true),
                $user->can('delete', $fixture),
                "Permiso de eliminación de partido incorrecto para el rol {$role}."
            );
        }
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create(['role' => 'fan']);

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk();
    }

    public function test_guest_cannot_access_dashboard(): void
    {
        $this->get(route('dashboard'))
            ->assertRedirect('/login');
    }
}
