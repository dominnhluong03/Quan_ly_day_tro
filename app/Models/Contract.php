<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contract extends Model
{
    use HasFactory;
<<<<<<< HEAD
=======

>>>>>>> feb1f02 (first commit)
    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'room_id',
        'start_date',
        'end_date',
        'deposit',
        'electric_price',
        'water_price',
        'service_note',
        'status',
        'contract_file',

<<<<<<< HEAD
=======

>>>>>>> feb1f02 (first commit)
        // ký hợp đồng
        'tenant_signed_at',
        'tenant_signed_name',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'tenant_signed_at' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class);
    }

    public function room()
    {
        return $this->belongsTo(\App\Models\Room::class);
    }
}