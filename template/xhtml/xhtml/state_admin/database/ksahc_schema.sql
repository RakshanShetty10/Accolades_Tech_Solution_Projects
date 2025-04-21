-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2025 at 06:32 AM
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
-- Database: `ksahc_schema`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_master`
--

CREATE TABLE `account_master` (
  `account_id` int(11) NOT NULL,
  `account_name` varchar(250) DEFAULT NULL,
  `account_status` varchar(25) DEFAULT NULL,
  `account_created_by` varchar(100) DEFAULT NULL,
  `account_created_on` datetime DEFAULT NULL,
  `account_last_updated_by` varchar(100) DEFAULT NULL,
  `account_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `address_change`
--

CREATE TABLE `address_change` (
  `change_id` int(11) NOT NULL,
  `practitioner_id` int(11) DEFAULT NULL,
  `change_description` text DEFAULT NULL,
  `change_file` varchar(50) DEFAULT NULL,
  `reply_message` text DEFAULT NULL,
  `change_created_on` datetime DEFAULT NULL,
  `change_status` varchar(25) DEFAULT NULL,
  `change_last_updated_on` datetime DEFAULT NULL,
  `change_last_updated_by` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `announcement_id` int(11) NOT NULL,
  `announcement_title` varchar(5000) DEFAULT NULL,
  `announcement_status` varchar(25) DEFAULT NULL,
  `announcement_created_by` varchar(100) DEFAULT NULL,
  `announcement_created_on` datetime DEFAULT NULL,
  `announcement_last_updated_by` varchar(100) DEFAULT NULL,
  `announcement_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appoint_id` int(11) NOT NULL,
  `appoint_name` varchar(100) DEFAULT NULL,
  `appoint_mobile` varchar(13) DEFAULT NULL,
  `appoint_email` varchar(50) DEFAULT NULL,
  `appoint_category` varchar(50) DEFAULT NULL,
  `appoint_description` text DEFAULT NULL,
  `appoint_slot_time` varchar(50) DEFAULT NULL,
  `appoint_status` varchar(50) DEFAULT NULL,
  `appoint_created_by` varchar(50) DEFAULT NULL,
  `appoint_created_on` datetime DEFAULT NULL,
  `appoint_updated_by` varchar(50) DEFAULT NULL,
  `appoint_updated_on` date DEFAULT NULL,
  `appoint_date` date DEFAULT NULL,
  `appoint_admin_description` text DEFAULT NULL,
  `practitioner_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointment_master`
--

CREATE TABLE `appointment_master` (
  `appointment_master_id` int(11) NOT NULL,
  `no_of_people` int(11) DEFAULT NULL,
  `slot_timing` varchar(50) DEFAULT NULL,
  `weekday` varchar(20) NOT NULL DEFAULT 'Monday'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_balance`
--

CREATE TABLE `bank_balance` (
  `bank_balance_id` int(11) NOT NULL,
  `code_ksdc` varchar(250) DEFAULT NULL,
  `financial_year` varchar(25) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `opening_balance` decimal(10,2) DEFAULT NULL,
  `closing_balance` decimal(10,2) DEFAULT NULL,
  `bank_balance_status` varchar(25) DEFAULT NULL,
  `bank_balance_last_updated_by` varchar(100) DEFAULT NULL,
  `bank_balance_last_updated_on` datetime DEFAULT NULL,
  `bank_balance_created_on` datetime DEFAULT NULL,
  `bank_balance_created_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_master`
--

CREATE TABLE `bank_master` (
  `bank_id` int(11) NOT NULL,
  `bank_code` int(11) DEFAULT NULL,
  `bank_name` varchar(250) DEFAULT NULL,
  `bank_address` text DEFAULT NULL,
  `bank_IFSC_code` varchar(25) DEFAULT NULL,
  `bank_account_number` varchar(250) DEFAULT NULL,
  `bank_status` varchar(25) DEFAULT NULL,
  `bank_created_by` varchar(250) DEFAULT NULL,
  `bank_created_on` datetime DEFAULT NULL,
  `bank_last_updated_by` varchar(250) DEFAULT NULL,
  `bank_last_updated_on` datetime DEFAULT NULL,
  `bank_code_ksdc` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budget_master`
--

CREATE TABLE `budget_master` (
  `budget_id` int(11) NOT NULL,
  `financial_year` varchar(25) DEFAULT NULL,
  `ledger_id` int(11) DEFAULT NULL,
  `budget_amount` decimal(10,2) DEFAULT NULL,
  `budget_status` varchar(25) DEFAULT NULL,
  `budget_last_updated_by` varchar(100) DEFAULT NULL,
  `budget_last_updated_on` datetime DEFAULT NULL,
  `budget_created_on` datetime DEFAULT NULL,
  `budget_created_by` varchar(100) DEFAULT NULL,
  `code_ksdc` varchar(250) DEFAULT NULL,
  `ledger_code` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `calender`
--

CREATE TABLE `calender` (
  `calender_id` int(11) NOT NULL,
  `calender_date` date DEFAULT NULL,
  `calender_holiday` varchar(50) DEFAULT NULL,
  `calender_status` varchar(50) DEFAULT NULL,
  `calender_created_by` varchar(50) DEFAULT NULL,
  `calender_created_on` date DEFAULT NULL,
  `calender_updated_on` date DEFAULT NULL,
  `calender_updated_by` varchar(50) DEFAULT NULL,
  `calendar_description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cashbook`
--

CREATE TABLE `cashbook` (
  `cashbook_id` int(11) NOT NULL,
  `cashbook_total` int(11) NOT NULL,
  `cashbook_date` date DEFAULT NULL,
  `cashbook_reference_number` varchar(250) DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `ledger_id` int(11) DEFAULT NULL,
  `cashbook_type` varchar(25) DEFAULT NULL,
  `payment_mode_id` int(11) DEFAULT NULL,
  `cashbook_created_on` datetime DEFAULT NULL,
  `cashbook_created_by` varchar(250) DEFAULT NULL,
  `cashbook_last_updated_on` datetime DEFAULT NULL,
  `cashbook_last_updated_by` varchar(250) DEFAULT NULL,
  `cashbook_number` int(11) DEFAULT NULL,
  `cashbook_status` varchar(25) DEFAULT NULL,
  `cashbook_cancel_date` date DEFAULT NULL,
  `cashbook_remark` text DEFAULT NULL,
  `cashbook_cancel_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cashbook_for_master`
--

CREATE TABLE `cashbook_for_master` (
  `cashbook_for_id` int(11) NOT NULL,
  `cashbook_for` varchar(250) DEFAULT NULL,
  `cashbook_for_status` varchar(25) DEFAULT NULL,
  `cashbook_created_by` varchar(250) DEFAULT NULL,
  `cashbook_created_on` datetime DEFAULT NULL,
  `cashbook_last_updated_by` varchar(250) DEFAULT NULL,
  `cashbook_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cashbook_number_master`
--

CREATE TABLE `cashbook_number_master` (
  `cashbook_number_master_id` int(11) NOT NULL,
  `cashbook_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cashbook_receipt`
--

CREATE TABLE `cashbook_receipt` (
  `cashbook_receipt_id` int(11) NOT NULL,
  `receipt_total` int(11) DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `receipt_for_id` int(11) DEFAULT NULL,
  `receipt_type` varchar(25) DEFAULT NULL,
  `receipt_reference_number` varchar(250) DEFAULT NULL,
  `receipt_created_on` datetime DEFAULT NULL,
  `receipt_created_by` varchar(250) DEFAULT NULL,
  `receipt_last_updated_on` datetime DEFAULT NULL,
  `receipt_last_updated_by` varchar(250) DEFAULT NULL,
  `receipt_number` int(11) DEFAULT NULL,
  `receipt_status` varchar(25) DEFAULT NULL,
  `receipt_remit_status` varchar(25) DEFAULT NULL,
  `receipt_remitted_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cash_balance`
--

CREATE TABLE `cash_balance` (
  `cash_balance_id` int(11) NOT NULL,
  `code_ksdc` varchar(250) DEFAULT NULL,
  `financial_year` varchar(25) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `opening_balance` decimal(10,2) DEFAULT NULL,
  `closing_balance` decimal(10,2) DEFAULT NULL,
  `cash_balance_status` varchar(25) DEFAULT NULL,
  `cash_balance_last_updated_by` varchar(100) DEFAULT NULL,
  `cash_balance_last_updated_on` datetime DEFAULT NULL,
  `cash_balance_created_on` datetime DEFAULT NULL,
  `cash_balance_created_by` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_master`
--

CREATE TABLE `category_master` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(250) DEFAULT NULL,
  `category_status` varchar(25) DEFAULT NULL,
  `category_created_by` varchar(250) DEFAULT NULL,
  `category_created_on` datetime DEFAULT NULL,
  `category_last_updated_by` varchar(250) DEFAULT NULL,
  `category_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `college_master`
--

CREATE TABLE `college_master` (
  `college_id` int(11) NOT NULL,
  `university_id` int(11) DEFAULT NULL,
  `college_name` varchar(250) DEFAULT NULL,
  `college_address` text DEFAULT NULL,
  `college_city` varchar(250) DEFAULT NULL,
  `college_type` varchar(25) DEFAULT NULL,
  `college_code` varchar(25) DEFAULT NULL,
  `college_principle_name` varchar(250) DEFAULT NULL,
  `college_contact_number` varchar(25) DEFAULT NULL,
  `college_email_address` varchar(250) DEFAULT NULL,
  `college_status` varchar(25) DEFAULT NULL,
  `college_created_on` datetime DEFAULT NULL,
  `college_created_by` varchar(250) DEFAULT NULL,
  `college_last_updated_on` datetime DEFAULT NULL,
  `college_last_updated_by` varchar(250) DEFAULT NULL,
  `college_id_ksdc` varchar(250) DEFAULT NULL,
  `university_id_ksdc` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `complaint`
--

CREATE TABLE `complaint` (
  `complaint_id` int(11) NOT NULL,
  `complaint_name` varchar(250) DEFAULT NULL,
  `complaint_email` varchar(250) DEFAULT NULL,
  `complaint_message` text DEFAULT NULL,
  `complaint_status` varchar(25) DEFAULT NULL,
  `complaint_comment` text DEFAULT NULL,
  `complaint_last_updated_by` varchar(250) DEFAULT NULL,
  `complaint_last_updated_on` datetime DEFAULT NULL,
  `complaint_created_on` datetime DEFAULT NULL,
  `practitioner_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `configure`
--

CREATE TABLE `configure` (
  `configure_id` int(11) NOT NULL,
  `webiste_email_address` varchar(250) DEFAULT NULL,
  `webiste_phone_number` varchar(250) DEFAULT NULL,
  `website_address_line1` varchar(1000) DEFAULT NULL,
  `website_address_line2` varchar(1000) DEFAULT NULL,
  `president_name` varchar(250) DEFAULT NULL,
  `president_qualification` varchar(250) DEFAULT NULL,
  `president_image` varchar(50) DEFAULT NULL,
  `president_image2` varchar(50) DEFAULT NULL,
  `president_official_email_address` varchar(250) DEFAULT NULL,
  `president_personal_email_address` varchar(250) DEFAULT NULL,
  `president_phone_number` varchar(25) DEFAULT NULL,
  `president_from_date` date DEFAULT NULL,
  `president_address` text DEFAULT NULL,
  `president_message` text DEFAULT NULL,
  `president_from` date DEFAULT NULL,
  `registrar_email_address` varchar(250) DEFAULT NULL,
  `platform_persentage` int(11) DEFAULT NULL,
  `secretary_address` text DEFAULT NULL,
  `configure_last_updated_on` datetime DEFAULT NULL,
  `configure_last_updated_by` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `contact_id` int(11) NOT NULL,
  `contact_name` varchar(250) DEFAULT NULL,
  `contact_email_address` varchar(250) DEFAULT NULL,
  `contact_phone_number` varchar(25) DEFAULT NULL,
  `contact_type` varchar(25) DEFAULT NULL,
  `contact_joining_date` date DEFAULT NULL,
  `contact_elected_year` varchar(25) DEFAULT NULL,
  `contact_status` varchar(25) DEFAULT NULL,
  `contact_last_updated_by` varchar(250) DEFAULT NULL,
  `contact_last_updated_on` datetime DEFAULT NULL,
  `contact_created_on` datetime DEFAULT NULL,
  `contact_created_by` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coordinator_master`
--

CREATE TABLE `coordinator_master` (
  `coordinator_id` int(11) NOT NULL,
  `coordinator_name` varchar(250) DEFAULT NULL,
  `coordinator_phone_number` varchar(25) DEFAULT NULL,
  `coordinator_email_address` varchar(250) DEFAULT NULL,
  `coordinator_status` varchar(25) DEFAULT NULL,
  `coordinator_created_by` varchar(250) DEFAULT NULL,
  `coordinator_created_on` datetime DEFAULT NULL,
  `coordinator_last_updated_by` varchar(250) DEFAULT NULL,
  `coordinator_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `council_master`
--

CREATE TABLE `council_master` (
  `council_id` int(11) NOT NULL,
  `council_name` varchar(250) DEFAULT NULL,
  `council_address` text DEFAULT NULL,
  `council_contact_person_name` varchar(250) DEFAULT NULL,
  `council_contact_number` varchar(25) DEFAULT NULL,
  `council_status` varchar(25) DEFAULT NULL,
  `council_created_by` varchar(250) DEFAULT NULL,
  `council_created_on` datetime DEFAULT NULL,
  `council_last_updated_by` varchar(250) DEFAULT NULL,
  `council_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `council_state`
--

CREATE TABLE `council_state` (
  `council_state_id` int(11) NOT NULL,
  `state_name` varchar(250) DEFAULT NULL,
  `council_state_status` varchar(25) DEFAULT 'Active',
  `council_state_created_by` varchar(250) DEFAULT NULL,
  `council_state_created_on` datetime DEFAULT NULL,
  `council_state_last_updated_by` varchar(250) DEFAULT NULL,
  `council_state_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `country_master`
--

CREATE TABLE `country_master` (
  `country_id` int(11) NOT NULL,
  `country_name` varchar(250) DEFAULT NULL,
  `country_status` varchar(25) DEFAULT NULL,
  `country_created_by` varchar(250) DEFAULT NULL,
  `country_created_on` datetime DEFAULT NULL,
  `country_last_updated_by` varchar(250) DEFAULT NULL,
  `country_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `course_id` int(11) NOT NULL,
  `course_name` varchar(250) DEFAULT NULL,
  `council_state_id` int(11) DEFAULT NULL,
  `course_status` varchar(25) DEFAULT 'Active',
  `course_created_by` varchar(250) DEFAULT NULL,
  `course_created_on` datetime DEFAULT NULL,
  `course_last_updated_by` varchar(250) DEFAULT NULL,
  `course_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `download_master`
--

CREATE TABLE `download_master` (
  `download_id` int(11) NOT NULL,
  `download_title` varchar(250) DEFAULT NULL,
  `download_file` varchar(100) DEFAULT NULL,
  `download_status` varchar(25) DEFAULT NULL,
  `download_created_by` varchar(250) DEFAULT NULL,
  `download_created_on` datetime DEFAULT NULL,
  `download_last_updated_by` varchar(250) DEFAULT NULL,
  `download_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `education_information`
--

CREATE TABLE `education_information` (
  `education_id` int(11) NOT NULL,
  `practitioner_id` int(11) DEFAULT NULL,
  `education_name` varchar(250) DEFAULT NULL,
  `education_year_of_passing` varchar(25) DEFAULT NULL,
  `education_month_of_passing` varchar(25) DEFAULT NULL,
  `college_id` int(11) DEFAULT NULL,
  `university_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `education_status` varchar(25) DEFAULT NULL,
  `education_created_by` varchar(250) DEFAULT NULL,
  `education_created_on` datetime DEFAULT NULL,
  `education_last_updated_on` datetime DEFAULT NULL,
  `practitioner_code_ksdc` varchar(250) DEFAULT NULL,
  `education_last_updated_by` varchar(250) DEFAULT NULL,
  `ksdc_college_id` varchar(250) DEFAULT NULL,
  `ksdc_university_id` varchar(250) DEFAULT NULL,
  `course_name` varchar(250) DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `ksdc_temp_yop` varchar(250) DEFAULT NULL,
  `ksdc_temp_university` varchar(250) DEFAULT NULL,
  `ksdc_temp_education_name` varchar(250) DEFAULT NULL,
  `ksdc_subject_code` varchar(25) DEFAULT NULL,
  `ksdc_teml_college` varchar(250) DEFAULT NULL,
  `EducationID` varchar(250) DEFAULT NULL,
  `institution_name` int(11) DEFAULT NULL,
  `internship_institution` int(11) DEFAULT NULL,
  `internship_from` date DEFAULT NULL,
  `internship_to` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enquiry`
--

CREATE TABLE `enquiry` (
  `enquiry_id` int(11) NOT NULL,
  `enquiry_name` varchar(250) NOT NULL,
  `enquiry_email` varchar(250) NOT NULL,
  `enquiry_message` text NOT NULL,
  `enquiry_status` varchar(25) NOT NULL,
  `enquiry_comment` text NOT NULL,
  `enquiry_last_updated_by` varchar(250) NOT NULL,
  `enquiry_last_updated_on` datetime NOT NULL,
  `enquiry_created_on` datetime NOT NULL,
  `enquiry_created_by` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `event_title` varchar(5000) DEFAULT NULL,
  `event_description` text DEFAULT NULL,
  `event_image` varchar(50) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `event_status` varchar(25) DEFAULT NULL,
  `event_created_by` varchar(250) DEFAULT NULL,
  `event_created_on` datetime DEFAULT NULL,
  `event_last_updated_by` varchar(250) DEFAULT NULL,
  `event_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `faq_id` int(11) NOT NULL,
  `faq_title` varchar(5000) DEFAULT NULL,
  `faq_description` text DEFAULT NULL,
  `faq_created_on` datetime DEFAULT NULL,
  `faq_created_by` varchar(250) DEFAULT NULL,
  `faq_last_updated_by` varchar(250) DEFAULT NULL,
  `faq_last_updated_on` datetime DEFAULT NULL,
  `faq_status` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `feedback_user_name` varchar(250) DEFAULT NULL,
  `feedback_user_email_address` varchar(250) DEFAULT NULL,
  `feedback_description` text DEFAULT NULL,
  `feedback_status` varchar(25) DEFAULT NULL,
  `feedback_created_on` datetime DEFAULT NULL,
  `feedback_last_updated_on` datetime DEFAULT NULL,
  `feedback_last_updated_by` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fees_master`
--

CREATE TABLE `fees_master` (
  `fees_id` int(11) NOT NULL,
  `fees_name` varchar(250) DEFAULT NULL,
  `fees_amount` int(11) DEFAULT NULL,
  `fees_status` varchar(25) DEFAULT NULL,
  `fees_created_by` varchar(250) DEFAULT NULL,
  `fees_created_on` datetime DEFAULT NULL,
  `fees_last_updated_by` varchar(250) DEFAULT NULL,
  `fees_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hall_master`
--

CREATE TABLE `hall_master` (
  `hall_id` int(11) NOT NULL,
  `hall_name` varchar(250) DEFAULT NULL,
  `state_id` int(11) DEFAULT NULL,
  `country` varchar(250) DEFAULT NULL,
  `hall_status` varchar(25) DEFAULT NULL,
  `hall_created_on` datetime DEFAULT NULL,
  `hall_created_by` varchar(250) DEFAULT NULL,
  `hall_last_updated_by` varchar(250) DEFAULT NULL,
  `hall_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ledger_master`
--

CREATE TABLE `ledger_master` (
  `ledger_id` int(11) NOT NULL,
  `ledger_code` varchar(25) DEFAULT NULL,
  `ledger_code_ksdc` varchar(150) DEFAULT NULL,
  `ledger_name` varchar(250) DEFAULT NULL,
  `ledger_type` varchar(50) DEFAULT NULL,
  `ledger_status` varchar(25) DEFAULT NULL,
  `ledger_created_by` varchar(250) DEFAULT NULL,
  `ledger_created_on` datetime DEFAULT NULL,
  `ledger_last_updated_by` varchar(250) DEFAULT NULL,
  `ledger_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `member_id` int(11) NOT NULL,
  `member_name` varchar(250) DEFAULT NULL,
  `member_designation` varchar(250) DEFAULT NULL,
  `member_image` varchar(50) DEFAULT NULL,
  `member_email` varchar(250) DEFAULT NULL,
  `member_phone` varchar(50) DEFAULT NULL,
  `member_address` text DEFAULT NULL,
  `member_qualification` varchar(250) DEFAULT NULL,
  `member_status` varchar(50) NOT NULL,
  `member_created_on` datetime DEFAULT NULL,
  `member_created_by` varchar(250) DEFAULT NULL,
  `member_last_updated_on` datetime DEFAULT NULL,
  `member_last_updated_by` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `past_president`
--

CREATE TABLE `past_president` (
  `president_id` int(11) NOT NULL,
  `president_name` varchar(250) DEFAULT NULL,
  `president_from_date` date DEFAULT NULL,
  `president_to_date` date DEFAULT NULL,
  `president_image` varchar(50) DEFAULT NULL,
  `president_status` varchar(25) DEFAULT NULL,
  `president_last_updated_on` datetime DEFAULT NULL,
  `president_last_updated_by` varchar(250) DEFAULT NULL,
  `president_created_on` datetime DEFAULT NULL,
  `president_created_by` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_mode`
--

CREATE TABLE `payment_mode` (
  `payment_mode_id` int(11) NOT NULL,
  `payment_mode` varchar(250) DEFAULT NULL,
  `payment_mode_status` varchar(250) DEFAULT NULL,
  `payment_mode_created_by` varchar(250) DEFAULT NULL,
  `payment_mode_created_on` datetime DEFAULT NULL,
  `payment_mode_last_updated_by` varchar(250) DEFAULT NULL,
  `payment_mode_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_manager`
--

CREATE TABLE `permission_manager` (
  `permission_id` int(11) NOT NULL,
  `permission_particular_id` int(11) DEFAULT NULL,
  `permission_username` varchar(250) DEFAULT NULL,
  `permission_view_permission` tinyint(4) DEFAULT NULL,
  `permission_edit_permission` tinyint(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_particulars`
--

CREATE TABLE `permission_particulars` (
  `permission_particular_id` int(11) NOT NULL,
  `permission_particulars` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `practitioner`
--

CREATE TABLE `practitioner` (
  `practitioner_id` int(11) NOT NULL,
  `registration_number` int(11) DEFAULT NULL,
  `registration_date` date DEFAULT NULL,
  `registration_type_id` int(11) DEFAULT NULL,
  `practitioner_title` varchar(25) DEFAULT NULL,
  `practitioner_name` varchar(250) DEFAULT NULL,
  `practitioner_change_of_name` varchar(250) DEFAULT NULL,
  `practitioner_spouse_name` varchar(250) DEFAULT NULL,
  `practitioner_birth_date` date DEFAULT NULL,
  `practitioner_birth_place` varchar(250) DEFAULT NULL,
  `practitioner_gender` varchar(25) DEFAULT NULL,
  `practitioner_nationality` varchar(250) DEFAULT NULL,
  `vote_status` varchar(50) DEFAULT NULL,
  `practitioner_email_id` varchar(250) DEFAULT NULL,
  `practitioner_mobile_number` varchar(25) DEFAULT NULL,
  `practitioner_username` varchar(50) DEFAULT NULL,
  `practitioner_password` varchar(250) DEFAULT NULL,
  `is_first_login` varchar(25) DEFAULT NULL,
  `practitioner_created_by` varchar(250) DEFAULT NULL,
  `practitioner_created_on` datetime DEFAULT NULL,
  `practitioner_last_updated_by` varchar(250) DEFAULT NULL,
  `practitioner_last_updated_on` datetime DEFAULT NULL,
  `practitioner_pan_number` varchar(20) DEFAULT NULL,
  `practitioner_aadhar_number` varchar(20) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `registration_status` varchar(25) DEFAULT NULL,
  `certificate_slno` varchar(50) DEFAULT NULL,
  `practitioner_signature` varchar(75) DEFAULT NULL,
  `practitioner_thumb` varchar(75) DEFAULT NULL,
  `practitioner_profile_image` varchar(75) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `practitioner_address`
--

CREATE TABLE `practitioner_address` (
  `practitioner_address_id` int(11) NOT NULL,
  `practitioner_id` int(11) DEFAULT NULL,
  `practitioner_code_ksdc` varchar(250) DEFAULT NULL,
  `practitioner_address_type` varchar(25) DEFAULT NULL,
  `practitioner_address_line1` varchar(5000) DEFAULT NULL,
  `practitioner_address_line2` varchar(5000) DEFAULT NULL,
  `practitioner_address_city` varchar(250) DEFAULT NULL,
  `state_name` varchar(250) DEFAULT NULL,
  `practitioner_address_pincode` varchar(10) DEFAULT NULL,
  `country_name` varchar(250) DEFAULT NULL,
  `practitioner_address_phoneno` varchar(25) DEFAULT NULL,
  `practitioner_address_status` varchar(25) DEFAULT NULL,
  `practitioner_address_created_by` varchar(250) DEFAULT NULL,
  `practitioner_address_created_on` datetime DEFAULT NULL,
  `practitioner_address_last_updated_by` varchar(250) DEFAULT NULL,
  `practitioner_address_last_updated_on` datetime DEFAULT NULL,
  `practitioner_address_secondary_phoneno` varchar(25) DEFAULT NULL,
  `practitioner_address_category` varchar(25) DEFAULT NULL,
  `AddressID` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `practitioner_noc`
--

CREATE TABLE `practitioner_noc` (
  `noc_id` int(11) NOT NULL,
  `practitioner_id` int(11) DEFAULT NULL,
  `council_id` int(11) DEFAULT NULL,
  `noc_issue_date` date DEFAULT NULL,
  `noc_reference_number` varchar(100) DEFAULT NULL,
  `dci_date` date DEFAULT NULL,
  `dci_reference_number` varchar(100) DEFAULT NULL,
  `noc_status` varchar(25) DEFAULT NULL,
  `noc_created_on` datetime DEFAULT NULL,
  `noc_created_by` varchar(250) DEFAULT NULL,
  `noc_last_updated_on` datetime DEFAULT NULL,
  `noc_last_updated_by` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `practitioner_remarks`
--

CREATE TABLE `practitioner_remarks` (
  `practitioner_remarks_id` int(11) NOT NULL,
  `practitioner_id` int(11) DEFAULT NULL,
  `practitioner_remarks` text DEFAULT NULL,
  `practitioner_status` varchar(25) DEFAULT NULL,
  `practitioner_created_by` varchar(250) DEFAULT NULL,
  `practitioner_created_on` datetime DEFAULT NULL,
  `practitioner_last_updated_by` varchar(250) DEFAULT NULL,
  `practitioner_last_updated_on` datetime DEFAULT NULL,
  `practitioner_id_ksdc` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `public_notice`
--

CREATE TABLE `public_notice` (
  `notice_id` int(11) NOT NULL,
  `notice_title` varchar(5000) DEFAULT NULL,
  `notice_file` varchar(50) DEFAULT NULL,
  `notice_status` varchar(25) DEFAULT NULL,
  `notice_created_on` datetime DEFAULT NULL,
  `notice_last_updated_by` varchar(250) DEFAULT NULL,
  `notice_last_updated_on` datetime DEFAULT NULL,
  `notice_created_by` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quick_links`
--

CREATE TABLE `quick_links` (
  `quicklink_id` int(11) NOT NULL,
  `quicklink_name` varchar(5000) DEFAULT NULL,
  `quicklink_url` varchar(500) DEFAULT NULL,
  `quicklink_status` varchar(25) DEFAULT NULL,
  `quicklink_created_by` varchar(250) DEFAULT NULL,
  `quicklink_created_on` datetime DEFAULT NULL,
  `quicklink_last_updated_by` varchar(250) DEFAULT NULL,
  `quicklink_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `razorpay`
--

CREATE TABLE `razorpay` (
  `razorpay_id` int(11) NOT NULL,
  `total_amount` int(11) DEFAULT NULL,
  `practitioner_id` int(11) DEFAULT NULL,
  `payment_status` varchar(25) DEFAULT NULL,
  `payment_date` datetime DEFAULT NULL,
  `order_id` varchar(100) DEFAULT NULL,
  `payment_id` varchar(100) DEFAULT NULL,
  `receipt_number` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receipt`
--

CREATE TABLE `receipt` (
  `receipt_id` int(11) NOT NULL,
  `receipt_total` int(11) DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `receipt_reference_number` varchar(250) DEFAULT NULL,
  `dd_date` date DEFAULT NULL,
  `bank_id_ksdc` varchar(250) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `payment_mode_id` int(11) DEFAULT NULL,
  `receipt_for_id` int(11) DEFAULT NULL,
  `PaymentFor` varchar(25) DEFAULT NULL,
  `receipt_type` varchar(25) DEFAULT NULL,
  `receipt_created_on` datetime DEFAULT NULL,
  `receipt_created_by` varchar(250) DEFAULT NULL,
  `receipt_last_updated_on` datetime DEFAULT NULL,
  `receipt_last_updated_by` varchar(250) DEFAULT NULL,
  `receipt_number` int(11) DEFAULT NULL,
  `receipt_remit_status` varchar(25) DEFAULT NULL,
  `receipt_cancel_date` date DEFAULT NULL,
  `receipt_status` varchar(25) DEFAULT NULL,
  `receipt_remark` text DEFAULT NULL,
  `receipt_remitted_on` date DEFAULT NULL,
  `practitioner_id` int(11) DEFAULT NULL,
  `receipt_cancel_reason` text DEFAULT NULL,
  `RenewalID` varchar(250) DEFAULT NULL,
  `slNo` varchar(250) DEFAULT NULL,
  `Fin_yr_code` varchar(250) DEFAULT NULL,
  `SincID` varchar(250) DEFAULT NULL,
  `DCIDate` varchar(250) DEFAULT NULL,
  `DCIRefNo` varchar(250) DEFAULT NULL,
  `address` varchar(5000) DEFAULT NULL,
  `internship_to` varchar(250) DEFAULT NULL,
  `internship_from` varchar(250) DEFAULT NULL,
  `internship_institution` varchar(250) DEFAULT NULL,
  `PractitionerID` varchar(250) DEFAULT NULL,
  `RenewalDate` date DEFAULT NULL,
  `receipt_validity` date DEFAULT NULL,
  `DD_ChequeNO` varchar(250) DEFAULT NULL,
  `DD_ChequeDate` date DEFAULT NULL,
  `TransactionNo` varchar(250) DEFAULT NULL,
  `TransactionDate` date DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receipt_for_master`
--

CREATE TABLE `receipt_for_master` (
  `receipt_for_id` int(11) NOT NULL,
  `receipt_for` varchar(250) DEFAULT NULL,
  `receipt_for_status` varchar(25) DEFAULT NULL,
  `receipt_created_by` varchar(250) DEFAULT NULL,
  `receipt_created_on` datetime DEFAULT NULL,
  `receipt_last_updated_by` varchar(250) DEFAULT NULL,
  `receipt_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receipt_number_master`
--

CREATE TABLE `receipt_number_master` (
  `receipt_number_master_id` int(11) NOT NULL,
  `receipt_number` int(11) DEFAULT NULL,
  `created_on` timestamp NULL DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receipt_preview`
--

CREATE TABLE `receipt_preview` (
  `receipt_preview_id` int(11) NOT NULL,
  `receipt_total` int(11) DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `receipt_reference_number` varchar(250) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `receipt_for_id` int(11) DEFAULT NULL,
  `receipt_type` varchar(25) DEFAULT NULL,
  `receipt_created_on` datetime DEFAULT NULL,
  `receipt_created_by` varchar(250) DEFAULT NULL,
  `receipt_number_temp` int(11) DEFAULT NULL,
  `receipt_remit_status` varchar(25) DEFAULT NULL,
  `receipt_status` varchar(25) DEFAULT NULL,
  `practitioner_id` int(11) DEFAULT NULL,
  `order_id` varchar(100) DEFAULT NULL,
  `receipt_validity` date DEFAULT NULL,
  `display_by` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receipt_temp`
--

CREATE TABLE `receipt_temp` (
  `receipt_temp_id` int(11) NOT NULL,
  `fees_id_ksdc` int(11) DEFAULT NULL,
  `fees_id` int(11) DEFAULT NULL,
  `total_amount` int(11) DEFAULT NULL,
  `receipt_number` int(11) DEFAULT NULL,
  `RenewalID` varchar(250) DEFAULT NULL,
  `PractitionerID` varchar(250) DEFAULT NULL,
  `CreatedBy` varchar(250) DEFAULT NULL,
  `CreatedOn` date DEFAULT NULL,
  `UpdatedBy` varchar(250) DEFAULT NULL,
  `UpdatedOn` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receipt_temp_preview`
--

CREATE TABLE `receipt_temp_preview` (
  `receipt_temp_preview_id` int(11) NOT NULL,
  `fees_id` int(11) DEFAULT NULL,
  `total_amount` int(11) DEFAULT NULL,
  `receipt_number_temp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registration_type_master`
--

CREATE TABLE `registration_type_master` (
  `registration_type_id` int(11) NOT NULL,
  `registration_type` varchar(50) DEFAULT NULL,
  `registration_type_status` varchar(25) DEFAULT NULL,
  `registration_creted_by` varchar(250) DEFAULT NULL,
  `registration_created_on` datetime DEFAULT NULL,
  `registration_last_updated_by` varchar(250) DEFAULT NULL,
  `registration_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `signature_master`
--

CREATE TABLE `signature_master` (
  `signature_id` int(11) NOT NULL,
  `signature_file` varchar(50) DEFAULT NULL,
  `signature_designation` varchar(250) DEFAULT NULL,
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `signature_status` varchar(25) DEFAULT NULL,
  `signature_created_by` varchar(250) DEFAULT NULL,
  `signature_created_on` datetime DEFAULT NULL,
  `signature_last_updated_by` varchar(250) DEFAULT NULL,
  `signature_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `slider_master`
--

CREATE TABLE `slider_master` (
  `slider_id` int(11) NOT NULL,
  `slider_title` varchar(250) DEFAULT NULL,
  `slider_subtitle` varchar(5000) DEFAULT NULL,
  `slider_image` varchar(50) DEFAULT NULL,
  `slider_status` varchar(250) DEFAULT NULL,
  `slider_created_by` varchar(250) DEFAULT NULL,
  `slider_created_on` datetime DEFAULT NULL,
  `slider_last_updated_by` varchar(250) DEFAULT NULL,
  `slider_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `speaker_master`
--

CREATE TABLE `speaker_master` (
  `speaker_id` int(11) NOT NULL,
  `speaker_name` varchar(250) DEFAULT NULL,
  `speaker_email_address` varchar(250) DEFAULT NULL,
  `speaker_phone_number` varchar(25) DEFAULT NULL,
  `speaker_status` varchar(25) DEFAULT NULL,
  `speaker_created_by` varchar(250) DEFAULT NULL,
  `speaker_created_on` datetime DEFAULT NULL,
  `speaker_last_updated_by` varchar(250) DEFAULT NULL,
  `speaker_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `state_master`
--

CREATE TABLE `state_master` (
  `state_id` int(11) NOT NULL,
  `country_name` varchar(250) DEFAULT NULL,
  `state_name` varchar(250) DEFAULT NULL,
  `state_status` varchar(25) DEFAULT NULL,
  `state_created_by` varchar(250) DEFAULT NULL,
  `state_created_on` datetime DEFAULT NULL,
  `state_last_updated_by` varchar(250) DEFAULT NULL,
  `state_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_master`
--

CREATE TABLE `subject_master` (
  `subject_id` int(11) NOT NULL,
  `subject_code` varchar(15) DEFAULT NULL,
  `subject_name` varchar(250) DEFAULT NULL,
  `subject_status` varchar(25) DEFAULT NULL,
  `subject_created_by` varchar(250) DEFAULT NULL,
  `subject_created_on` datetime DEFAULT NULL,
  `subject_last_updated_on` datetime DEFAULT NULL,
  `subject_last_updated_by` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_renewal_items`
--

CREATE TABLE `tbl_renewal_items` (
  `temp_id` int(11) NOT NULL,
  `RenewalID` varchar(50) NOT NULL,
  `FeeItemId` int(11) NOT NULL,
  `PractitionerID` varchar(50) NOT NULL,
  `FeeAmount` int(11) DEFAULT NULL,
  `CreatedBy` varchar(50) DEFAULT NULL,
  `CreatedOn` datetime DEFAULT NULL,
  `UpdatedBy` varchar(50) DEFAULT NULL,
  `UpdatedOn` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testimonials`
--

CREATE TABLE `testimonials` (
  `testimonial_id` int(11) NOT NULL,
  `testimonial_client_name` varchar(250) DEFAULT NULL,
  `testimonial_client_designation` varchar(250) DEFAULT NULL,
  `testimonial_description` text DEFAULT NULL,
  `testimonial_status` varchar(2500) DEFAULT NULL,
  `testimonial_created_by` varchar(250) DEFAULT NULL,
  `testimonial_created_on` datetime DEFAULT NULL,
  `testimonial_last_updated_by` varchar(250) DEFAULT NULL,
  `testimonial_last_updated_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `token`
--

CREATE TABLE `token` (
  `token_id` int(11) NOT NULL,
  `token` varchar(5000) DEFAULT NULL,
  `device` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `university_master`
--

CREATE TABLE `university_master` (
  `university_id` int(11) NOT NULL,
  `university_name` varchar(250) DEFAULT NULL,
  `university_status` varchar(25) DEFAULT NULL,
  `university_created_by` varchar(250) DEFAULT NULL,
  `university_created_on` datetime DEFAULT NULL,
  `university_last_updated_by` varchar(250) DEFAULT NULL,
  `university_last_updated_on` datetime DEFAULT NULL,
  `university_id_ksdc` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `users_id` int(11) NOT NULL,
  `user_role` varchar(50) DEFAULT NULL,
  `user_name` varchar(250) DEFAULT NULL,
  `user_password` varchar(50) DEFAULT NULL,
  `user_email_id` varchar(250) DEFAULT NULL,
  `user_status` varchar(50) DEFAULT NULL,
  `user_created_on` datetime DEFAULT NULL,
  `user_created_by` varchar(250) DEFAULT NULL,
  `user_last_updated_on` datetime DEFAULT NULL,
  `user_last_updated_by` varchar(250) DEFAULT NULL,
  `user_phone_number` varchar(25) DEFAULT NULL,
  `user_full_name` varchar(250) DEFAULT NULL,
  `user_image` varchar(50) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `voucher`
--

CREATE TABLE `voucher` (
  `voucher_id` int(11) NOT NULL,
  `voucher_total` int(11) NOT NULL,
  `voucher_date` date DEFAULT NULL,
  `voucher_reference_number` varchar(250) DEFAULT NULL,
  `bank_id` int(11) DEFAULT NULL,
  `account_id` int(11) DEFAULT NULL,
  `ledger_id` int(11) DEFAULT NULL,
  `payment_mode_id` int(11) DEFAULT NULL,
  `voucher_created_on` datetime DEFAULT NULL,
  `voucher_created_by` varchar(250) DEFAULT NULL,
  `voucher_last_updated_on` datetime DEFAULT NULL,
  `voucher_last_updated_by` varchar(250) DEFAULT NULL,
  `voucher_number` int(11) DEFAULT NULL,
  `voucher_status` varchar(25) DEFAULT NULL,
  `voucher_cancel_date` date DEFAULT NULL,
  `voucher_remark` text DEFAULT NULL,
  `voucher_cancel_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_master`
--
ALTER TABLE `account_master`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `address_change`
--
ALTER TABLE `address_change`
  ADD PRIMARY KEY (`change_id`);

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`announcement_id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appoint_id`);

--
-- Indexes for table `appointment_master`
--
ALTER TABLE `appointment_master`
  ADD PRIMARY KEY (`appointment_master_id`);

--
-- Indexes for table `bank_balance`
--
ALTER TABLE `bank_balance`
  ADD PRIMARY KEY (`bank_balance_id`);

--
-- Indexes for table `bank_master`
--
ALTER TABLE `bank_master`
  ADD PRIMARY KEY (`bank_id`);

--
-- Indexes for table `budget_master`
--
ALTER TABLE `budget_master`
  ADD PRIMARY KEY (`budget_id`);

--
-- Indexes for table `calender`
--
ALTER TABLE `calender`
  ADD PRIMARY KEY (`calender_id`);

--
-- Indexes for table `cashbook`
--
ALTER TABLE `cashbook`
  ADD PRIMARY KEY (`cashbook_id`);

--
-- Indexes for table `cashbook_for_master`
--
ALTER TABLE `cashbook_for_master`
  ADD PRIMARY KEY (`cashbook_for_id`);

--
-- Indexes for table `cashbook_number_master`
--
ALTER TABLE `cashbook_number_master`
  ADD PRIMARY KEY (`cashbook_number_master_id`);

--
-- Indexes for table `cashbook_receipt`
--
ALTER TABLE `cashbook_receipt`
  ADD PRIMARY KEY (`cashbook_receipt_id`);

--
-- Indexes for table `cash_balance`
--
ALTER TABLE `cash_balance`
  ADD PRIMARY KEY (`cash_balance_id`);

--
-- Indexes for table `category_master`
--
ALTER TABLE `category_master`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `college_master`
--
ALTER TABLE `college_master`
  ADD PRIMARY KEY (`college_id`);

--
-- Indexes for table `complaint`
--
ALTER TABLE `complaint`
  ADD PRIMARY KEY (`complaint_id`);

--
-- Indexes for table `configure`
--
ALTER TABLE `configure`
  ADD PRIMARY KEY (`configure_id`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`contact_id`);

--
-- Indexes for table `coordinator_master`
--
ALTER TABLE `coordinator_master`
  ADD PRIMARY KEY (`coordinator_id`);

--
-- Indexes for table `council_master`
--
ALTER TABLE `council_master`
  ADD PRIMARY KEY (`council_id`);

--
-- Indexes for table `council_state`
--
ALTER TABLE `council_state`
  ADD PRIMARY KEY (`council_state_id`);

--
-- Indexes for table `country_master`
--
ALTER TABLE `country_master`
  ADD PRIMARY KEY (`country_id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `download_master`
--
ALTER TABLE `download_master`
  ADD PRIMARY KEY (`download_id`);

--
-- Indexes for table `education_information`
--
ALTER TABLE `education_information`
  ADD PRIMARY KEY (`education_id`);

--
-- Indexes for table `enquiry`
--
ALTER TABLE `enquiry`
  ADD PRIMARY KEY (`enquiry_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`faq_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`);

--
-- Indexes for table `fees_master`
--
ALTER TABLE `fees_master`
  ADD PRIMARY KEY (`fees_id`);

--
-- Indexes for table `hall_master`
--
ALTER TABLE `hall_master`
  ADD PRIMARY KEY (`hall_id`);

--
-- Indexes for table `ledger_master`
--
ALTER TABLE `ledger_master`
  ADD PRIMARY KEY (`ledger_id`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`member_id`);

--
-- Indexes for table `past_president`
--
ALTER TABLE `past_president`
  ADD PRIMARY KEY (`president_id`);

--
-- Indexes for table `payment_mode`
--
ALTER TABLE `payment_mode`
  ADD PRIMARY KEY (`payment_mode_id`);

--
-- Indexes for table `permission_manager`
--
ALTER TABLE `permission_manager`
  ADD PRIMARY KEY (`permission_id`);

--
-- Indexes for table `permission_particulars`
--
ALTER TABLE `permission_particulars`
  ADD PRIMARY KEY (`permission_particular_id`);

--
-- Indexes for table `practitioner`
--
ALTER TABLE `practitioner`
  ADD PRIMARY KEY (`practitioner_id`);

--
-- Indexes for table `practitioner_address`
--
ALTER TABLE `practitioner_address`
  ADD PRIMARY KEY (`practitioner_address_id`);

--
-- Indexes for table `practitioner_noc`
--
ALTER TABLE `practitioner_noc`
  ADD PRIMARY KEY (`noc_id`);

--
-- Indexes for table `practitioner_remarks`
--
ALTER TABLE `practitioner_remarks`
  ADD PRIMARY KEY (`practitioner_remarks_id`);

--
-- Indexes for table `public_notice`
--
ALTER TABLE `public_notice`
  ADD PRIMARY KEY (`notice_id`);

--
-- Indexes for table `quick_links`
--
ALTER TABLE `quick_links`
  ADD PRIMARY KEY (`quicklink_id`);

--
-- Indexes for table `razorpay`
--
ALTER TABLE `razorpay`
  ADD PRIMARY KEY (`razorpay_id`);

--
-- Indexes for table `receipt`
--
ALTER TABLE `receipt`
  ADD PRIMARY KEY (`receipt_id`);

--
-- Indexes for table `receipt_for_master`
--
ALTER TABLE `receipt_for_master`
  ADD PRIMARY KEY (`receipt_for_id`);

--
-- Indexes for table `receipt_number_master`
--
ALTER TABLE `receipt_number_master`
  ADD PRIMARY KEY (`receipt_number_master_id`);

--
-- Indexes for table `receipt_preview`
--
ALTER TABLE `receipt_preview`
  ADD PRIMARY KEY (`receipt_preview_id`);

--
-- Indexes for table `receipt_temp`
--
ALTER TABLE `receipt_temp`
  ADD PRIMARY KEY (`receipt_temp_id`);

--
-- Indexes for table `receipt_temp_preview`
--
ALTER TABLE `receipt_temp_preview`
  ADD PRIMARY KEY (`receipt_temp_preview_id`);

--
-- Indexes for table `registration_type_master`
--
ALTER TABLE `registration_type_master`
  ADD PRIMARY KEY (`registration_type_id`);

--
-- Indexes for table `signature_master`
--
ALTER TABLE `signature_master`
  ADD PRIMARY KEY (`signature_id`);

--
-- Indexes for table `slider_master`
--
ALTER TABLE `slider_master`
  ADD PRIMARY KEY (`slider_id`);

--
-- Indexes for table `speaker_master`
--
ALTER TABLE `speaker_master`
  ADD PRIMARY KEY (`speaker_id`);

--
-- Indexes for table `state_master`
--
ALTER TABLE `state_master`
  ADD PRIMARY KEY (`state_id`);

--
-- Indexes for table `subject_master`
--
ALTER TABLE `subject_master`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `tbl_renewal_items`
--
ALTER TABLE `tbl_renewal_items`
  ADD PRIMARY KEY (`temp_id`);

--
-- Indexes for table `testimonials`
--
ALTER TABLE `testimonials`
  ADD PRIMARY KEY (`testimonial_id`);

--
-- Indexes for table `token`
--
ALTER TABLE `token`
  ADD PRIMARY KEY (`token_id`);

--
-- Indexes for table `university_master`
--
ALTER TABLE `university_master`
  ADD PRIMARY KEY (`university_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`users_id`);

--
-- Indexes for table `voucher`
--
ALTER TABLE `voucher`
  ADD PRIMARY KEY (`voucher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_master`
--
ALTER TABLE `account_master`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `address_change`
--
ALTER TABLE `address_change`
  MODIFY `change_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `announcement`
--
ALTER TABLE `announcement`
  MODIFY `announcement_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appoint_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointment_master`
--
ALTER TABLE `appointment_master`
  MODIFY `appointment_master_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_balance`
--
ALTER TABLE `bank_balance`
  MODIFY `bank_balance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bank_master`
--
ALTER TABLE `bank_master`
  MODIFY `bank_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budget_master`
--
ALTER TABLE `budget_master`
  MODIFY `budget_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `calender`
--
ALTER TABLE `calender`
  MODIFY `calender_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cashbook`
--
ALTER TABLE `cashbook`
  MODIFY `cashbook_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cashbook_for_master`
--
ALTER TABLE `cashbook_for_master`
  MODIFY `cashbook_for_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cashbook_number_master`
--
ALTER TABLE `cashbook_number_master`
  MODIFY `cashbook_number_master_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cashbook_receipt`
--
ALTER TABLE `cashbook_receipt`
  MODIFY `cashbook_receipt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash_balance`
--
ALTER TABLE `cash_balance`
  MODIFY `cash_balance_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category_master`
--
ALTER TABLE `category_master`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `college_master`
--
ALTER TABLE `college_master`
  MODIFY `college_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complaint`
--
ALTER TABLE `complaint`
  MODIFY `complaint_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `configure`
--
ALTER TABLE `configure`
  MODIFY `configure_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `contact_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coordinator_master`
--
ALTER TABLE `coordinator_master`
  MODIFY `coordinator_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `council_master`
--
ALTER TABLE `council_master`
  MODIFY `council_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `council_state`
--
ALTER TABLE `council_state`
  MODIFY `council_state_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `country_master`
--
ALTER TABLE `country_master`
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `course_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `download_master`
--
ALTER TABLE `download_master`
  MODIFY `download_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `education_information`
--
ALTER TABLE `education_information`
  MODIFY `education_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `enquiry`
--
ALTER TABLE `enquiry`
  MODIFY `enquiry_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `faq_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fees_master`
--
ALTER TABLE `fees_master`
  MODIFY `fees_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hall_master`
--
ALTER TABLE `hall_master`
  MODIFY `hall_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ledger_master`
--
ALTER TABLE `ledger_master`
  MODIFY `ledger_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `past_president`
--
ALTER TABLE `past_president`
  MODIFY `president_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_mode`
--
ALTER TABLE `payment_mode`
  MODIFY `payment_mode_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permission_manager`
--
ALTER TABLE `permission_manager`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permission_particulars`
--
ALTER TABLE `permission_particulars`
  MODIFY `permission_particular_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `practitioner`
--
ALTER TABLE `practitioner`
  MODIFY `practitioner_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `practitioner_address`
--
ALTER TABLE `practitioner_address`
  MODIFY `practitioner_address_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `practitioner_noc`
--
ALTER TABLE `practitioner_noc`
  MODIFY `noc_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `practitioner_remarks`
--
ALTER TABLE `practitioner_remarks`
  MODIFY `practitioner_remarks_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `public_notice`
--
ALTER TABLE `public_notice`
  MODIFY `notice_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quick_links`
--
ALTER TABLE `quick_links`
  MODIFY `quicklink_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `razorpay`
--
ALTER TABLE `razorpay`
  MODIFY `razorpay_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receipt`
--
ALTER TABLE `receipt`
  MODIFY `receipt_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receipt_for_master`
--
ALTER TABLE `receipt_for_master`
  MODIFY `receipt_for_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receipt_number_master`
--
ALTER TABLE `receipt_number_master`
  MODIFY `receipt_number_master_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receipt_preview`
--
ALTER TABLE `receipt_preview`
  MODIFY `receipt_preview_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receipt_temp`
--
ALTER TABLE `receipt_temp`
  MODIFY `receipt_temp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receipt_temp_preview`
--
ALTER TABLE `receipt_temp_preview`
  MODIFY `receipt_temp_preview_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registration_type_master`
--
ALTER TABLE `registration_type_master`
  MODIFY `registration_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `signature_master`
--
ALTER TABLE `signature_master`
  MODIFY `signature_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `slider_master`
--
ALTER TABLE `slider_master`
  MODIFY `slider_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `speaker_master`
--
ALTER TABLE `speaker_master`
  MODIFY `speaker_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `state_master`
--
ALTER TABLE `state_master`
  MODIFY `state_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subject_master`
--
ALTER TABLE `subject_master`
  MODIFY `subject_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_renewal_items`
--
ALTER TABLE `tbl_renewal_items`
  MODIFY `temp_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `testimonials`
--
ALTER TABLE `testimonials`
  MODIFY `testimonial_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `token`
--
ALTER TABLE `token`
  MODIFY `token_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `university_master`
--
ALTER TABLE `university_master`
  MODIFY `university_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `users_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `voucher`
--
ALTER TABLE `voucher`
  MODIFY `voucher_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
