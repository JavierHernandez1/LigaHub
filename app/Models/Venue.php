<?php

namespace App\Models;

use Database\Factories\VenueFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Venue extends Model
{
    /** @use HasFactory<VenueFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'city',
        'address',
        'capacity',
    ];

    public function teams(): HasMany
    {
        return $this->hasMany(Team::class);
    }

    public function fixtures(): HasMany
    {
        return $this->hasMany(Fixture::class);
    }
}
