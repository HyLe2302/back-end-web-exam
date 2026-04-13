<?php

namespace App\Controllers;

use App\Services\CohortService;

class CohortController
{

    protected CohortService $cohortService;

    public function __construct()
    {
        $this->cohortService = new CohortService();
    }
    
    public function getAll() { 
        try { 
            $data = $this->cohortService->getAll(); 

            return successResponse($data, 200, 'Lấy danh sách khóa học thành công'); 
        } catch (\Throwable $e) { 
            return errorResponse(500, 'Lấy danh sách khóa học thất bại', [ 
                'exception' => $e->getMessage() 
            ]); 
        } 
    }

}