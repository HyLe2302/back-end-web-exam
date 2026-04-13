<?php

namespace App\Controllers;

use App\Services\GradeService;

class GradeController
{
    protected GradeService $gradeService;

    public function __construct()
    {
        $this->gradeService = new GradeService();
    }

    public function getGradesByStudent($studentId)
    {
        try {
            $data = $this->gradeService->getGradesByStudent($studentId);

            successResponse($data, 200, [
                'total_records' => count($data)
            ]);
        } catch (\Throwable $e) {
            errorResponse(500, 'Lấy danh sách điểm sinh viên thất bại', [
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function getSubjectDetail()
    {
        $studentId = $_GET['student_id'] ?? null;
        $subjectId = $_GET['subject_id'] ?? null;

        if (!$studentId) {
            errorResponse(400, 'Thiếu student_id');
        }

        if (!$subjectId) {
            errorResponse(400, 'Thiếu subject_id');
        }

        try {
            $data = $this->gradeService->getSubjectDetail($studentId, $subjectId);

            if (!$data) {
                errorResponse(404, 'Không tìm thấy dữ liệu');
            }

            successResponse($data, 200);
        } catch (\Throwable $e) {
            errorResponse(500, 'Lấy chi tiết môn học thất bại', [
                'exception' => $e->getMessage()
            ]);
        }
    }

}