<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expedition extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'order_number',
        'customer_id',
        'input_date',
        'travel_date',
        'origin',
        'destination',
        'distance',
        'description',
        'industry_sector_id',
        'route_id',
        'detail_route',
        'expedition_type',
        'vendor_id',
        'fleet_id',
        'driver_id',
        'eta',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'input_date' => 'date',
        'travel_date' => 'date',
        'distance' => 'integer',
        'eta' => 'integer',
    ];

    /**
     * Get the user that owns the expedition.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the customer that owns the expedition.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the industry sector that owns the expedition.
     */
    public function industrySector(): BelongsTo
    {
        return $this->belongsTo(IndustrySector::class);
    }

    /**
     * Get the fleet that owns the expedition.
     */
    public function fleet(): BelongsTo
    {
        return $this->belongsTo(Fleet::class);
    }

    /**
     * Get the driver that owns the expedition.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Get the vendor that owns the expedition.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the vendor costs for this expedition.
     */
    public function vendorCosts(): HasMany
    {
        return $this->hasMany(ExpeditionCostVendor::class);
    }

    /**
     * Get the fleet costs for this expedition.
     */
    public function fleetCosts(): HasMany
    {
        return $this->hasMany(ExpeditionCostFleet::class);
    }
}
