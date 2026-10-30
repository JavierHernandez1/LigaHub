<?php

namespace Tests\Feature;

use App\Models\Competition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompetitionCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_competitions(): void
    {
        $this->get(route('competitions.index'))
            ->assertRedirect('/login');
    }

    public function test_fan_can_view_competitions_but_cannot_create_them(): void
    {
        $fan = User::factory()->create(['role' => 'fan']);

        $this->actingAs($fan)
            ->get(route('competitions.index'))
            ->assertOk()
            ->assertDontSee('Nueva competición', false);

        $this->actingAs($fan)
            ->get(route('competitions.create'))
            ->assertForbidden();
    }

    public function test_organizer_can_create_a_competition_and_generate_its_slug(): void
    {
        $organizer = User::factory()->create(['role' => 'organizer']);

        $response = $this->actingAs($organizer)->post(route('competitions.store'), [
            'name' => 'Liga de Prueba',
            'description' => 'Competición creada mediante una prueba funcional.',
            'type' => 'league',
            'organizer' => 'Organización de Prueba',
        ]);

        $competition = Competition::where('name', 'Liga de Prueba')->first();

        $this->assertNotNull($competition);
        $this->assertSame('liga-de-prueba', $competition->slug);

        $response->assertRedirect(route('competitions.show', $competition));
    }

    public function test_fan_cannot_create_a_competition(): void
    {
        $fan = User::factory()->create(['role' => 'fan']);

        $this->actingAs($fan)
            ->post(route('competitions.store'), [
                'name' => 'Competición no permitida',
                'type' => 'league',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('competitions', [
            'name' => 'Competición no permitida',
        ]);
    }

    public function test_competition_creation_validates_required_fields(): void
    {
        $organizer = User::factory()->create(['role' => 'organizer']);

        $this->actingAs($organizer)
            ->post(route('competitions.store'), [
                'name' => '',
                'type' => 'invalid',
            ])
            ->assertSessionHasErrors(['name', 'type']);
    }

    public function test_organizer_can_update_a_competition(): void
    {
        $organizer = User::factory()->create(['role' => 'organizer']);
        $competition = Competition::factory()->create([
            'name' => 'Nombre anterior',
            'slug' => 'nombre-anterior',
        ]);

        $this->actingAs($organizer)
            ->put(route('competitions.update', $competition), [
                'name' => 'Nombre actualizado',
                'description' => 'Descripción actualizada.',
                'type' => 'cup',
                'organizer' => 'Organizador actualizado',
            ])
            ->assertRedirect(route('competitions.show', $competition));

        $this->assertDatabaseHas('competitions', [
            'id' => $competition->id,
            'name' => 'Nombre actualizado',
            'slug' => 'nombre-actualizado',
            'type' => 'cup',
        ]);
    }

    public function test_fan_cannot_update_a_competition(): void
    {
        $fan = User::factory()->create(['role' => 'fan']);
        $competition = Competition::factory()->create();

        $this->actingAs($fan)
            ->put(route('competitions.update', $competition), [
                'name' => 'Cambio no autorizado',
                'type' => 'league',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('competitions', [
            'id' => $competition->id,
            'name' => 'Cambio no autorizado',
        ]);
    }

    public function test_only_admin_can_delete_a_competition(): void
    {
        $fan = User::factory()->create(['role' => 'fan']);
        $competition = Competition::factory()->create();

        $this->actingAs($fan)
            ->delete(route('competitions.destroy', $competition))
            ->assertForbidden();

        $this->assertDatabaseHas('competitions', [
            'id' => $competition->id,
            'deleted_at' => null,
        ]);

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->delete(route('competitions.destroy', $competition))
            ->assertRedirect(route('competitions.index'));

        $this->assertSoftDeleted('competitions', [
            'id' => $competition->id,
        ]);
    }
}