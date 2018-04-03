-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2018-04-03 16:55:00
-- 服务器版本： 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `yunprint`
--
CREATE DATABASE IF NOT EXISTS `yunprint` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `yunprint`;

-- --------------------------------------------------------

--
-- 表的结构 `filepath`
--

CREATE TABLE IF NOT EXISTS `filepath` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `realname` varchar(200) NOT NULL,
  `savename` varchar(200) NOT NULL,
  `author` varchar(80) DEFAULT NULL,
  `time` timestamp NULL DEFAULT NULL COMMENT '上传时间',
  `status` int(1) DEFAULT NULL COMMENT '状态0-非公开 1-公开',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='文件名映射表' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `filepath`
--

INSERT INTO `filepath` (`id`, `realname`, `savename`, `author`, `time`, `status`) VALUES
(1, '2017-2018年度五四评优申报材料清单（附教务处成绩截图模板）.docx', '20180327\\c382d20769f4f0515260343d0e7bad6e.docx', NULL, '2018-03-27 04:51:40', 1),
(2, '附件7：优秀个人汇总表.xls', '20180327\\a9f30f836493bc26e5024048d1b3ff81.xls', NULL, '2018-03-27 04:51:40', 1);

-- --------------------------------------------------------

--
-- 表的结构 `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `filenum` int(11) NOT NULL,
  `filepath` text NOT NULL,
  `status` int(1) NOT NULL COMMENT '0-待接取 1-待完成 2-待领取 3-已完成 9-已取消',
  `createtime` timestamp NOT NULL,
  `finishtime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `shore`
--

CREATE TABLE IF NOT EXISTS `shore` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `realname` varchar(40) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `realname` varchar(40) NOT NULL,
  `tel` varchar(20) NOT NULL,
  `time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `realname`, `tel`, `time`) VALUES
(1, 'xzfff', '123', '谢泽丰', '13125177868', '2018-03-27 00:45:11');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
