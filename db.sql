-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th5 16, 2025 lúc 04:15 AM
-- Phiên bản máy phục vụ: 8.0.30
-- Phiên bản PHP: 8.3.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `job_danh-gia-nhan-su`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `departments`
--

CREATE TABLE `departments` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Nhân sự', 'đê', '2025-05-15 06:10:11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int NOT NULL,
  `employee_id` int NOT NULL,
  `department_id` int DEFAULT NULL,
  `evaluation_form_id` int DEFAULT NULL,
  `content` text NOT NULL,
  `status` enum('sent','reviewed','approved') DEFAULT 'sent',
  `data` text NOT NULL,
  `manager_comment` text COMMENT 'Nhận xét của lãnh đạo',
  `deputy_director_comment` text,
  `director_comment` text COMMENT 'Nhận xét của giám đốc',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `evaluations`
--

INSERT INTO `evaluations` (`id`, `employee_id`, `department_id`, `evaluation_form_id`, `content`, `status`, `data`, `manager_comment`, `deputy_director_comment`, `director_comment`, `created_at`) VALUES
(6, 3, NULL, NULL, '{\"criteria\":{\"1\":{\"score\":\"5\"},\"2\":{\"score\":\"4\"},\"3\":{\"score\":\"4\"},\"4\":{\"score\":\"4\"},\"5\":{\"score\":\"3\"},\"6\":{\"score\":\"2\"},\"7\":{\"score\":\"2\"},\"8\":{\"score\":\"2\"},\"9\":{\"score\":\"10\"},\"10\":{\"score\":\"2\"},\"11\":{\"score\":\"2\"}},\"completion_level\":\"60\",\"total_score\":\"100\",\"notes\":\"\"}', 'approved', '{\"1\":{\"score\":\"5\"},\"2\":{\"score\":\"4\"},\"3\":{\"score\":\"4\"},\"4\":{\"score\":\"4\"},\"5\":{\"score\":\"3\"},\"6\":{\"score\":\"2\"},\"7\":{\"score\":\"2\"},\"8\":{\"score\":\"2\"},\"9\":{\"score\":\"10\"},\"10\":{\"score\":\"2\"},\"11\":{\"score\":\"2\"}}', 'OKe nha', NULL, 'oke', '2025-05-12 15:25:19'),
(7, 2, NULL, NULL, '{\"criteria\":{\"1\":{\"score\":\"5\"},\"2\":{\"score\":\"4\"},\"3\":{\"score\":\"4\"},\"4\":{\"score\":\"4\"},\"5\":{\"score\":\"3\"},\"6\":{\"score\":\"2\"},\"7\":{\"score\":\"2\"},\"8\":{\"score\":\"2\"},\"9\":{\"score\":\"2\"},\"10\":{\"score\":\"2\"},\"11\":{\"score\":\"5\"},\"12\":{\"score\":\"5\"}},\"part3_level_1\":\"30\",\"part3_level_2\":\"30\",\"total_score\":\"100\",\"notes\":\"ddd\"}', 'approved', '{\"1\":{\"score\":\"5\"},\"2\":{\"score\":\"4\"},\"3\":{\"score\":\"4\"},\"4\":{\"score\":\"4\"},\"5\":{\"score\":\"3\"},\"6\":{\"score\":\"2\"},\"7\":{\"score\":\"2\"},\"8\":{\"score\":\"2\"},\"9\":{\"score\":\"2\"},\"10\":{\"score\":\"2\"},\"11\":{\"score\":\"5\"},\"12\":{\"score\":\"5\"}}', NULL, NULL, 'good\r\n', '2025-05-12 15:27:50');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `evaluation_forms`
--

CREATE TABLE `evaluation_forms` (
  `id` int NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên form đánh giá',
  `department_id` int DEFAULT NULL COMMENT 'ID phòng ban (NULL nếu là form mặc định)',
  `form_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nhan_vien' COMMENT 'Loại form (nhan_vien/lanh_dao)',
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung form dạng JSON',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `evaluation_forms`
--

INSERT INTO `evaluation_forms` (`id`, `name`, `department_id`, `form_type`, `content`, `created_at`, `updated_at`) VALUES
(3, 'Đỗ Văn Vũ', 1, 'nhan_vien', '{\r\n  \"form_type\": \"nhan_vien\",\r\n  \"part1\": {\r\n    \"title\": \"I. Adu hacker\",\r\n    \"total_max\": 20,\r\n    \"criteria\": {\r\n      \"1\": {\r\n        \"text\": \"Thực hiện nghiêm túc các quy định, quy chế, nội quy của cơ quan\",\r\n        \"max_score\": 5\r\n      },\r\n      \"2\": {\r\n        \"text\": \"Chấp hành sự phân công của tổ chức\",\r\n        \"max_score\": 4\r\n      },\r\n      \"3\": {\r\n        \"text\": \"Có thái độ đúng mực và phong cách ứng xử, lề lối làm việc chuẩn mực\",\r\n        \"max_score\": 4\r\n      },\r\n      \"4\": {\r\n        \"text\": \"Có tinh thần trách nhiệm với công việc, phương pháp làm việc khoa học\",\r\n        \"max_score\": 4\r\n      },\r\n      \"5\": {\r\n        \"text\": \"Báo cáo đầy đủ, kịp thời, trung thực với cấp trên\",\r\n        \"max_score\": 3\r\n      }\r\n    }\r\n  }\r\n}', '2025-05-15 14:12:02', '2025-05-15 16:00:37'),
(7, 'DC x OP Luffy Raglan Hoodie - Multicolor', 1, 'lanh_dao', '{\r\n  \"form_type\": \"lanh_dao\",\r\n  \"part1\": {\r\n    \"title\": \"I. Ý thức tổ chức kỷ luật\",\r\n    \"total_max\": 20,\r\n    \"criteria\": {\r\n      \"1\": {\r\n        \"text\": \"Thực hiện nghiêm túc các quy định, quy chế, nội quy của cơ quan\",\r\n        \"max_score\": 5\r\n      },\r\n      \"2\": {\r\n        \"text\": \"Chấp hành sự phân công của tổ chức\",\r\n        \"max_score\": 4\r\n      },\r\n      \"3\": {\r\n        \"text\": \"Có thái độ đúng mực và phong cách ứng xử, lề lối làm việc chuẩn mực\",\r\n        \"max_score\": 4\r\n      },\r\n      \"4\": {\r\n        \"text\": \"Có tinh thần trách nhiệm với công việc, phương pháp làm việc khoa học\",\r\n        \"max_score\": 4\r\n      },\r\n      \"5\": {\r\n        \"text\": \"Báo cáo đầy đủ, kịp thời, trung thực với cấp trên\",\r\n        \"max_score\": 3\r\n      }\r\n    }\r\n  },\r\n  \"part2\": {\r\n    \"title\": \"II. Năng lực và kỹ năng\",\r\n    \"total_max\": 20,\r\n    \"criteria\": {\r\n      \"6\": {\r\n        \"text\": \"Có năng lực tập hợp công chức, viên chức, xây dựng đơn vị bộ phận đoàn kết\",\r\n        \"max_score\": 2\r\n      },\r\n      \"7\": {\r\n        \"text\": \"Chỉ đạo, điều hành, kiểm soát việc thực hiện nhiệm vụ\",\r\n        \"max_score\": 2\r\n      },\r\n      \"8\": {\r\n        \"text\": \"Phối hợp, tạo lập mối quan hệ tốt với cá nhân, tổ chức\",\r\n        \"max_score\": 2\r\n      },\r\n      \"9\": {\r\n        \"text\": \"Hoàn thành kịp thời và bảo đảm chất lượng, hiệu quả nhiệm vụ đột xuất\",\r\n        \"max_score\": 2\r\n      },\r\n      \"10\": {\r\n        \"text\": \"Làm tốt công tác tham mưu, hoạch định, xây dựng văn bản quy phạm pháp luật\",\r\n        \"max_score\": 2\r\n      },\r\n      \"11\": {\r\n        \"text\": \"Làm tốt công tác kiểm tra, thanh tra, giải quyết khiếu nại\",\r\n        \"max_score\": 5\r\n      },\r\n      \"12\": {\r\n        \"text\": \"Xây dựng chương trình, kế hoạch hoạt động hàng Quý\",\r\n        \"max_score\": 5\r\n      }\r\n    }\r\n  },\r\n  \"part3\": {\r\n    \"title\": \"III. Kết quả thực hiện chức trách, nhiệm vụ được giao\",\r\n    \"total_max\": 60,\r\n    \"criteria\": {\r\n      \"level1\": {\r\n        \"text\": \"Thực hiện nhiệm vụ được giao đảm bảo tiến độ và chất lượng\",\r\n        \"max_score\": 30\r\n      },\r\n      \"level2\": {\r\n        \"text\": \"Lãnh đạo, chỉ đạo, điều hành các cơ quan, đơn vị hoặc lĩnh vực công tác\",\r\n        \"max_score\": 30\r\n      }\r\n    }\r\n  }\r\n}', '2025-05-15 14:34:59', NULL),
(8, 'Form đánh giá Chuyên viên - Mặc định', NULL, 'nhan_vien', '{\r\n  \"form_type\": \"nhan_vien\",\r\n  \"part1\": {\r\n    \"title\": \"I. Adu hacker\",\r\n    \"total_max\": 20,\r\n    \"criteria\": {\r\n      \"1\": {\r\n        \"text\": \"Thực hiện nghiêm túc các quy định, quy chế, nội quy của cơ quan\",\r\n        \"max_score\": 5\r\n      },\r\n      \"2\": {\r\n        \"text\": \"Chấp hành sự phân công của tổ chức\",\r\n        \"max_score\": 4\r\n      },\r\n      \"3\": {\r\n        \"text\": \"Có thái độ đúng mực và phong cách ứng xử, lề lối làm việc chuẩn mực\",\r\n        \"max_score\": 4\r\n      },\r\n      \"4\": {\r\n        \"text\": \"Có tinh thần trách nhiệm với công việc, phương pháp làm việc khoa học\",\r\n        \"max_score\": 4\r\n      },\r\n      \"5\": {\r\n        \"text\": \"Báo cáo đầy đủ, kịp thời, trung thực với cấp trên\",\r\n        \"max_score\": 3\r\n      }\r\n    }\r\n  }\r\n}', '2025-05-15 15:18:21', NULL),
(9, 'Form đánh giá Lãnh đạo - Mặc định', NULL, 'lanh_dao', '{\r\n  \"form_type\": \"lanh_dao\",\r\n  \"part1\": {\r\n    \"title\": \"I. Ý thức tổ chức kỷ luật\",\r\n    \"total_max\": 20,\r\n    \"criteria\": {\r\n      \"1\": {\r\n        \"text\": \"Thực hiện nghiêm túc các quy định, quy chế, nội quy của cơ quan\",\r\n        \"max_score\": 5\r\n      },\r\n      \"2\": {\r\n        \"text\": \"Chấp hành sự phân công của tổ chức\",\r\n        \"max_score\": 4\r\n      },\r\n      \"3\": {\r\n        \"text\": \"Có thái độ đúng mực và phong cách ứng xử, lề lối làm việc chuẩn mực\",\r\n        \"max_score\": 4\r\n      },\r\n      \"4\": {\r\n        \"text\": \"Có tinh thần trách nhiệm với công việc, phương pháp làm việc khoa học\",\r\n        \"max_score\": 4\r\n      },\r\n      \"5\": {\r\n        \"text\": \"Báo cáo đầy đủ, kịp thời, trung thực với cấp trên\",\r\n        \"max_score\": 3\r\n      }\r\n    }\r\n  },\r\n  \"part2\": {\r\n    \"title\": \"II. Năng lực và kỹ năng\",\r\n    \"total_max\": 20,\r\n    \"criteria\": {\r\n      \"6\": {\r\n        \"text\": \"Có năng lực tập hợp công chức, viên chức, xây dựng đơn vị bộ phận đoàn kết\",\r\n        \"max_score\": 2\r\n      },\r\n      \"7\": {\r\n        \"text\": \"Chỉ đạo, điều hành, kiểm soát việc thực hiện nhiệm vụ\",\r\n        \"max_score\": 2\r\n      },\r\n      \"8\": {\r\n        \"text\": \"Phối hợp, tạo lập mối quan hệ tốt với cá nhân, tổ chức\",\r\n        \"max_score\": 2\r\n      },\r\n      \"9\": {\r\n        \"text\": \"Hoàn thành kịp thời và bảo đảm chất lượng, hiệu quả nhiệm vụ đột xuất\",\r\n        \"max_score\": 2\r\n      },\r\n      \"10\": {\r\n        \"text\": \"Làm tốt công tác tham mưu, hoạch định, xây dựng văn bản quy phạm pháp luật\",\r\n        \"max_score\": 2\r\n      },\r\n      \"11\": {\r\n        \"text\": \"Làm tốt công tác kiểm tra, thanh tra, giải quyết khiếu nại\",\r\n        \"max_score\": 5\r\n      },\r\n      \"12\": {\r\n        \"text\": \"Xây dựng chương trình, kế hoạch hoạt động hàng Quý\",\r\n        \"max_score\": 5\r\n      }\r\n    }\r\n  },\r\n  \"part3\": {\r\n    \"title\": \"III. Kết quả thực hiện chức trách, nhiệm vụ được giao\",\r\n    \"total_max\": 60,\r\n    \"criteria\": {\r\n      \"level1\": {\r\n        \"text\": \"Thực hiện nhiệm vụ được giao đảm bảo tiến độ và chất lượng\",\r\n        \"max_score\": 60\r\n      }\r\n    }\r\n  }\r\n}', '2025-05-15 16:19:18', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','nhan_vien','lanh_dao','giam_doc','pho_giam_doc') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Giám Đốc', 'giamdoc@gmail.com', '$2a$12$4e7KuB9kE7jOQgE3vnZoH.Cuks.PzUJMXXUa/a7ivHvgcSGNBX/uu', 'giam_doc', '2025-05-09 10:56:55'),
(2, 'Lãnh đạo', 'lanhdao@gmail.com', '$2a$12$4e7KuB9kE7jOQgE3vnZoH.Cuks.PzUJMXXUa/a7ivHvgcSGNBX/uu', 'lanh_dao', '2025-05-09 10:56:55'),
(3, 'Chuyên Viên', 'nhanvien@gmail.com', '$2a$12$4e7KuB9kE7jOQgE3vnZoH.Cuks.PzUJMXXUa/a7ivHvgcSGNBX/uu', 'nhan_vien', '2025-05-09 10:56:55'),
(4, 'Admin', 'admin@gmail.com', '$2y$10$KwJ.mSvkd29mFdcyG36jVOjq48UTjLnk3UOofbMt9IEeQl/olbViO', 'admin', '2025-05-09 11:35:14'),
(6, 'Vũ vudevweb Đỗ', 'vudoidol354@hotmail.com', '$2y$10$Hbu6mFaNmYUJ/iUcHbhqkeZkvtHGWhR4rgTwBL1FAp7LjLHVXFWHW', 'lanh_dao', '2025-05-09 11:43:14'),
(7, 'phó giám đốc', 'phogiamdoc@gmail.com', '$2y$10$LEsF0ARW0pfJe0emvdgPPOnXVzO5RvlkfplC72H2XvxydlbiTlxt2', 'pho_giam_doc', '2025-05-15 15:23:39');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_departments`
--

CREATE TABLE `user_departments` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `department_id` int NOT NULL,
  `is_leader` tinyint(1) DEFAULT '0' COMMENT 'Đánh dấu là lãnh đạo phòng',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `user_departments`
--

INSERT INTO `user_departments` (`id`, `user_id`, `department_id`, `is_leader`, `created_at`) VALUES
(1, 3, 1, 0, '2025-05-15 06:10:18'),
(2, 2, 1, 1, '2025-05-15 06:10:21'),
(4, 6, 1, 0, '2025-05-15 07:56:39');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `evaluations_form_fk` (`evaluation_form_id`);

--
-- Chỉ mục cho bảng `evaluation_forms`
--
ALTER TABLE `evaluation_forms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `department_form_type` (`department_id`,`form_type`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `user_departments`
--
ALTER TABLE `user_departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `department_id` (`department_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `evaluation_forms`
--
ALTER TABLE `evaluation_forms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `user_departments`
--
ALTER TABLE `user_departments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_form_fk` FOREIGN KEY (`evaluation_form_id`) REFERENCES `evaluation_forms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `evaluations_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `evaluations_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `evaluation_forms`
--
ALTER TABLE `evaluation_forms`
  ADD CONSTRAINT `evaluation_forms_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `user_departments`
--
ALTER TABLE `user_departments`
  ADD CONSTRAINT `user_departments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_departments_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
