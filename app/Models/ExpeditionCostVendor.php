<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpeditionCostVendor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'expedition_id',
        'vendor_id',
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
     * Get the vendor that owns the cost.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }
}
