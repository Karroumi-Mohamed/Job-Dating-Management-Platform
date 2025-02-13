<?php

namespace App\Models;

use App\Core\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes;

    protected $table = 'announcements';
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'title',
        'description',
        'company_id',
        'image'
    ];

    public $timestamps = true;

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
} 