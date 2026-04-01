<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    public function tenant()
    {
        return $this->hasOne(Tenant::class);
    }
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    
}
