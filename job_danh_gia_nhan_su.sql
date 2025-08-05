-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 07, 2025 at 10:33 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `job_danh_gia_nhan_su`
--

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Nhân sự', 'đê', '2025-05-15 06:10:11');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int NOT NULL,
  `employee_id` int NOT NULL,
  `department_id` int DEFAULT NULL,
  `evaluation_form_id` int DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Đánh giá của Nhân viên',
  `status` enum('sent','reviewed','deputy_reviewed','approved') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'sent' COMMENT 'Trạng thái của form đánh giá',
  `data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Điểm tối đa với mỗi tiêu chí',
  `manager_comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Nhận xét của lãnh đạo',
  `deputy_director_comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '	Nhận xét của phó giám đốc',
  `director_comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Nhận xét của giám đốc',
  `extra_deduction` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT '	Điểm trừ thêm của nhân viên đánh giá',
  `extra_deduction_leader` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Điểm trừ thêm của cán bộ đánh giá',
  `extra_deduction_deputy_director` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Điểm trừ thêm của PGĐ đánh giá',
  `extra_deduction_director` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Điểm trừ thêm của GĐ đánh giá',
  `employee_rescore_total` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tổng điểm nhân viên duyệt',
  `employee_rescore_final` varchar(256) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tổng điểm nhân viên duyệt chính thức',
  `leader_rescore` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Đánh giá của Lãnh đạo',
  `leader_rescore_total` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tổng điểm lãnh đạo duyệt',
  `leader_rescore_final` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '	Tổng điểm Lãnh đạo duyệt chính thức	',
  `deputy_director_rescore` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Đánh giá của phó giám đốc',
  `deputy_director_rescore_total` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tổng điểm PGĐ duyệt',
  `deputy_director_rescore_final` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '	Tổng điểm PGĐ duyệt chính thức	',
  `director_rescore` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Đánh giá của giám đốc',
  `director_rescore_total` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tổng điểm GĐ duyệt',
  `director_rescore_final` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tổng điểm GĐ duyệt chính thức',
  `signatures` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci COMMENT 'Thông tin user tham gia',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`id`, `employee_id`, `department_id`, `evaluation_form_id`, `content`, `status`, `data`, `manager_comment`, `deputy_director_comment`, `director_comment`, `extra_deduction`, `extra_deduction_leader`, `extra_deduction_deputy_director`, `extra_deduction_director`, `employee_rescore_total`, `employee_rescore_final`, `leader_rescore`, `leader_rescore_total`, `leader_rescore_final`, `deputy_director_rescore`, `deputy_director_rescore_total`, `deputy_director_rescore_final`, `director_rescore`, `director_rescore_total`, `director_rescore_final`, `signatures`, `created_at`) VALUES
(6, 3, NULL, NULL, '{\"criteria\":{\"1\":{\"score\":\"5\"},\"2\":{\"score\":\"4\"},\"3\":{\"score\":\"4\"},\"4\":{\"score\":\"4\"},\"5\":{\"score\":\"3\"},\"6\":{\"score\":\"2\"},\"7\":{\"score\":\"2\"},\"8\":{\"score\":\"2\"},\"9\":{\"score\":\"10\"},\"10\":{\"score\":\"2\"},\"11\":{\"score\":\"2\"}},\"completion_level\":\"60\",\"total_score\":\"100\",\"notes\":\"\"}', 'approved', '{\"1\":{\"score\":\"5\"},\"2\":{\"score\":\"4\"},\"3\":{\"score\":\"4\"},\"4\":{\"score\":\"4\"},\"5\":{\"score\":\"3\"},\"6\":{\"score\":\"2\"},\"7\":{\"score\":\"2\"},\"8\":{\"score\":\"2\"},\"9\":{\"score\":\"10\"},\"10\":{\"score\":\"2\"},\"11\":{\"score\":\"2\"}}', 'OKe nha', NULL, 'oke', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-12 15:25:19'),
(7, 2, NULL, NULL, '{\"criteria\":{\"1\":{\"score\":\"5\"},\"2\":{\"score\":\"4\"},\"3\":{\"score\":\"4\"},\"4\":{\"score\":\"4\"},\"5\":{\"score\":\"3\"},\"6\":{\"score\":\"2\"},\"7\":{\"score\":\"2\"},\"8\":{\"score\":\"2\"},\"9\":{\"score\":\"2\"},\"10\":{\"score\":\"2\"},\"11\":{\"score\":\"5\"},\"12\":{\"score\":\"5\"}},\"part3_level_1\":\"30\",\"part3_level_2\":\"30\",\"total_score\":\"100\",\"notes\":\"ddd\"}', 'approved', '{\"1\":{\"score\":\"5\"},\"2\":{\"score\":\"4\"},\"3\":{\"score\":\"4\"},\"4\":{\"score\":\"4\"},\"5\":{\"score\":\"3\"},\"6\":{\"score\":\"2\"},\"7\":{\"score\":\"2\"},\"8\":{\"score\":\"2\"},\"9\":{\"score\":\"2\"},\"10\":{\"score\":\"2\"},\"11\":{\"score\":\"5\"},\"12\":{\"score\":\"5\"}}', NULL, NULL, 'good\r\n', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-12 15:27:50'),
(8, 3, 1, 3, '{\"criteria\":{\"1\":{\"score\":\"4\"},\"2\":{\"score\":\"3\"},\"3\":{\"score\":\"3\"},\"4\":{\"score\":\"3\"},\"5\":{\"score\":\"3\"}},\"total_score\":\"16\",\"notes\":\"\"}', 'reviewed', '{\"1\":{\"score\":\"4\"},\"2\":{\"score\":\"3\"},\"3\":{\"score\":\"3\"},\"4\":{\"score\":\"3\"},\"5\":{\"score\":\"3\"}}', 'testy', NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, '[]', '0', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-03 01:34:23'),
(9, 3, 1, 2, '{\"criteria\":{\"criteria_1\":{\"score\":\"1\"},\"criteria_2\":{\"score\":\"1\"},\"criteria_3\":{\"score\":\"1\"},\"criteria_4\":{\"score\":\"1\"},\"criteria_5\":{\"score\":\"1\"},\"criteria_6\":{\"score\":\"1\"},\"criteria_7\":{\"score\":\"1\"},\"criteria_8\":{\"score\":\"1\"},\"criteria_9\":{\"score\":\"1\"},\"criteria_10\":{\"score\":\"1\"},\"criteria_11\":{\"score\":\"1\"},\"criteria_12\":{\"score\":\"1\"},\"criteria_13\":{\"score\":\"1\"},\"criteria_14\":{\"score\":\"1\"}},\"total_score\":\"14\",\"notes\":\"\"}', 'sent', '{\"criteria_1\":{\"score\":\"1\"},\"criteria_2\":{\"score\":\"1\"},\"criteria_3\":{\"score\":\"1\"},\"criteria_4\":{\"score\":\"1\"},\"criteria_5\":{\"score\":\"1\"},\"criteria_6\":{\"score\":\"1\"},\"criteria_7\":{\"score\":\"1\"},\"criteria_8\":{\"score\":\"1\"},\"criteria_9\":{\"score\":\"1\"},\"criteria_10\":{\"score\":\"1\"},\"criteria_11\":{\"score\":\"1\"},\"criteria_12\":{\"score\":\"1\"},\"criteria_13\":{\"score\":\"1\"},\"criteria_14\":{\"score\":\"1\"}}', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-03 01:38:03'),
(10, 3, 1, 2, '{\"criteria\":{\"criteria_1\":{\"score\":\"2\"},\"criteria_2\":{\"score\":\"2\"},\"criteria_3\":{\"score\":\"2\"},\"criteria_4\":{\"score\":\"2\"},\"criteria_5\":{\"score\":\"2\"},\"criteria_6\":{\"score\":\"2\"},\"criteria_7\":{\"score\":\"2\"},\"criteria_8\":{\"score\":\"2\"},\"criteria_9\":{\"score\":\"2\"},\"criteria_10\":{\"score\":\"2\"},\"criteria_11\":{\"score\":\"2\"},\"criteria_12\":{\"score\":\"2\"},\"criteria_13\":{\"score\":\"2\"},\"criteria_14\":{\"score\":\"2\"}},\"total_score\":\"28\",\"notes\":\"\"}', 'approved', '{\"criteria_1\":{\"score\":\"2\"},\"criteria_2\":{\"score\":\"2\"},\"criteria_3\":{\"score\":\"2\"},\"criteria_4\":{\"score\":\"2\"},\"criteria_5\":{\"score\":\"2\"},\"criteria_6\":{\"score\":\"2\"},\"criteria_7\":{\"score\":\"2\"},\"criteria_8\":{\"score\":\"2\"},\"criteria_9\":{\"score\":\"2\"},\"criteria_10\":{\"score\":\"2\"},\"criteria_11\":{\"score\":\"2\"},\"criteria_12\":{\"score\":\"2\"},\"criteria_13\":{\"score\":\"2\"},\"criteria_14\":{\"score\":\"2\"}}', '56565656', 'dsfdf', 'adsadsad', '0', NULL, NULL, NULL, NULL, NULL, '{\"criteria_5\":\"2\",\"criteria_10\":\"2\",\"criteria_11\":\"2\",\"criteria_14\":\"1\",\"criteria_1\":\"3\",\"criteria_2\":\"2\",\"criteria_3\":\"3\",\"criteria_4\":\"3\",\"criteria_6\":\"4\",\"criteria_7\":\"4\",\"criteria_8\":\"1\",\"criteria_9\":\"1\",\"criteria_12\":\"3\",\"criteria_13\":\"1\"}', '32', '32', '{\"criteria_1\":\"2\",\"criteria_2\":\"3\",\"criteria_3\":\"\",\"criteria_4\":\"4\",\"criteria_5\":\"\",\"criteria_6\":\"1\",\"criteria_7\":\"\",\"criteria_8\":\"\",\"criteria_9\":\"\",\"criteria_10\":\"\",\"criteria_11\":\"\",\"criteria_12\":\"\",\"criteria_13\":\"\",\"criteria_14\":\"\"}', '10', '10', '{\"criteria_1\":\"1\",\"criteria_2\":\"2\",\"criteria_3\":\"2\",\"criteria_4\":\"2\",\"criteria_5\":\"2\",\"criteria_6\":\"2\",\"criteria_7\":\"1\",\"criteria_8\":\"2\",\"criteria_9\":\"2\",\"criteria_10\":\"2\",\"criteria_11\":\"2\",\"criteria_12\":\"2\",\"criteria_13\":\"2\",\"criteria_14\":\"2\"}', '26', '26', NULL, '2025-07-03 01:39:41'),
(11, 2, 1, 11, '{\"criteria\":{\"criteria_1\":{\"score\":\"2\"},\"criteria_2\":{\"score\":\"1\"},\"criteria_3\":{\"score\":\"2\"},\"criteria_4\":{\"score\":\"1\"},\"criteria_5\":{\"score\":\"1\"},\"criteria_6\":{\"score\":\"1\"},\"criteria_7\":{\"score\":\"2\"},\"criteria_8\":{\"score\":\"2\"},\"criteria_9\":{\"score\":\"2\"},\"criteria_10\":{\"score\":\"1\"},\"criteria_11\":{\"score\":\"2\"},\"criteria_12\":{\"score\":\"1\"},\"criteria_13\":{\"score\":\"1\"},\"criteria_14\":{\"score\":\"2\"},\"criteria_15\":{\"score\":\"2\"},\"criteria_16\":{\"score\":\"2\"}},\"part3_level_1\":\"10\",\"part3_level_2\":\"10\",\"total_score\":\"25\",\"notes\":\"sadasd\"}', 'approved', '{\"criteria_1\":{\"score\":\"2\"},\"criteria_2\":{\"score\":\"1\"},\"criteria_3\":{\"score\":\"2\"},\"criteria_4\":{\"score\":\"1\"},\"criteria_5\":{\"score\":\"1\"},\"criteria_6\":{\"score\":\"1\"},\"criteria_7\":{\"score\":\"2\"},\"criteria_8\":{\"score\":\"2\"},\"criteria_9\":{\"score\":\"2\"},\"criteria_10\":{\"score\":\"1\"},\"criteria_11\":{\"score\":\"2\"},\"criteria_12\":{\"score\":\"1\"},\"criteria_13\":{\"score\":\"1\"},\"criteria_14\":{\"score\":\"2\"},\"criteria_15\":{\"score\":\"2\"},\"criteria_16\":{\"score\":\"2\"}}', 'ádasdsads', 'fdgdfgfg', 'dsfdsfdf', '0', NULL, NULL, NULL, NULL, NULL, '{\"criteria_4\":\"4\",\"criteria_9\":\"6\",\"criteria_12\":\"5\",\"criteria_16\":\"4\",\"criteria_1\":\"4\",\"criteria_2\":\"3\",\"criteria_3\":\"4\",\"criteria_5\":\"5\",\"criteria_6\":\"5\",\"criteria_7\":\"6\",\"criteria_8\":\"7\",\"criteria_10\":\"6\",\"criteria_11\":\"6\",\"criteria_13\":\"7\",\"criteria_14\":\"8\",\"criteria_15\":\"5\"}', '85', '85', '{\"criteria_1\":\"4\",\"criteria_2\":\"3\",\"criteria_3\":\"3\",\"criteria_4\":\"4\",\"criteria_5\":\"5\",\"criteria_6\":\"5\",\"criteria_7\":\"5\",\"criteria_8\":\"6\",\"criteria_9\":\"7\",\"criteria_10\":\"6\",\"criteria_11\":\"5\",\"criteria_12\":\"4\",\"criteria_13\":\"7\",\"criteria_14\":\"7\",\"criteria_15\":\"5\",\"criteria_16\":\"4\"}', '80', '80', '{\"criteria_1\":\"4\",\"criteria_2\":\"3\",\"criteria_3\":\"4\",\"criteria_4\":\"4\",\"criteria_5\":\"4\",\"criteria_6\":\"4\",\"criteria_7\":\"5\",\"criteria_8\":\"6\",\"criteria_9\":\"7\",\"criteria_10\":\"6\",\"criteria_11\":\"6\",\"criteria_12\":\"4\",\"criteria_13\":\"7\",\"criteria_14\":\"8\",\"criteria_15\":\"4\",\"criteria_16\":\"4\"}', '80', '80', '{\"2\":{\"id\":2,\"name\":\"Lãnh đạo\",\"email\":\"lanhdao@gmail.com\",\"password\":\"$2a$12$4e7KuB9kE7jOQgE3vnZoH.Cuks.PzUJMXXUa\\/a7ivHvgcSGNBX\\/uu\",\"role\":\"lanh_dao\",\"position\":\"Lãnh đạo\",\"employee_unit\":\"Phòng Nghiên cứu\",\"created_at\":\"2025-05-09 17:56:55\"},\"7\":{\"id\":7,\"name\":\"phó giám đốc\",\"email\":\"phogiamdoc@gmail.com\",\"password\":\"$2y$10$LEsF0ARW0pfJe0emvdgPPOnXVzO5RvlkfplC72H2XvxydlbiTlxt2\",\"role\":\"pho_giam_doc\",\"position\":\"Phó giám đốc\",\"employee_unit\":\"Phòng Nghiên cứu, Kiểm thử\",\"created_at\":\"2025-05-15 22:23:39\"},\"1\":{\"id\":1,\"name\":\"Giám Đốc\",\"email\":\"giamdoc@gmail.com\",\"password\":\"$2a$12$4e7KuB9kE7jOQgE3vnZoH.Cuks.PzUJMXXUa\\/a7ivHvgcSGNBX\\/uu\",\"role\":\"giam_doc\",\"position\":\"Giám Đốc\",\"employee_unit\":\"Phòng Nghiên cứu, Kiểm thử\",\"created_at\":\"2025-05-09 17:56:55\"}}', '2025-07-03 08:24:45'),
(13, 3, 1, 12, '{\"criteria\":{\"criteria_1\":{\"score\":\"4\"},\"criteria_2\":{\"score\":\"4\"},\"criteria_3\":{\"score\":\"4\"},\"criteria_4\":{\"score\":\"4\"},\"criteria_5\":{\"score\":\"4\"},\"criteria_6\":{\"score\":\"8\"},\"criteria_7\":{\"score\":\"6\"},\"criteria_8\":{\"score\":\"2\"},\"criteria_9\":{\"score\":\"2\"},\"criteria_10\":{\"score\":\"2\"},\"criteria_11\":{\"score\":\"50\"},\"criteria_13\":{\"score\":\"10\"}},\"total_score\":\"100\",\"notes\":\"\"}', 'reviewed', '{\"criteria_1\":{\"score\":\"4\"},\"criteria_2\":{\"score\":\"4\"},\"criteria_3\":{\"score\":\"4\"},\"criteria_4\":{\"score\":\"4\"},\"criteria_5\":{\"score\":\"4\"},\"criteria_6\":{\"score\":\"8\"},\"criteria_7\":{\"score\":\"6\"},\"criteria_8\":{\"score\":\"2\"},\"criteria_9\":{\"score\":\"2\"},\"criteria_10\":{\"score\":\"2\"},\"criteria_11\":{\"score\":\"50\"},\"criteria_13\":{\"score\":\"10\"}}', 'lanh dao ok', '', NULL, '6', NULL, NULL, NULL, NULL, NULL, '{\"criteria_5\":\"4\",\"criteria_10\":\"2\",\"criteria_11\":\"50\",\"criteria_13\":\"5\",\"criteria_1\":\"4\",\"criteria_2\":\"4\",\"criteria_3\":\"4\",\"criteria_4\":\"4\",\"criteria_6\":\"8\",\"criteria_7\":\"6\",\"criteria_8\":\"2\",\"criteria_9\":\"2\"}', '95', '94', NULL, NULL, NULL, NULL, NULL, NULL, '{\"2\":{\"id\":2,\"name\":\"Lãnh đạo\",\"email\":\"lanhdao@gmail.com\",\"password\":\"$2a$12$4e7KuB9kE7jOQgE3vnZoH.Cuks.PzUJMXXUa\\/a7ivHvgcSGNBX\\/uu\",\"role\":\"lanh_dao\",\"position\":\"Lãnh đạo\",\"employee_unit\":\"Phòng Nghiên cứu\",\"created_at\":\"2025-05-09 17:56:55\"},\"7\":{\"id\":7,\"name\":\"phó giám đốc\",\"email\":\"phogiamdoc@gmail.com\",\"password\":\"$2y$10$LEsF0ARW0pfJe0emvdgPPOnXVzO5RvlkfplC72H2XvxydlbiTlxt2\",\"role\":\"pho_giam_doc\",\"position\":\"Phó giám đốc\",\"employee_unit\":\"Phòng Nghiên cứu, Kiểm thử\",\"created_at\":\"2025-05-15 22:23:39\"},\"1\":{\"id\":1,\"name\":\"Giám Đốc\",\"email\":\"giamdoc@gmail.com\",\"password\":\"$2a$12$4e7KuB9kE7jOQgE3vnZoH.Cuks.PzUJMXXUa\\/a7ivHvgcSGNBX\\/uu\",\"role\":\"giam_doc\",\"position\":\"Giám Đốc\",\"employee_unit\":\"Phòng Nghiên cứu, Kiểm thử\",\"created_at\":\"2025-05-09 17:56:55\"}}', '2025-07-06 15:43:05'),
(14, 3, 1, 12, '{\"criteria\":{\"criteria_1\":{\"score\":\"4\"},\"criteria_2\":{\"score\":\"4\"},\"criteria_3\":{\"score\":\"4\"},\"criteria_4\":{\"score\":\"4\"},\"criteria_5\":{\"score\":\"4\"},\"criteria_6\":{\"score\":\"8\"},\"criteria_7\":{\"score\":\"6\"},\"criteria_8\":{\"score\":\"2\"},\"criteria_9\":{\"score\":\"2\"},\"criteria_10\":{\"score\":\"2\"},\"criteria_11\":{\"score\":\"50\"},\"criteria_13\":{\"score\":\"10\"}},\"total_score\":\"100\",\"notes\":\"\"}', 'approved', '{\"criteria_1\":{\"score\":\"4\"},\"criteria_2\":{\"score\":\"4\"},\"criteria_3\":{\"score\":\"4\"},\"criteria_4\":{\"score\":\"4\"},\"criteria_5\":{\"score\":\"4\"},\"criteria_6\":{\"score\":\"8\"},\"criteria_7\":{\"score\":\"6\"},\"criteria_8\":{\"score\":\"2\"},\"criteria_9\":{\"score\":\"2\"},\"criteria_10\":{\"score\":\"2\"},\"criteria_11\":{\"score\":\"50\"},\"criteria_13\":{\"score\":\"10\"}}', 'lanh dao ok', 'pgd ok', 'giam doc ok', '3', '2', '1', '0', '100', '97', '{\"criteria_5\":\"4\",\"criteria_10\":\"2\",\"criteria_11\":\"50\",\"criteria_13\":\"10\",\"criteria_1\":\"4\",\"criteria_2\":\"4\",\"criteria_3\":\"4\",\"criteria_4\":\"4\",\"criteria_6\":\"8\",\"criteria_7\":\"6\",\"criteria_8\":\"2\",\"criteria_9\":\"2\"}', '100', '98', '{\"criteria_1\":\"4\",\"criteria_2\":\"4\",\"criteria_3\":\"4\",\"criteria_4\":\"4\",\"criteria_5\":\"4\",\"criteria_6\":\"8\",\"criteria_7\":\"6\",\"criteria_8\":\"2\",\"criteria_9\":\"2\",\"criteria_10\":\"2\",\"criteria_11\":\"50\",\"criteria_13\":\"10\"}', '100', '99', '{\"criteria_1\":\"4\",\"criteria_2\":\"4\",\"criteria_3\":\"4\",\"criteria_4\":\"4\",\"criteria_5\":\"4\",\"criteria_6\":\"8\",\"criteria_7\":\"6\",\"criteria_8\":\"2\",\"criteria_9\":\"2\",\"criteria_10\":\"2\",\"criteria_11\":\"50\",\"criteria_13\":\"5\"}', '95', '95', '{\"2\":{\"id\":2,\"name\":\"Lãnh đạo\",\"email\":\"lanhdao@gmail.com\",\"password\":\"$2a$12$4e7KuB9kE7jOQgE3vnZoH.Cuks.PzUJMXXUa\\/a7ivHvgcSGNBX\\/uu\",\"role\":\"lanh_dao\",\"position\":\"Lãnh đạo\",\"employee_unit\":\"Phòng Nghiên cứu\",\"created_at\":\"2025-05-09 17:56:55\"},\"7\":{\"id\":7,\"name\":\"phó giám đốc\",\"email\":\"phogiamdoc@gmail.com\",\"password\":\"$2y$10$LEsF0ARW0pfJe0emvdgPPOnXVzO5RvlkfplC72H2XvxydlbiTlxt2\",\"role\":\"pho_giam_doc\",\"position\":\"Phó giám đốc\",\"employee_unit\":\"Phòng Nghiên cứu, Kiểm thử\",\"created_at\":\"2025-05-15 22:23:39\"},\"1\":{\"id\":1,\"name\":\"Giám Đốc\",\"email\":\"giamdoc@gmail.com\",\"password\":\"$2a$12$4e7KuB9kE7jOQgE3vnZoH.Cuks.PzUJMXXUa\\/a7ivHvgcSGNBX\\/uu\",\"role\":\"giam_doc\",\"position\":\"Giám Đốc\",\"employee_unit\":\"Phòng Nghiên cứu, Kiểm thử\",\"created_at\":\"2025-05-09 17:56:55\"}}', '2025-07-07 09:07:48');

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_forms`
--

CREATE TABLE `evaluation_forms` (
  `id` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên form đánh giá',
  `department_id` int DEFAULT NULL COMMENT 'ID phòng ban (NULL nếu là form mặc định)',
  `form_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nhan_vien' COMMENT 'Loại form (nhan_vien/lanh_dao)',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Nội dung form dạng JSON',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evaluation_forms`
--

INSERT INTO `evaluation_forms` (`id`, `name`, `department_id`, `form_type`, `content`, `created_at`, `updated_at`) VALUES
(2, 'Test 372025', NULL, 'nhan_vien', '{\"form_type\":\"nhan_vien\",\"sections\":[{\"id\":\"section_1\",\"title\":\"I. Năng lực và kỹ năng chung\",\"description\":\"Đánh giá năng lực, kỹ năng chung trong công việc\",\"weight\":20,\"criteria\":[{\"id\":\"criteria_1\",\"text\":\"Ý thức tổ chức kỷ luật (Chấp hành các quy định đã được ban hành. Thường xuyên đi học hỏi và đóng góp tích cực cho tập thể, có ý thức về thời gian và sử dụng thời gian có hiệu quả)\",\"max_score\":4,\"examples\":[{\"level\":1,\"description\":\"Thường xuyên vi phạm nội quy, quy định cơ quan\"},{\"level\":2,\"description\":\"Đôi khi không tuân thủ đầy đủ quy định\"},{\"level\":3,\"description\":\"Thực hiện tốt các quy định, đúng giờ làm việc\"},{\"level\":4,\"description\":\"Tuân thủ nghiêm túc tất cả quy định, là tấm gương cho đồng nghiệp\"}]},{\"id\":\"criteria_2\",\"text\":\"Chất lượng và năng suất trong công việc\",\"max_score\":4,\"examples\":[{\"level\":1,\"description\":\"Chất lượng công việc thấp, không đạt yêu cầu\"},{\"level\":2,\"description\":\"Đạt yêu cầu cơ bản, đôi khi cần chỉnh sửa\"},{\"level\":3,\"description\":\"Chất lượng tốt, ít khi cần sửa chữa\"},{\"level\":4,\"description\":\"Chất lượng xuất sắc, vượt mức kỳ vọng\"}]},{\"id\":\"criteria_3\",\"text\":\"Tính tự chủ và trách nhiệm trong công việc, khả năng tự làm việc\",\"max_score\":4,\"examples\":[{\"level\":1,\"description\":\"Luôn cần sự giám sát và hướng dẫn thường xuyên\"},{\"level\":2,\"description\":\"Đôi khi cần hướng dẫn và giám sát\"},{\"level\":3,\"description\":\"Có thể làm việc độc lập và chịu trách nhiệm\"},{\"level\":4,\"description\":\"Làm việc độc lập xuất sắc, chủ động giải quyết vấn đề phát sinh\"}]},{\"id\":\"criteria_4\",\"text\":\"Tính chính xác, hoàn thành công việc đúng tiến độ và hiệu quả\",\"max_score\":4,\"examples\":[{\"level\":1,\"description\":\"Thường xuyên làm sai và chậm tiến độ\"},{\"level\":2,\"description\":\"Đôi khi còn sai sót và chậm tiến độ\"},{\"level\":3,\"description\":\"Hoàn thành đúng tiến độ với độ chính xác cao\"},{\"level\":4,\"description\":\"Luôn hoàn thành trước hạn với độ chính xác tuyệt đối\"}]},{\"id\":\"criteria_5\",\"text\":\"Khả năng tư duy, sáng tạo và cải tiến trong công việc\",\"max_score\":4,\"examples\":[{\"level\":1,\"description\":\"Không có ý tưởng sáng tạo, làm việc theo lối mòn\"},{\"level\":2,\"description\":\"Đôi khi có ý tưởng cải tiến nhỏ\"},{\"level\":3,\"description\":\"Thường xuyên đề xuất cải tiến hợp lý\"},{\"level\":4,\"description\":\"Liên tục đề xuất sáng kiến mới, cải tiến quy trình làm việc\"}]}]},{\"id\":\"section_2\",\"title\":\"II. Năng lực về kỹ năng (KSCL thực hiện tất cả công tác phân giao)\",\"description\":\"Đánh giá năng lực chuyên môn và kỹ năng thực hiện nhiệm vụ\",\"weight\":20,\"criteria\":[{\"id\":\"criteria_6\",\"text\":\"Khả năng chuyên môn, trình độ trong giải quyết công việc\",\"max_score\":8,\"examples\":[{\"level\":2,\"description\":\"Kiến thức chuyên môn hạn chế, thường xuyên cần hỗ trợ\"},{\"level\":4,\"description\":\"Có đủ kiến thức chuyên môn cơ bản để thực hiện công việc\"},{\"level\":6,\"description\":\"Kiến thức chuyên môn tốt, xử lý tốt các tình huống phát sinh\"},{\"level\":8,\"description\":\"Chuyên môn xuất sắc, có thể đào tạo, hướng dẫn cho người khác\"}]},{\"id\":\"criteria_7\",\"text\":\"Kỹ năng lập kế hoạch, tổ chức, thực hiện công việc\",\"max_score\":6,\"examples\":[{\"level\":2,\"description\":\"Thiếu kỹ năng lập kế hoạch, thường bị động trong công việc\"},{\"level\":4,\"description\":\"Có kỹ năng lập kế hoạch cơ bản, đôi khi cần điều chỉnh\"},{\"level\":6,\"description\":\"Lập kế hoạch chi tiết, tổ chức thực hiện hiệu quả\"}]},{\"id\":\"criteria_8\",\"text\":\"Phối hợp, làm việc nhóm và với các phòng, bộ phận liên quan\",\"max_score\":2,\"examples\":[{\"level\":1,\"description\":\"Khó khăn trong phối hợp, thiếu kỹ năng làm việc nhóm\"},{\"level\":2,\"description\":\"Phối hợp tốt, chủ động kết nối với các đơn vị liên quan\"}]},{\"id\":\"criteria_9\",\"text\":\"Kỹ năng phân tích, xử lý tình huống và giải quyết vấn đề\",\"max_score\":2,\"examples\":[{\"level\":1,\"description\":\"Khả năng phân tích hạn chế, thiếu kỹ năng giải quyết vấn đề\"},{\"level\":2,\"description\":\"Phân tích tốt, giải quyết vấn đề hiệu quả và kịp thời\"}]},{\"id\":\"criteria_10\",\"text\":\"Khả năng sử dụng phương tiện, thiết bị, công nghệ\",\"max_score\":2,\"examples\":[{\"level\":1,\"description\":\"Hạn chế trong sử dụng các phương tiện, thiết bị\"},{\"level\":2,\"description\":\"Thành thạo và sử dụng hiệu quả các phương tiện, thiết bị\"}]}]},{\"id\":\"section_3\",\"title\":\"III. Kết quả thực hiện nhiệm vụ được giao (KPIs theo định kỳ hàng kỳ)\",\"description\":\"Đánh giá kết quả công việc so với chỉ tiêu được giao\",\"weight\":50,\"criteria\":[{\"id\":\"criteria_11\",\"text\":\"Kết quả thực hiện các công việc, nhiệm vụ được giao (KPIs)\",\"max_score\":50,\"examples\":[{\"level\":10,\"description\":\"Hoàn thành dưới 50% chỉ tiêu được giao\"},{\"level\":20,\"description\":\"Hoàn thành từ 50-70% chỉ tiêu được giao\"},{\"level\":30,\"description\":\"Hoàn thành từ 70-85% chỉ tiêu được giao\"},{\"level\":40,\"description\":\"Hoàn thành từ 85-95% chỉ tiêu được giao\"},{\"level\":50,\"description\":\"Hoàn thành 95-100% hoặc vượt chỉ tiêu được giao\"}]}]},{\"id\":\"section_4\",\"title\":\"IV. Đột phá sáng tạo, tinh thần học hỏi, phục vụ\",\"description\":\"Đánh giá khả năng sáng tạo và tinh thần học hỏi\",\"weight\":10,\"criteria\":[{\"id\":\"criteria_12\",\"text\":\"Tinh thần học hỏi, phát triển kỹ năng và kiến thức\",\"max_score\":4,\"examples\":[{\"level\":1,\"description\":\"Ít chủ động học hỏi, phát triển bản thân\"},{\"level\":2,\"description\":\"Thỉnh thoảng chủ động học hỏi khi được khuyến khích\"},{\"level\":3,\"description\":\"Thường xuyên học hỏi, nâng cao kỹ năng\"},{\"level\":4,\"description\":\"Chủ động học hỏi, không ngừng tự phát triển bản thân\"}]},{\"id\":\"criteria_13\",\"text\":\"Đề xuất sáng kiến, cải tiến trong công việc\",\"max_score\":3,\"examples\":[{\"level\":1,\"description\":\"Không có sáng kiến, đề xuất cải tiến\"},{\"level\":2,\"description\":\"Có một số đề xuất cải tiến nhỏ\"},{\"level\":3,\"description\":\"Thường xuyên đề xuất sáng kiến và cải tiến có giá trị\"}]},{\"id\":\"criteria_14\",\"text\":\"Tinh thần phục vụ, thái độ với đồng nghiệp và người được phục vụ\",\"max_score\":3,\"examples\":[{\"level\":1,\"description\":\"Thái độ phục vụ chưa tốt, đôi khi gây phiền hà\"},{\"level\":2,\"description\":\"Thái độ phục vụ tốt, thân thiện với mọi người\"},{\"level\":3,\"description\":\"Luôn nhiệt tình, tận tâm phục vụ, được mọi người đánh giá cao\"}]}]}],\"competency_levels\":[{\"name\":\"Không đạt\",\"range\":\"0-69\",\"description\":\"Không đáp ứng yêu cầu, cần cải thiện nhiều\"},{\"name\":\"Đạt\",\"range\":\"70-84\",\"description\":\"Đáp ứng yêu cầu cơ bản của vị trí\"},{\"name\":\"Xuất sắc\",\"range\":\"85-100\",\"description\":\"Vượt mức kỳ vọng, đóng góp xuất sắc\"}]}', '2025-07-03 08:17:12', '2025-07-06 15:03:52'),
(3, 'Đỗ Văn Vũ', NULL, 'nhan_vien', '{\n  \"form_type\": \"nhan_vien\",\n  \"part1\": {\n    \"title\": \"I. Adu hacker\",\n    \"total_max\": 20,\n    \"criteria\": {\n      \"1\": {\n        \"text\": \"Thực hiện nghiêm túc các quy định, quy chế, nội quy của cơ quan\",\n        \"max_score\": 5\n      },\n      \"2\": {\n        \"text\": \"Chấp hành sự phân công của tổ chức\",\n        \"max_score\": 4\n      },\n      \"3\": {\n        \"text\": \"Có thái độ đúng mực và phong cách ứng xử, lề lối làm việc chuẩn mực\",\n        \"max_score\": 4\n      },\n      \"4\": {\n        \"text\": \"Có tinh thần trách nhiệm với công việc, phương pháp làm việc khoa học\",\n        \"max_score\": 4\n      },\n      \"5\": {\n        \"text\": \"Báo cáo đầy đủ, kịp thời, trung thực với cấp trên\",\n        \"max_score\": 3\n      }\n    }\n  }\n}', '2025-05-15 14:12:02', '2025-07-03 08:37:08'),
(8, 'Form đánh giá Chuyên viên - Mặc định', NULL, 'nhan_vien', '{\n  \"form_type\": \"nhan_vien\",\n  \"part1\": {\n    \"title\": \"I. Adu hacker\",\n    \"total_max\": 20,\n    \"criteria\": {\n      \"1\": {\n        \"text\": \"Thực hiện nghiêm túc các quy định, quy chế, nội quy của cơ quan\",\n        \"max_score\": 5\n      },\n      \"2\": {\n        \"text\": \"Chấp hành sự phân công của tổ chức\",\n        \"max_score\": 4\n      },\n      \"3\": {\n        \"text\": \"Có thái độ đúng mực và phong cách ứng xử, lề lối làm việc chuẩn mực\",\n        \"max_score\": 4\n      },\n      \"4\": {\n        \"text\": \"Có tinh thần trách nhiệm với công việc, phương pháp làm việc khoa học\",\n        \"max_score\": 4\n      },\n      \"5\": {\n        \"text\": \"Báo cáo đầy đủ, kịp thời, trung thực với cấp trên\",\n        \"max_score\": 3\n      }\n    }\n  }\n}', '2025-05-15 15:18:21', '2025-07-03 08:18:26'),
(9, 'Form đánh giá Lãnh đạo - Mặc định', NULL, 'lanh_dao', '{\n  \"form_type\": \"lanh_dao\",\n  \"part1\": {\n    \"title\": \"I. Ý thức tổ chức kỷ luật\",\n    \"total_max\": 20,\n    \"criteria\": {\n      \"1\": {\n        \"text\": \"Thực hiện nghiêm túc các quy định, quy chế, nội quy của cơ quan\",\n        \"max_score\": 5\n      },\n      \"2\": {\n        \"text\": \"Chấp hành sự phân công của tổ chức\",\n        \"max_score\": 4\n      },\n      \"3\": {\n        \"text\": \"Có thái độ đúng mực và phong cách ứng xử, lề lối làm việc chuẩn mực\",\n        \"max_score\": 4\n      },\n      \"4\": {\n        \"text\": \"Có tinh thần trách nhiệm với công việc, phương pháp làm việc khoa học\",\n        \"max_score\": 4\n      },\n      \"5\": {\n        \"text\": \"Báo cáo đầy đủ, kịp thời, trung thực với cấp trên\",\n        \"max_score\": 3\n      }\n    }\n  },\n  \"part2\": {\n    \"title\": \"II. Năng lực và kỹ năng\",\n    \"total_max\": 20,\n    \"criteria\": {\n      \"6\": {\n        \"text\": \"Có năng lực tập hợp công chức, viên chức, xây dựng đơn vị bộ phận đoàn kết\",\n        \"max_score\": 2\n      },\n      \"7\": {\n        \"text\": \"Chỉ đạo, điều hành, kiểm soát việc thực hiện nhiệm vụ\",\n        \"max_score\": 2\n      },\n      \"8\": {\n        \"text\": \"Phối hợp, tạo lập mối quan hệ tốt với cá nhân, tổ chức\",\n        \"max_score\": 2\n      },\n      \"9\": {\n        \"text\": \"Hoàn thành kịp thời và bảo đảm chất lượng, hiệu quả nhiệm vụ đột xuất\",\n        \"max_score\": 2\n      },\n      \"10\": {\n        \"text\": \"Làm tốt công tác tham mưu, hoạch định, xây dựng văn bản quy phạm pháp luật\",\n        \"max_score\": 2\n      },\n      \"11\": {\n        \"text\": \"Làm tốt công tác kiểm tra, thanh tra, giải quyết khiếu nại\",\n        \"max_score\": 5\n      },\n      \"12\": {\n        \"text\": \"Xây dựng chương trình, kế hoạch hoạt động hàng Quý\",\n        \"max_score\": 5\n      }\n    }\n  },\n  \"part3\": {\n    \"title\": \"III. Kết quả thực hiện chức trách, nhiệm vụ được giao\",\n    \"total_max\": 60,\n    \"criteria\": {\n      \"level1\": {\n        \"text\": \"Thực hiện nhiệm vụ được giao đảm bảo tiến độ và chất lượng\",\n        \"max_score\": 60\n      }\n    }\n  }\n}', '2025-05-15 16:19:18', '2025-07-03 08:45:57'),
(11, 'Form Lãnh đạo', NULL, 'lanh_dao', '{\"form_type\":\"lanh_dao\",\"sections\":[{\"id\":\"section_1\",\"title\":\"I. Ý thức tổ chức kỷ luật\",\"description\":\"Đánh giá ý thức, thái độ và tuân thủ kỷ luật của lãnh đạo\",\"weight\":15,\"criteria\":[{\"id\":\"criteria_1\",\"text\":\"Thực hiện nghiêm túc các quy định, quy chế, nội quy của cơ quan\",\"max_score\":4,\"examples\":[{\"level\":1,\"description\":\"Thường xuyên vi phạm nội quy, quy định\"},{\"level\":2,\"description\":\"Thỉnh thoảng không tuân thủ một số quy định nhỏ\"},{\"level\":3,\"description\":\"Tuân thủ tốt các quy định của cơ quan\"},{\"level\":4,\"description\":\"Luôn gương mẫu chấp hành nghiêm túc, vận động người khác tuân thủ\"}]},{\"id\":\"criteria_2\",\"text\":\"Chấp hành sự phân công của tổ chức\",\"max_score\":3,\"examples\":[{\"level\":1,\"description\":\"Thường từ chối hoặc không hoàn thành nhiệm vụ được giao\"},{\"level\":2,\"description\":\"Đôi khi chưa hoàn thành tốt nhiệm vụ được giao\"},{\"level\":3,\"description\":\"Luôn chấp hành tốt và chủ động trong mọi phân công\"}]},{\"id\":\"criteria_3\",\"text\":\"Có thái độ đúng mực và phong cách ứng xử, lề lối làm việc chuẩn mực\",\"max_score\":4,\"examples\":[{\"level\":1,\"description\":\"Thái độ và phong cách không phù hợp với vị trí lãnh đạo\"},{\"level\":2,\"description\":\"Đôi khi có cách ứng xử chưa phù hợp trong một số tình huống\"},{\"level\":3,\"description\":\"Thường có thái độ và cách ứng xử phù hợp\"},{\"level\":4,\"description\":\"Luôn là tấm gương về thái độ, phong cách và lề lối làm việc\"}]},{\"id\":\"criteria_4\",\"text\":\"Báo cáo đầy đủ, kịp thời, trung thực với cấp trên\",\"max_score\":4,\"examples\":[{\"level\":1,\"description\":\"Báo cáo không đầy đủ, thiếu trung thực\"},{\"level\":2,\"description\":\"Báo cáo chậm trễ hoặc thiếu một số thông tin quan trọng\"},{\"level\":3,\"description\":\"Báo cáo đầy đủ và kịp thời các vấn đề quan trọng\"},{\"level\":4,\"description\":\"Luôn chủ động báo cáo kịp thời, đầy đủ và trung thực\"}]}]},{\"id\":\"section_2\",\"title\":\"II. Năng lực lãnh đạo và quản lý\",\"description\":\"Đánh giá khả năng lãnh đạo, điều hành và quản lý\",\"weight\":35,\"criteria\":[{\"id\":\"criteria_5\",\"text\":\"Có năng lực tập hợp và xây dựng tập thể đoàn kết\",\"max_score\":6,\"examples\":[{\"level\":2,\"description\":\"Chưa tạo được sự đồng thuận, tập thể thiếu đoàn kết\"},{\"level\":4,\"description\":\"Có khả năng tập hợp, duy trì đoàn kết trong tập thể\"},{\"level\":6,\"description\":\"Xây dựng được tập thể đoàn kết, gắn bó và hỗ trợ lẫn nhau\"}]},{\"id\":\"criteria_6\",\"text\":\"Khả năng chỉ đạo, điều hành, kiểm soát việc thực hiện nhiệm vụ\",\"max_score\":7,\"examples\":[{\"level\":2,\"description\":\"Thiếu kỹ năng chỉ đạo, điều hành còn lúng túng\"},{\"level\":4,\"description\":\"Có khả năng chỉ đạo, điều hành các công việc thường ngày\"},{\"level\":6,\"description\":\"Điều hành hiệu quả, kiểm soát tốt tiến độ và chất lượng công việc\"},{\"level\":7,\"description\":\"Xuất sắc trong chỉ đạo và điều hành, đạt hiệu quả cao\"}]},{\"id\":\"criteria_7\",\"text\":\"Khả năng phân công, giao việc phù hợp với năng lực\",\"max_score\":6,\"examples\":[{\"level\":2,\"description\":\"Phân công không phù hợp với năng lực của nhân viên\"},{\"level\":4,\"description\":\"Phân công công việc tương đối phù hợp với năng lực\"},{\"level\":6,\"description\":\"Phân công công việc rất phù hợp, phát huy tối đa năng lực của nhân viên\"}]},{\"id\":\"criteria_8\",\"text\":\"Khả năng ra quyết định và giải quyết vấn đề\",\"max_score\":8,\"examples\":[{\"level\":2,\"description\":\"Thường do dự, trì hoãn hoặc né tránh việc ra quyết định\"},{\"level\":4,\"description\":\"Ra quyết định phù hợp trong các tình huống thông thường\"},{\"level\":6,\"description\":\"Ra quyết định tốt, giải quyết hiệu quả các vấn đề phức tạp\"},{\"level\":8,\"description\":\"Xuất sắc trong việc ra quyết định, giải quyết các vấn đề khó khăn\"}]},{\"id\":\"criteria_9\",\"text\":\"Khả năng lập kế hoạch chiến lược và tổ chức thực hiện\",\"max_score\":8,\"examples\":[{\"level\":2,\"description\":\"Không có tầm nhìn chiến lược, kế hoạch thiếu khả thi\"},{\"level\":4,\"description\":\"Có kế hoạch phù hợp, tổ chức thực hiện còn hạn chế\"},{\"level\":6,\"description\":\"Lập kế hoạch tốt, tổ chức thực hiện hiệu quả\"},{\"level\":8,\"description\":\"Có tầm nhìn chiến lược xuất sắc, triển khai thực hiện đạt kết quả cao\"}]}]},{\"id\":\"section_3\",\"title\":\"III. Khả năng chuyên môn và tham mưu\",\"description\":\"Đánh giá kiến thức chuyên môn và khả năng tham mưu, đề xuất\",\"weight\":20,\"criteria\":[{\"id\":\"criteria_10\",\"text\":\"Làm tốt công tác tham mưu, hoạch định chính sách\",\"max_score\":8,\"examples\":[{\"level\":2,\"description\":\"Năng lực tham mưu còn hạn chế, đề xuất chưa khả thi\"},{\"level\":4,\"description\":\"Có khả năng tham mưu các vấn đề thường ngày\"},{\"level\":6,\"description\":\"Tham mưu tốt các chính sách, kế hoạch quan trọng\"},{\"level\":8,\"description\":\"Xuất sắc trong tham mưu hoạch định chính sách, định hướng phát triển\"}]},{\"id\":\"criteria_11\",\"text\":\"Năng lực chuyên môn trong lĩnh vực phụ trách\",\"max_score\":7,\"examples\":[{\"level\":2,\"description\":\"Kiến thức chuyên môn còn hạn chế\"},{\"level\":4,\"description\":\"Có kiến thức chuyên môn đủ để điều hành công việc\"},{\"level\":6,\"description\":\"Kiến thức chuyên môn tốt, áp dụng hiệu quả vào thực tiễn\"},{\"level\":7,\"description\":\"Kiến thức chuyên môn sâu rộng, là chuyên gia trong lĩnh vực\"}]},{\"id\":\"criteria_12\",\"text\":\"Khả năng đổi mới và sáng tạo trong quản lý\",\"max_score\":5,\"examples\":[{\"level\":1,\"description\":\"Ít có đề xuất đổi mới, thường theo lối mòn cũ\"},{\"level\":3,\"description\":\"Có một số cải tiến, đổi mới trong quản lý\"},{\"level\":5,\"description\":\"Thường xuyên đề xuất ý tưởng sáng tạo, cải tiến hiệu quả\"}]}]},{\"id\":\"section_4\",\"title\":\"IV. Kết quả thực hiện nhiệm vụ\",\"description\":\"Đánh giá kết quả công việc và hiệu quả quản lý\",\"weight\":30,\"criteria\":[{\"id\":\"criteria_13\",\"text\":\"Kết quả hoàn thành nhiệm vụ được giao\",\"max_score\":10,\"examples\":[{\"level\":3,\"description\":\"Hoàn thành dưới 70% nhiệm vụ được giao\"},{\"level\":6,\"description\":\"Hoàn thành từ 70% đến dưới 90% nhiệm vụ được giao\"},{\"level\":8,\"description\":\"Hoàn thành từ 90% đến 100% nhiệm vụ được giao\"},{\"level\":10,\"description\":\"Hoàn thành 100% nhiệm vụ, có nhiều kết quả vượt trội\"}]},{\"id\":\"criteria_14\",\"text\":\"Kết quả hoạt động của đơn vị được giao phụ trách\",\"max_score\":10,\"examples\":[{\"level\":3,\"description\":\"Kết quả hoạt động của đơn vị còn nhiều hạn chế\"},{\"level\":6,\"description\":\"Đơn vị hoạt động ổn định, đạt được các mục tiêu cơ bản\"},{\"level\":8,\"description\":\"Đơn vị hoạt động tốt, đạt hầu hết các mục tiêu đề ra\"},{\"level\":10,\"description\":\"Đơn vị hoạt động xuất sắc, vượt mọi chỉ tiêu đề ra\"}]},{\"id\":\"criteria_15\",\"text\":\"Hiệu quả sử dụng và phát triển nguồn lực\",\"max_score\":5,\"examples\":[{\"level\":1,\"description\":\"Sử dụng nguồn lực lãng phí, chưa hiệu quả\"},{\"level\":3,\"description\":\"Sử dụng nguồn lực hợp lý, đạt hiệu quả trung bình\"},{\"level\":5,\"description\":\"Sử dụng nguồn lực hiệu quả, tối ưu hóa chi phí và nguồn lực\"}]},{\"id\":\"criteria_16\",\"text\":\"Mức độ hoàn thành các chỉ tiêu, kế hoạch của tổ chức\",\"max_score\":5,\"examples\":[{\"level\":1,\"description\":\"Không đạt nhiều chỉ tiêu, kế hoạch đề ra\"},{\"level\":3,\"description\":\"Đạt được hầu hết các chỉ tiêu, kế hoạch quan trọng\"},{\"level\":5,\"description\":\"Vượt mức hầu hết các chỉ tiêu, kế hoạch quan trọng\"}]}]}],\"competency_levels\":[{\"name\":\"Yếu\",\"range\":\"0-49\",\"description\":\"Không đáp ứng yêu cầu của vị trí lãnh đạo\"},{\"name\":\"Trung bình\",\"range\":\"50-69\",\"description\":\"Đáp ứng một phần yêu cầu của vị trí lãnh đạo\"},{\"name\":\"Khá\",\"range\":\"70-84\",\"description\":\"Đáp ứng hầu hết yêu cầu của vị trí lãnh đạo\"},{\"name\":\"Tốt\",\"range\":\"85-94\",\"description\":\"Đáp ứng đầy đủ, thỉnh thoảng vượt mức yêu cầu\"},{\"name\":\"Xuất sắc\",\"range\":\"95-100\",\"description\":\"Vượt xa yêu cầu của vị trí lãnh đạo\"}]}', '2025-07-03 15:20:25', '2025-07-06 15:03:50'),
(12, 'form final', 1, 'nhan_vien', '{\"form_type\":\"nhan_vien\",\"sections\":[{\"id\":\"section_1\",\"title\":\"I. Năng lực và kỹ năng chung\",\"description\":\"Đánh giá năng lực, kỹ năng chung trong công việc\",\"weight\":20,\"criteria\":[{\"id\":\"criteria_1\",\"text\":\"Ý thức tổ chức kỷ luật (Chấp hành các quy định đã được ban hành. Thường xuyên đi học hỏi và đóng góp tích cực cho tập thể, có ý thức về thời gian và sử dụng thời gian có hiệu quả)\",\"max_score\":4,\"examples\":[{\"level\":1,\"description\":\"Thường xuyên vi phạm nội quy, quy định cơ quan\"},{\"level\":2,\"description\":\"Đôi khi không tuân thủ đầy đủ quy định\"},{\"level\":3,\"description\":\"Thực hiện tốt các quy định, đúng giờ làm việc\"},{\"level\":4,\"description\":\"Tuân thủ nghiêm túc tất cả quy định, là tấm gương cho đồng nghiệp\"}]},{\"id\":\"criteria_2\",\"text\":\"Chất lượng và năng suất trong công việc\",\"max_score\":4,\"examples\":[{\"level\":1,\"description\":\"Chất lượng công việc thấp, không đạt yêu cầu\"},{\"level\":2,\"description\":\"Đạt yêu cầu cơ bản, đôi khi cần chỉnh sửa\"},{\"level\":3,\"description\":\"Chất lượng tốt, ít khi cần sửa chữa\"},{\"level\":4,\"description\":\"Chất lượng xuất sắc, vượt mức kỳ vọng\"}]},{\"id\":\"criteria_3\",\"text\":\"Tính tự chủ và trách nhiệm trong công việc, khả năng tự làm việc\",\"max_score\":4,\"examples\":[{\"level\":1,\"description\":\"Luôn cần sự giám sát và hướng dẫn thường xuyên\"},{\"level\":2,\"description\":\"Đôi khi cần hướng dẫn và giám sát\"},{\"level\":3,\"description\":\"Có thể làm việc độc lập và chịu trách nhiệm\"},{\"level\":4,\"description\":\"Làm việc độc lập xuất sắc, chủ động giải quyết vấn đề phát sinh\"}]},{\"id\":\"criteria_4\",\"text\":\"Tính chính xác, hoàn thành công việc đúng tiến độ và hiệu quả\",\"max_score\":4,\"examples\":[{\"level\":1,\"description\":\"Thường xuyên làm sai và chậm tiến độ\"},{\"level\":2,\"description\":\"Đôi khi còn sai sót và chậm tiến độ\"},{\"level\":3,\"description\":\"Hoàn thành đúng tiến độ với độ chính xác cao\"},{\"level\":4,\"description\":\"Luôn hoàn thành trước hạn với độ chính xác tuyệt đối\"}]},{\"id\":\"criteria_5\",\"text\":\"Khả năng tư duy, sáng tạo và cải tiến trong công việc\",\"max_score\":4,\"examples\":[{\"level\":1,\"description\":\"Không có ý tưởng sáng tạo, làm việc theo lối mòn\"},{\"level\":2,\"description\":\"Đôi khi có ý tưởng cải tiến nhỏ\"},{\"level\":3,\"description\":\"Thường xuyên đề xuất cải tiến hợp lý\"},{\"level\":4,\"description\":\"Liên tục đề xuất sáng kiến mới, cải tiến quy trình làm việc\"}]}]},{\"id\":\"section_2\",\"title\":\"II. Năng lực về kỹ năng (KSCL thực hiện tất cả công tác phân giao)\",\"description\":\"Đánh giá năng lực chuyên môn và kỹ năng thực hiện nhiệm vụ\",\"weight\":20,\"criteria\":[{\"id\":\"criteria_6\",\"text\":\"Khả năng chuyên môn, trình độ trong giải quyết công việc\",\"max_score\":8,\"examples\":[{\"level\":2,\"description\":\"Kiến thức chuyên môn hạn chế, thường xuyên cần hỗ trợ\"},{\"level\":4,\"description\":\"Có đủ kiến thức chuyên môn cơ bản để thực hiện công việc\"},{\"level\":6,\"description\":\"Kiến thức chuyên môn tốt, xử lý tốt các tình huống phát sinh\"},{\"level\":8,\"description\":\"Chuyên môn xuất sắc, có thể đào tạo, hướng dẫn cho người khác\"}]},{\"id\":\"criteria_7\",\"text\":\"Kỹ năng lập kế hoạch, tổ chức, thực hiện công việc\",\"max_score\":6,\"examples\":[{\"level\":2,\"description\":\"Thiếu kỹ năng lập kế hoạch, thường bị động trong công việc\"},{\"level\":4,\"description\":\"Có kỹ năng lập kế hoạch cơ bản, đôi khi cần điều chỉnh\"},{\"level\":6,\"description\":\"Lập kế hoạch chi tiết, tổ chức thực hiện hiệu quả\"}]},{\"id\":\"criteria_8\",\"text\":\"Phối hợp, làm việc nhóm và với các phòng, bộ phận liên quan\",\"max_score\":2,\"examples\":[{\"level\":1,\"description\":\"Khó khăn trong phối hợp, thiếu kỹ năng làm việc nhóm\"},{\"level\":2,\"description\":\"Phối hợp tốt, chủ động kết nối với các đơn vị liên quan\"}]},{\"id\":\"criteria_9\",\"text\":\"Kỹ năng phân tích, xử lý tình huống và giải quyết vấn đề\",\"max_score\":2,\"examples\":[{\"level\":1,\"description\":\"Khả năng phân tích hạn chế, thiếu kỹ năng giải quyết vấn đề\"},{\"level\":2,\"description\":\"Phân tích tốt, giải quyết vấn đề hiệu quả và kịp thời\"}]},{\"id\":\"criteria_10\",\"text\":\"Khả năng sử dụng phương tiện, thiết bị, công nghệ\",\"max_score\":2,\"examples\":[{\"level\":1,\"description\":\"Hạn chế trong sử dụng các phương tiện, thiết bị\"},{\"level\":2,\"description\":\"Thành thạo và sử dụng hiệu quả các phương tiện, thiết bị\"}]}]},{\"id\":\"section_3\",\"title\":\"III. Kết quả thực hiện nhiệm vụ được giao (KPIs theo định kỳ hàng kỳ)\",\"description\":\"Đánh giá kết quả công việc so với chỉ tiêu được giao\",\"weight\":50,\"criteria\":[{\"id\":\"criteria_11\",\"text\":\"Kết quả thực hiện các công việc, nhiệm vụ được giao (KPIs)\",\"max_score\":50,\"examples\":[{\"level\":10,\"description\":\"Hoàn thành dưới 50% chỉ tiêu được giao\"},{\"level\":20,\"description\":\"Hoàn thành từ 50-70% chỉ tiêu được giao\"},{\"level\":30,\"description\":\"Hoàn thành từ 70-85% chỉ tiêu được giao\"},{\"level\":40,\"description\":\"Hoàn thành từ 85-95% chỉ tiêu được giao\"},{\"level\":50,\"description\":\"Hoàn thành 95-100% hoặc vượt chỉ tiêu được giao\"}]}]},{\"id\":\"section_4\",\"title\":\"IV. Phan tram hoan thanh\",\"description\":\"Phan tram hoan thanh\",\"weight\":10,\"criteria\":[{\"id\":\"criteria_13\",\"text\":\"Phan tram hoan thanh\",\"max_score\":10,\"examples\":[]}]}],\"competency_levels\":[{\"name\":\"Không đạt\",\"range\":\"0-69\",\"description\":\"Không đáp ứng yêu cầu, cần cải thiện nhiều\"},{\"name\":\"Đạt\",\"range\":\"70-84\",\"description\":\"Đáp ứng yêu cầu cơ bản của vị trí\"},{\"name\":\"Xuất sắc\",\"range\":\"85-100\",\"description\":\"Vượt mức kỳ vọng, đóng góp xuất sắc\"}]}', '2025-07-06 15:06:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','nhan_vien','lanh_dao','giam_doc','pho_giam_doc') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `employee_unit` varchar(256) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `position`, `employee_unit`, `created_at`) VALUES
(1, 'Giám Đốc', 'giamdoc@gmail.com', '$2a$12$4e7KuB9kE7jOQgE3vnZoH.Cuks.PzUJMXXUa/a7ivHvgcSGNBX/uu', 'giam_doc', 'Giám Đốc', 'Phòng Nghiên cứu, Kiểm thử', '2025-05-09 10:56:55'),
(2, 'Lãnh đạo', 'lanhdao@gmail.com', '$2a$12$4e7KuB9kE7jOQgE3vnZoH.Cuks.PzUJMXXUa/a7ivHvgcSGNBX/uu', 'lanh_dao', 'Lãnh đạo', 'Phòng Nghiên cứu', '2025-05-09 10:56:55'),
(3, 'Chuyên Viên', 'nhanvien@gmail.com', '$2a$12$4e7KuB9kE7jOQgE3vnZoH.Cuks.PzUJMXXUa/a7ivHvgcSGNBX/uu', 'nhan_vien', 'Chuyên Viên', 'Phòng Nghiên cứu, Kiểm thử', '2025-05-09 10:56:55'),
(4, 'Admin', 'admin@gmail.com', '$2y$10$LEsF0ARW0pfJe0emvdgPPOnXVzO5RvlkfplC72H2XvxydlbiTlxt2', 'admin', 'Admin', 'Phòng Nghiên cứu, Kiểm thử', '2025-05-09 11:35:14'),
(6, 'Vũ vudevweb Đỗ', 'vudoidol354@hotmail.com', '$2y$10$Hbu6mFaNmYUJ/iUcHbhqkeZkvtHGWhR4rgTwBL1FAp7LjLHVXFWHW', 'lanh_dao', 'Vũ vudevweb Đỗ', 'Phòng Nghiên cứu, Kiểm thử', '2025-05-09 11:43:14'),
(7, 'phó giám đốc', 'phogiamdoc@gmail.com', '$2y$10$LEsF0ARW0pfJe0emvdgPPOnXVzO5RvlkfplC72H2XvxydlbiTlxt2', 'pho_giam_doc', 'Phó giám đốc', 'Phòng Nghiên cứu, Kiểm thử', '2025-05-15 15:23:39');

-- --------------------------------------------------------

--
-- Table structure for table `user_departments`
--

CREATE TABLE `user_departments` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `department_id` int NOT NULL,
  `is_leader` tinyint(1) DEFAULT '0' COMMENT 'Đánh dấu là lãnh đạo phòng',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_departments`
--

INSERT INTO `user_departments` (`id`, `user_id`, `department_id`, `is_leader`, `created_at`) VALUES
(1, 3, 1, 0, '2025-05-15 06:10:18'),
(2, 2, 1, 1, '2025-05-15 06:10:21'),
(4, 6, 1, 0, '2025-05-15 07:56:39');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `evaluations_form_fk` (`evaluation_form_id`);

--
-- Indexes for table `evaluation_forms`
--
ALTER TABLE `evaluation_forms`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `department_form_type` (`department_id`,`form_type`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_departments`
--
ALTER TABLE `user_departments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `department_id` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `evaluation_forms`
--
ALTER TABLE `evaluation_forms`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_departments`
--
ALTER TABLE `user_departments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_form_fk` FOREIGN KEY (`evaluation_form_id`) REFERENCES `evaluation_forms` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `evaluations_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `evaluations_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `evaluation_forms`
--
ALTER TABLE `evaluation_forms`
  ADD CONSTRAINT `evaluation_forms_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_departments`
--
ALTER TABLE `user_departments`
  ADD CONSTRAINT `user_departments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_departments_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
