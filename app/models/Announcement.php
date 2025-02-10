<?php

namespace App\Models;

use App\Core\Model;

class Announcement extends Model
{
    protected $table = 'announcements';
    protected $softDelete = true;
    
    protected $fillable = [
        'title',
        'description',
        'company_id'
    ];

    public $timestamps = true;

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
} 