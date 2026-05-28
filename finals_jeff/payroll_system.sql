-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 26, 2026 at 09:18 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `payroll_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time_in` time DEFAULT NULL,
  `time_out` time DEFAULT NULL,
  `hours_worked` decimal(5,2) DEFAULT 0.00,
  `status` varchar(20) DEFAULT 'present' COMMENT 'present, absent, late',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `employee_id`, `date`, `time_in`, `time_out`, `hours_worked`, `status`, `created_at`) VALUES
(1, 1, '2026-05-20', '07:42:37', '07:42:50', 0.00, 'present', '2026-05-20 05:42:37'),
(2, 5, '2026-05-20', '08:28:48', '08:28:55', 0.00, 'present', '2026-05-20 06:28:48'),
(3, 2, '2026-05-20', '08:29:31', '08:31:38', 0.04, 'present', '2026-05-20 06:29:31'),
(6, 6, '2026-05-20', '16:04:02', '16:40:22', 0.00, 'present', '2026-05-20 08:04:02'),
(7, 6, '2026-05-22', '19:13:59', '19:16:08', 0.00, 'present', '2026-05-22 11:13:59'),
(8, 1, '2026-05-22', '19:15:52', '19:34:38', 0.00, 'present', '2026-05-22 11:15:52'),
(9, 5, '2026-05-22', '19:22:44', NULL, 0.00, 'present', '2026-05-22 11:22:44'),
(10, 2, '2026-05-22', '19:48:36', '19:49:03', 0.00, 'present', '2026-05-22 11:48:36'),
(11, 1, '2026-05-23', '18:07:23', '18:55:39', 0.00, 'present', '2026-05-23 10:07:23'),
(12, 5, '2026-05-23', '18:09:19', NULL, 0.00, 'present', '2026-05-23 10:09:19'),
(13, 6, '2026-05-23', '18:10:29', '18:58:03', 0.00, 'present', '2026-05-23 10:10:29'),
(14, 1, '2026-05-15', '08:00:00', '17:00:00', 8.00, 'present', '2026-05-23 11:26:15'),
(17, 1, '2026-05-16', '08:00:00', '17:00:00', 8.00, 'present', '2026-05-23 11:28:03'),
(18, 6, '2026-05-17', '08:00:00', '17:00:00', 8.00, 'present', '2026-05-23 11:28:18'),
(19, 6, '2026-05-16', '08:00:00', '17:00:00', 8.00, 'present', '2026-05-23 11:28:27'),
(20, 2, '2026-05-27', '08:00:00', '17:00:00', 8.00, 'present', '2026-05-23 11:45:13'),
(21, 1, '2026-05-26', '14:24:12', NULL, 0.00, 'present', '2026-05-26 06:24:12');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `department_name`, `created_at`) VALUES
(1, 'Human Resources', '2026-05-13 01:37:44'),
(2, 'IT Department', '2026-05-13 01:37:44'),
(3, 'Sales', '2026-05-13 01:37:44'),
(4, 'Operations', '2026-05-13 01:37:44'),
(5, 'Finance', '2026-05-13 01:37:44');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(20) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `shift_id` int(11) DEFAULT NULL,
  `hire_date` date NOT NULL,
  `status` varchar(20) DEFAULT 'active',
  `is_hr` tinyint(1) DEFAULT 0 COMMENT '1 = HR privileges',
  `is_admin` tinyint(1) DEFAULT 0 COMMENT '1 = Admin privileges',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_id`, `firstname`, `lastname`, `email`, `password`, `phone`, `role_id`, `department_id`, `shift_id`, `hire_date`, `status`, `is_hr`, `is_admin`, `created_at`) VALUES
(1, 'EMP001', 'Admin', 'User', 'admin@company.com', 'admin123', '', 1, 1, 2, '2024-01-01', 'active', 1, 1, '2026-05-13 01:40:24'),
(2, 'EMP002', 'Juan', 'Dela Cruz', 'juan@company.com', 'pass123', '123456789', 3, 2, 2, '2024-02-15', 'active', 0, 0, '2026-05-13 01:40:39'),
(5, 'EMP666', 'chan', 'chan', 'chan@company.com', 'qwerty123', '', 1, 2, 2, '2026-05-18', 'active', 0, 0, '2026-05-18 10:25:37'),
(6, 'EMP692', 'Leah', 'Fernandez', 'leah@company.com', '123', '12345678899', 5, 5, 2, '2026-05-20', 'active', 0, 0, '2026-05-20 07:56:48');

-- --------------------------------------------------------

--
-- Table structure for table `employee_incentives`
--

CREATE TABLE `employee_incentives` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `incentive_type_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_incentives`
--

INSERT INTO `employee_incentives` (`id`, `employee_id`, `incentive_type_id`, `amount`, `remarks`, `created_at`) VALUES
(1, 2, 1, 1200.00, 'good job', '2026-05-23 12:04:28'),
(2, 2, 1, 12345.00, 'qwert', '2026-05-26 06:15:37'),
(3, 2, 1, 1000.00, 'hardworker', '2026-05-26 06:20:01'),
(4, 5, 1, 10.00, 'qwe', '2026-05-26 06:21:04'),
(5, 5, 8, 1500.00, 'trip ko lang', '2026-05-26 06:37:23');

-- --------------------------------------------------------

--
-- Table structure for table `incentive_types`
--

CREATE TABLE `incentive_types` (
  `id` int(11) NOT NULL,
  `incentive_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `employee_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date_given` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `incentive_types`
--

INSERT INTO `incentive_types` (`id`, `incentive_name`, `description`, `created_at`, `employee_id`, `amount`, `date_given`) VALUES
(1, 'Performance Bonus', 'Monthly performance-based bonus', '2026-05-13 01:39:19', 1, 100.00, '2026-05-18'),
(3, 'Attendance Bonus', 'Perfect attendance reward', '2026-05-13 01:39:19', 1, 100.00, '2026-05-18'),
(4, 'Holiday Bonus', '13th month pay or holiday bonus', '2026-05-13 01:39:19', 1, 1200.00, '2026-05-18'),
(5, 'Overtime Pay', 'Additional pay for overtime work', '2026-05-13 01:39:19', 1, 70.00, '2026-05-18'),
(8, 'gift card', 'regalo ni manager', '2026-05-26 06:37:01', 0, 1500.00, '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `employee_name` varchar(100) DEFAULT NULL,
  `leave_type_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` int(11) NOT NULL,
  `reason` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending' COMMENT 'pending, approved, rejected',
  `approved_by` int(11) DEFAULT NULL,
  `approval_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `employee_id`, `employee_name`, `leave_type_id`, `start_date`, `end_date`, `total_days`, `reason`, `status`, `approved_by`, `approval_date`, `created_at`) VALUES
(1, 1, NULL, 2, '2026-05-22', '2026-06-22', 32, 'sakit leeg ko', 'pending', NULL, NULL, '2026-05-22 11:19:30'),
(2, 1, NULL, 1, '2026-05-23', '2026-06-23', 32, 'asdf', 'pending', NULL, NULL, '2026-05-23 10:47:36'),
(3, 1, NULL, 5, '2026-05-30', '2026-05-30', 1, 'nabuntis aso namen', 'approved', NULL, NULL, '2026-05-23 10:47:52'),
(4, 1, NULL, 4, '2026-05-23', '2026-06-23', 32, 'zxcv', 'approved', NULL, NULL, '2026-05-23 10:48:53'),
(5, 6, NULL, 5, '2026-05-23', '2026-06-23', 32, 'buntis pusa', 'pending', NULL, NULL, '2026-05-23 11:22:32'),
(6, 2, NULL, 2, '2026-05-23', '2026-05-27', 5, 'tigdas', 'approved', NULL, NULL, '2026-05-23 11:42:22');

-- --------------------------------------------------------

--
-- Table structure for table `leave_types`
--

CREATE TABLE `leave_types` (
  `id` int(11) NOT NULL,
  `leave_name` varchar(50) NOT NULL,
  `is_paid` tinyint(1) DEFAULT 0 COMMENT '1 = Paid leave, 0 = Unpaid',
  `max_days` int(11) DEFAULT 0 COMMENT 'Max days per year, 0 = unlimited',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_types`
--

INSERT INTO `leave_types` (`id`, `leave_name`, `is_paid`, `max_days`, `created_at`) VALUES
(1, 'Vacation Leave', 1, 15, '2026-05-13 01:38:49'),
(2, 'Sick Leave', 1, 10, '2026-05-13 01:38:49'),
(3, 'Emergency Leave', 1, 5, '2026-05-13 01:38:49'),
(4, 'Unpaid Leave', 0, 0, '2026-05-13 01:38:49'),
(5, 'Maternity Leave', 1, 60, '2026-05-13 01:38:49');

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `month_year` date NOT NULL COMMENT 'Format: YYYY-MM-01',
  `basic_salary` decimal(10,2) DEFAULT 0.00,
  `total_incentives` decimal(10,2) DEFAULT 0.00,
  `total_deductions` decimal(10,2) DEFAULT 0.00,
  `days_worked` int(11) DEFAULT 0,
  `days_absent` int(11) DEFAULT 0,
  `paid_leaves` int(11) DEFAULT 0,
  `unpaid_leaves` int(11) DEFAULT 0,
  `total_hours` decimal(8,2) DEFAULT 0.00,
  `overtime_hours` decimal(8,2) DEFAULT 0.00,
  `gross_pay` decimal(10,2) DEFAULT 0.00,
  `net_pay` decimal(10,2) DEFAULT 0.00,
  `status` varchar(20) DEFAULT 'draft' COMMENT 'draft, finalized, paid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payroll`
--

INSERT INTO `payroll` (`id`, `employee_id`, `month_year`, `basic_salary`, `total_incentives`, `total_deductions`, `days_worked`, `days_absent`, `paid_leaves`, `unpaid_leaves`, `total_hours`, `overtime_hours`, `gross_pay`, `net_pay`, `status`, `created_at`) VALUES
(2, 5, '2026-05-01', 6818.18, 1510.00, 1249.23, 3, 0, 0, 0, 0.00, 0.00, 8328.18, 7078.95, 'finalized', '2026-05-20 06:50:14'),
(4, 2, '2026-05-01', 9090.91, 14545.00, 3545.39, 3, 0, 5, 0, 8.04, 0.00, 23635.91, 20090.52, 'draft', '2026-05-20 07:11:32'),
(5, 6, '2026-05-01', 2727.27, 0.00, 409.09, 5, 0, 0, 0, 16.00, 0.00, 2727.27, 2318.18, 'draft', '2026-05-20 08:27:21'),
(9, 1, '2026-05-01', 4545.45, 1470.00, 902.32, 2, 0, 0, 0, 0.00, 0.00, 6015.45, 5113.14, 'draft', '2026-05-22 11:34:53');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `level` int(11) NOT NULL COMMENT 'Hierarchy: 1=Entry, 5=Manager, 10=Executive',
  `monthly_salary` decimal(10,2) NOT NULL,
  `hourly_rate` decimal(8,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `level`, `monthly_salary`, `hourly_rate`, `created_at`) VALUES
(1, 'Manager', 5, 50000.00, 300.00, '2026-05-13 01:38:07'),
(2, 'Supervisor', 4, 35000.00, 210.00, '2026-05-13 01:38:07'),
(3, 'Senior Staff', 3, 25000.00, 150.00, '2026-05-13 01:38:07'),
(4, 'Junior Staff', 2, 18000.00, 110.00, '2026-05-13 01:38:07'),
(5, 'Intern', 1, 12000.00, 75.00, '2026-05-13 01:38:07');

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` int(11) NOT NULL,
  `shift_name` varchar(50) NOT NULL,
  `time_in` time NOT NULL,
  `time_out` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`id`, `shift_name`, `time_in`, `time_out`, `created_at`) VALUES
(1, 'Day Shift', '08:00:00', '17:00:00', '2026-05-13 01:38:28'),
(2, 'Night Shift', '20:00:00', '04:00:00', '2026-05-13 01:38:28');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_attendance` (`employee_id`,`date`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `shift_id` (`shift_id`);

--
-- Indexes for table `employee_incentives`
--
ALTER TABLE `employee_incentives`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `incentive_types`
--
ALTER TABLE `incentive_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `leave_type_id` (`leave_type_id`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_payroll` (`employee_id`,`month_year`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `employee_incentives`
--
ALTER TABLE `employee_incentives`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `incentive_types`
--
ALTER TABLE `incentive_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payroll`
--
ALTER TABLE `payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `employees_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `employees_ibfk_3` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`);

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
  ADD CONSTRAINT `leave_requests_ibfk_2` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`),
  ADD CONSTRAINT `leave_requests_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `employees` (`id`);

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `payroll_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
