<?php

namespace App\Models;

use App\Core\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use SoftDeletes;

    protected $table = 'companies';

    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'name',
        'description',
        'logo'
    ];

    public function announcements()
    {
        return $this->hasMany(Announcement::class);
    }
} 