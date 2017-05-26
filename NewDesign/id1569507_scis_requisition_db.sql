-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 25, 2017 at 02:45 AM
-- Server version: 10.1.20-MariaDB
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `id1569507_scis_requisition_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `itemsnotpo`
--

CREATE TABLE `itemsnotpo` (
  `id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_accomplished` date DEFAULT NULL,
  `request_slip_no` int(11) NOT NULL COMMENT 'request_slip.id',
  `amount` double DEFAULT '0',
  `itemStatus` enum('Pending','Canceled','Delivered') NOT NULL DEFAULT 'Pending',
  `remarks` varchar(255) DEFAULT 'None',
  `supplier` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `itemspo`
--

CREATE TABLE `itemspo` (
  `iditemspo` int(11) NOT NULL,
  `quantity` int(11) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `Location` varchar(255) DEFAULT NULL,
  `unitprice` double DEFAULT '0',
  `amount` double DEFAULT '0',
  `poid` int(11) NOT NULL,
  `itemspostatus` enum('Pending','Canceled','Delivered') NOT NULL DEFAULT 'Pending',
  `date_complete` date DEFAULT NULL,
  `supplier_po` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Items for PO';

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order`
--

CREATE TABLE `purchase_order` (
  `id` int(11) NOT NULL,
  `po_no` varchar(11) DEFAULT NULL,
  `date_of_po` date DEFAULT NULL,
  `supplier` varchar(45) DEFAULT NULL,
  `totalamt` double DEFAULT NULL,
  `request_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `request_slip`
--

CREATE TABLE `request_slip` (
  `id` int(11) NOT NULL,
  `rs_no` int(111) NOT NULL,
  `requested_by` varchar(255) NOT NULL COMMENT 'users.id',
  `date_needed` date DEFAULT NULL,
  `time_needed` varchar(7) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `purpose` text NOT NULL COMMENT 'another word for reason',
  `status` varchar(255) NOT NULL COMMENT 'pending/cancelled/forPO/delivered/in-progrees/completed',
  `type` enum('ItemsNoPO','PO','Service') NOT NULL COMMENT 'The category',
  `ConcernedOffice` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `idServices` int(11) NOT NULL,
  `description` varchar(45) DEFAULT NULL,
  `status` enum('Pending','Canceled','Completed') NOT NULL,
  `remarks` text,
  `requestID` int(11) NOT NULL,
  `date_completed` date DEFAULT NULL,
  `service_provider` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$wHcRpicZaO5kwPlxNAWuyO6XCdCu/DQOxAOMwxZOyPhLPeZtX8Rj2', '2017-05-11 03:29:32', '2017-05-06 08:40:23'),
(2, 'jl', '$2y$10$NWfnEz.GRJgvqSbjqbMiFeqOESUtuXMwKtuiRF9slber5fegNnwGO', '2017-05-07 09:58:34', '2017-05-07 09:58:34');

-- --------------------------------------------------------

--
-- Table structure for table `user_details`
--

CREATE TABLE `user_details` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'users.id',
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_details`
--

INSERT INTO `user_details` (`id`, `user_id`, `firstname`, `lastname`, `created_at`, `updated_at`) VALUES
(1, 1, 'SCIS', 'Admin', '2017-05-25 02:41:57', '2017-05-06 08:40:23'),
(2, 2, 'JL', 'Black', '2017-05-11 01:34:34', '2017-05-07 09:58:34');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `itemsnotpo`
--
ALTER TABLE `itemsnotpo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_slip_no` (`request_slip_no`);

--
-- Indexes for table `itemspo`
--
ALTER TABLE `itemspo`
  ADD PRIMARY KEY (`iditemspo`),
  ADD KEY `POIDFK_idx` (`poid`),
  ADD KEY `iditemspo` (`iditemspo`,`quantity`,`description`,`remarks`,`Location`,`unitprice`,`amount`,`poid`,`itemspostatus`),
  ADD KEY `iditemspo_2` (`iditemspo`,`quantity`,`description`,`remarks`,`Location`,`unitprice`,`amount`,`poid`,`itemspostatus`);

--
-- Indexes for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_id` (`request_id`);

--
-- Indexes for table `request_slip`
--
ALTER TABLE `request_slip`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`idServices`),
  ADD KEY `ServicesFKRequest_idx` (`requestID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_details`
--
ALTER TABLE `user_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `itemsnotpo`
--
ALTER TABLE `itemsnotpo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `itemspo`
--
ALTER TABLE `itemspo`
  MODIFY `iditemspo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `purchase_order`
--
ALTER TABLE `purchase_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `request_slip`
--
ALTER TABLE `request_slip`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `idServices` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `user_details`
--
ALTER TABLE `user_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `itemsnotpo`
--
ALTER TABLE `itemsnotpo`
  ADD CONSTRAINT `request_slipFK` FOREIGN KEY (`request_slip_no`) REFERENCES `request_slip` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `itemspo`
--
ALTER TABLE `itemspo`
  ADD CONSTRAINT `PoFKID` FOREIGN KEY (`poid`) REFERENCES `purchase_order` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `purchase_order`
--
ALTER TABLE `purchase_order`
  ADD CONSTRAINT `fk_reqid_reqslip` FOREIGN KEY (`request_id`) REFERENCES `request_slip` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `ServicesFKRequest` FOREIGN KEY (`requestID`) REFERENCES `request_slip` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_details`
--
ALTER TABLE `user_details`
  ADD CONSTRAINT `user_details_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
