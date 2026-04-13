<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecheckRequest extends Model
{
    protected $table = 'recheck_request';
    protected $primaryKey = 'request_id';
    public $timestamps = false;

    protected $fillable = [
        'student_id',
        'semester_id',
        'date_request',
        'status',
        'is_read',
        'is_new'
    ];

    public function details()
    {
        return $this->hasMany(RecheckDetail::class, 'request_id', 'request_id');
    }

    
}