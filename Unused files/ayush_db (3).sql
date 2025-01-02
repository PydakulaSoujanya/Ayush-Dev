-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 09, 2024 at 07:22 AM
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
-- Database: `ayush_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `allotment`
--

CREATE TABLE `allotment` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `service_type` varchar(255) NOT NULL,
  `shift` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `no_of_hours` int(11) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `day_1` varchar(255) DEFAULT NULL,
  `day_2` varchar(255) DEFAULT NULL,
  `day_3` varchar(255) DEFAULT NULL,
  `day_4` varchar(255) DEFAULT NULL,
  `day_5` varchar(255) DEFAULT NULL,
  `day_6` varchar(255) DEFAULT NULL,
  `day_7` varchar(255) DEFAULT NULL,
  `day_8` varchar(255) DEFAULT NULL,
  `day_9` varchar(255) DEFAULT NULL,
  `day_10` varchar(255) DEFAULT NULL,
  `day_11` varchar(255) DEFAULT NULL,
  `day_12` varchar(255) DEFAULT NULL,
  `day_13` varchar(255) DEFAULT NULL,
  `day_14` varchar(255) DEFAULT NULL,
  `day_15` varchar(255) DEFAULT NULL,
  `day_16` varchar(255) DEFAULT NULL,
  `day_17` varchar(255) DEFAULT NULL,
  `day_18` varchar(255) DEFAULT NULL,
  `day_19` varchar(255) DEFAULT NULL,
  `day_20` varchar(255) DEFAULT NULL,
  `day_21` varchar(255) DEFAULT NULL,
  `day_22` varchar(255) DEFAULT NULL,
  `day_23` varchar(255) DEFAULT NULL,
  `day_24` varchar(255) DEFAULT NULL,
  `day_25` varchar(255) DEFAULT NULL,
  `day_26` varchar(255) DEFAULT NULL,
  `day_27` varchar(255) DEFAULT NULL,
  `day_28` varchar(255) DEFAULT NULL,
  `day_29` varchar(255) DEFAULT NULL,
  `day_30` varchar(255) DEFAULT NULL,
  `day_31` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allotment`
--

INSERT INTO `allotment` (`id`, `employee_id`, `name`, `patient_id`, `patient_name`, `service_type`, `shift`, `status`, `no_of_hours`, `created_at`, `updated_at`, `day_1`, `day_2`, `day_3`, `day_4`, `day_5`, `day_6`, `day_7`, `day_8`, `day_9`, `day_10`, `day_11`, `day_12`, `day_13`, `day_14`, `day_15`, `day_16`, `day_17`, `day_18`, `day_19`, `day_20`, `day_21`, `day_22`, `day_23`, `day_24`, `day_25`, `day_26`, `day_27`, `day_28`, `day_29`, `day_30`, `day_31`, `start_date`, `end_date`) VALUES
(1, 3, 'alekhya kodam', 0, '3', 'Semi-Trained Nurse', 'Night', 'Assigned', 8, '2024-12-06 15:00:35', '2024-12-06 15:01:32', '', '', 'yes', 'yes', 'yes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-06', '2024-12-26'),
(2, 7, 'Soujanya', 0, '4', 'Care Taker', 'Flexible', 'Assigned', 12, '2024-12-06 15:27:55', '2024-12-06 16:23:46', NULL, NULL, NULL, NULL, NULL, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-07', '2024-12-17'),
(3, 3, 'alekhya kodam', 0, '2', 'Semi-Trained Nurse', 'Day', 'Assigned', 24, '2024-12-06 16:07:38', '2024-12-06 16:26:21', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'yes', 'yes', 'yes', 'yes', 'yes', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-08', '2024-12-12');

-- --------------------------------------------------------

--
-- Table structure for table `allotment_old`
--

CREATE TABLE `allotment_old` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `patient_id` int(255) NOT NULL,
  `patient_name` varchar(255) DEFAULT NULL,
  `service_type` varchar(50) DEFAULT NULL,
  `shift` varchar(20) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `no_of_hours` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `allotment_old`
--

INSERT INTO `allotment_old` (`id`, `employee_id`, `name`, `patient_id`, `patient_name`, `service_type`, `shift`, `start_date`, `end_date`, `status`, `no_of_hours`, `created_at`, `updated_at`) VALUES
(1, 0, '0', 0, 'Harish', 'Fully Trained Nurse', 'Day', '2024-12-05', '2024-12-19', 'Assigned', '8 Hours', '2024-12-05 07:36:16', '2024-12-05 07:36:16'),
(4, 3, 'alekhya kodam', 0, 'Harish', 'Fully Trained Nurse', 'Day', '2024-12-05', '2024-12-11', 'Assigned', '12 Hours', '2024-12-05 10:34:34', '2024-12-05 10:34:34'),
(13, 7, 'Soujanya', 0, '4', 'Fully Trained Nurse', 'Day', '2024-12-05', '2024-12-20', 'Assigned', '24 Hours', '2024-12-05 13:50:33', '2024-12-05 13:50:33'),
(14, 3, 'alekhya kodam', 0, '3', 'Care Taker', 'Flexible', '2024-12-06', '2024-12-18', 'Assigned', '12 Hours', '2024-12-06 09:00:17', '2024-12-06 09:00:17'),
(15, 7, 'Soujanya', 0, '2', 'Nani\'s', 'Day', '2024-12-13', '2024-12-24', 'Assigned', '8 Hours', '2024-12-06 09:03:36', '2024-12-06 09:03:36');

-- --------------------------------------------------------

--
-- Table structure for table `customer_master`
--

CREATE TABLE `customer_master` (
  `id` int(11) NOT NULL,
  `patient_name` varchar(255) DEFAULT NULL,
  `relationship` varchar(100) DEFAULT NULL,
  `customer_name` varchar(255) NOT NULL,
  `emergency_contact_number` varchar(15) NOT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `medical_conditions` text DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `patient_age` int(11) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `care_requirements` varchar(50) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `mobility_status` varchar(50) DEFAULT NULL,
  `discharge_summary_sheet` varchar(255) DEFAULT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer_master`
--

INSERT INTO `customer_master` (`id`, `patient_name`, `relationship`, `customer_name`, `emergency_contact_number`, `blood_group`, `medical_conditions`, `email`, `patient_age`, `gender`, `care_requirements`, `created_at`, `updated_at`, `mobility_status`, `discharge_summary_sheet`, `address`) VALUES
(11, 'Bhargav', 'guardian', 'Bhargav', '9874563210', 'O+', '3', 'bhargav@gmail.com', 28, 'male', 'fully-trained-nurse', NULL, NULL, 'Walking', 'document (2).pdf', 'Hyd'),
(15, 'soujanya', 'parent', 'Soujanya', '9874563210', 'A+', '1', 'soujanya@gmail.com', 25, 'female', 'fully-trained-nurse', NULL, NULL, 'Walking', 'web development theory.txt', 'Knr'),
(16, 'Alekhya', 'parent', 'Alekhya', '9874563210', 'AB-', '1', 'alkehya@gmail.com', 26, 'female', 'caretaker', NULL, NULL, 'Walking', 'web development theory.txt', 'Hyd'),
(18, 'Priya', 'sibling', 'Priya', '9234567890', 'A+', '3', 'priya@gmail.com', 26, 'female', '', NULL, NULL, 'Walking', 'web development theory.txt', 'Vmd'),
(19, 'Priya', 'sibling', 'Priya', '9234567890', 'A+', '3', 'priya@gmail.com', 26, 'female', '', NULL, NULL, 'Walking', 'web development theory.txt', 'Vmd'),
(20, 'Priya', 'sibling', 'Priya', '9234567890', 'A+', '3', 'priya@gmail.com', 26, 'female', '', NULL, NULL, 'Walking', 'web development theory.txt', 'Vmd'),
(21, 'test', 'grandchild', 'Wade Stone', '269', 'O+', 'Veniam quam fugiat', 'ryxiti@mailinator.com', 40, 'female', NULL, NULL, NULL, 'Wheelchair', 'web development theory.txt', 'Rerum dolorem placea'),
(22, 'Aurelia Spence', '', 'Quentin Harrell', '891', 'B-', 'Voluptas minus quis ', 'kico@mailinator.com', 5, 'other', NULL, NULL, NULL, 'Walking', '', 'Nam veritatis exerci'),
(23, 'Bruce Mcleod', '', 'Jane Joyce', '422', 'O+', 'Non vel adipisicing ', 'dyqohyf@mailinator.com', 43, 'female', NULL, NULL, NULL, 'Wheelchair', '', 'Duis aut quis tenetu'),
(24, 'test2', '', 'test2', '8106517443', 'O+', 'test medcial con', 'koduri.bhagath@gmail.com', 67, 'male', NULL, NULL, NULL, 'Walking', 'Answer.txt', 'H.No. 1-97,Madhapur'),
(25, 'test3', 'friend', 'test2', '8106517443', 'A+', 'no', 'koduri.bhagath@gmail.com', 21, 'male', NULL, '2024-12-06 00:00:00', '2024-12-06 00:00:00', 'Walking', 'Top 90+ AWS Interview Questions and Answers for 2024-output.docx', 'Bhavan rishi nagar\r\nbehind natraj theatre\r\nTakshila Scholl lane');

-- --------------------------------------------------------

--
-- Table structure for table `emp_info`
--

CREATE TABLE `emp_info` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `dob` date NOT NULL,
  `gender` varchar(150) NOT NULL,
  `phone` varchar(250) NOT NULL,
  `email` varchar(250) NOT NULL,
  `role` varchar(150) NOT NULL,
  `qualification` varchar(150) NOT NULL,
  `experience` varchar(150) NOT NULL,
  `doj` date NOT NULL,
  `aadhar` varchar(150) NOT NULL,
  `police_verification` varchar(150) NOT NULL,
  `daily_rate` decimal(50,0) NOT NULL,
  `status` varchar(150) NOT NULL,
  `termination_date` date NOT NULL,
  `document` varchar(255) NOT NULL,
  `bank_name` varchar(150) NOT NULL,
  `bank_account_no` varchar(150) NOT NULL,
  `ifsc_code` varchar(150) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emp_info`
--

INSERT INTO `emp_info` (`id`, `name`, `dob`, `gender`, `phone`, `email`, `role`, `qualification`, `experience`, `doj`, `aadhar`, `police_verification`, `daily_rate`, `status`, `termination_date`, `document`, `bank_name`, `bank_account_no`, `ifsc_code`, `address`) VALUES
(3, 'alekhya kodam', '2024-01-02', 'female', '9553897696', 'allushyamk@gmail.com', 'admin', '10th', '0-1', '0000-00-00', '', 'verified', 1000, 'active', '2024-12-03', 'uploads/Alekhya sripathi.docx', 'sbi', '', 'ifsc4442215', 'thumkunta, secundrabad'),
(7, 'Soujanya', '1997-02-11', 'female', '9492003253', 'sspandrala261126@gmail.com', 'manager', 'degree', '2-3', '2024-12-02', '935767756357', 'verified', 2400, 'active', '0000-00-00', 'uploads/292022_22290230535_HTNO_2291909368_081120241224.pdf', 'SBI', '955636253336356', 'SBIN0012502', '8-7-270/1, Hanuman nagar, Ganesh Nagar\r\nKarimnagar');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `id` int(11) NOT NULL,
  `invoice_id` varchar(250) NOT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `service_id` varchar(25) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `customer_email` varchar(500) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `due_date` datetime DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`id`, `invoice_id`, `customer_id`, `service_id`, `customer_name`, `mobile_number`, `customer_email`, `total_amount`, `due_date`, `status`, `created_at`, `updated_at`) VALUES
(9, 'invoice_6754421C7047D835886', NULL, '12', '3', '9865471236', 'v@gmail.com', 15600.00, NULL, 'Pending', '2024-12-07 12:39:56', '2024-12-07 12:39:56'),
(10, 'invoice_67544632839B5852157', NULL, '13', 'manu', '9988990099', 'manu@yahoo.co', 18000.00, NULL, 'Pending', '2024-12-07 12:57:22', '2024-12-07 12:57:22'),
(12, 'invoice_675447A40FF9E843080', NULL, '7', 'savitha', '8897791988', 'savitha.gundla08@gmail.com', 9800.00, NULL, 'Pending', '2024-12-07 13:03:32', '2024-12-07 13:03:32');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `service_type` enum('fully_trained_nurse','semi_trained_nurse','care_taker') NOT NULL,
  `from_date` date NOT NULL,
  `end_date` date NOT NULL,
  `duration` int(11) NOT NULL,
  `base_charges` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('paid','pending','partially_paid') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `customer_name`, `service_type`, `from_date`, `end_date`, `duration`, `base_charges`, `total_amount`, `status`, `created_at`) VALUES
(8, 'poojith', 'semi_trained_nurse', '2024-12-06', '2024-12-26', 20, 500.00, 5000.00, 'pending', '2024-12-05 08:29:03'),
(10, 'savitha', 'fully_trained_nurse', '2024-12-06', '2024-12-19', 13, 500.00, 2111.00, 'paid', '2024-12-05 09:29:20'),
(11, 'Venkatesh', 'fully_trained_nurse', '2024-12-05', '2024-12-12', 7, 500.00, 1500.00, 'partially_paid', '2024-12-05 13:13:18'),
(12, 'soujanya', 'semi_trained_nurse', '2024-12-19', '2025-01-01', 13, 500.00, 6000.00, 'pending', '2024-12-05 13:19:07'),
(13, '', '', '2024-12-05', '2024-12-10', 5, 1000.00, 5000.00, '', '2024-12-05 13:26:54'),
(14, '', '', '2024-12-05', '2024-12-10', 5, 1000.00, 5000.00, '', '2024-12-05 13:27:00'),
(15, '', '', '2024-12-05', '2024-12-10', 5, 1000.00, 5000.00, '', '2024-12-05 13:27:09'),
(16, 'Venkatesh', 'fully_trained_nurse', '2024-12-12', '2024-12-10', 0, 1000.00, 5000.00, 'pending', '2024-12-05 14:13:42'),
(17, '', '', '0000-00-00', '0000-00-00', 0, 0.00, 0.00, '', '2024-12-06 23:56:16');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `email`, `password`) VALUES
(1, 'admin@gmail.com', 'admin@123');

-- --------------------------------------------------------

--
-- Table structure for table `service_master`
--

CREATE TABLE `service_master` (
  `id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `daily_rate_8_hours` decimal(10,2) NOT NULL,
  `daily_rate_12_hours` decimal(10,2) NOT NULL,
  `daily_rate_24_hours` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_master`
--

INSERT INTO `service_master` (`id`, `service_name`, `status`, `daily_rate_8_hours`, `daily_rate_12_hours`, `daily_rate_24_hours`, `description`, `created_at`) VALUES
(3, 'nannies', 'active', 200.00, 400.00, 800.00, 'zdfbvzdfbv', '2024-12-05 09:57:06'),
(4, 'care_taker', 'active', 2333.00, 443.00, 231.00, 'xzdgbzdsfardf', '2024-12-05 09:58:53'),
(5, 'fully_trained_nurse', 'active', 500.00, 600.00, 800.00, 'bjjbjk', '2024-12-05 09:59:50'),
(6, 'care_taker', 'active', 800.00, 1400.00, 2800.00, 'jujjujj', '2024-12-05 10:01:07'),
(8, 'fully_trained_nurse', 'active', 400.00, 800.00, 1500.00, 'bfbfff', '2024-12-05 14:06:32');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `contact_no` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `enquiry_date` date NOT NULL,
  `enquiry_time` time NOT NULL,
  `service_type` varchar(100) NOT NULL,
  `from_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `total_days` int(11) DEFAULT NULL,
  `service_price` decimal(10,2) DEFAULT NULL,
  `invoice_status` varchar(250) DEFAULT NULL,
  `enquiry_source` varchar(100) NOT NULL,
  `priority_level` varchar(50) NOT NULL,
  `status` varchar(50) NOT NULL,
  `assigned_employee` varchar(255) NOT NULL,
  `request_details` text NOT NULL,
  `resolution_notes` text NOT NULL,
  `comments` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_requests`
--

INSERT INTO `service_requests` (`id`, `customer_name`, `contact_no`, `email`, `enquiry_date`, `enquiry_time`, `service_type`, `from_date`, `end_date`, `total_days`, `service_price`, `invoice_status`, `enquiry_source`, `priority_level`, `status`, `assigned_employee`, `request_details`, `resolution_notes`, `comments`, `created_at`) VALUES
(7, 'savitha', '8897791988', 'savitha.gundla08@gmail.com', '0000-00-00', '16:44:00', 'bank', NULL, NULL, NULL, 9800.00, 'Generated', 'phone', 'low', 'new', '', 'asdf', 'aefgveas', 'adfaz', '2024-12-04 11:14:53'),
(12, 'test', '9865471236', 'v@gmail.com', '2024-12-06', '19:59:00', 'fully_trained_nurse', '2024-12-07', '2024-12-19', 13, 15600.00, 'Generated', 'phone', 'medium', 'pending', '', 'prices', 'asdf', 'qwerfe', '2024-12-06 13:30:04'),
(13, 'manu', '9988990099', 'manu@yahoo.co', '2024-12-08', '18:12:00', 'fully_trained_nurse', '2024-12-21', '2025-01-04', 15, 18000.00, 'Generated', 'phone', 'medium', 'pending', '', 'prices', 'sddf', 'szdz', '2024-12-07 12:42:58');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `vendors`
--

CREATE TABLE `vendors` (
  `id` int(11) NOT NULL,
  `vendor_name` varchar(255) NOT NULL,
  `gstin` varchar(50) NOT NULL,
  `contact_person` varchar(255) NOT NULL,
  `supporting_documents` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `services_provided` varchar(255) NOT NULL,
  `vendor_type` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `additional_notes` text DEFAULT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_number` varchar(20) NOT NULL,
  `ifsc` varchar(20) NOT NULL,
  `status` varchar(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vendors`
--

INSERT INTO `vendors` (`id`, `vendor_name`, `gstin`, `contact_person`, `supporting_documents`, `phone_number`, `email`, `services_provided`, `vendor_type`, `address`, `additional_notes`, `bank_name`, `account_number`, `ifsc`, `status`, `created_at`, `updated_at`) VALUES
(1, 'soujanya', '32', 'Soujanya', '', '09492003253', 'sspandrala61126@gmail.com', 'Fully Trained Nurse', 'Individual', '8-7-270/1, Hanuman nagar, Ganesh Nagar\\r\\nKarimnagar', '', '', '', '', 'Active', '2024-12-04 10:53:35', '2024-12-04 10:53:35'),
(4, 'soumya', '20', 'Soujanya', NULL, '09492003253', 'sspandrala261126@gmail.com', 'Fully Trained Nurse', 'Individual', '8-7-270/1, Hanuman nagar, Ganesh Nagar\r\nKarimnagar', '', '', '', '', 'Active', '2024-12-04 11:02:06', '2024-12-04 11:02:06'),
(8, 'soujanya', '55455875', 'Soujanya', 'uploads/292022_22290230535_HTNO_2291909368_081120241224.pdf', '09492003253', 'sspandrala26@gmail.com', 'Fully Trained Nurse', 'Individual', '8-7-270/1, Hanuman nagar, Ganesh Nagar\r\nKarimnagar', 'bfbff', 'fvh', '2245336214', 'UBIN0815918', 'Active', '2024-12-05 13:33:10', '2024-12-05 13:33:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allotment`
--
ALTER TABLE `allotment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `allotment_old`
--
ALTER TABLE `allotment_old`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customer_master`
--
ALTER TABLE `customer_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emp_info`
--
ALTER TABLE `emp_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_master`
--
ALTER TABLE `service_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vendors`
--
ALTER TABLE `vendors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `gstin` (`gstin`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allotment`
--
ALTER TABLE `allotment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `allotment_old`
--
ALTER TABLE `allotment_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `customer_master`
--
ALTER TABLE `customer_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `emp_info`
--
ALTER TABLE `emp_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `service_master`
--
ALTER TABLE `service_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `vendors`
--
ALTER TABLE `vendors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
