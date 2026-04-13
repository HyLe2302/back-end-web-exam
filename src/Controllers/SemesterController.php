<?php

namespace App\Controllers;

use App\Services\SemesterService;

class SemesterController{

    protected SemesterService $semesterService;

    public function __construct()
    {
        $this->semesterService = new SemesterService();
    }

    public function getAll()
    {
        try {
            $semesters = $this->semesterService->getAll();
            return successResponse($semesters, 200);
        } catch (\Exception $e) {
            return errorResponse(400, $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if(!$data){
                \errorResponse(400, "Invalid JSON input");
            }

            $semester = $this->semesterService->create($data);
            return successResponse($semester, 201, "Semester created successfully");

        } catch (\Exception $e) {
            return errorResponse(400, $e->getMessage());
        }
    }

    public function update($id)
    {
        try {
            $data = json_decode(file_get_contents('php://input'), true);

            if(!$data){
                \errorResponse(400, "Invalid JSON input");
            }

            $semester = $this->semesterService->update($id, $data);
            return successResponse($semester, 200, "Semester updated successfully");

        } catch (\Exception $e) {
            return errorResponse(400, $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $this->semesterService->delete($id);
            return successResponse(null, 200, "Semester deleted successfully");
        } catch (\Exception $e) {
            return errorResponse(400, $e->getMessage());
        }
    }

    public function getInfoById($id)
    {
        try {
            $semester = $this->semesterService->getInfoById($id);
            if (!$semester) {
                return errorResponse(404, "Semester not found");
            }
            return successResponse($semester, 200);
        } catch (\Exception $e) {
            return errorResponse(400, $e->getMessage());
        }
    }

    public function getOpenSemesterByStudent($studentId)
{
    try {
        $data = $this->semesterService->getOpenSemesterByStudent($studentId);

        successResponse($data);
    } catch (\Throwable $e) {
        errorResponse(500, 'Lấy học kỳ mở theo sinh viên thất bại', [
            'exception' => $e->getMessage()
        ]);
    }
}
}