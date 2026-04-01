<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'house_number',
        'street',
        'ward',
        'district',
        'city',
        'description',
        'amenities'
    ];

    protected $casts = [
        'amenities' => 'array'
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
