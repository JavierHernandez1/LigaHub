<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Competition;
use App\Models\Fixture;
use App\Models\MatchEvent;
use App\Models\Player;
use App\Models\Season;
use App\Models\Team;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        fake()->seed(20261014);

        User::factory()->create([
            'name' => 'Admin LigaHub',
            'email' => 'admin@ligahub.test',
        ]);

        $competition = Competition::create([
            'name' => 'LigaHub Municipal',
            'slug' => 'ligahub-municipal',
            'description' => 'Competicion base para validar equipos, jugadores, calendario y eventos.',
            'type' => 'league',
            'organizer' => 'LigaHub',
        ]);

        $category = Category::create([
            'name' => 'Senior Masculino',
            'slug' => 'senior-masculino',
            'age_group' => 'senior',
            'gender' => 'male',
        ]);

        $season = Season::create([
            'competition_id' => $competition->id,
            'category_id' => $category->id,
            'name' => '2026/2027',
            'starts_on' => '2026-09-01',
            'ends_on' => '2027-06-30',
            'status' => 'active',
        ]);

        $teamData = [
            ['name' => 'Atletico Centro', 'city' => 'Madrid', 'venue' => 'Campo Municipal Centro'],
            ['name' => 'Union Norte', 'city' => 'Alcobendas', 'venue' => 'Estadio Norte'],
            ['name' => 'Deportivo Rio', 'city' => 'Getafe', 'venue' => 'Ciudad Deportiva Rio'],
            ['name' => 'Sporting Sur', 'city' => 'Leganes', 'venue' => 'Polideportivo Sur'],
        ];

        $positions = ['goalkeeper', 'defender', 'midfielder', 'forward', 'defender', 'midfielder'];
        $teams = collect($teamData)->map(function (array $data, int $teamIndex) use ($season, $positions): Team {
            $venue = Venue::create([
                'name' => $data['venue'],
                'city' => $data['city'],
                'address' => 'Avenida del Deporte '.($teamIndex + 1),
                'capacity' => 1500 + ($teamIndex * 750),
            ]);

            $team = Team::create([
                'venue_id' => $venue->id,
                'name' => $data['name'],
                'slug' => Str::slug($data['name']),
                'city' => $data['city'],
                'founded_year' => 1985 + $teamIndex,
            ]);

            Player::factory()
                ->count(6)
                ->create()
                ->each(function (Player $player, int $playerIndex) use ($team, $season, $positions): void {
                    $team->players()->attach($player->id, [
                        'season_id' => $season->id,
                        'squad_number' => $playerIndex + 1,
                        'position' => $positions[$playerIndex],
                        'joined_on' => $season->starts_on,
                        'left_on' => null,
                        'is_captain' => $playerIndex === 2,
                    ]);
                });

            return $team;
        })->values();

        $fixtures = collect([
            [$teams[0], $teams[1], 1, '2026-09-12 18:00:00', 2, 1],
            [$teams[2], $teams[3], 1, '2026-09-12 20:00:00', 0, 0],
            [$teams[0], $teams[2], 2, '2026-09-19 18:00:00', null, null],
            [$teams[1], $teams[3], 2, '2026-09-19 20:00:00', null, null],
        ])->map(function (array $fixtureData) use ($season): Fixture {
            [$homeTeam, $awayTeam, $matchday, $scheduledAt, $homeScore, $awayScore] = $fixtureData;

            return Fixture::create([
                'season_id' => $season->id,
                'home_team_id' => $homeTeam->id,
                'away_team_id' => $awayTeam->id,
                'venue_id' => $homeTeam->venue_id,
                'matchday' => $matchday,
                'scheduled_at' => $scheduledAt,
                'status' => $homeScore === null ? 'scheduled' : 'completed',
                'home_score' => $homeScore,
                'away_score' => $awayScore,
            ]);
        });

        $firstFixture = $fixtures->first();
        $homePlayers = $teams[0]->players()->wherePivot('season_id', $season->id)->get();
        $awayPlayers = $teams[1]->players()->wherePivot('season_id', $season->id)->get();

        MatchEvent::create([
            'fixture_id' => $firstFixture->id,
            'team_id' => $teams[0]->id,
            'player_id' => $homePlayers[3]->id,
            'type' => 'goal',
            'minute' => 18,
            'extra_minute' => null,
            'notes' => 'Gol tras asistencia desde banda derecha.',
        ]);

        MatchEvent::create([
            'fixture_id' => $firstFixture->id,
            'team_id' => $teams[1]->id,
            'player_id' => $awayPlayers[4]->id,
            'type' => 'yellow_card',
            'minute' => 42,
            'extra_minute' => null,
            'notes' => 'Entrada imprudente.',
        ]);

        MatchEvent::create([
            'fixture_id' => $firstFixture->id,
            'team_id' => $teams[0]->id,
            'player_id' => $homePlayers[2]->id,
            'type' => 'goal',
            'minute' => 76,
            'extra_minute' => null,
            'notes' => 'Segundo gol local.',
        ]);
    }
}
