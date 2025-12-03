-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2025 at 04:33 AM
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
-- Database: `itws2110‐fall2025‐sitc‐quiz2`
--

-- --------------------------------------------------------

--
-- Table structure for table `projectmembership`
--

CREATE TABLE `projectmembership` (
  `projectId` int(11) NOT NULL,
  `memberId` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projectmembership`
--

INSERT INTO `projectmembership` (`projectId`, `memberId`) VALUES
(1, 'danas'),
(1, 'pwong'),
(1, 'sitc'),
(1, 'spauln'),
(6, 'chua'),
(6, 'lleungn'),
(6, 'sitc'),
(7, 'juniperh'),
(7, 'sitc'),
(7, 'thilankam'),
(8, 'ngok'),
(8, 'sitc'),
(8, 'zhangj');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `projectId` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`projectId`, `name`, `description`) VALUES
(1, 'VERA', 'NASA Data Visualization'),
(6, 'VolunteerConnect', 'A website to help connect individuals to volunteer opportunities based on their interests and location.'),
(7, 'Web Sys Website', 'A website displaying information on the course Web Systems.'),
(8, 'Night Shift', 'ICU Management game built for HackRPI');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userId` varchar(50) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `nickName` varchar(100) DEFAULT NULL,
  `passwordHash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `firstName`, `lastName`, `nickName`, `passwordHash`) VALUES
('chua', 'Alexa', 'Chu', 'Alexa', '$2y$12$IEcOAMtueMAlH5NIA6pEZePjdebZ7SIfE2fNG6wvysl2/Uj0bp9EW'),
('danas', 'Dana', 'Siong Sin', 'Dana', '$2y$12$VSuaN7T6/Ww7hqyfBkcB2OktwSNNmc8lw30R4yquPhbsenzqH1W.K'),
('juniperh', 'Juniper', 'Huang', 'Juniper', '$2y$12$vIu1VClB9pB2MLXLmFy69uTImsorWZm6FIyGh1cELHWc/tcst0kHe'),
('lleungn', 'Nicole', 'Lleung', 'Nicole', '$2y$12$I1GItcic7s69CGM5dTKJlOcakVA55xgZsHCF3/oZOGc7pSo6yKWT.'),
('ngok', 'Kateri', 'Ngo', 'Kat', '$2y$12$uhLiY5uWolEmtZ1WHudXwew9LPCQFUAGCelezmPHOw2NQMcD/vxWW'),
('perezj', 'Jamie', 'Perez', 'James', '$2y$12$6xy55Sm5iLWSsg7OI69uAuMjn2.pnStqzdQwolTV55JLIGkaSNCfO'),
('pwong', 'Priscilla', 'Wong', 'Pris', '$2y$12$TrLuyLGhS7XaguAqY.T1fO0307UGjwJtvfjZ62f5TjvjeiARXaTea'),
('sitc', 'Courteney', 'Sit', 'Court', '$2y$12$2R1euuzAW877yK/IEpOQKuKfQyRNRT6B/SQTVMORs/awG41Hz7aUC'),
('spauln', 'Nicole', 'Spaulding', 'Nicole', '$2y$12$lKkMlDp6Wlm7JfLihoYG1uRz4MoVGQYmBJfJBVS3e/gmaImMLX4je'),
('thilankam', 'Thilanka', 'Munasinghe', 'Thilanka', '$2y$12$jnAOnorHa6WDIadDzA43IeWldSHo.6d2bJrQZG/g.XhnNs0fyRIg.'),
('villaverx', 'Xenia', 'Villaver', 'Xen', '$2y$12$zDVvl4BW4q4mPYUrMbexVe8thBxW1qKLKyesCEDebDlyW6VXOoWmy'),
('zhangj', 'Justin', 'Zhang', 'Justin', '$2y$12$xIYkQrI0aaftOniPymPhsemyebag180Vq2QJPg8aKi7DdWhIDLL4S');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `projectmembership`
--
ALTER TABLE `projectmembership`
  ADD PRIMARY KEY (`projectId`,`memberId`),
  ADD KEY `memberId` (`memberId`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`projectId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `projectId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `projectmembership`
--
ALTER TABLE `projectmembership`
  ADD CONSTRAINT `projectmembership_ibfk_1` FOREIGN KEY (`projectId`) REFERENCES `projects` (`projectId`),
  ADD CONSTRAINT `projectmembership_ibfk_2` FOREIGN KEY (`memberId`) REFERENCES `users` (`userId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
