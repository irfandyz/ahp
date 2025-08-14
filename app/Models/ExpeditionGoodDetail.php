<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExpeditionGoodDetail extends Model
{
    protected $fillable = [
        'expedition_good_id',
        'name',
        'quantity',
        'unit',
        'remark',
    ];

    /**
     * Get the expedition good that owns this detail.
     */
    public function expeditionGood(): BelongsTo
    {
        return $this->belongsTo(ExpeditionGood::class);
    }
}
