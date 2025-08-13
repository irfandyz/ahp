<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpeditionCostFleet extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'expedition_id',
        'fleet_id',
        'cost',
    ];

    /**
     * Get the expedition that owns the cost.
     */
    public function expedition(): BelongsTo
    {
        return $this->belongsTo(Expedition::class);
    }

    /**
     * Get the fleet that owns the cost.
     */
    public function fleet(): BelongsTo
    {
        return $this->belongsTo(Fleet::class);
    }
}
