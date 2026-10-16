<?php

namespace App\Models;

use Database\Factories\MatchEventFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MatchEvent extends Model
{
    /** @use HasFactory<MatchEventFactory> */
    use HasFactory;

    protected $fillable = [
        'fixture_id',
        'team_id',
        'player_id',
        'type',
        'minute',
        'extra_minute',
        'notes',
    ];

    public function fixture(): BelongsTo
    {
        return $this->belongsTo(Fixture::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
