-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 23, 2014 at 02:32 PM
-- Server version: 5.5.29
-- PHP Version: 5.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `kr`
--

-- --------------------------------------------------------

--
-- Table structure for table `checkitem`
--

CREATE TABLE IF NOT EXISTS `checkitem` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `list_id` bigint(20) NOT NULL,
  `content` mediumtext NOT NULL,
  `status` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `list_id` (`list_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `checkitem`
--

INSERT INTO `checkitem` (`id`, `list_id`, `content`, `status`, `created_date`) VALUES
(1, 1, 'Add Exceptions', 1, '2014-03-23 12:23:43'),
(2, 1, 'Restart Browser', 1, '2014-03-23 12:27:06');

-- --------------------------------------------------------

--
-- Table structure for table `checklist`
--

CREATE TABLE IF NOT EXISTS `checklist` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `content` mediumtext NOT NULL,
  `status` int(11) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `checklist`
--

INSERT INTO `checklist` (`id`, `name`, `content`, `status`, `created_date`) VALUES
(1, 'Unable to Access C2', 'Check with compatibility settings', 1, '2014-03-23 12:16:17');

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE IF NOT EXISTS `ticket` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `external_id` varchar(16) NOT NULL,
  `checklist_id` bigint(20) NOT NULL,
  `status` int(11) NOT NULL,
  `assigned_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `ticket`
--

INSERT INTO `ticket` (`id`, `external_id`, `checklist_id`, `status`, `assigned_date`) VALUES
(1, 'ticket-123', 1, 0, '2014-03-23 13:02:14');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_check`
--

CREATE TABLE IF NOT EXISTS `ticket_check` (
  `id` tinyint(4) NOT NULL AUTO_INCREMENT,
  `ticket_id` bigint(20) NOT NULL,
  `checkitem_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `created_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ticket_id` (`ticket_id`),
  KEY `checkitem_id` (`checkitem_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `ticket_check`
--

INSERT INTO `ticket_check` (`id`, `ticket_id`, `checkitem_id`, `user_id`, `created_date`) VALUES
(2, 1, 1, 1, '2014-03-23 13:17:29');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rights` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `rights`) VALUES
(1, '383363', '$1$jlx7dKiT$8o0Vgzc0mQu2x23PjnYQ./', 1),
(2, '383364', '$1$XBBXol/W$pyCgzdjp0JIGiwB3mdaBK.', 0),
(3, '383365', '$1$oMLejJVv$hodEq2p5O/fZqO55ebbFM0', 0);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `checkitem`
--
ALTER TABLE `checkitem`
  ADD CONSTRAINT `checkitem_ibfk_1` FOREIGN KEY (`list_id`) REFERENCES `checklist` (`id`);

--
-- Constraints for table `ticket_check`
--
ALTER TABLE `ticket_check`
  ADD CONSTRAINT `ticket_check_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `ticket` (`id`),
  ADD CONSTRAINT `ticket_check_ibfk_2` FOREIGN KEY (`checkitem_id`) REFERENCES `checkitem` (`id`),
  ADD CONSTRAINT `ticket_check_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
