<?php

namespace App\Models;

use Database\Factories\TeamFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Team extends Model
{
    /** @use HasFactory<TeamFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'venue_id',
        'name',
        'slug',
        'city',
        'founded_year',
    ];

    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(Player::class)
            ->using(PlayerTeam::class)
            ->withPivot([
                'id',
                'season_id',
                'squad_number',
                'position',
                'joined_on',
                'left_on',
                'is_captain',
            ])
            ->withTimestamps();
    }

    public function squadMemberships(): HasMany
    {
        return $this->hasMany(PlayerTeam::class);
    }

    public function homeFixtures(): HasMany
    {
        return $this->hasMany(Fixture::class, 'home_team_id');
    }

    public function awayFixtures(): HasMany
    {
        return $this->hasMany(Fixture::class, 'away_team_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(MatchEvent::class);
    }
}
