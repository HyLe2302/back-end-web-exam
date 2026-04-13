-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 13, 2026 lúc 10:45 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `recheck_exam`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cohorts`
--

CREATE TABLE `cohorts` (
  `id` int(10) NOT NULL,
  `cohort` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cohorts`
--

INSERT INTO `cohorts` (`id`, `cohort`) VALUES
(17, 'Khóa 17'),
(18, 'Khóa 18'),
(19, 'Khóa 19'),
(20, 'Khóa 20'),
(21, 'Khóa 21'),
(22, 'Khóa 22'),
(23, 'Khóa 23'),
(24, 'Khóa 24'),
(25, 'Khóa 25'),
(26, 'Khóa 26'),
(27, 'Khóa 27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `exams`
--

CREATE TABLE `exams` (
  `exam_id` int(11) NOT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `semester_id` int(11) DEFAULT NULL,
  `date_exam` date DEFAULT NULL,
  `venue_exam` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `exams`
--

INSERT INTO `exams` (`exam_id`, `subject_id`, `semester_id`, `date_exam`, `venue_exam`) VALUES
(1, 1, 1, '2022-12-10', 'A101 - 07:30'),
(2, 2, 1, '2022-12-12', 'A102 - 09:00'),
(3, 3, 1, '2022-12-14', 'B201 - 13:30'),
(4, 4, 1, '2022-12-16', 'B202 - 07:30'),
(5, 5, 1, '2022-12-18', 'C101 - 09:00'),
(6, 6, 1, '2022-12-20', 'C102 - 13:30'),
(7, 7, 2, '2023-05-10', 'A101 - 07:30'),
(8, 8, 2, '2023-05-12', 'A102 - 09:00'),
(9, 9, 2, '2023-05-14', 'B201 - 13:30'),
(10, 10, 2, '2023-05-16', 'B202 - 07:30'),
(11, 11, 2, '2023-05-18', 'C101 - 09:00'),
(12, 12, 2, '2023-05-20', 'C102 - 13:30'),
(13, 13, 2, '2023-05-22', 'D201 - 07:30');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `grades`
--

CREATE TABLE `grades` (
  `grade_id` int(11) NOT NULL,
  `student_id` varchar(20) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `semester_id` int(11) DEFAULT NULL,
  `attempt_number` int(11) DEFAULT NULL,
  `attendance_score` decimal(4,2) DEFAULT NULL,
  `assignment_score` decimal(4,2) DEFAULT NULL,
  `midterm_score` decimal(4,2) DEFAULT NULL,
  `final_exam_score` decimal(4,2) DEFAULT NULL,
  `score_10_scale` decimal(4,2) DEFAULT NULL,
  `letter_grade` varchar(2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `grades`
--

INSERT INTO `grades` (`grade_id`, `student_id`, `subject_id`, `semester_id`, `attempt_number`, `attendance_score`, `assignment_score`, `midterm_score`, `final_exam_score`, `score_10_scale`, `letter_grade`) VALUES
(1, '22ITEB027', 1, 1, 1, 10.00, 10.00, 7.50, 8.00, 8.50, 'A'),
(2, '22ITEB027', 2, 1, 1, 10.00, NULL, 9.00, 6.60, 7.80, 'B'),
(3, '22ITEB027', 3, 1, 1, 10.00, 7.70, 8.00, 9.00, 8.60, 'A'),
(4, '22ITEB027', 4, 1, 1, 10.00, 8.50, 7.50, 8.30, 8.40, 'B'),
(5, '22ITEB027', 5, 1, 1, 8.00, NULL, NULL, NULL, NULL, NULL),
(6, '22ITEB027', 6, 1, 1, 10.00, NULL, 9.50, 7.50, 8.40, 'B'),
(7, '22ITEB027', 7, 2, 1, 10.00, NULL, 8.00, 5.00, 6.60, 'C'),
(8, '22ITEB027', 8, 2, 1, 10.00, NULL, 9.00, 7.00, 9.20, 'A'),
(9, '22ITEB027', 9, 2, 1, 9.00, NULL, NULL, NULL, NULL, NULL),
(10, '22ITEB027', 10, 2, 1, 9.00, 10.00, 8.50, 8.50, 8.90, 'A'),
(11, '22ITEB027', 11, 2, 1, 9.00, 9.00, 8.50, 8.00, 8.30, 'B'),
(12, '22ITEB027', 12, 2, 1, 10.00, NULL, 7.00, NULL, NULL, NULL),
(13, '22ITEB027', 13, 2, 1, 9.00, NULL, NULL, 8.50, 8.70, 'A'),
(14, '22IT2005', 1, 1, 1, 10.00, 10.00, 7.50, 8.00, 8.50, 'A'),
(15, '22IT2005', 2, 1, 1, 10.00, NULL, 9.00, 6.60, 7.80, 'B'),
(16, '22IT2005', 3, 1, 1, 10.00, 7.70, 8.00, 9.00, 8.60, 'A'),
(17, '22IT2005', 4, 1, 1, 10.00, 8.50, 7.50, 8.30, 8.40, 'B'),
(18, '22IT2005', 5, 1, 1, 8.00, NULL, NULL, NULL, NULL, NULL),
(19, '22IT2005', 6, 1, 1, 10.00, NULL, 9.50, 7.50, 8.40, 'B'),
(20, '22IT2005', 7, 2, 1, 10.00, NULL, 8.00, 5.00, 6.60, 'C'),
(21, '22IT2005', 8, 2, 1, 10.00, NULL, 9.00, 9.00, 9.20, 'A'),
(22, '22IT2005', 9, 2, 1, 9.00, NULL, NULL, NULL, NULL, NULL),
(23, '22IT2005', 10, 2, 1, 9.00, 10.00, 8.50, 8.50, 8.90, 'A'),
(24, '22IT2005', 11, 2, 1, 9.00, 9.00, 8.50, 7.70, 8.30, 'B'),
(25, '22IT2005', 12, 2, 1, 10.00, NULL, 7.00, NULL, NULL, NULL),
(26, '22IT2005', 13, 2, 1, 9.00, NULL, NULL, 8.50, 8.70, 'A');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `recheck_detail`
--

CREATE TABLE `recheck_detail` (
  `detail_id` int(11) NOT NULL,
  `request_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `final_score` decimal(4,2) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('pending','approved','rejected','completed') NOT NULL DEFAULT 'pending',
  `rechecked_score` decimal(10,0) DEFAULT NULL,
  `score_updated_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `recheck_detail`
--

INSERT INTO `recheck_detail` (`detail_id`, `request_id`, `subject_id`, `final_score`, `reason`, `status`, `rechecked_score`, `score_updated_at`) VALUES
(1, 1, 10, 8.50, 'aaaaa', 'pending', NULL, NULL),
(25, 28, 13, 8.50, 'ssss', 'approved', NULL, NULL),
(26, 28, 10, 8.50, 'sai sót', 'pending', NULL, NULL),
(27, 29, 11, 7.70, 'dsdđ', 'completed', 8, '2026-04-10'),
(28, 30, 8, 5.00, 'sai sót trong quá trình nhập liệu', 'completed', 7, '2026-04-13'),
(29, 31, 8, 7.00, 'dfsdfsdfsdf', 'pending', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `recheck_request`
--

CREATE TABLE `recheck_request` (
  `request_id` int(11) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `semester_id` int(11) NOT NULL,
  `date_request` date DEFAULT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '0',
  `is_read` int(10) DEFAULT 0,
  `is_new` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `recheck_request`
--

INSERT INTO `recheck_request` (`request_id`, `student_id`, `semester_id`, `date_request`, `status`, `is_read`, `is_new`) VALUES
(1, '22IT2005', 2, '2026-04-01', '0', 1, 0),
(28, '22ITEB027', 2, '2026-04-10', '1', 1, 1),
(29, '22ITEB027', 2, '2026-04-10', '1', 1, 1),
(30, '22ITEB027', 2, '2026-04-13', '1', 1, 1),
(31, '22ITEB027', 2, '2026-04-13', '1', 1, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `semester`
--

CREATE TABLE `semester` (
  `semester_id` int(11) NOT NULL,
  `semester_name` varchar(50) DEFAULT NULL,
  `academic_year` varchar(20) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `notice` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `cohort_id` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `semester`
--

INSERT INTO `semester` (`semester_id`, `semester_name`, `academic_year`, `start_date`, `end_date`, `notice`, `status`, `cohort_id`) VALUES
(1, 'Học kỳ 1', '2022-2023', '2026-03-01', '2026-03-31', '', 0, 22),
(2, 'Học kỳ 2', '2022-2023', '2026-03-03', '2026-04-30', '', 1, 22);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `students`
--

CREATE TABLE `students` (
  `student_id` varchar(20) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `class` varchar(50) DEFAULT NULL,
  `cohort_id` int(10) NOT NULL,
  `faculty` varchar(50) NOT NULL,
  `level` varchar(20) DEFAULT NULL,
  `day_of_birth` date DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `students`
--

INSERT INTO `students` (`student_id`, `name`, `class`, `cohort_id`, `faculty`, `level`, `day_of_birth`, `phone`, `email`) VALUES
('22IT2005', 'Doãn Bá Hoàn', '22GBA', 22, 'Khoa học máy tính', 'Đại học', '2004-03-12', '0102747646', 'hoandb.22it@vku.udn.vn'),
('22ITEB027', 'Le Ngoc Huy', '22ITe', 22, 'Kĩ thuật máy tính', 'Dai Hoc', '2004-02-23', '0392949504', 'huyln.22ite@vku.udn.vn');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `subjects`
--

CREATE TABLE `subjects` (
  `subject_id` int(11) NOT NULL,
  `subject_name` varchar(255) DEFAULT NULL,
  `teacher_name` varchar(50) NOT NULL,
  `credits` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `subjects`
--

INSERT INTO `subjects` (`subject_id`, `subject_name`, `teacher_name`, `credits`) VALUES
(1, 'Lập trình hướng đối tượng', 'Nguyễn Minh Khang', 3),
(2, 'Tiếng Anh 1', 'Trần Thảo Vy', 3),
(3, 'Tiếng Anh chuyên ngành 1 IT', 'Lê Gia Huy', 2),
(4, 'Tin học đại cương', 'Phạm Ngọc Anh', 3),
(5, 'Nhập môn ngành và kỹ năng mềm IT', 'Hoàng Quang Hải', 2),
(6, 'Giải tích 1', 'Võ Bảo Trân', 2),
(7, 'Phân tích và thiết kế giải thuật', 'Đặng Nhật Minh', 2),
(8, 'Quản trị dự án phần mềm', 'Bùi Tú Uyên', 2),
(9, 'Chuyên đề 3', 'Đỗ Đức Phúc', 2),
(10, 'Thiết kế UX/UI', 'Hồ Khánh Linh', 2),
(11, 'Bảo mật và An toàn hệ thống thông tin', 'Vũ Mai Phương', 2),
(12, 'Chủ nghĩa xã hội khoa học', 'Phan Hoàng Nam', 2),
(13, 'Đồ án chuyên ngành 2 (IT)', 'Dương Anh Quân', 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cohorts`
--
ALTER TABLE `cohorts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`exam_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Chỉ mục cho bảng `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`grade_id`),
  ADD UNIQUE KEY `student_id` (`student_id`,`subject_id`,`semester_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Chỉ mục cho bảng `recheck_detail`
--
ALTER TABLE `recheck_detail`
  ADD PRIMARY KEY (`detail_id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Chỉ mục cho bảng `recheck_request`
--
ALTER TABLE `recheck_request`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `semester_id` (`semester_id`);

--
-- Chỉ mục cho bảng `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`semester_id`);

--
-- Chỉ mục cho bảng `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD KEY `cohort_id` (`cohort_id`);

--
-- Chỉ mục cho bảng `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subject_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cohorts`
--
ALTER TABLE `cohorts`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT cho bảng `exams`
--
ALTER TABLE `exams`
  MODIFY `exam_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `grades`
--
ALTER TABLE `grades`
  MODIFY `grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `recheck_detail`
--
ALTER TABLE `recheck_detail`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT cho bảng `recheck_request`
--
ALTER TABLE `recheck_request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT cho bảng `semester`
--
ALTER TABLE `semester`
  MODIFY `semester_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `exams`
--
ALTER TABLE `exams`
  ADD CONSTRAINT `exams_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`),
  ADD CONSTRAINT `exams_ibfk_2` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`semester_id`);

--
-- Các ràng buộc cho bảng `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `fk_grades_semester` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`semester_id`),
  ADD CONSTRAINT `fk_grades_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`),
  ADD CONSTRAINT `fk_grades_subject` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`subject_id`);

--
-- Các ràng buộc cho bảng `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_students_cohort` FOREIGN KEY (`cohort_id`) REFERENCES `cohorts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
