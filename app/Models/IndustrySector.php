<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IndustrySector extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the expeditions for this industry sector.
     */
    public function expeditions(): HasMany
    {
        return $this->hasMany(Expedition::class);
    }
}
