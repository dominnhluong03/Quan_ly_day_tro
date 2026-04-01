<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $fillable = [
        'room_id','tenant_id','room_asset_id',
        'title','content','status','image_path','resolved_at'
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function asset()
    {
        return $this->belongsTo(RoomAsset::class, 'room_asset_id');
    }

    public function roomAsset()
    {
        return $this->belongsTo(RoomAsset::class, 'room_asset_id');
    }
}