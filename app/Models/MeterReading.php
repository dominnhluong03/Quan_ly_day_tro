<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MeterReading extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'room_id','month','year',
        'electric_old','electric_new',
        'water_old','water_new',
        'created_at'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}