<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $timestamps = false; // ✅ tắt created_at + updated_at tự động
    protected $fillable = [
        'invoice_id',
        'image_path',
        'note',
        'status',
        'approved_at',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}