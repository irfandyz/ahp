<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpeditionCostVendor extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'expedition_cost_vendors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'expedition_id',
        'sales_amount',
        'vendor_cost',
        'deposit_cost',
        'deposit_date',
        'other_cost',
        'description',
        'total_cost',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'sales_amount' => 'integer',
            'vendor_cost' => 'integer',
            'deposit_cost' => 'integer',
            'deposit_date' => 'date',
            'other_cost' => 'integer',
            'description' => 'string',
            'total_cost' => 'integer',
        ];
    }

    /**
     * Get the expedition that owns the cost.
     */
    public function expedition(): BelongsTo
    {
        return $this->belongsTo(Expedition::class);
    }

    /**
     * Calculate the total cost based on all cost components.
     */
    public function calculateTotalCost(): int
    {
        return ($this->vendor_cost ?? 0) +
               ($this->deposit_cost ?? 0) +
               ($this->other_cost ?? 0);
    }

    /**
     * Boot the model and add event listeners.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-calculate total cost before saving
        static::saving(function ($model) {
            $model->total_cost = $model->calculateTotalCost();
        });
    }
}
