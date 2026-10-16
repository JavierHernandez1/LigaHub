<?php

namespace App\Models;

use Database\Factories\FixtureFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fixture extends Model
{
    /** @use HasFactory<FixtureFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'season_id',
        'home_team_id',
        'away_team_id',
        'venue_id',
        'matchday',
        'scheduled_at',
        'status',
        'home_score',
        'away_score',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
        ];
    }

    public function season(): BelongsTo
    {
        return $this->belongsTo(Season::class);
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(MatchEvent::class);
    }
}
