<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'consignee_id',
        'input_date',
        'etd',
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
        'etd' => 'date',
        'distance' => 'integer',
        'eta' => 'date',
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
     * Get the consignee that owns the expedition.
     */
    public function consignee(): BelongsTo
    {
        return $this->belongsTo(Consignee::class);
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
    public function vendorCosts(): HasOne
    {
        return $this->hasOne(ExpeditionCostVendor::class);
    }

    /**
     * Get the fleet costs for this expedition.
     */
    public function fleetCosts(): HasOne
    {
        return $this->hasOne(ExpeditionCostFleet::class);
    }

    /**
     * Get the route that owns the expedition.
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Get the goods for this expedition.
     */
    public function expeditionGoods(): HasOne
    {
        return $this->hasOne(ExpeditionGood::class);
    }
}
