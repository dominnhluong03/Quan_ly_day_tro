<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomAsset extends Model
{
    protected $table = 'room_assets';

    protected $fillable = [
        'room_id',
        'name',
        'quantity',
        'status',
        'note'
    ];

    public $timestamps = false;

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function issues()
    {
        return $this->hasMany(Issue::class, 'room_asset_id');
    }
}