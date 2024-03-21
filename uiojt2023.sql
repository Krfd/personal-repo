-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2024 at 09:52 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `uiojt2023`
--

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category`) VALUES
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('PDS'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('PDS'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR'),
('IPCR');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `client_id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `profile` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `is_admin` int(11) NOT NULL,
  `is_deleted` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`client_id`, `fullname`, `email`, `profile`, `phone`, `password`, `is_admin`, `is_deleted`, `created`) VALUES
(1, 'PRO ADMIN', 'admin@email.com', '', '09123456789', '$2y$10$o4FAwR6WKCptJq02JshhTOKkfJHT89Z8gt463FRAcix/d/k4QLj1y', 1, 0, '2024-02-17 04:37:19'),
(2, 'Gerald', 'gerald@email.com', '6.jpg', '09123456789', '$2y$10$hAn5dvbP5uDvrlyp9qE0derbTsJd5pIc9x/TH/NTnHxcuV7pfE51y', 0, 0, '2024-03-02 14:36:17'),
(3, 'Juan', 'juan@email.com', '', '09123456789', '$2y$10$Iyo0LjoR9DdsuFL4uKt1KeW3YAXEE6tDF.9dAukVuMGqflVXZ8J/S', 0, 0, '2024-02-19 01:21:33'),
(5, 'Karl Fredriech', 'karl@gmail.com', 'user3_man.png', '09128496845', '$2y$10$CRl04QIgWI9IOj6v1I1dmOyE2/GMCpyMJriRo39C7u5gFfr67Fzqi', 0, 0, '2024-02-29 05:50:32'),
(6, 'OPR ADMIN 2', 'admin2@email.com', '', '09123456789', '$2y$10$DMp.wLQWXiVWff/ldKw0yunheDdbkbROEmeDr9aBJXcTn9eaLsRdK', 2, 0, '2024-03-01 02:50:27'),
(7, 'OPR ADMIN 3', 'admin3@email.com', '', '09123456789', '$2y$10$UDLwqPM.YYg2CE8LjWIAPOXsthxx6LOGLHRqsaQSacTcSaS0r6Jo6', 2, 0, '2024-03-01 02:51:05');

-- --------------------------------------------------------

--
-- Table structure for table `file_upload`
--

CREATE TABLE `file_upload` (
  `file_id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `fileName` varchar(255) NOT NULL,
  `extension` varchar(255) NOT NULL,
  `folder` varchar(255) NOT NULL,
  `parent_folder` varchar(255) NOT NULL,
  `asc_folder` varchar(255) NOT NULL,
  `deleted_parent_folder` varchar(255) NOT NULL,
  `archive_parent_folder` int(11) NOT NULL,
  `is_archive` int(11) NOT NULL,
  `is_deleted` int(11) NOT NULL,
  `uploaded` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `file_upload`
--

INSERT INTO `file_upload` (`file_id`, `client_id`, `publisher`, `title`, `category`, `fileName`, `extension`, `folder`, `parent_folder`, `asc_folder`, `deleted_parent_folder`, `archive_parent_folder`, `is_archive`, `is_deleted`, `uploaded`, `updated`) VALUES
(86, 2, 'Gerald', 'Book.xlsx', 'IPCR', 'Book.xlsx', 'xlsx', '', '', '', '', 0, 1, 0, '2024-03-05 08:39:48', '2024-03-05 08:39:48'),
(92, 2, 'Gerald', 'Analytics.pptx', 'IPCR', 'Analytics.pptx', 'pptx', 'test1', 'test1', '', '0', 1, 0, 0, '2024-03-05 09:02:01', '2024-03-05 09:02:01'),
(94, 2, 'Gerald', 'Document.docx', 'IPCR', 'Document.docx', 'docx', '', '', '', '', 0, 0, 0, '2024-03-05 09:23:00', '2024-03-05 09:23:00');

-- --------------------------------------------------------

--
-- Table structure for table `folder_upload`
--

CREATE TABLE `folder_upload` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `publisher` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `folder_name` varchar(255) NOT NULL,
  `folder` varchar(255) NOT NULL,
  `parent_folder` varchar(255) NOT NULL,
  `deleted_parent_folder` varchar(255) NOT NULL,
  `archive_parent_folder` int(11) NOT NULL,
  `is_archive` int(11) NOT NULL,
  `is_deleted` int(11) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `folder_upload`
--

INSERT INTO `folder_upload` (`id`, `client_id`, `publisher`, `category`, `folder_name`, `folder`, `parent_folder`, `deleted_parent_folder`, `archive_parent_folder`, `is_archive`, `is_deleted`, `created`, `updated`) VALUES
(244, 2, 'Gerald', 'IPCR', 'test1', 'test1', '', '', 1, 0, 0, '2024-03-05 09:01:42', '2024-03-05 09:13:08'),
(245, 2, 'Gerald', 'IPCR', 'sub_test1', 'sub_test1', 'test1', '0', 0, 0, 0, '2024-03-05 09:01:50', '2024-03-05 09:01:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `file_upload`
--
ALTER TABLE `file_upload`
  ADD PRIMARY KEY (`file_id`);

--
-- Indexes for table `folder_upload`
--
ALTER TABLE `folder_upload`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `client_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `file_upload`
--
ALTER TABLE `file_upload`
  MODIFY `file_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT for table `folder_upload`
--
ALTER TABLE `folder_upload`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
