<?php

namespace App\Models;

use App\Core\Model;
use App\Models\Role;

class User extends Model 
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = ['password'];
    public $timestamps = true;

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
