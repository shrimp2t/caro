-- phpMyAdmin SQL Dump
-- version 3.5.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 11, 2014 at 01:52 PM
-- Server version: 5.5.29
-- PHP Version: 5.4.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `caro`
--

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `room_id` bigint(15) NOT NULL AUTO_INCREMENT,
  `player_1` bigint(15) NOT NULL,
  `player_2` bigint(15) NOT NULL,
  `status` varchar(15) NOT NULL,
  `players_status` varchar(20) NOT NULL,
  `room_name` varchar(60) CHARACTER SET utf8 NOT NULL,
  `player_1_stt` varchar(5) NOT NULL,
  `player_2_stt` varchar(5) NOT NULL,
  `tracking` text CHARACTER SET utf8 NOT NULL,
  `first_turn` bigint(15) NOT NULL,
  `turn` varchar(2) NOT NULL,
  `last_pos` varchar(100) NOT NULL,
  `winner` bigint(15) NOT NULL,
  PRIMARY KEY (`room_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(15) NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) NOT NULL,
  `user_pass` varchar(100) CHARACTER SET utf8 NOT NULL,
  `user_nice_name` varchar(100) CHARACTER SET utf8 NOT NULL,
  `status` varchar(10) NOT NULL,
  `user_added` datetime NOT NULL,
  `active_key` varchar(60) NOT NULL,
  `last_log` bigint(15) NOT NULL,
  `last_admin_check` int(10) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_login`, `user_pass`, `user_nice_name`, `status`, `user_added`, `active_key`, `last_log`, `last_admin_check`) VALUES
(1, 'yen', '123456', '', 'logged_out', '0000-00-00 00:00:00', '', 0, 1397272737),
(2, 'admin', '123456', '', 'logged_in', '0000-00-00 00:00:00', '', 1399809140, 1397272737),
(3, 'thien', 'thien', '', 'logged_out', '0000-00-00 00:00:00', '', 1397234116, 1397272737),
(4, 'tung', 'tung', '', 'logged_out', '0000-00-00 00:00:00', '', 0, 1397272737),
(5, 'binh', 'binh', '', 'logged_out', '0000-00-00 00:00:00', '', 0, 1397272737),
(6, 'sa', 'sa', '', 'logged_in', '0000-00-00 00:00:00', '', 1399809141, 1397272737);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
