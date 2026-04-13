<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    protected $table = 'semester';
    protected $primaryKey = 'semester_id';
    public $timestamps = false;

    protected $fillable = [
        'cohort_id',
        'semester_name',
        'academic_year',
        'start_date',
        'end_date',
        'notice',
        'status'
    ];
}