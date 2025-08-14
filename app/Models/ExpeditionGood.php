<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExpeditionGood extends Model
{
    protected $fillable = [
        'expedition_id',
        'good_description',
        'remark_dispatch',
        'remark_receive',
        'date_dispatch',
        'date_receive',
        'total_goods',
        'gross_weight',
        'dimension_total',
        'packaging',
        'special_instruction',
    ];

    protected $casts = [
        'date_dispatch' => 'date',
        'date_receive' => 'date',
        'total_goods' => 'integer',
        'gross_weight' => 'integer',
        'dimension_total' => 'integer',
    ];

    /**
     * Get the expedition that owns the goods.
     */
    public function expedition(): BelongsTo
    {
        return $this->belongsTo(Expedition::class);
    }

    /**
     * Get the good details for this expedition good.
     */
    public function goodDetails(): HasMany
    {
        return $this->hasMany(ExpeditionGoodDetail::class);
    }
}
