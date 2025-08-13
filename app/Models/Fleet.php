<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Fleet extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'fleet_type_id',
        'plate_number',
        'description',
    ];

    /**
     * Get the fleet type that owns the fleet.
     */
    public function fleetType(): BelongsTo
    {
        return $this->belongsTo(FleetType::class);
    }

    /**
     * Get the expeditions for this fleet.
     */
    public function expeditions(): HasMany
    {
        return $this->hasMany(Expedition::class);
    }
}
