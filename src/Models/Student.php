<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $primaryKey = 'student_id';
    public $timestamps = false;

    protected $casts = [
        'student_id' => 'string'
    ];
}
