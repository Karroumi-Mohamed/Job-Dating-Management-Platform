<?php

namespace App\Models;

use App\Core\Model;
use App\Models\Permission;
use App\Models\User;

class Role extends Model
{
    protected $fillable = ['name'];

    public function permissions()
    {
        $this->belongsToMany(Permission::class, 'role_permission');
    }

    public function users()
    {
        $this->hasMany(User::class);
    }
}
