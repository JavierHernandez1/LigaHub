<?php

namespace App\Models;

use Database\Factories\PlayerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Player extends Model
{
    /** @use HasFactory<PlayerFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'document_number',
        'birth_date',
        'preferred_foot',
    ];

    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class)
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

    public function events(): HasMany
    {
        return $this->hasMany(MatchEvent::class);
    }
}
