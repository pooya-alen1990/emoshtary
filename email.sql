-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 04, 2015 at 05:33 AM
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
-- Table structure for table `email`
--

CREATE TABLE IF NOT EXISTS `email` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `url` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

--
-- Dumping data for table `email`
--

INSERT INTO `email` (`id`, `name`, `url`) VALUES
(1, '1-ایستگاه.org', 'istgah.org.zip\r\n'),
(2, '2-علمی فایلس', 'elmi_files.zip'),
(3, '3-کاربران یاهو', 'karbaraneyahoo.zip'),
(4, '4-شبکه اجتماعی ایرانی', 'shabakeejtemaiirani.zip'),
(5, '5-شبکه اجتماعی خارجی', 'shabakeejtemaikhareji.zip'),
(6, '6-سایت های نیازمندیها', 'siteniazmandiha.zip'),
(7, '7-گردشگری', 'gardeshgari.zip'),
(8, '8-تفریحی', 'tafrihi.zip'),
(9, '9-کاربران وب مستر ها', 'karbaranewebmasterha.zip'),
(10, '10-کاربران فعال ایرانی', 'karbaranefaalirani.zip'),
(11, '11-فروشگاههای اینترنتی', 'forooshgahhayeinterneti.zip'),
(12, '12-متخصصان و پزشکان', 'motekhasesanvapezeshkan.zip'),
(13, '13- مشاغل به تفکیک شغل', 'mashaghebetafkikshoghl.zip'),
(14, '14-سایت های پر بازدید', 'sitehayeporbazdid.zip'),
(15, '15-سایت های دانلود', 'sitehayedanlod.zip'),
(16, '16-سایت های موزیک', 'sitehayemusic.zip'),
(17, '17-کاربران وبلاگها', 'karbaraneweblogha.zip'),
(18, '18-علمی آموزشی', 'elmiamoozeshi.zip'),
(19, '19-اعضای وبلاگها', 'azayeweblogha.zip'),
(20, '20-دانشجویان و محققان', 'daneshjooyanvamohagheghan.zip'),
(21, '21-گروه های یاهو', 'goroohhayeyahoo.zip'),
(22, '22-  فروشندگان و خریداران', 'forooshandeganvakharidaran.zip'),
(23, '23-لیست ایمیل های خارجی', 'listemailhayekhareji.zip'),
(24, '24-  پست و نمایندگیها', 'postvanamayandegiha.zip'),
(25, '25-کاربران سایت های خبری', 'karbaranesitehayekhabari.zip'),
(26, '26-کاربران سایت های ورزشی', 'karbaranesitehayevarzeshi.zip'),
(27, '27-مشاغل با تفکیک', 'mashaghelbatafkik.zip'),
(28, '28-آموزشگاه ها', 'amoozeshgahha.zip'),
(29, '29-برنامه نویسان', 'barnamenevisan.zip'),
(30, '30-لیست جی میل ها', 'listegmailha.zip'),
(31, '31-نشریه و روزنامه نگار ها', 'nashriyevarooznamenegarha.zip'),
(32, '32-شرکت های تولیدی', 'sherkathayetolidi.zip'),
(33, '33-شرکت های بازرگانی', 'sherkathayebazargani.zip'),
(34, '34-شرکت های چاپ و چاپخانه ها', 'sherkathayechapvachapkhaneha.zip'),
(35, '35-شرکت های تبلیغاتی', 'sherkathayetablighati.zip'),
(36, '36-شرکت های بزرگ تهران', 'sherkathayebozorgtehran.zip'),
(37, '37-شرکت های خدماتی', 'sherkathayekhadamati.zip'),
(38, '38-شرکت های مسافربری', 'sherkathayemosaferbari.zip'),
(39, '39-تدریس خصوصی', 'tadriskhosoosi.zip'),
(40, '40-خدمات اینترنتی', 'khadamateinterneti.zip');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
