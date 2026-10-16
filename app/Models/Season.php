<?php

namespace App\Models;

use Database\Factories\SeasonFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Season extends Model
{
    /** @use HasFactory<SeasonFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'competition_id',
        'category_id',
        'name',
        'starts_on',
        'ends_on',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'starts_on' => 'date',
            'ends_on' => 'date',
        ];
    }

    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function fixtures(): HasMany
    {
        return $this->hasMany(Fixture::class);
    }

    public function squadMemberships(): HasMany
    {
        return $this->hasMany(PlayerTeam::class);
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'player_team')
            ->using(PlayerTeam::class)
            ->select('teams.*')
            ->distinct();
    }
}
