-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 25, 2026 at 05:57 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ojtmsv2`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `title`, `content`, `status`, `created_by`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Welcome to the OJT Monitoring System', 'Dear students and faculty, welcome to the AI-Assisted OJT Monitoring System. Please complete your profile and begin submitting your required OJT documents through the Checklist section. For questions, use the AI Chatbot available in your dashboard.', 'active', 1, '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(2, 'Reminder: DTR Submission Deadline', 'All students are reminded to submit their Daily Time Records every end of the week. Failure to submit on time may affect your OJT credit hours. Coordinate with your supervisor for proper validation before submission.', 'active', 1, '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(3, 'OJT Orientation Schedule', 'An OJT orientation will be held this coming Friday. All 4th year IT students are required to attend. Details will be sent through the official school email. Please check this platform regularly for updates.', 'active', 1, '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `announcement_reads`
--

CREATE TABLE `announcement_reads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `announcement_id` bigint(20) UNSIGNED NOT NULL,
  `read_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cms_settings`
--

CREATE TABLE `cms_settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` longtext DEFAULT NULL,
  `section` varchar(255) NOT NULL DEFAULT 'general',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cms_settings`
--

INSERT INTO `cms_settings` (`id`, `key`, `value`, `section`, `created_at`, `updated_at`) VALUES
(1, 'header', 'AI-Assisted OJT Monitoring System', 'general', '2026-03-23 23:36:51', '2026-03-23 23:36:51'),
(2, 'subheader', 'Streamline On-the-Job Training Management with Intelligent Analytics', 'general', '2026-03-23 23:36:51', '2026-03-23 23:36:51'),
(3, 'about', 'The OJT Monitoring System (OJTMS) is committed to fostering meaningful partnerships between educational institutions, companies, and students through comprehensive on-the-job training management, real-time monitoring, and intelligent analytics.', 'general', '2026-03-23 23:36:51', '2026-03-23 23:36:51'),
(4, 'mission', 'To empower students and educators through an intelligent, integrated platform that monitors OJT progress, ensures quality internship experiences, and facilitates meaningful skill development.', 'general', '2026-03-23 23:36:51', '2026-03-23 23:36:51'),
(5, 'vision', 'A comprehensive platform leveraging AI and data analytics to create transparent, measurable, and transformative internship experiences that bridge academia and industry.', 'general', '2026-03-23 23:36:51', '2026-03-23 23:36:51'),
(6, 'contact_email', 'ojtms@example.edu.ph', 'contact', '2026-03-23 23:36:51', '2026-03-23 23:36:51'),
(7, 'contact_phone', '+63 900 000 0000', 'contact', '2026-03-23 23:36:51', '2026-03-23 23:36:51'),
(8, 'contact_address', 'Sample City, Philippines', 'contact', '2026-03-23 23:36:51', '2026-03-23 23:36:51'),
(9, 'facebook_url', 'https://facebook.com', 'social_media', '2026-03-23 23:36:51', '2026-03-23 23:36:51'),
(10, 'instagram_url', 'https://instagram.com', 'social_media', '2026-03-23 23:36:51', '2026-03-23 23:36:51'),
(11, 'linkedin_url', 'https://linkedin.com', 'social_media', '2026-03-23 23:36:51', '2026-03-23 23:36:51'),
(12, 'twitter_url', 'https://twitter.com', 'social_media', '2026-03-23 23:36:51', '2026-03-23 23:36:51'),
(13, 'youtube_url', 'https://youtube.com', 'social_media', '2026-03-23 23:36:51', '2026-03-23 23:36:51'),
(14, 'openai_api_key', '', 'openai', '2026-03-24 17:02:00', '2026-03-25 16:31:34'),
(15, 'openai_enabled', '1', 'openai', '2026-03-24 17:02:00', '2026-03-24 17:02:00');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `faculty_id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'General',
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `faculty_id`, `category`, `question`, `answer`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 'DTR & Attendance', 'How do I submit my Daily Time Record (DTR)?', 'Go to your Checklist, select \"Daily Time Record\", then click \"Submit New DTR\". Fill in the week covered, total hours worked, and supervisor name, then upload your signed DTR file. Click Submit and wait for faculty review.', NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15'),
(2, 2, 'DTR & Attendance', 'What happens if my DTR is declined?', 'If your DTR is declined, check the faculty remarks on your DTR entry for the specific reason. Correct the issue (e.g., missing signature, incorrect hours) and resubmit a revised DTR.', NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15'),
(3, 2, 'DTR & Attendance', 'How many hours are required to complete OJT?', 'The required OJT hours depend on your program. Your faculty will set the target hours on your DTR checklist. Typically, IT students are required to complete 486 hours. Check with your faculty for your specific requirement.', NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15'),
(4, 2, 'Documents & Submissions', 'What documents do I need to complete my OJT checklist?', 'You need to submit the following: Medical Record, Receipt of OJT Kit, Waiver, Endorsement Letter, MOA (Memorandum of Agreement), DTR (weekly), Weekly Report, Monthly Appraisal, Supervisor Evaluation, and Certificate of Completion.', NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15'),
(5, 2, 'Documents & Submissions', 'How do I submit my Weekly Report?', 'Go to the Weekly Report section from the sidebar. Click \"Submit New Weekly Report\", fill in the week details and accomplishments, upload your weekly report file, and submit. Your faculty will review it.', NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15'),
(6, 2, 'Documents & Submissions', 'Can I edit a submission after it has been submitted?', 'You can edit a submission only while its status is \"Pending\" (not yet reviewed by faculty). Once the faculty has started reviewing or approved/declined it, editing is no longer allowed.', NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15'),
(7, 2, 'Reviews & Status', 'How long does faculty review take?', 'Reviews are typically processed within 1–3 working days. You will see the status updated on your checklist page. If it has been more than 3 days, you may reach out to your assigned faculty.', NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15'),
(8, 2, 'Reviews & Status', 'What do the submission statuses mean?', '\"Pending\" means your submission is awaiting faculty review. \"Approved\" means it has been accepted. \"Declined\" means there is an issue — check faculty remarks and resubmit. \"Revision Needed\" means minor corrections are required.', NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15'),
(9, 2, 'Account & Profile', 'How do I update my profile or contact information?', 'Go to Profile Settings from the sidebar. You can update your email address, contact number, and password. Make sure your contact information is always up to date for important announcements.', NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15'),
(10, 2, 'Account & Profile', 'I forgot my password. What should I do?', 'Contact your faculty or the system administrator to have your password reset. You can also reach out via the Incident Report feature for urgent account issues.', NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15'),
(11, 2, 'Incident Reports', 'When should I file an Incident Report?', 'File an Incident Report for any unusual or serious situation that occurred during your OJT — such as accidents, workplace misconduct, health issues, property damage, or security concerns. You can attach evidence files to your report.', NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15'),
(12, 2, 'Incident Reports', 'What happens after I submit an Incident Report?', 'Your faculty will receive and review the report. They will update the status to \"Reviewing\", \"Action Taken\", \"Resolved\", or \"Declined\" and may provide remarks. You can monitor the status from your Incident Report page.', NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15');

-- --------------------------------------------------------

--
-- Table structure for table `incident_reports`
--

CREATE TABLE `incident_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `incident_date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `action_taken` text DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `faculty_status` varchar(255) NOT NULL DEFAULT 'pending',
  `faculty_remarks` text DEFAULT NULL,
  `faculty_reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(41, '0001_01_01_000000_create_users_table', 1),
(42, '0001_01_01_000001_create_cache_table', 1),
(43, '0001_01_01_000002_create_jobs_table', 1),
(44, '2026_03_23_000003_create_sections_table', 1),
(54, '2026_03_23_000004_add_soft_deletes_to_sections_table', 2),
(55, '2026_03_23_000006_alter_users_table_for_ojtms', 3),
(56, '2026_03_23_120000_create_cms_settings_table', 3),
(57, '2026_03_24_000005_create_announcements_table', 3),
(58, '2026_03_24_000007_create_student_checklists_table', 3),
(59, '2026_03_24_000008_add_medical_record_fields_to_student_checklists', 3),
(60, '2026_03_24_000009_add_receipt_of_ojt_kit_fields_to_student_checklists', 3),
(61, '2026_03_24_000010_add_waiver_fields_to_student_checklists', 3),
(62, '2026_03_24_000011_add_endorsement_letter_fields_to_student_checklists', 3),
(64, '2026_03_24_000013_add_dtr_fields_to_student_checklists', 4),
(65, '2026_03_24_000014_add_weekly_report_fields_to_student_checklists', 4),
(66, '2026_03_24_000015_add_monthly_appraisal_fields_to_student_checklists', 4),
(67, '2026_03_24_000016_add_supervisor_eval_fields_to_student_checklists', 5),
(68, '2026_03_24_000017_add_coc_fields_to_student_checklists', 5),
(69, '2026_03_24_091625_create_incident_reports_table', 5),
(70, '2026_03_24_095354_drop_unique_add_index_student_checklists', 6),
(71, '2026_03_24_000018_create_announcement_reads_table', 7),
(72, '2026_03_25_001212_add_faculty_fields_to_incident_reports_table', 8),
(73, '2026_03_25_004821_create_faqs_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sections`
--

CREATE TABLE `sections` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `school_year` varchar(255) NOT NULL,
  `term` varchar(255) NOT NULL,
  `day` varchar(255) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `days_count` int(11) DEFAULT NULL,
  `room` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive','completed') NOT NULL DEFAULT 'active',
  `faculty_id` bigint(20) UNSIGNED DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sections`
--

INSERT INTO `sections` (`id`, `name`, `school_year`, `term`, `day`, `start_time`, `end_time`, `days_count`, `room`, `description`, `status`, `faculty_id`, `capacity`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'BSIT-4A', '2025-2026', 'Term 2', 'Monday / Wednesday', '08:00:00', '12:00:00', 2, 'Room 301', 'Bachelor of Science in Information Technology — 4th Year Section A', 'active', 2, 35, '2026-03-25 16:47:12', '2026-03-25 16:47:14', NULL),
(2, 'BSIT-4B', '2025-2026', 'Term 2', 'Tuesday / Thursday', '13:00:00', '17:00:00', 2, 'Room 302', 'Bachelor of Science in Information Technology — 4th Year Section B', 'active', 3, 35, '2026-03-25 16:47:12', '2026-03-25 16:47:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('PbF0nf3uwMpBZXEvQoM9PfNGWcabSR6IooMkJNL5', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTo2OntzOjY6Il90b2tlbiI7czo0MDoia05BYlRsVDlOR2ZnREVSbjNmQUVidzZrdFVqSEtWVW9KWkNlM1VYRCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Nzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9mYWN1bHR5L3NlY3Rpb24vMS9zdHVkZW50cy80L2NoZWNrbGlzdC9NZWRpY2FsJTJCUmVjb3JkIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo3OiJ1c2VyX2lkIjtpOjI7czo0OiJ1c2VyIjtPOjE1OiJBcHBcTW9kZWxzXFVzZXIiOjMzOntzOjEzOiIAKgBjb25uZWN0aW9uIjtzOjU6Im15c3FsIjtzOjg6IgAqAHRhYmxlIjtzOjU6InVzZXJzIjtzOjEzOiIAKgBwcmltYXJ5S2V5IjtzOjI6ImlkIjtzOjEwOiIAKgBrZXlUeXBlIjtzOjM6ImludCI7czoxMjoiaW5jcmVtZW50aW5nIjtiOjE7czo3OiIAKgB3aXRoIjthOjA6e31zOjEyOiIAKgB3aXRoQ291bnQiO2E6MDp7fXM6MTk6InByZXZlbnRzTGF6eUxvYWRpbmciO2I6MDtzOjEwOiIAKgBwZXJQYWdlIjtpOjE1O3M6NjoiZXhpc3RzIjtiOjE7czoxODoid2FzUmVjZW50bHlDcmVhdGVkIjtiOjA7czoyODoiACoAZXNjYXBlV2hlbkNhc3RpbmdUb1N0cmluZyI7YjowO3M6MTM6IgAqAGF0dHJpYnV0ZXMiO2E6MjE6e3M6MjoiaWQiO2k6MjtzOjExOiJlbXBsb3llZV9pZCI7czo3OiJGQUMtMDAxIjtzOjEwOiJzdHVkZW50X2lkIjtOO3M6NDoicm9sZSI7czo3OiJmYWN1bHR5IjtzOjEwOiJmaXJzdF9uYW1lIjtzOjU6Ik1hcmlhIjtzOjExOiJtaWRkbGVfbmFtZSI7czoxOiJMIjtzOjk6Imxhc3RfbmFtZSI7czo1OiJSZXllcyI7czo4OiJ1c2VybmFtZSI7czo2OiJtcmV5ZXMiO3M6NDoibmFtZSI7TjtzOjU6ImVtYWlsIjtzOjE5OiJtcmV5ZXNAb2p0bXMuZWR1LnBoIjtzOjc6ImNvbnRhY3QiO3M6MTE6IjA5MTAwMDAwMDAyIjtzOjEwOiJkZXBhcnRtZW50IjtzOjEzOiJJVCBEZXBhcnRtZW50IjtzOjEwOiJzZWN0aW9uX2lkIjtpOjE7czo2OiJzdGF0dXMiO3M6NjoiYWN0aXZlIjtzOjE2OiJsYXN0X2FjdGl2aXR5X2F0IjtOO3M6MTA6ImRlbGV0ZWRfYXQiO047czoxNzoiZW1haWxfdmVyaWZpZWRfYXQiO047czo4OiJwYXNzd29yZCI7czo2MDoiJDJ5JDEyJHNDVWxmbUV2U0p2b0ozQ0p2SS9kOS5QaGJaZVBiZWVYeXJ2U1IuMjNwa1N4NjZRY3B0SW8uIjtzOjE0OiJyZW1lbWJlcl90b2tlbiI7TjtzOjEwOiJjcmVhdGVkX2F0IjtzOjE5OiIyMDI2LTAzLTI2IDAwOjM3OjE3IjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjE5OiIyMDI2LTAzLTI2IDAwOjM3OjE3Ijt9czoxMToiACoAb3JpZ2luYWwiO2E6MjE6e3M6MjoiaWQiO2k6MjtzOjExOiJlbXBsb3llZV9pZCI7czo3OiJGQUMtMDAxIjtzOjEwOiJzdHVkZW50X2lkIjtOO3M6NDoicm9sZSI7czo3OiJmYWN1bHR5IjtzOjEwOiJmaXJzdF9uYW1lIjtzOjU6Ik1hcmlhIjtzOjExOiJtaWRkbGVfbmFtZSI7czoxOiJMIjtzOjk6Imxhc3RfbmFtZSI7czo1OiJSZXllcyI7czo4OiJ1c2VybmFtZSI7czo2OiJtcmV5ZXMiO3M6NDoibmFtZSI7TjtzOjU6ImVtYWlsIjtzOjE5OiJtcmV5ZXNAb2p0bXMuZWR1LnBoIjtzOjc6ImNvbnRhY3QiO3M6MTE6IjA5MTAwMDAwMDAyIjtzOjEwOiJkZXBhcnRtZW50IjtzOjEzOiJJVCBEZXBhcnRtZW50IjtzOjEwOiJzZWN0aW9uX2lkIjtpOjE7czo2OiJzdGF0dXMiO3M6NjoiYWN0aXZlIjtzOjE2OiJsYXN0X2FjdGl2aXR5X2F0IjtOO3M6MTA6ImRlbGV0ZWRfYXQiO047czoxNzoiZW1haWxfdmVyaWZpZWRfYXQiO047czo4OiJwYXNzd29yZCI7czo2MDoiJDJ5JDEyJHNDVWxmbUV2U0p2b0ozQ0p2SS9kOS5QaGJaZVBiZWVYeXJ2U1IuMjNwa1N4NjZRY3B0SW8uIjtzOjE0OiJyZW1lbWJlcl90b2tlbiI7TjtzOjEwOiJjcmVhdGVkX2F0IjtzOjE5OiIyMDI2LTAzLTI2IDAwOjM3OjE3IjtzOjEwOiJ1cGRhdGVkX2F0IjtzOjE5OiIyMDI2LTAzLTI2IDAwOjM3OjE3Ijt9czoxMDoiACoAY2hhbmdlcyI7YTowOnt9czo4OiIAKgBjYXN0cyI7YTo0OntzOjE3OiJlbWFpbF92ZXJpZmllZF9hdCI7czo4OiJkYXRldGltZSI7czo4OiJwYXNzd29yZCI7czo2OiJoYXNoZWQiO3M6MTY6Imxhc3RfYWN0aXZpdHlfYXQiO3M6ODoiZGF0ZXRpbWUiO3M6MTA6ImRlbGV0ZWRfYXQiO3M6ODoiZGF0ZXRpbWUiO31zOjE3OiIAKgBjbGFzc0Nhc3RDYWNoZSI7YTowOnt9czoyMToiACoAYXR0cmlidXRlQ2FzdENhY2hlIjthOjA6e31zOjEzOiIAKgBkYXRlRm9ybWF0IjtOO3M6MTA6IgAqAGFwcGVuZHMiO2E6MDp7fXM6MTk6IgAqAGRpc3BhdGNoZXNFdmVudHMiO2E6MDp7fXM6MTQ6IgAqAG9ic2VydmFibGVzIjthOjA6e31zOjEyOiIAKgByZWxhdGlvbnMiO2E6MDp7fXM6MTA6IgAqAHRvdWNoZXMiO2E6MDp7fXM6MTA6InRpbWVzdGFtcHMiO2I6MTtzOjEzOiJ1c2VzVW5pcXVlSWRzIjtiOjA7czo5OiIAKgBoaWRkZW4iO2E6Mjp7aTowO3M6ODoicGFzc3dvcmQiO2k6MTtzOjE0OiJyZW1lbWJlcl90b2tlbiI7fXM6MTA6IgAqAHZpc2libGUiO2E6MDp7fXM6MTE6IgAqAGZpbGxhYmxlIjthOjE0OntpOjA7czoxMToiZW1wbG95ZWVfaWQiO2k6MTtzOjEwOiJzdHVkZW50X2lkIjtpOjI7czo0OiJyb2xlIjtpOjM7czoxMDoiZmlyc3RfbmFtZSI7aTo0O3M6MTE6Im1pZGRsZV9uYW1lIjtpOjU7czo5OiJsYXN0X25hbWUiO2k6NjtzOjg6InVzZXJuYW1lIjtpOjc7czo4OiJwYXNzd29yZCI7aTo4O3M6NToiZW1haWwiO2k6OTtzOjc6ImNvbnRhY3QiO2k6MTA7czoxMDoiZGVwYXJ0bWVudCI7aToxMTtzOjEwOiJzZWN0aW9uX2lkIjtpOjEyO3M6Njoic3RhdHVzIjtpOjEzO3M6MTY6Imxhc3RfYWN0aXZpdHlfYXQiO31zOjEwOiIAKgBndWFyZGVkIjthOjE6e2k6MDtzOjE6IioiO31zOjE5OiIAKgBhdXRoUGFzc3dvcmROYW1lIjtzOjg6InBhc3N3b3JkIjtzOjIwOiIAKgByZW1lbWJlclRva2VuTmFtZSI7czoxNDoicmVtZW1iZXJfdG9rZW4iO3M6MTY6IgAqAGZvcmNlRGVsZXRpbmciO2I6MDt9czo5OiJ1c2VyX3JvbGUiO3M6NzoiZmFjdWx0eSI7fQ==', 1774457191),
('WNd4rFhsf1a8gPCvOpJAswu4mEEKkAMFBsbbbvEJ', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/145.0.0.0 Safari/537.36', 'YTozOntzOjY6Il9mbGFzaCI7YToyOntzOjM6Im5ldyI7YTowOnt9czozOiJvbGQiO2E6MDp7fX1zOjk6Il9wcmV2aW91cyI7YToxOntzOjM6InVybCI7czoyMToiaHR0cDovLzEyNy4wLjAuMTo4MDAwIjt9czo2OiJfdG9rZW4iO3M6NDA6IkM0SU5nUmJBdkhyZlBCR2tuU3ZIRlZGR1dCaEVzTXlManlRVDU0bFgiO30=', 1774457297);

-- --------------------------------------------------------

--
-- Table structure for table `student_checklists`
--

CREATE TABLE `student_checklists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `section_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `item` varchar(255) NOT NULL,
  `student_file` varchar(255) DEFAULT NULL,
  `student_files` text DEFAULT NULL,
  `student_clinic_name` varchar(255) DEFAULT NULL,
  `student_clinic_address` varchar(255) DEFAULT NULL,
  `student_encoded_at` datetime DEFAULT NULL,
  `student_submitted_at` datetime DEFAULT NULL,
  `student_submission_status` enum('pending','submitted','needs_revision') NOT NULL DEFAULT 'pending',
  `student_enrolled_at` datetime DEFAULT NULL,
  `student_paid_date` datetime DEFAULT NULL,
  `student_receipt_number` varchar(255) DEFAULT NULL,
  `student_guardian_name` varchar(255) DEFAULT NULL,
  `student_guardian_contact` varchar(255) DEFAULT NULL,
  `student_guardian_email` varchar(255) DEFAULT NULL,
  `student_guardian_social` varchar(255) DEFAULT NULL,
  `student_endorsement_date` datetime DEFAULT NULL,
  `student_start_date` datetime DEFAULT NULL,
  `student_supervisor_signed_by` varchar(255) DEFAULT NULL,
  `student_dtr_week` varchar(255) DEFAULT NULL,
  `student_dtr_hours` decimal(8,2) DEFAULT NULL,
  `student_dtr_validated_by` varchar(255) DEFAULT NULL,
  `student_dtr_total_hours` int(11) NOT NULL DEFAULT 0,
  `faculty_dtr_target_hours` decimal(8,2) NOT NULL DEFAULT 720.00,
  `faculty_dtr_reviewed_at` timestamp NULL DEFAULT NULL,
  `student_weekly_week` varchar(255) DEFAULT NULL,
  `student_weekly_task_description` text DEFAULT NULL,
  `student_weekly_supervisor_feedback` text DEFAULT NULL,
  `student_weekly_files` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`student_weekly_files`)),
  `student_weekly_submitted_at` timestamp NULL DEFAULT NULL,
  `faculty_weekly_remarks` text DEFAULT NULL,
  `faculty_weekly_reviewed_at` timestamp NULL DEFAULT NULL,
  `student_appraisal_month` varchar(255) DEFAULT NULL,
  `student_appraisal_file` varchar(255) DEFAULT NULL,
  `student_appraisal_feedback` text DEFAULT NULL,
  `student_appraisal_grade_rating` varchar(255) DEFAULT NULL,
  `student_appraisal_evaluated_by` varchar(255) DEFAULT NULL,
  `student_appraisal_submitted_at` timestamp NULL DEFAULT NULL,
  `faculty_appraisal_remarks` text DEFAULT NULL,
  `faculty_appraisal_reviewed_at` timestamp NULL DEFAULT NULL,
  `student_supervisor_eval_file` varchar(255) DEFAULT NULL,
  `student_supervisor_eval_grade` varchar(255) DEFAULT NULL,
  `student_supervisor_eval_submitted_at` datetime DEFAULT NULL,
  `faculty_supervisor_eval_remarks` text DEFAULT NULL,
  `faculty_supervisor_eval_reviewed_at` datetime DEFAULT NULL,
  `student_coc_file` varchar(255) DEFAULT NULL,
  `student_coc_signed_by` varchar(255) DEFAULT NULL,
  `student_coc_company` varchar(255) DEFAULT NULL,
  `student_coc_receive_date` date DEFAULT NULL,
  `student_coc_date_issued` date DEFAULT NULL,
  `student_coc_submitted_at` datetime DEFAULT NULL,
  `faculty_coc_remarks` text DEFAULT NULL,
  `faculty_coc_reviewed_at` datetime DEFAULT NULL,
  `student_remarks` text DEFAULT NULL,
  `faculty_status` enum('pending','approved','declined') NOT NULL DEFAULT 'pending',
  `faculty_remarks` text DEFAULT NULL,
  `faculty_reviewed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_checklists`
--

INSERT INTO `student_checklists` (`id`, `section_id`, `student_id`, `item`, `student_file`, `student_files`, `student_clinic_name`, `student_clinic_address`, `student_encoded_at`, `student_submitted_at`, `student_submission_status`, `student_enrolled_at`, `student_paid_date`, `student_receipt_number`, `student_guardian_name`, `student_guardian_contact`, `student_guardian_email`, `student_guardian_social`, `student_endorsement_date`, `student_start_date`, `student_supervisor_signed_by`, `student_dtr_week`, `student_dtr_hours`, `student_dtr_validated_by`, `student_dtr_total_hours`, `faculty_dtr_target_hours`, `faculty_dtr_reviewed_at`, `student_weekly_week`, `student_weekly_task_description`, `student_weekly_supervisor_feedback`, `student_weekly_files`, `student_weekly_submitted_at`, `faculty_weekly_remarks`, `faculty_weekly_reviewed_at`, `student_appraisal_month`, `student_appraisal_file`, `student_appraisal_feedback`, `student_appraisal_grade_rating`, `student_appraisal_evaluated_by`, `student_appraisal_submitted_at`, `faculty_appraisal_remarks`, `faculty_appraisal_reviewed_at`, `student_supervisor_eval_file`, `student_supervisor_eval_grade`, `student_supervisor_eval_submitted_at`, `faculty_supervisor_eval_remarks`, `faculty_supervisor_eval_reviewed_at`, `student_coc_file`, `student_coc_signed_by`, `student_coc_company`, `student_coc_receive_date`, `student_coc_date_issued`, `student_coc_submitted_at`, `faculty_coc_remarks`, `faculty_coc_reviewed_at`, `student_remarks`, `faculty_status`, `faculty_remarks`, `faculty_reviewed_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 5, 'Medical Record', NULL, NULL, 'MSTIP Clinic', 'Makati City', '2026-03-06 00:47:15', '2026-03-06 00:47:15', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 720.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', 'Complete. Good to go.', '2026-03-08 00:47:15', '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(2, 1, 5, 'Receipt of OJT Kit', NULL, NULL, NULL, NULL, '2026-03-07 00:47:15', '2026-03-07 00:47:15', 'pending', NULL, '2026-03-07 00:47:15', 'OR-2526-0042', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 720.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', 'Receipt number confirmed.', '2026-03-09 00:47:15', '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(3, 1, 5, 'Waiver', NULL, NULL, NULL, NULL, '2026-03-16 00:47:15', '2026-03-16 00:47:15', 'pending', NULL, NULL, NULL, 'Ricardo Dela Torre', '09300000001', 'rdelatorre@email.com', 'N/A', NULL, NULL, NULL, NULL, NULL, NULL, 0, 720.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(4, 1, 5, 'Endorsement letter', NULL, NULL, NULL, NULL, '2026-03-18 00:47:15', '2026-03-18 00:47:15', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-03-18 00:47:15', '2026-03-19 00:47:15', NULL, NULL, NULL, NULL, 0, 720.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Endorsed to ABC Tech Company, Makati City.', 'pending', NULL, NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(5, 1, 5, 'DTR', NULL, NULL, NULL, NULL, NULL, '2026-03-12 00:47:15', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Week 1 — January 2026', 40.00, 'Mr. John Supervisor', 0, 720.00, '2026-03-13 16:47:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', 'Verified. 40 hours counted.', NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(6, 1, 5, 'DTR', NULL, NULL, NULL, NULL, NULL, '2026-03-21 00:47:15', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Week 2 — January 2026', 40.00, 'Mr. John Supervisor', 0, 720.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(7, 1, 5, 'Weekly report', NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 720.00, NULL, 'Week 1 — January 2026', 'Assisted in front-end development using React.js. Attended orientation and team standup meetings. Helped debug UI components assigned by the team lead.', 'Good progress. Showed initiative.', NULL, '2026-03-19 16:47:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(8, 2, 6, 'Medical Record', NULL, NULL, 'City Health Center', 'Makati City', '2026-01-30 00:47:15', '2026-01-30 00:47:15', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 720.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, '2026-02-01 00:47:15', '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(9, 2, 6, 'Receipt of OJT Kit', NULL, NULL, NULL, NULL, '2026-01-31 00:47:15', '2026-01-31 00:47:15', 'pending', NULL, '2026-01-31 00:47:15', 'OR-2526-0017', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 720.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, '2026-02-02 00:47:15', '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(10, 2, 6, 'Waiver', NULL, NULL, NULL, NULL, '2026-02-04 00:47:15', '2026-02-04 00:47:15', 'pending', NULL, NULL, NULL, 'Luisa Villanueva', '09300000002', 'lvillanueva@email.com', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 720.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, '2026-02-06 00:47:15', '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(11, 2, 6, 'Endorsement letter', NULL, NULL, NULL, NULL, '2026-02-06 00:47:15', '2026-02-06 00:47:15', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2026-02-06 00:47:15', '2026-02-09 00:47:15', NULL, NULL, NULL, NULL, 0, 720.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, '2026-02-08 00:47:15', '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(12, 2, 6, 'MOA', NULL, NULL, NULL, NULL, '2026-02-10 00:47:15', '2026-02-10 00:47:15', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 720.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'MOA signed by XYZ Solutions Inc.', 'approved', NULL, '2026-02-12 00:47:15', '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(13, 2, 6, 'DTR', NULL, NULL, NULL, NULL, NULL, '2026-02-12 00:47:15', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Week 1 — November 2025', 40.00, 'Ms. Ana Torres', 0, 720.00, '2026-02-13 16:47:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', '40 hours approved.', NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(14, 2, 6, 'DTR', NULL, NULL, NULL, NULL, NULL, '2026-02-19 00:47:15', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Week 2 — November 2025', 40.00, 'Ms. Ana Torres', 0, 720.00, '2026-02-20 16:47:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', '40 hours approved.', NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(15, 2, 6, 'DTR', NULL, NULL, NULL, NULL, NULL, '2026-02-26 00:47:15', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Week 3 — December 2025', 40.00, 'Ms. Ana Torres', 0, 720.00, '2026-02-27 16:47:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', '40 hours approved.', NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(16, 2, 6, 'DTR', NULL, NULL, NULL, NULL, NULL, '2026-03-21 00:47:15', 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Week 4 — January 2026', 40.00, 'Ms. Ana Torres', 0, 720.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(17, 2, 6, 'Weekly report', NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 720.00, NULL, 'Week 1 — November 2025', 'Worked on assigned tasks including system testing, documentation, and backend API development.', 'Performed well this week.', NULL, '2026-02-13 16:47:15', 'Report reviewed and accepted.', '2026-02-15 16:47:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(18, 2, 6, 'Weekly report', NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 720.00, NULL, 'Week 2 — November 2025', 'Worked on assigned tasks including system testing, documentation, and backend API development.', 'Performed well this week.', NULL, '2026-02-20 16:47:15', 'Report reviewed and accepted.', '2026-02-22 16:47:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'approved', NULL, NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(19, 2, 6, 'Weekly report', NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 720.00, NULL, 'Week 3 — December 2025', 'Worked on assigned tasks including system testing, documentation, and backend API development.', 'Performed well this week.', NULL, '2026-03-21 16:47:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(20, 2, 6, 'Monthly appraisal', NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 720.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'November 2025', NULL, NULL, '90', 'Ms. Ana Torres', '2026-03-19 16:47:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL),
(21, 2, 6, 'Supervisor evaluation', NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 720.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '88', '2026-03-23 00:47:15', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'pending', NULL, NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` varchar(255) DEFAULT NULL,
  `student_id` varchar(255) DEFAULT NULL,
  `role` enum('admin','faculty','student') NOT NULL DEFAULT 'student',
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `department` varchar(255) DEFAULT NULL,
  `section_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `last_activity_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `employee_id`, `student_id`, `role`, `first_name`, `middle_name`, `last_name`, `username`, `name`, `email`, `contact`, `department`, `section_id`, `status`, `last_activity_at`, `deleted_at`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'ADM-001', NULL, 'admin', 'System', NULL, 'Administrator', 'admin', NULL, 'admin@ojtms.edu.ph', '09100000001', 'Administration', NULL, 'active', NULL, NULL, NULL, '$2y$12$rREke7KKss0CfGvIsw21x.KhWXhUbyGvXA/.hSNDvsqp9m0Dlg0mG', NULL, '2026-03-25 16:47:13', '2026-03-25 16:47:13'),
(2, 'FAC-001', NULL, 'faculty', 'Maria', 'L', 'Reyes', 'mreyes', NULL, 'mreyes@ojtms.edu.ph', '09100000002', 'IT Department', 1, 'active', NULL, NULL, NULL, '$2y$12$M44Xt4yi7dWw.4x8n45aAOGu.O.pUyxN1ochR/Rxgl9sB3tGAsLhi', NULL, '2026-03-25 16:47:13', '2026-03-25 16:47:13'),
(3, 'FAC-002', NULL, 'faculty', 'Jose', 'P', 'Santos', 'jsantos', NULL, 'jsantos@ojtms.edu.ph', '09100000003', 'IT Department', 2, 'active', NULL, NULL, NULL, '$2y$12$eToRWFDE7gulaDgD4Mb9QelUBMYA9n6VqaAA4SLFQcwcilQtzwjD.', NULL, '2026-03-25 16:47:14', '2026-03-25 16:47:14'),
(4, NULL, 'STU-2526-001', 'student', 'Anna', 'R', 'Cruz', 'acruz', NULL, 'acruz@student.ojtms.edu.ph', '09200000001', 'IT Department', 1, 'active', NULL, NULL, NULL, '$2y$12$zXf1H4Wwxxln/ZQcmE0gh.yevoQ7OFszqQvcNGZ52NMchEpATKz.G', NULL, '2026-03-25 16:47:14', '2026-03-25 16:47:14'),
(5, NULL, 'STU-2526-002', 'student', 'Miguel', 'A', 'Dela Torre', 'mdelatorre', NULL, 'mdelatorre@student.ojtms.edu.ph', '09200000002', 'IT Department', 1, 'active', NULL, NULL, NULL, '$2y$12$y.Ps4t6wR2xBu15bVGuTBeSz8sFsEulwEyWFQgGszSdnseZzJ79Ta', NULL, '2026-03-25 16:47:14', '2026-03-25 16:47:14'),
(6, NULL, 'STU-2526-003', 'student', 'Sofia', 'M', 'Villanueva', 'svillanueva', NULL, 'svillanueva@student.ojtms.edu.ph', '09200000003', 'IT Department', 2, 'active', NULL, NULL, NULL, '$2y$12$0qGE8Ss86Zh0VP7.0zkKeemZtXTewrf18iTHmVDwk/EbQ.7GUqxLG', NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15'),
(7, NULL, 'STU-2526-004', 'student', 'Carlos', 'J', 'Mendoza', 'cmendoza', NULL, 'cmendoza@student.ojtms.edu.ph', '09200000004', 'IT Department', 2, 'active', NULL, NULL, NULL, '$2y$12$J/8olExmD7U13GuQYl7FoOknaU4lgzsvJ39f5UMNfdn/HE5eRfu/a', NULL, '2026-03-25 16:47:15', '2026-03-25 16:47:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `announcements_created_by_foreign` (`created_by`);

--
-- Indexes for table `announcement_reads`
--
ALTER TABLE `announcement_reads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `announcement_reads_user_id_announcement_id_unique` (`user_id`,`announcement_id`),
  ADD KEY `announcement_reads_announcement_id_foreign` (`announcement_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cms_settings`
--
ALTER TABLE `cms_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cms_settings_key_unique` (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `faqs_faculty_id_foreign` (`faculty_id`);

--
-- Indexes for table `incident_reports`
--
ALTER TABLE `incident_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incident_reports_student_id_foreign` (`student_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sections`
--
ALTER TABLE `sections`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `student_checklists`
--
ALTER TABLE `student_checklists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_checklists_student_id_foreign` (`student_id`),
  ADD KEY `sc_section_id_idx` (`section_id`),
  ADD KEY `sc_section_student_item_idx` (`section_id`,`student_id`,`item`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_employee_id_unique` (`employee_id`),
  ADD UNIQUE KEY `users_student_id_unique` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `announcement_reads`
--
ALTER TABLE `announcement_reads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cms_settings`
--
ALTER TABLE `cms_settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `incident_reports`
--
ALTER TABLE `incident_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `sections`
--
ALTER TABLE `sections`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `student_checklists`
--
ALTER TABLE `student_checklists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `announcement_reads`
--
ALTER TABLE `announcement_reads`
  ADD CONSTRAINT `announcement_reads_announcement_id_foreign` FOREIGN KEY (`announcement_id`) REFERENCES `announcements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `announcement_reads_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `faqs`
--
ALTER TABLE `faqs`
  ADD CONSTRAINT `faqs_faculty_id_foreign` FOREIGN KEY (`faculty_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `incident_reports`
--
ALTER TABLE `incident_reports`
  ADD CONSTRAINT `incident_reports_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_checklists`
--
ALTER TABLE `student_checklists`
  ADD CONSTRAINT `student_checklists_section_id_foreign` FOREIGN KEY (`section_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_checklists_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
