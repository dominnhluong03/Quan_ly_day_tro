<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'building_id',
        'room_code',
        'floor',
        'area',
        'max_people',
        'price',
        'status',
        'description',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function images()
    {
        return $this->hasMany(RoomImage::class);
    }

    public function assets()
    {
        return $this->hasMany(RoomAsset::class);
    }

    // ✅ thêm cái này để BillController lấy hợp đồng active
    public function contracts()
    {
        return $this->hasMany(\App\Models\Contract::class);
    }
}