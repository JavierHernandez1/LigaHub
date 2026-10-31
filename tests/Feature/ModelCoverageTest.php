<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Competition;
use App\Models\Venue;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModelCoverageTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_has_many_seasons(): void
    {
        $category = Category::factory()->create();

        $this->assertInstanceOf(HasMany::class, $category->seasons());
        $this->assertSame($category->id, $category->seasons()->getParent()->id);
    }

    public function test_competition_has_many_seasons(): void
    {
        $competition = Competition::factory()->create();

        $this->assertInstanceOf(HasMany::class, $competition->seasons());
        $this->assertSame($competition->id, $competition->seasons()->getParent()->id);
    }

    public function test_venue_has_many_teams_and_fixtures(): void
    {
        $venue = Venue::factory()->create();

        $this->assertInstanceOf(HasMany::class, $venue->teams());
        $this->assertInstanceOf(HasMany::class, $venue->fixtures());
        $this->assertSame($venue->id, $venue->teams()->getParent()->id);
        $this->assertSame($venue->id, $venue->fixtures()->getParent()->id);
    }

    public function test_category_uses_soft_deletes(): void
    {
        $category = Category::factory()->create();
        $category->delete();

        $this->assertSoftDeleted('categories', ['id' => $category->id]);
        $this->assertNull(Category::find($category->id));
        $this->assertNotNull(Category::withTrashed()->find($category->id));
    }

    public function test_competition_uses_soft_deletes(): void
    {
        $competition = Competition::factory()->create();
        $competition->delete();

        $this->assertSoftDeleted('competitions', ['id' => $competition->id]);
        $this->assertNull(Competition::find($competition->id));
        $this->assertNotNull(Competition::withTrashed()->find($competition->id));
    }

    public function test_venue_uses_soft_deletes(): void
    {
        $venue = Venue::factory()->create();
        $venue->delete();

        $this->assertSoftDeleted('venues', ['id' => $venue->id]);
        $this->assertNull(Venue::find($venue->id));
        $this->assertNotNull(Venue::withTrashed()->find($venue->id));
    }

    public function test_models_allow_mass_assignment_of_fillable_attributes(): void
    {
        $category = new Category([
            'name' => 'Senior',
            'slug' => 'senior',
            'age_group' => 'Senior',
            'gender' => 'mixed',
        ]);

        $competition = new Competition([
            'name' => 'Liga Regional',
            'slug' => 'liga-regional',
            'description' => 'Competición de prueba',
            'type' => 'league',
            'organizer' => 'Organización de prueba',
        ]);

        $venue = new Venue([
            'name' => 'Campo Central',
            'city' => 'Murcia',
            'address' => 'Calle Principal',
            'capacity' => 1000,
        ]);

        $this->assertSame('Senior', $category->name);
        $this->assertSame('Liga Regional', $competition->name);
        $this->assertSame('Campo Central', $venue->name);
        $this->assertSame(1000, $venue->capacity);
    }
}
