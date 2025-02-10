<?php

namespace App\Models;

use App\Core\Model;

class Company extends Model
{
    protected $table = 'companies';
    protected $softDelete = true;
    
    protected $fillable = [
        'name',
        'description'
    ];

    public $timestamps = true;

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
} 