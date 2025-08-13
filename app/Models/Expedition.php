<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\ExpeditionCostFleet;
use App\Models\ExpeditionCostVendor;

class Expedition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'order_number',
        'input_date',
        'travel_date',
        'origin',
        'destination',
        'distance',
        'industry_sector_id',
        'description',
        'route_id',
        'detail_route',
        'expedition_type',
        'vendor_id',
        'fleet_id',
        'driver_id',
        'customer_id',
        'eta',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'eta' => 'integer',
        ];
    }

    /**
     * Get the user that owns the expedition.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the industry sector for the expedition.
     */
    public function industrySector(): BelongsTo
    {
        return $this->belongsTo(IndustrySector::class);
    }

    /**
     * Get the route for the expedition.
     */
    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    /**
     * Get the vendor for the expedition.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * Get the fleet for the expedition.
     */
    public function fleet(): BelongsTo
    {
        return $this->belongsTo(Fleet::class);
    }

    /**
     * Get the customer for the expedition.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the driver for the expedition.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(Driver::class);
    }

    /**
     * Get the fleet costs for the expedition.
     */
    public function fleet_costs(): HasOne
    {
        return $this->hasOne(ExpeditionCostFleet::class);
    }

    /**
     * Get the vendor costs for the expedition.
     */
    public function vendor_costs(): HasOne
    {
        return $this->hasOne(ExpeditionCostVendor::class);
    }

    /**
     * Get the total cost for fleet expeditions.
     */
    public function getFleetTotalCostAttribute(): int
    {
        if ($this->expedition_type === 'fleet' && $this->fleetCosts) {
            return $this->fleetCosts->total_cost ?? 0;
        }
        return 0;
    }

    /**
     * Get the total cost for vendor expeditions.
     */
    public function getVendorTotalCostAttribute(): int
    {
        if ($this->expedition_type === 'vendor' && $this->vendorCosts) {
            return $this->vendorCosts->total_cost ?? 0;
        }
        return 0;
    }

    /**
     * Get the total cost based on expedition type.
     */
    public function getTotalCostAttribute(): int
    {
        if ($this->expedition_type === 'fleet') {
            return $this->fleet_total_cost;
        } elseif ($this->expedition_type === 'vendor') {
            return $this->vendor_total_cost;
        }
        return 0;
    }

    /**
     * Get the sales amount from cost records.
     */
    public function getSalesAmountAttribute(): int
    {
        if ($this->expedition_type === 'fleet' && $this->fleetCosts) {
            return $this->fleetCosts->sales_amount ?? 0;
        } elseif ($this->expedition_type === 'vendor' && $this->vendorCosts) {
            return $this->vendorCosts->sales_amount ?? 0;
        }
        return 0;
    }
}
