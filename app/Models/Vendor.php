<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'company',
        'address',
        'city',
        'pic',
        'title_pic',
        'phone',
        'moda',
        'fleet',
        'area_service_coverage',
    ];

    /**
     * Get the expeditions for this vendor.
     */
    public function expeditions(): HasMany
    {
        return $this->hasMany(Expedition::class);
    }

    /**
     * Get expeditions count attribute.
     */
    public function getExpeditionsCountAttribute(): int
    {
        return $this->expeditions()->count();
    }
}
