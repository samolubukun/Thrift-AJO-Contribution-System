-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2025 at 04:59 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.1.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `thrift_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `email`, `password`, `created_at`) VALUES
(2, 'samuelolubukun@gmail.com', '$2y$10$LJG/HKB1Ialp2pd2.ArRD.q.Mq38M/V0pNbWKDaEGs/JHMVo2Pb2a', '2025-04-27 17:22:54'),
(3, 'admin.thriftcontribution@gmail.com', '$2y$10$PE3oVyTKTsbyZXw96i6jYex9BHqcAFNtOdRMBnh0VvnoRzKXSukiu', '2025-04-28 06:51:10'),
(4, 'jedjedi@gmail.com', '$2y$10$VhOvmkpV0FmTSJQK4Iz5IuOMqyeWpNtJAhr5IE2K9e88mgCoNFxgu', '2025-04-28 07:46:25');

-- --------------------------------------------------------

--
-- Table structure for table `contributions`
--

CREATE TABLE `contributions` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `member_name` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date_of_contribution` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contributions`
--

INSERT INTO `contributions` (`id`, `member_id`, `member_name`, `amount`, `date_of_contribution`) VALUES
(1, 1, 'Chinedu Adekoya', '5000.00', '2024-09-04 17:31:58'),
(2, 2, 'Chinedu Afolabi', '5000.00', '2024-09-28 12:40:58'),
(3, 3, 'Chiamaka Oyedepo', '5000.00', '2024-09-09 17:27:40'),
(4, 4, 'Chiamaka Nwosu', '5000.00', '2024-09-12 15:52:42'),
(5, 5, 'Kayode Adeyemi', '5000.00', '2024-09-16 14:58:41'),
(6, 6, 'Musa Akinola', '5000.00', '2024-09-08 08:31:58'),
(7, 7, 'Rotimi Njoku', '5000.00', '2024-09-18 08:53:05'),
(8, 8, 'Rotimi Adeyemi', '5000.00', '2024-09-16 14:30:57'),
(9, 9, 'Temitope Nwosu', '5000.00', '2024-09-22 17:58:17'),
(10, 10, 'Ikechukwu Adeleke', '5000.00', '2024-09-02 17:50:43'),
(11, 11, 'Rotimi Olarewaju', '5000.00', '2024-09-21 09:12:10'),
(12, 12, 'Chioma Oyedepo', '5000.00', '2024-09-18 18:31:17'),
(13, 13, 'Rotimi Ezinwa', '10000.00', '2024-09-01 13:14:50'),
(14, 14, 'Bukola Afolabi', '10000.00', '2024-09-21 08:27:36'),
(15, 15, 'Chiamaka Njoku', '10000.00', '2024-09-08 15:52:39'),
(16, 16, 'Ikechukwu Oyedepo', '10000.00', '2024-09-17 18:19:48'),
(17, 17, 'Segun Okafor', '10000.00', '2024-09-08 16:11:27'),
(18, 18, 'Adebayo Nwosu', '10000.00', '2024-09-28 19:34:17'),
(19, 19, 'Adebayo Nwachukwu', '10000.00', '2024-09-09 14:36:22'),
(20, 20, 'Chioma Nwosu', '10000.00', '2024-09-21 14:46:59'),
(21, 21, 'Nneka Olarewaju', '20000.00', '2024-09-20 17:15:29'),
(22, 22, 'Chiamaka Chukwu', '20000.00', '2024-09-14 19:48:57'),
(23, 23, 'Blessing Ogunleye', '20000.00', '2024-09-27 08:09:28'),
(24, 24, 'Obinna Eze', '20000.00', '2024-09-23 09:17:58'),
(25, 25, 'Emeka Ezinwa', '20000.00', '2024-09-14 17:51:04'),
(26, 26, 'Chiamaka Afolabi', '20000.00', '2024-09-11 11:04:19'),
(27, 27, 'Oluwaseun Nwosu', '20000.00', '2024-09-18 20:51:58'),
(28, 1, 'Chinedu Adekoya', '5000.00', '2024-10-07 08:44:57'),
(29, 2, 'Chinedu Afolabi', '5000.00', '2024-10-02 17:05:15'),
(30, 3, 'Chiamaka Oyedepo', '5000.00', '2024-10-23 13:19:24'),
(31, 4, 'Chiamaka Nwosu', '5000.00', '2024-10-06 10:24:10'),
(32, 5, 'Kayode Adeyemi', '5000.00', '2024-10-13 19:18:42'),
(33, 6, 'Musa Akinola', '5000.00', '2024-10-11 10:24:34'),
(34, 7, 'Rotimi Njoku', '5000.00', '2024-10-11 20:13:19'),
(35, 8, 'Rotimi Adeyemi', '5000.00', '2024-10-01 18:16:24'),
(36, 9, 'Temitope Nwosu', '5000.00', '2024-10-11 12:46:59'),
(37, 10, 'Ikechukwu Adeleke', '5000.00', '2024-10-19 12:11:15'),
(38, 11, 'Rotimi Olarewaju', '5000.00', '2024-10-10 17:49:54'),
(39, 12, 'Chioma Oyedepo', '5000.00', '2024-10-08 18:15:11'),
(40, 13, 'Rotimi Ezinwa', '10000.00', '2024-10-14 10:00:23'),
(41, 14, 'Bukola Afolabi', '10000.00', '2024-10-05 14:10:27'),
(42, 15, 'Chiamaka Njoku', '10000.00', '2024-10-08 13:09:24'),
(43, 16, 'Ikechukwu Oyedepo', '10000.00', '2024-10-22 17:41:12'),
(44, 17, 'Segun Okafor', '10000.00', '2024-10-19 15:59:41'),
(45, 18, 'Adebayo Nwosu', '10000.00', '2024-10-18 12:11:31'),
(46, 19, 'Adebayo Nwachukwu', '10000.00', '2024-10-15 14:44:47'),
(47, 20, 'Chioma Nwosu', '10000.00', '2024-10-02 16:36:50'),
(48, 21, 'Nneka Olarewaju', '20000.00', '2024-10-03 19:40:59'),
(49, 22, 'Chiamaka Chukwu', '20000.00', '2024-10-11 08:02:27'),
(50, 23, 'Blessing Ogunleye', '20000.00', '2024-10-21 20:20:52'),
(51, 24, 'Obinna Eze', '20000.00', '2024-10-16 19:07:54'),
(52, 25, 'Emeka Ezinwa', '20000.00', '2024-10-07 11:34:05'),
(53, 26, 'Chiamaka Afolabi', '20000.00', '2024-10-12 13:16:11'),
(54, 27, 'Oluwaseun Nwosu', '20000.00', '2024-10-07 20:18:59'),
(55, 1, 'Chinedu Adekoya', '5000.00', '2024-11-06 13:08:12'),
(56, 2, 'Chinedu Afolabi', '5000.00', '2024-11-14 09:01:07'),
(57, 3, 'Chiamaka Oyedepo', '5000.00', '2024-11-13 19:32:08'),
(58, 4, 'Chiamaka Nwosu', '5000.00', '2024-11-17 10:34:09'),
(59, 5, 'Kayode Adeyemi', '5000.00', '2024-11-05 18:51:45'),
(60, 6, 'Musa Akinola', '5000.00', '2024-11-14 19:00:32'),
(61, 7, 'Rotimi Njoku', '5000.00', '2024-11-19 17:18:37'),
(62, 8, 'Rotimi Adeyemi', '5000.00', '2024-11-16 16:52:04'),
(63, 9, 'Temitope Nwosu', '5000.00', '2024-11-02 08:32:53'),
(64, 10, 'Ikechukwu Adeleke', '5000.00', '2024-11-11 12:16:42'),
(65, 11, 'Rotimi Olarewaju', '5000.00', '2024-11-24 13:21:22'),
(66, 12, 'Chioma Oyedepo', '5000.00', '2024-11-17 18:11:15'),
(67, 13, 'Rotimi Ezinwa', '10000.00', '2024-11-06 17:28:37'),
(68, 14, 'Bukola Afolabi', '10000.00', '2024-11-22 10:13:08'),
(69, 15, 'Chiamaka Njoku', '10000.00', '2024-11-02 12:08:08'),
(70, 16, 'Ikechukwu Oyedepo', '10000.00', '2024-11-14 13:09:00'),
(71, 17, 'Segun Okafor', '10000.00', '2024-11-04 14:05:14'),
(72, 18, 'Adebayo Nwosu', '10000.00', '2024-11-05 17:45:33'),
(73, 19, 'Adebayo Nwachukwu', '10000.00', '2024-11-04 19:03:06'),
(74, 20, 'Chioma Nwosu', '10000.00', '2024-11-11 14:34:06'),
(75, 21, 'Nneka Olarewaju', '20000.00', '2024-11-25 10:47:20'),
(76, 22, 'Chiamaka Chukwu', '20000.00', '2024-11-18 09:59:27'),
(77, 23, 'Blessing Ogunleye', '20000.00', '2024-11-08 17:44:57'),
(78, 24, 'Obinna Eze', '20000.00', '2024-11-03 18:35:18'),
(79, 25, 'Emeka Ezinwa', '20000.00', '2024-11-20 14:09:01'),
(80, 26, 'Chiamaka Afolabi', '20000.00', '2024-11-22 19:39:58'),
(81, 27, 'Oluwaseun Nwosu', '20000.00', '2024-11-15 20:29:44'),
(82, 1, 'Chinedu Adekoya', '5000.00', '2024-12-23 20:29:41'),
(83, 2, 'Chinedu Afolabi', '5000.00', '2024-12-27 16:17:07'),
(84, 3, 'Chiamaka Oyedepo', '5000.00', '2024-12-15 09:01:36'),
(85, 4, 'Chiamaka Nwosu', '5000.00', '2024-12-15 20:52:58'),
(86, 5, 'Kayode Adeyemi', '5000.00', '2024-12-15 17:48:20'),
(87, 6, 'Musa Akinola', '5000.00', '2024-12-22 15:17:48'),
(88, 7, 'Rotimi Njoku', '5000.00', '2024-12-17 11:48:19'),
(89, 8, 'Rotimi Adeyemi', '5000.00', '2024-12-12 13:56:14'),
(90, 9, 'Temitope Nwosu', '5000.00', '2024-12-11 14:47:00'),
(91, 10, 'Ikechukwu Adeleke', '5000.00', '2024-12-03 08:41:19'),
(92, 11, 'Rotimi Olarewaju', '5000.00', '2024-12-07 15:31:07'),
(93, 12, 'Chioma Oyedepo', '5000.00', '2024-12-20 11:43:49'),
(94, 13, 'Rotimi Ezinwa', '10000.00', '2024-12-24 13:36:01'),
(95, 14, 'Bukola Afolabi', '10000.00', '2024-12-27 09:54:51'),
(96, 15, 'Chiamaka Njoku', '10000.00', '2024-12-06 16:34:21'),
(97, 16, 'Ikechukwu Oyedepo', '10000.00', '2024-12-09 09:40:42'),
(98, 17, 'Segun Okafor', '10000.00', '2024-12-08 11:20:43'),
(99, 18, 'Adebayo Nwosu', '10000.00', '2024-12-06 09:46:45'),
(100, 19, 'Adebayo Nwachukwu', '10000.00', '2024-12-03 17:22:39'),
(101, 20, 'Chioma Nwosu', '10000.00', '2024-12-25 13:46:45'),
(102, 21, 'Nneka Olarewaju', '20000.00', '2024-12-09 19:39:46'),
(103, 22, 'Chiamaka Chukwu', '20000.00', '2024-12-23 16:47:33'),
(104, 23, 'Blessing Ogunleye', '20000.00', '2024-12-24 08:25:17'),
(105, 24, 'Obinna Eze', '20000.00', '2024-12-10 15:39:14'),
(106, 25, 'Emeka Ezinwa', '20000.00', '2024-12-22 18:45:54'),
(107, 26, 'Chiamaka Afolabi', '20000.00', '2024-12-07 15:36:22'),
(108, 27, 'Oluwaseun Nwosu', '20000.00', '2024-12-06 10:17:01'),
(109, 1, 'Chinedu Adekoya', '5000.00', '2025-01-24 13:44:07'),
(110, 2, 'Chinedu Afolabi', '5000.00', '2025-01-14 17:02:59'),
(111, 3, 'Chiamaka Oyedepo', '5000.00', '2025-01-01 19:00:52'),
(112, 4, 'Chiamaka Nwosu', '5000.00', '2025-01-10 10:49:06'),
(113, 5, 'Kayode Adeyemi', '5000.00', '2025-01-18 18:28:19'),
(114, 6, 'Musa Akinola', '5000.00', '2025-01-12 10:08:26'),
(115, 7, 'Rotimi Njoku', '5000.00', '2025-01-14 11:46:48'),
(116, 8, 'Rotimi Adeyemi', '5000.00', '2025-01-06 19:10:12'),
(117, 9, 'Temitope Nwosu', '5000.00', '2025-01-14 18:03:32'),
(118, 10, 'Ikechukwu Adeleke', '5000.00', '2025-01-26 14:41:37'),
(119, 11, 'Rotimi Olarewaju', '5000.00', '2025-01-09 11:41:28'),
(120, 12, 'Chioma Oyedepo', '5000.00', '2025-01-08 19:11:46'),
(121, 13, 'Rotimi Ezinwa', '10000.00', '2025-01-05 12:59:35'),
(122, 14, 'Bukola Afolabi', '10000.00', '2025-01-06 08:04:41'),
(123, 15, 'Chiamaka Njoku', '10000.00', '2025-01-03 14:27:30'),
(124, 16, 'Ikechukwu Oyedepo', '10000.00', '2025-01-24 10:34:25'),
(125, 17, 'Segun Okafor', '10000.00', '2025-01-01 11:05:13'),
(126, 18, 'Adebayo Nwosu', '10000.00', '2025-01-25 18:11:20'),
(127, 19, 'Adebayo Nwachukwu', '10000.00', '2025-01-25 17:18:14'),
(128, 20, 'Chioma Nwosu', '10000.00', '2025-01-09 12:23:01'),
(129, 21, 'Nneka Olarewaju', '20000.00', '2025-01-25 12:14:42'),
(130, 22, 'Chiamaka Chukwu', '20000.00', '2025-01-12 19:53:10'),
(131, 23, 'Blessing Ogunleye', '20000.00', '2025-01-27 10:39:12'),
(132, 24, 'Obinna Eze', '20000.00', '2025-01-18 17:20:30'),
(133, 25, 'Emeka Ezinwa', '20000.00', '2025-01-25 13:29:06'),
(134, 26, 'Chiamaka Afolabi', '20000.00', '2025-01-27 18:12:04'),
(135, 27, 'Oluwaseun Nwosu', '20000.00', '2025-01-27 16:24:37'),
(136, 1, 'Chinedu Adekoya', '5000.00', '2025-02-05 08:21:38'),
(137, 2, 'Chinedu Afolabi', '5000.00', '2025-02-01 09:52:33'),
(138, 3, 'Chiamaka Oyedepo', '5000.00', '2025-02-24 14:16:10'),
(139, 4, 'Chiamaka Nwosu', '5000.00', '2025-02-12 15:07:08'),
(140, 5, 'Kayode Adeyemi', '5000.00', '2025-02-03 08:53:44'),
(141, 6, 'Musa Akinola', '5000.00', '2025-02-18 09:01:44'),
(142, 7, 'Rotimi Njoku', '5000.00', '2025-02-22 12:35:11'),
(143, 8, 'Rotimi Adeyemi', '5000.00', '2025-02-08 12:54:54'),
(144, 9, 'Temitope Nwosu', '5000.00', '2025-02-23 20:16:29'),
(145, 10, 'Ikechukwu Adeleke', '5000.00', '2025-02-21 09:15:46'),
(146, 11, 'Rotimi Olarewaju', '5000.00', '2025-02-01 19:30:54'),
(147, 12, 'Chioma Oyedepo', '5000.00', '2025-02-09 10:22:59'),
(148, 13, 'Rotimi Ezinwa', '10000.00', '2025-02-10 19:33:37'),
(149, 14, 'Bukola Afolabi', '10000.00', '2025-02-01 18:53:04'),
(150, 15, 'Chiamaka Njoku', '10000.00', '2025-02-17 19:23:23'),
(151, 16, 'Ikechukwu Oyedepo', '10000.00', '2025-02-03 19:09:34'),
(152, 17, 'Segun Okafor', '10000.00', '2025-02-13 20:49:04'),
(153, 18, 'Adebayo Nwosu', '10000.00', '2025-02-16 19:54:41'),
(154, 19, 'Adebayo Nwachukwu', '10000.00', '2025-02-06 20:51:42'),
(155, 20, 'Chioma Nwosu', '10000.00', '2025-02-26 11:15:06'),
(156, 21, 'Nneka Olarewaju', '20000.00', '2025-02-21 20:52:28'),
(157, 22, 'Chiamaka Chukwu', '20000.00', '2025-02-11 12:12:32'),
(158, 23, 'Blessing Ogunleye', '20000.00', '2025-02-21 11:31:52'),
(159, 24, 'Obinna Eze', '20000.00', '2025-02-15 13:01:10'),
(160, 25, 'Emeka Ezinwa', '20000.00', '2025-02-12 18:18:32'),
(161, 26, 'Chiamaka Afolabi', '20000.00', '2025-02-03 17:46:40'),
(162, 27, 'Oluwaseun Nwosu', '20000.00', '2025-02-27 13:10:24'),
(163, 1, 'Chinedu Adekoya', '5000.00', '2025-03-15 08:08:47'),
(164, 2, 'Chinedu Afolabi', '5000.00', '2025-03-26 14:25:01'),
(165, 3, 'Chiamaka Oyedepo', '5000.00', '2025-03-27 12:38:58'),
(166, 4, 'Chiamaka Nwosu', '5000.00', '2025-03-26 08:52:17'),
(167, 5, 'Kayode Adeyemi', '5000.00', '2025-03-14 17:16:10'),
(168, 6, 'Musa Akinola', '5000.00', '2025-03-21 18:35:49'),
(169, 7, 'Rotimi Njoku', '5000.00', '2025-03-16 15:58:56'),
(170, 8, 'Rotimi Adeyemi', '5000.00', '2025-03-08 13:28:53'),
(171, 9, 'Temitope Nwosu', '5000.00', '2025-03-07 20:09:52'),
(172, 10, 'Ikechukwu Adeleke', '5000.00', '2025-03-12 09:05:09'),
(173, 11, 'Rotimi Olarewaju', '5000.00', '2025-03-05 12:53:28'),
(174, 12, 'Chioma Oyedepo', '5000.00', '2025-03-23 16:12:14'),
(175, 13, 'Rotimi Ezinwa', '10000.00', '2025-03-08 16:48:52'),
(176, 14, 'Bukola Afolabi', '10000.00', '2025-03-13 19:13:13'),
(177, 15, 'Chiamaka Njoku', '10000.00', '2025-03-19 08:03:56'),
(178, 16, 'Ikechukwu Oyedepo', '10000.00', '2025-03-03 15:51:00'),
(179, 17, 'Segun Okafor', '10000.00', '2025-03-09 10:12:10'),
(180, 18, 'Adebayo Nwosu', '10000.00', '2025-03-20 16:04:17'),
(181, 19, 'Adebayo Nwachukwu', '10000.00', '2025-03-23 16:40:58'),
(182, 20, 'Chioma Nwosu', '10000.00', '2025-03-12 13:25:34'),
(183, 21, 'Nneka Olarewaju', '20000.00', '2025-03-02 08:43:34'),
(184, 22, 'Chiamaka Chukwu', '20000.00', '2025-03-09 09:52:22'),
(185, 23, 'Blessing Ogunleye', '20000.00', '2025-03-15 12:19:49'),
(186, 24, 'Obinna Eze', '20000.00', '2025-03-28 09:17:49'),
(187, 25, 'Emeka Ezinwa', '20000.00', '2025-03-06 20:53:20'),
(188, 26, 'Chiamaka Afolabi', '20000.00', '2025-03-23 20:46:48'),
(189, 27, 'Oluwaseun Nwosu', '20000.00', '2025-03-12 20:27:23'),
(190, 28, 'Hunter King', '20000.00', '2025-04-28 09:37:04');

-- --------------------------------------------------------

--
-- Table structure for table `fund_distribution`
--

CREATE TABLE `fund_distribution` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `distribution_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fund_distribution`
--

INSERT INTO `fund_distribution` (`id`, `group_id`, `member_id`, `amount`, `distribution_date`) VALUES
(1, 1, 1, '60000.00', '2024-09-27'),
(2, 1, 2, '60000.00', '2024-10-27'),
(3, 1, 3, '60000.00', '2024-11-27'),
(4, 1, 4, '60000.00', '2024-12-27'),
(5, 1, 5, '60000.00', '2025-01-27'),
(6, 1, 6, '60000.00', '2025-02-27'),
(7, 1, 7, '60000.00', '2025-03-27'),
(8, 2, 13, '80000.00', '2024-09-27'),
(9, 2, 14, '80000.00', '2024-10-27'),
(10, 2, 15, '80000.00', '2024-11-27'),
(11, 2, 16, '80000.00', '2024-12-27'),
(12, 2, 17, '80000.00', '2025-01-27'),
(13, 2, 18, '80000.00', '2025-02-27'),
(14, 2, 19, '80000.00', '2025-03-27'),
(15, 3, 21, '140000.00', '2024-09-27'),
(16, 3, 22, '140000.00', '2024-10-27'),
(17, 3, 23, '140000.00', '2024-11-27'),
(18, 3, 24, '140000.00', '2024-12-27'),
(19, 3, 25, '140000.00', '2025-01-27'),
(20, 3, 26, '140000.00', '2025-02-27'),
(21, 3, 27, '140000.00', '2025-03-27'),
(22, 1, 2, '55000.00', '2025-04-28');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `current_number_of_members` int(11) NOT NULL DEFAULT 0,
  `max_members` int(11) NOT NULL DEFAULT 12,
  `date_created` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `amount`, `current_number_of_members`, `max_members`, `date_created`) VALUES
(1, 'Ajo Cooperative', '5000.00', 11, 12, '2024-09-01'),
(2, 'Esusu Group', '10000.00', 8, 12, '2024-09-01'),
(3, 'Adashi Union', '20000.00', 9, 12, '2024-09-01');

-- --------------------------------------------------------

--
-- Table structure for table `group_join_requests`
--

CREATE TABLE `group_join_requests` (
  `id` int(11) UNSIGNED NOT NULL,
  `member_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `request_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group_join_requests`
--

INSERT INTO `group_join_requests` (`id`, `member_id`, `group_id`, `request_date`, `status`) VALUES
(1, 28, 3, '2025-04-28 07:35:01', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `date_joined` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `group_members`
--

INSERT INTO `group_members` (`id`, `group_id`, `member_id`, `date_joined`) VALUES
(2, 1, 2, '2024-08-31 23:00:00'),
(3, 1, 3, '2024-08-31 23:00:00'),
(4, 1, 4, '2024-08-31 23:00:00'),
(5, 1, 5, '2024-08-31 23:00:00'),
(6, 1, 6, '2024-08-31 23:00:00'),
(7, 1, 7, '2024-08-31 23:00:00'),
(8, 1, 8, '2024-08-31 23:00:00'),
(9, 1, 9, '2024-08-31 23:00:00'),
(10, 1, 10, '2024-08-31 23:00:00'),
(11, 1, 11, '2024-08-31 23:00:00'),
(12, 1, 12, '2024-08-31 23:00:00'),
(13, 2, 13, '2024-08-31 23:00:00'),
(14, 2, 14, '2024-08-31 23:00:00'),
(15, 2, 15, '2024-08-31 23:00:00'),
(16, 2, 16, '2024-08-31 23:00:00'),
(17, 2, 17, '2024-08-31 23:00:00'),
(18, 2, 18, '2024-08-31 23:00:00'),
(19, 2, 19, '2024-08-31 23:00:00'),
(20, 2, 20, '2024-08-31 23:00:00'),
(21, 3, 21, '2024-08-31 23:00:00'),
(22, 3, 22, '2024-08-31 23:00:00'),
(23, 3, 23, '2024-08-31 23:00:00'),
(24, 3, 24, '2024-08-31 23:00:00'),
(25, 3, 25, '2024-08-31 23:00:00'),
(26, 3, 26, '2024-08-31 23:00:00'),
(27, 3, 27, '2024-08-31 23:00:00'),
(29, 3, 28, '2025-04-28 07:36:14'),
(30, 3, 1, '2025-04-28 07:39:39');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `contribution_plan` decimal(10,2) NOT NULL,
  `contribution_status` enum('Pending','Contributed') DEFAULT 'Pending',
  `rotation_order` int(11) NOT NULL,
  `date_joined` timestamp NOT NULL DEFAULT current_timestamp(),
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_account_number` varchar(20) DEFAULT NULL,
  `bank_code` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `first_name`, `middle_name`, `last_name`, `email`, `password`, `phone_number`, `address`, `profile_picture`, `contribution_plan`, `contribution_status`, `rotation_order`, `date_joined`, `bank_name`, `bank_account_number`, `bank_code`) VALUES
(1, 'Chinedu', 'Ifeoma', 'Adekoya', 'chinedu.adekoya@gmail.com', '$2y$10$5mU.JLIp1wU8O4Ck87kugenGqzrNV3Ktf6458M5/vIy3l6AOsY0wu', '08098905105', '106 Mobolaji Bank Anthony Way, Osubi, Warri', NULL, '5000.00', 'Contributed', 1, '2024-08-31 23:00:00', 'Diamond Bank Plc', '5503763952', '63150162'),
(2, 'Chinedu', 'Ifeoma', 'Afolabi', 'chinedu.afolabi@gmail.com', '$2y$10$mJW6u.yZQ8mdMg3PhR9Y..DjB2Puo2HPvIuHthsDKDRr4Ge0fhkiq', '08097184729', '112 Bourdillon Road, Aduwawa, Benin City', NULL, '5000.00', 'Pending', 13, '2024-08-31 23:00:00', 'First City Monument Bank Plc', '9680282666', '214150018'),
(3, 'Chiamaka', 'Chidimma', 'Oyedepo', 'chiamaka.oyedepo@gmail.com', '$2y$10$.9YpjFOkFsDXuE1tbTGWfeczGY4.SaGHnQl5mb57FNNPZyLDVuk9C', '08020719328', '49 Opebi Road, Kumbotso, Kano', NULL, '5000.00', 'Pending', 1, '2024-08-31 23:00:00', 'Polaris Bank Plc', '1809203940', '76151006'),
(4, 'Chiamaka', 'Obioma', 'Nwosu', 'chiamaka.nwosu@gmail.com', '$2y$10$IGPjJalSO9akRqlnI.p1Ae2CU3w.8cjPybdbUQnCteEQYH3TXC8ci', '08040742656', '74 Awolowo Road, GRA, Port Harcourt', NULL, '5000.00', 'Pending', 2, '2024-08-31 23:00:00', 'ECOBank', '6698678411', '56080016'),
(5, 'Kayode', 'Damilola', 'Adeyemi', 'kayode.adeyemi@gmail.com', '$2y$10$d.Utf57ERiLPBAWKJZOFReiuCFkSslqYI5diAUmJra.YQHjJnknUS', '08051357035', '78 Opebi Road, Diobu, Port Harcourt', NULL, '5000.00', 'Pending', 3, '2024-08-31 23:00:00', 'Polaris Bank Plc', '4539687953', '76151006'),
(6, 'Musa', 'Chizoba', 'Akinola', 'musa.akinola@gmail.com', '$2y$10$EeEH9ypq6O7LevajERaWaOLXvaNAgPYpJ53xvqALH5jmBGsIrzdcq', '08091647899', '108 Bourdillon Road, Gwarinpa, Abuja', NULL, '5000.00', 'Pending', 4, '2024-08-31 23:00:00', 'Equitorial Trust Bank Limited', '3510612394', '40150101'),
(7, 'Rotimi', 'Folashade', 'Njoku', 'rotimi.njoku@gmail.com', '$2y$10$ZUKxCdLEs5s5HkuRr81njuR2IOOaJb2ET6lv9eW13V8aJ54WWExQG', '08095137132', '100 Ahmadu Bello Way, Big Qua, Calabar', NULL, '5000.00', 'Pending', 5, '2024-08-31 23:00:00', 'First City Monument Bank Plc', '3567603873', '214150018'),
(8, 'Rotimi', 'Olabisi', 'Adeyemi', 'rotimi.adeyemi@gmail.com', '$2y$10$nMTVsE7/km7VVMSobRyDzuRGf40LB8JIfo4IRPg5sIT4C.JI43GF2', '08028262767', '141 Akin Adesola Street, Mokola, Ibadan', NULL, '5000.00', 'Pending', 6, '2024-08-31 23:00:00', 'Stanbic-Ibtc Bank Plc', '8242614271', '221159522'),
(9, 'Temitope', 'Chukwuma', 'Nwosu', 'temitope.nwosu@gmail.com', '$2y$10$RrLfzvdSm18TPsaR0fJ6tOZZIJLUe7ablQhqL/4QRddCIZN2XbTvW', '08045921682', '104 Bourdillon Road, D-Line, Port Harcourt', NULL, '5000.00', 'Pending', 7, '2024-08-31 23:00:00', 'Diamond Bank Plc', '5306788415', '63150162'),
(10, 'Ikechukwu', 'Chidinma', 'Adeleke', 'ikechukwu.adeleke@gmail.com', '$2y$10$4PR96PhoNcqmsgoj/Avps.QruQ487WsAz9FwQPkcldZX1Kw0bp.Mm', '08072425388', '10 Bourdillon Road, Ikeja, Lagos', NULL, '5000.00', 'Pending', 8, '2024-08-31 23:00:00', 'Finbank Plc', '6414629268', '85151275'),
(11, 'Rotimi', 'Obioma', 'Olarewaju', 'rotimi.olarewaju@gmail.com', '$2y$10$3sLe3KevMyQizizLU4zLL.lbNB2I/t9IXRqewIoGnYv/olcf2vseO', '08027655017', '46 Bourdillon Road, Uwani, Enugu', NULL, '5000.00', 'Pending', 9, '2024-08-31 23:00:00', 'Nigeria International Bank (Citigroup)', '4289247977', '23150005'),
(12, 'Chioma', 'Chizoba', 'Oyedepo', 'chioma.oyedepo@gmail.com', '$2y$10$B.SooQpibxD9yzAeBoouKOSja/RufV4./t6eNmr.RS5pn5gaVIwka', '08059200445', '133 Adeola Odeku Street, New Benin, Benin City', NULL, '5000.00', 'Pending', 10, '2024-08-31 23:00:00', 'Union Bank Of Nigeria Plc', '3101398127', '32156825'),
(13, 'Rotimi', 'Chibuike', 'Ezinwa', 'rotimi.ezinwa@gmail.com', '$2y$10$T5Hpjq.ppVTIki0cFA2xr.YtMWaAm/fhlrTlfffs5T7DsPpPftV42', '08042736496', '34 Awolowo Road, Karu, Abuja', NULL, '10000.00', 'Contributed', 13, '2024-08-31 23:00:00', 'Equitorial Trust Bank Limited', '9159909924', '40150101'),
(14, 'Bukola', 'Nkechi', 'Afolabi', 'bukola.afolabi@gmail.com', '$2y$10$V7CrjlLUjucZuuW/BdmJRuPuydvurQmiqe8WuL/gFxXMSe1WR0O1.', '08032249185', '111 Bourdillon Road, IBB Way, Calabar', NULL, '10000.00', 'Contributed', 14, '2024-08-31 23:00:00', 'ECOBank', '3885917894', '56080016'),
(15, 'Chiamaka', 'Bolaji', 'Njoku', 'chiamaka.njoku@gmail.com', '$2y$10$0t.kejzn/vIBB0Rp05yKCezFcnGzHx4ouhtEmFhVuEGZvwdh5keGy', '08095456426', '69 Mobolaji Bank Anthony Way, Emene, Enugu', NULL, '10000.00', 'Contributed', 15, '2024-08-31 23:00:00', 'Access Bank', '1054384505', '44150149'),
(16, 'Ikechukwu', 'Damilola', 'Oyedepo', 'ikechukwu.oyedepo@gmail.com', '$2y$10$VUMzuwt0sgcPYKIjeNl9eugekdFbknJopP1F76qil5o01jGVRPRKa', '08089945547', '119 Akin Adesola Street, Elekahia, Port Harcourt', NULL, '10000.00', 'Contributed', 16, '2024-08-31 23:00:00', 'Ecobank Nigeria Plc', '1736005122', '50150311'),
(17, 'Segun', 'Obioma', 'Okafor', 'segun.okafor@gmail.com', '$2y$10$cbgHy5M1HdtId8FiqBBPuuFk6n1yALQihcIBkR7nsTJ9Ac3BygAgK', '08010470475', '112 Opebi Road, Calabar South, Calabar', NULL, '10000.00', 'Contributed', 17, '2024-08-31 23:00:00', 'Finbank Plc', '8305878061', '85151275'),
(18, 'Adebayo', 'Chukwuma', 'Nwosu', 'adebayo.nwosu@gmail.com', '$2y$10$BU0q106VKT0qjq9xxJ1FneBZKsQ.NQuFvgvf1U.0nkGS1zwnfXIW2', '08046559551', '80 Adeola Odeku Street, GRA, Enugu', NULL, '10000.00', 'Contributed', 18, '2024-08-31 23:00:00', 'Guaranty Trust Bank Plc', '8868511392', '58152052'),
(19, 'Adebayo', 'Ifeoma', 'Nwachukwu', 'adebayo.nwachukwu@gmail.com', '$2y$10$5mRnkb5H1JvKpYWbnN28cOHJW6DuAbCqLBkh3uPsxPUPySd/k2pFm', '08027383368', '17 Akin Adesola Street, Fagge, Kano', NULL, '10000.00', 'Contributed', 19, '2024-08-31 23:00:00', 'Keystone Bank', '7758676933', '82150017'),
(20, 'Chioma', 'Bolaji', 'Nwosu', 'chioma.nwosu@gmail.com', '$2y$10$9QPv/Vc5N.dE/XBANKuITemLMOS9/Iz0L1nKQ6gf/mw.TBMlgMVpy', '08052121733', '36 Awolowo Road, Atimbo, Calabar', NULL, '10000.00', 'Contributed', 20, '2024-08-31 23:00:00', 'Enterprise Bank', '7277340098', '84150015'),
(21, 'Nneka', 'Chidimma', 'Olarewaju', 'nneka.olarewaju@gmail.com', '$2y$10$a008hgEZmJVRolBuaJCJ4eJUj2KCSsGlF5oCHWOFzvshTn34OIvrS', '08062568418', '42 Bourdillon Road, Dala, Kano', NULL, '20000.00', 'Contributed', 21, '2024-08-31 23:00:00', 'Mainstreet Bank', '3261601090', '14150030'),
(22, 'Chiamaka', 'Damilola', 'Chukwu', 'chiamaka.chukwu@gmail.com', '$2y$10$0mo9/m9XlC/It4RLB5OOO.Ocu4qJXRZXdOuvxJ.pZfvwv4Jh42Zc6', '08072427304', '53 Bourdillon Road, Garki, Abuja', NULL, '20000.00', 'Contributed', 22, '2024-08-31 23:00:00', 'Sterling Bank Plc', '5107556219', '232150029'),
(23, 'Blessing', 'Chidi', 'Ogunleye', 'blessing.ogunleye@gmail.com', '$2y$10$ghx.vOdJUhQsTLb5HahkO.dzqcjc8durv/FudAxNXZ02NOOU5RokW', '08032953999', '95 Adeola Odeku Street, Satellite Town, Calabar', NULL, '20000.00', 'Contributed', 23, '2024-08-31 23:00:00', 'Ecobank Nigeria Plc', '7053524383', '50150311'),
(24, 'Obinna', 'Adewale', 'Eze', 'obinna.eze@gmail.com', '$2y$10$swFyVzZSRljHoszNAuXMX.4gLnFxUbaQMgf/4vdzKUV9uD7W2lVgG', '08058030513', '64 Opebi Road, PTI Road, Warri', NULL, '20000.00', 'Contributed', 24, '2024-08-31 23:00:00', 'Access Bank', '3221477437', '44150149'),
(25, 'Emeka', 'Nkechi', 'Ezinwa', 'emeka.ezinwa@gmail.com', '$2y$10$cNJ7GIRF.njtZ8qPSButMuqUuBfByikSqedGoFOONdy2gGpemrf/K', '08044166583', '13 Ahmadu Bello Way, Airport Road, Benin City', NULL, '20000.00', 'Contributed', 25, '2024-08-31 23:00:00', 'First City Monument Bank Plc', '4069904284', '214150018'),
(26, 'Chiamaka', 'Adewale', 'Afolabi', 'chiamaka.afolabi@gmail.com', '$2y$10$dO5zFzSKrnHZnGr1W7w/4eGXhaWATtLyWMTaXTv786EGpzAfOOp5m', '08093248153', '11 Akin Adesola Street, Apapa, Lagos', NULL, '20000.00', 'Contributed', 26, '2024-08-31 23:00:00', 'First City Monument Bank Plc', '7069224287', '214150018'),
(27, 'Oluwaseun', 'Chukwuma', 'Nwosu', 'oluwaseun.nwosu@gmail.com', '$2y$10$wk2Gw1CAsgxgnFb7iYLFjumBptGmynGwhPPIV4eendYjcQ7AWFhzW', '08024104810', '109 Ahmadu Bello Way, PTI Road, Warri', NULL, '20000.00', 'Contributed', 27, '2024-08-31 23:00:00', 'Mainstreet Bank', '1974076457', '14150030'),
(28, 'Hunter', 'Darius', 'King', 'hunterking4lf@gmail.com', '$2y$10$/AS3G1NB9exhXywJurHpkuvZwlfBGQuQm5RwfWUOSZSwhPBKkNMvi', '0808090809', 'Rand Street, Degema Close, Area 10 Abuja', 'profile_680f2fd079d9e.jpg', '0.00', 'Contributed', 1, '2025-04-28 07:34:42', 'Zenith Bank PLC', '4662226163', '44150013');

-- --------------------------------------------------------

--
-- Table structure for table `reassignment_requests`
--

CREATE TABLE `reassignment_requests` (
  `id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `current_group_id` int(11) NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `request_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reassignment_requests`
--

INSERT INTO `reassignment_requests` (`id`, `member_id`, `current_group_id`, `reason`, `status`, `request_date`) VALUES
(1, 1, 1, 'I would like to request a reassignment to the group: Adashi Union. Reason: I just want to contribute the amount they are contributing instead', 'Approved', '2025-04-28 07:38:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `contributions`
--
ALTER TABLE `contributions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `idx_contributions_date` (`date_of_contribution`);

--
-- Indexes for table `fund_distribution`
--
ALTER TABLE `fund_distribution`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_group_current_members` (`current_number_of_members`);

--
-- Indexes for table `group_join_requests`
--
ALTER TABLE `group_join_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_member_group_request` (`member_id`,`group_id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_group_member` (`group_id`,`member_id`),
  ADD KEY `member_id` (`member_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_member_contribution_status` (`contribution_status`),
  ADD KEY `idx_member_rotation` (`rotation_order`);

--
-- Indexes for table `reassignment_requests`
--
ALTER TABLE `reassignment_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`member_id`),
  ADD KEY `current_group_id` (`current_group_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contributions`
--
ALTER TABLE `contributions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT for table `fund_distribution`
--
ALTER TABLE `fund_distribution`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `group_join_requests`
--
ALTER TABLE `group_join_requests`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `group_members`
--
ALTER TABLE `group_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `reassignment_requests`
--
ALTER TABLE `reassignment_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contributions`
--
ALTER TABLE `contributions`
  ADD CONSTRAINT `contributions_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fund_distribution`
--
ALTER TABLE `fund_distribution`
  ADD CONSTRAINT `fund_distribution_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `fund_distribution_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`);

--
-- Constraints for table `group_join_requests`
--
ALTER TABLE `group_join_requests`
  ADD CONSTRAINT `group_join_requests_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_join_requests_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `group_members`
--
ALTER TABLE `group_members`
  ADD CONSTRAINT `group_members_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_members_ibfk_2` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reassignment_requests`
--
ALTER TABLE `reassignment_requests`
  ADD CONSTRAINT `reassignment_requests_ibfk_1` FOREIGN KEY (`member_id`) REFERENCES `members` (`id`),
  ADD CONSTRAINT `reassignment_requests_ibfk_2` FOREIGN KEY (`current_group_id`) REFERENCES `groups` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
