<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consignee extends Model
{
    use HasFactory;

    protected $fillable = [
        'company',
        'address',
        'phone',
        'email',
    ];

    public function expeditions()
    {
        return $this->hasMany(Expedition::class);
    }
}
