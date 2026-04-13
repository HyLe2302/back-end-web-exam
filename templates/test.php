<?php

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Load file mẫu có sẵn header
$templatePath = __DIR__ . '/phan_cong_cham_phuc_khao.xlsx';
$spreadsheet = IOFactory::load($templatePath);

$sheet = $spreadsheet->getSheetByName('sheet1');

// Dữ liệu giả
$fakeData = [
    [
        'student_id' => 'SV001',
        'name' => 'Nguyễn Văn An',
        'email' => 'sv001@student.edu.vn',
        'class' => 'CNTT01',
        'phone' => '0901234567',
        'subject_name' => 'Lập trình Web',
        'teacher_name' => 'Nguyễn Văn B',
        'venue_exam' => 'A101 - 07:30',
        'date_exam' => '01/04/2026',
        'final_exam_score' => 6.5,
        'recheck_score' => '',
        'reason' => 'Em nghĩ điểm phần thực hành chưa được cộng đầy đủ',
        'grader_1' => '',
        'grader_2' => '',
        'grader_3' => '',
        'grader_4' => '',
        'graded_at' => '',
        'faculty' => 'Công nghệ thông tin',
        'cohort' => 'Khóa 20'
    ],
    [
        'student_id' => 'SV002',
        'name' => 'Trần Thị Bình',
        'email' => 'sv002@student.edu.vn',
        'class' => 'CNTT02',
        'phone' => '0912345678',
        'subject_name' => 'Cơ sở dữ liệu',
        'teacher_name' => 'Phạm Văn E',
        'venue_exam' => 'P202 - 14:00',
        'date_exam' => '03/04/2026',
        'final_exam_score' => 5.0,
        'recheck_score' => '',
        'reason' => 'Em muốn kiểm tra lại phần truy vấn SQL',
        'grader_1' => '',
        'grader_2' => '',
        'grader_3' => '',
        'grader_4' => '',
        'graded_at' => '',
        'faculty' => 'Công nghệ thông tin',
        'cohort' => 'Khóa 21'
    ]
];

// Đổ dữ liệu từ dòng 2
$row = 8;

foreach ($fakeData as $index => $item) {
    $sheet->setCellValue('A' . $row, $index + 1);
    $sheet->setCellValue('B' . $row, $item['student_id']);
    $sheet->setCellValue('C' . $row, $item['name']);
    $sheet->setCellValue('D' . $row, $item['email']);
    $sheet->setCellValue('E' . $row, $item['class']);
    $sheet->setCellValue('F' . $row, $item['phone']);
    $sheet->setCellValue('G' . $row, $item['subject_class']);
    $sheet->setCellValue('H' . $row, $item['teacher']);
    $sheet->setCellValue('I' . $row, $item['exam_room']);
    $sheet->setCellValue('J' . $row, $item['exam_time']);
    $sheet->setCellValue('K' . $row, $item['published_score']);
    $sheet->setCellValue('L' . $row, $item['recheck_score']);
    $sheet->setCellValue('M' . $row, $item['reason']);
    $sheet->setCellValue('N' . $row, $item['grader_1']);
    $sheet->setCellValue('O' . $row, $item['grader_2']);
    $sheet->setCellValue('P' . $row, $item['grader_3'] ?? '');
    $sheet->setCellValue('Q' . $row, $item['grader_4'] ?? '');
    $sheet->setCellValue('R' . $row, $item['graded_at']);
    $sheet->setCellValue('S' . $row, $item['faculty']);
    $sheet->setCellValue('T' . $row, $item['cohort']);

    $row++;
}

// Xuất file
$fileName = 'danh_sach_phuc_khao.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;