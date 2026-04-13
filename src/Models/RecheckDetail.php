<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecheckDetail extends Model
{
    protected $table = 'recheck_detail';
    protected $primaryKey = 'detail_id';
    public $timestamps = false;

    protected $fillable = [
        'request_id',
        'subject_id',
        'final_score',
        'reason',
        'status',
        'rechecked_scrore',
        'scrore_update_at'
    ];

    public function request()
    {
        return $this->belongsTo(RecheckRequest::class, 'request_id', 'request_id');
    }
}