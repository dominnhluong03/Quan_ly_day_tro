<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $table = 'tenants';

    protected $fillable = [

        'user_id',
        'phone',
        'job',
        'birthday',
        'gender',
        'hometown',
        'cccd_front',
        'cccd_back',
        'status',

    ];

    public $timestamps = true;


    /* Quan hệ user */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function contracts()
    {
        return $this->hasMany(\App\Models\Contract::class);
    }
}
