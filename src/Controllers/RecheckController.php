<?php

namespace App\Controllers;

use App\Services\RecheckService;
use PhpOffice\PhpWord\TemplateProcessor;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RecheckController
{
    protected RecheckService $recheckService;

    public function __construct()
    {
        $this->recheckService = new RecheckService();
    }

    //student functions

    public function store()
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);

            if (!$input) {
                errorResponse(400, 'Dữ liệu gửi lên không hợp lệ');
            }

            $result = $this->recheckService->store($input);

            successResponse($result, 201);
        } catch (\Throwable $e) {
            errorResponse(500, 'Tạo đơn phúc khảo thất bại', [
                'exception' => $e->getMessage()
            ]);
        }
    }

    function removeVietnameseAccents($str)
    {
        $str = strtolower($str);

        $accents = [
            'a' => ['á','à','ả','ã','ạ','ă','ắ','ằ','ẳ','ẵ','ặ','â','ấ','ầ','ẩ','ẫ','ậ'],
            'd' => ['đ'],
            'e' => ['é','è','ẻ','ẽ','ẹ','ê','ế','ề','ể','ễ','ệ'],
            'i' => ['í','ì','ỉ','ĩ','ị'],
            'o' => ['ó','ò','ỏ','õ','ọ','ô','ố','ồ','ổ','ỗ','ộ','ơ','ớ','ờ','ở','ỡ','ợ'],
            'u' => ['ú','ù','ủ','ũ','ụ','ư','ứ','ừ','ử','ữ','ự'],
            'y' => ['ý','ỳ','ỷ','ỹ','ỵ']
        ];

        foreach ($accents as $nonAccent => $accentList) {
            foreach ($accentList as $accent) {
                $str = str_replace($accent, $nonAccent, $str);
            }
        }

        // thay khoảng trắng = _
        $str = preg_replace('/\s+/', '_', $str);

        return $str;
    }

    public function download($id)
    {
        try {
            $formData = $this->recheckService->getRecheckFormData((int)$id);

            $template = new TemplateProcessor(__DIR__ . '/../../templates/Don_xin_phuc_khao.docx');

            $template->setValue('name', $formData['student']['name'] ?? '');
            $template->setValue('email', $formData['student']['email'] ?? '');
            $template->setValue('day_of_birth', $formData['student']['day_of_birth'] ?? '');
            $template->setValue('class', $formData['student']['class'] ?? '');
            $template->setValue('student_id', $formData['student']['student_id'] ?? '');
            $template->setValue('academic', $formData['semester']['academic_year'] ?? '');
            $template->setValue('level', $formData['student']['level'] ?? '');
            $template->setValue('phone', $formData['student']['phone'] ?? '');
            $template->setValue(
                'semester',
                ($formData['semester']['semester_name'] ?? '') . ' (' . ($formData['semester']['academic_year'] ?? '') . ')'
            );
            $template->setValue('total', count($formData['details']));

            $template->cloneRow('stt', count($formData['details']));

            foreach ($formData['details'] as $index => $item) {
                $i = $index + 1;

                $template->setValue("stt#$i", $i);
                $template->setValue("subject#$i", $item['subject_name'] ?? '');
                $template->setValue("teacher#$i", $item['teacher_name'] ?? '');
                $template->setValue("date#$i", $item['date_exam'] ?? '');
                $template->setValue("place#$i", $item['venue_exam'] ?? '');
                $template->setValue("score#$i", $item['final_score'] ?? '');
                $template->setValue("reason#$i", $item['reason'] ?? '');
            }

            $storagePath = __DIR__ . '/../../storage';
            if (!is_dir($storagePath)) {
                mkdir($storagePath, 0777, true);
            }

            $studentName = $formData['student']['name'] ?? 'unknown';
            $studentNameNoAccent = $this->removeVietnameseAccents($studentName);

            $fileName = 'don_phuc_khao_' . $studentNameNoAccent . '_'. '.docx';
            $filePath = $storagePath . '/' . $fileName;

            $template->saveAs($filePath);

            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=\"$fileName\"");
            header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
            header("Content-Length: " . filesize($filePath));

            readfile($filePath);
            unlink($filePath);
            exit;
        } catch (\Throwable $e) {
            errorResponse(500, 'Tải đơn phúc khảo thất bại', [
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function getListByStudent($studentId)
    {
        try {
            $data = $this->recheckService->getListByStudent($studentId);

            successResponse($data, 200, [
                'student_id' => $studentId,
                'total_records' => count($data)
            ]);
        } catch (\Throwable $e) {
            errorResponse(500, 'Lấy danh sách môn đang pending thất bại', [
                'exception' => $e->getMessage()
            ]);
        }
    }


    //admin functions

    public function getUnreadRequestCount()
    {
        try {
            $count = $this->recheckService->getUnreadRequestCount();

            return successResponse([
                'unread_count' => $count
            ], 200, '');

        } catch (\Exception $e) {
            return errorResponse(400, $e->getMessage());
        }
    }

    public function markAllRequestAsRead()
    {
        try {
            $this->recheckService->markAllRequestAsRead();

            return successResponse([], 200, 'Đã đánh dấu đã đọc');

        } catch (\Exception $e) {
            return errorResponse(400, $e->getMessage());
        }
    }

    public function markNewRead($requestId)
    {
        try {
            $this->recheckService->markNewRead($requestId);

        } catch (\Exception $e) {
            return errorResponse(400, $e->getMessage());
        }
    }

    public function getStatistics()
    {
        try {
            $data = $this->recheckService->getStatistics();

            return successResponse($data, 200, 'Lấy thống kê thành công');
        } catch (\Throwable $e) {
            return errorResponse(500, 'Lấy thống kê thất bại', [
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function getStudentRecheckList()
    {
        try {
            $data = $this->recheckService->getStudentRecheckList();

            return successResponse($data, 200, 'Lấy danh sách sinh viên gửi đơn thành công');
        } catch (\Throwable $e) {
            return errorResponse(500, 'Lấy danh sách thất bại', [
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function getSubjectsByStudent($studentId, $requestId)
    {
        try {
            $data = $this->recheckService->getSubjectsByStudent($studentId, $requestId);

            return successResponse($data);

        } catch (\Throwable $e) {
            return errorResponse(500, 'Lấy danh sách môn phúc khảo thất bại', [
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function updateDetailStatus($detailId)
    {
        try {
            $input = json_decode(file_get_contents('php://input'), true);

            if (!$input || !isset($input['status'])) {
                return errorResponse(400, 'Thiếu status');
            }

            $result = $this->recheckService->updateDetailStatus($detailId, $input['status']);

            return successResponse($result);

        } catch (\Throwable $e) {
            return errorResponse(500, 'Cập nhật trạng thái thất bại', [
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function updateDetailScore($detailId)
    {
        try {
            $body = json_decode(file_get_contents("php://input"), true);

            $recheckedScore = $body['rechecked_score'] ?? null;

            if ($recheckedScore === null || $recheckedScore === '') {
                return errorResponse(400, 'Vui lòng nhập điểm phúc khảo');
            }

            $data = $this->recheckService->updateDetailScore(
                $detailId,
                $recheckedScore
            );

            successResponse($data, 200);
        } catch (\Throwable $e) {
            errorResponse(500, $e->getMessage());
        }
    }

    public function getApprovedDetailStatus()
    {
        try {
            $data = $this->recheckService->getApprovedDetailStatus();

            successResponse($data, 200, [
                'total_records' => count($data)
            ]);
        } catch (\Throwable $e) {
            errorResponse(500, 'Lấy danh sách phúc khảo approved thất bại', [
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function getCompletedDetailStatus()
    {
        try {
            $data = $this->recheckService->getCompletedDetailStatus();

            successResponse($data, 200, [
                'total_records' => count($data)
            ]);
        } catch (\Throwable $e) {
            errorResponse(500, 'Lấy danh sách phúc khảo completed thất bại', [
                'exception' => $e->getMessage()
            ]);
        }
}

    public function updateRequestStatus($requestId) { 
        try { 
            
            $data = json_decode(file_get_contents("php://input"), true); 
            $result = $this->recheckService->updateRequestStatus((int)$requestId, $data);

            return successResponse($result, 200, 'Cập nhật trạng thái thành công'); 
            } catch (\Throwable $e) { 
                return errorResponse(400, $e->getMessage()); 
            } 
    }

    public function validateExport($cohort = 'all', $status = 'approved')
    {
        try {
            $cohort = $_GET['cohort'] ?? 'all';
            $status = $_GET['status'] ?? 'approved';

            $result = $this->recheckService->exportRecheckData($cohort, $status);

            if (!$result['success']) {
                return errorResponse(400, $result['message']);
            }

            $totalRows = count($result['data']);

            return successResponse([
                'total_rows' => $totalRows,
                'message' => 'Có ' . $totalRows . ' dòng dữ liệu để xuất'
            ], 200, '');

        } catch (\Exception $e) {
            return errorResponse(400, $e->getMessage());
        }
    }

    public function exportExcel()
    {
        $cohort = $_GET['cohort'] ?? 'all';
        $status = $_GET['status'] ?? 'approved';

        $result = $this->recheckService->exportRecheckData($cohort, $status);

        if (!$result['success']) {
            return errorResponse(400, $result['message']);
        }

        $data = $result['data'];

        $templatePath = __DIR__ .'/../../templates/phan_cong_cham_phuc_khao.xlsx';
        $spreadsheet = IOFactory::load($templatePath);

        $sheet = $spreadsheet->getSheetByName('sheet1');

        $row = 8;

        foreach ($data as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item -> student_id);
            $sheet->setCellValue('C' . $row, $item -> name);
            $sheet->setCellValue('D' . $row, $item -> email);
            $sheet->setCellValue('E' . $row, $item -> class);
            $sheet->setCellValue('F' . $row, $item -> phone);
            $sheet->setCellValue('G' . $row, $item -> subject_name);
            $sheet->setCellValue('H' . $row, $item -> teacher_name);
            $sheet->setCellValue('I' . $row, $item -> venue_exam);
            $sheet->setCellValue('J' . $row, $item -> date_exam);
            $sheet->setCellValue('K' . $row, $item -> final_exam_score);
            $sheet->setCellValue('L' . $row, $item -> recheck_score ?? '');
            $sheet->setCellValue('M' . $row, $item -> reason);
            $sheet->setCellValue('N' . $row, $item -> grader_1 ?? '');
            $sheet->setCellValue('O' . $row, $item -> grader_2 ?? '');
            $sheet->setCellValue('P' . $row, $item -> grader_3 ?? '');
            $sheet->setCellValue('Q' . $row, $item -> grader_4 ?? '');
            $sheet->setCellValue('R' . $row, $item -> graded_at ?? '');
            $sheet->setCellValue('S' . $row, $item -> faculty);
            $sheet->setCellValue('T' . $row, $item -> cohort);
            $sheet->setCellValue('U' . $row, $item -> status);

            $row++;
        }

        // Auto width
        foreach (range('A', 'L') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // File name theo filter
        $fileName = 'Danh sách phúc khảo ';

        if ($cohort == 'all') {
            $fileName .= 'toàn khóa ';
        }

        if ($cohort !== 'all') {
            $fileName .= 'khóa ' . $cohort;
        }

        if ($status !== 'all') {
            $fileName .= 'với trạng thái ' . $status;
        }

        $fileName .= '.xlsx';

        if (ob_get_length()) {
            ob_end_clean();
        }

        // Xuất file
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}