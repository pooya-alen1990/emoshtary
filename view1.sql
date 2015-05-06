-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2015 at 02:55 PM
-- Server version: 5.6.12-log
-- PHP Version: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `emoshtary`
--

-- --------------------------------------------------------

--
-- Structure for view `view1`
--

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view1` AS select `advertises`.`id` AS `id`,`advertises`.`name` AS `name`,`advertises`.`cat_id` AS `cat_id`,`advertises`.`sub_cat_id` AS `sub_cat_id`,`advertises`.`slogan` AS `slogan`,`advertises`.`city_id` AS `city_id`,`advertises`.`province_id` AS `province_id`,`advertises`.`address` AS `address`,`advertises`.`phone` AS `phone`,`advertises`.`mobile` AS `mobile`,`advertises`.`email` AS `adv_email`,`advertises`.`website` AS `website`,`advertises`.`keywords` AS `keywords`,`advertises`.`register_date` AS `register_date`,`advertises`.`google_map` AS `google_map`,`advertises`.`activate` AS `activate`,`advertises`.`user_id` AS `user_id`,`advertises`.`image` AS `image`,`users`.`id` AS `id1`,`users`.`activation_code` AS `activation_code`,`users`.`asiatech_code` AS `asiatech_code`,`users`.`first_name` AS `first_name`,`users`.`last_name` AS `last_name`,`users`.`melli_code` AS `melli_code`,`users`.`email` AS `email`,`users`.`mobile` AS `mobile1`,`users`.`city_id` AS `city_id1`,`users`.`province_id` AS `province_id1`,`users`.`address` AS `address1`,`users`.`password` AS `password`,`users`.`register_date` AS `register_date1` from (`users` join `advertises` on((`advertises`.`user_id` = `users`.`id`)));

--
-- VIEW  `view1`
-- Data: None
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
