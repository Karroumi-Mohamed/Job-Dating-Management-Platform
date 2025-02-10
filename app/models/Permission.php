<?php

namespace App\Models;

use App\Core\Model;
use App\Models\Role;

class Permission extends Model
{
    protected $fillable = ['name'];

    public function roles()
    {
        $this->belongsToMany(Role::class);
    }
}
