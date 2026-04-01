<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'contract_id',
        'meter_id',
        'month',
        'year',
        'room_price',
        'electric_cost',
        'water_cost',
        'service_total',
        'total',
        'status',
        'invoice_file',
        'qr_image',      // ✅ thêm
        'created_at',
    ];

    public function contract()
    {
        return $this->belongsTo(\App\Models\Contract::class);
    }

    public function meter()
    {
        return $this->belongsTo(\App\Models\MeterReading::class, 'meter_id');
    }

    public function services()
    {
        return $this->hasMany(\App\Models\InvoiceService::class, 'invoice_id');
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }

    public function latestPayment()
    {
        return $this->hasOne(\App\Models\Payment::class)->latestOfMany();
    }
}