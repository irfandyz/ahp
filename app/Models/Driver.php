<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
    ];

    /**
     * Get the expeditions for this driver.
     */
    public function expeditions(): HasMany
    {
        return $this->hasMany(Expedition::class);
    }
}
