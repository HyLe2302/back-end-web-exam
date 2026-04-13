<?php

namespace App\Services;

use Illuminate\Database\Capsule\Manager as Capsule;

class CohortService
{
    //admin functions
    public function getAll() { 
        return Capsule::table('cohorts') 
        ->select( 'id', 'cohort' ) 
        ->orderBy('id', 'asc') 
        ->get() 
        ->map(function ($item) 
        { 
            return [ 
                'id' => (int) $item->id, 
                'cohort' => $item->cohort 
            ]; 
        }); 
    }
}