<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Competition;
use App\Models\Fixture;
use App\Models\MatchEvent;
use App\Models\Player;
use App\Models\Season;
use App\Models\Team;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeded_database_demonstrates_core_relationships(): void
    {
        $this->seed();

        $this->assertDatabaseCount('competitions', 1);
        $this->assertDatabaseCount('categories', 1);
        $this->assertDatabaseCount('teams', 4);
        $this->assertDatabaseCount('players', 24);
        $this->assertDatabaseCount('fixtures', 4);
        $this->assertDatabaseCount('match_events', 3);

        $season = Season::with(['competition', 'category', 'teams', 'fixtures.events'])->firstOrFail();

        $this->assertSame('LigaHub Municipal', $season->competition->name);
        $this->assertSame('Senior Masculino', $season->category->name);
        $this->assertCount(4, $season->teams);
        $this->assertCount(4, $season->fixtures);
        $this->assertCount(3, $season->fixtures->first()->events);

        $team = Team::with('players')->where('slug', 'atletico-centro')->firstOrFail();

        $this->assertCount(6, $team->players);
        $this->assertSame(1, $team->players->first()->pivot->squad_number);
        $this->assertSame($season->id, $team->players->first()->pivot->season_id);
    }

    public function test_player_team_pivot_keeps_extra_squad_data_from_code(): void
    {
        $season = Season::factory()->create([
            'name' => '2026/2027',
            'starts_on' => '2026-09-01',
            'ends_on' => '2027-06-30',
            'status' => 'active',
        ]);
        $team = Team::factory()->create();
        $player = Player::factory()->create();

        $team->players()->attach($player->id, [
            'season_id' => $season->id,
            'squad_number' => 10,
            'position' => 'midfielder',
            'joined_on' => '2026-09-01',
            'left_on' => null,
            'is_captain' => true,
        ]);

        $team->players()->updateExistingPivot($player->id, [
            'squad_number' => 8,
            'position' => 'forward',
        ]);

        $playerWithTeam = Player::with('teams')->firstOrFail();
        $pivot = $playerWithTeam->teams->first()->pivot;

        $this->assertSame(8, $pivot->squad_number);
        $this->assertSame('forward', $pivot->position);
        $this->assertTrue((bool) $pivot->is_captain);
        $this->assertDatabaseHas('player_team', [
            'player_id' => $player->id,
            'team_id' => $team->id,
            'season_id' => $season->id,
            'squad_number' => 8,
            'position' => 'forward',
            'is_captain' => true,
        ]);
    }

    public function test_fixtures_link_season_teams_venue_and_events(): void
    {
        $competition = Competition::factory()->create();
        $category = Category::factory()->create();
        $season = Season::factory()->create([
            'competition_id' => $competition->id,
            'category_id' => $category->id,
        ]);
        $venue = Venue::factory()->create();
        $homeTeam = Team::factory()->create(['venue_id' => $venue->id]);
        $awayTeam = Team::factory()->create();
        $player = Player::factory()->create();

        $homeTeam->players()->attach($player->id, [
            'season_id' => $season->id,
            'squad_number' => 9,
            'position' => 'forward',
            'joined_on' => $season->starts_on,
            'is_captain' => false,
        ]);

        $fixture = Fixture::factory()->completed()->create([
            'season_id' => $season->id,
            'home_team_id' => $homeTeam->id,
            'away_team_id' => $awayTeam->id,
            'venue_id' => $venue->id,
            'matchday' => 3,
        ]);

        MatchEvent::factory()->create([
            'fixture_id' => $fixture->id,
            'team_id' => $homeTeam->id,
            'player_id' => $player->id,
            'type' => 'goal',
            'minute' => 64,
        ]);

        $fixture->load(['season.competition', 'homeTeam.players', 'awayTeam', 'venue', 'events.player']);

        $this->assertSame($competition->id, $fixture->season->competition->id);
        $this->assertSame($homeTeam->id, $fixture->homeTeam->id);
        $this->assertSame($awayTeam->id, $fixture->awayTeam->id);
        $this->assertSame($venue->id, $fixture->venue->id);
        $this->assertSame('goal', $fixture->events->first()->type);
        $this->assertSame($player->id, $fixture->events->first()->player->id);
    }

    public function test_soft_deleted_team_stays_available_for_historical_queries(): void
    {
        $this->seed();

        $team = Team::where('slug', 'atletico-centro')->firstOrFail();
        $fixtureId = $team->homeFixtures()->firstOrFail()->id;

        $team->delete();

        $this->assertSoftDeleted('teams', ['id' => $team->id]);
        $this->assertNotNull(Team::withTrashed()->find($team->id));
        $this->assertSame($team->id, Fixture::findOrFail($fixtureId)->home_team_id);
    }
}
