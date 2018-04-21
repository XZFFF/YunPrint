-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2018-04-21 17:14:42
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='文件名映射表' AUTO_INCREMENT=5 ;

--
-- 转存表中的数据 `filepath`
--

INSERT INTO `filepath` (`id`, `realname`, `savename`, `author`, `time`, `status`) VALUES
(1, '信息化建设项目立项申报书(1).docx', '20180420\\21b1a5015c667968646212b5131cd924.docx', '朱孟岳', '2018-04-20 15:12:18', 1),
(2, '网络中心宣传部04.14docx.docx', '20180420\\ebb32f8bc55f137f47a25d3bb3e23682.docx', '朱孟岳', '2018-04-20 15:12:18', 1),
(3, '信息化建设项目立项申报书(1).docx', '20180420\\dd3d7b3e444dbd9b522f0a3df83432dc.docx', '朱孟岳', '2018-04-20 15:17:56', 1),
(4, '网络中心宣传部04.14docx.docx', '20180420\\122220728e29cd9082cbb55844d24961.docx', '朱孟岳', '2018-04-20 15:17:56', 1);

-- --------------------------------------------------------

--
-- 表的结构 `order`
--

CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `filenum` int(11) NOT NULL,
  `file1id` int(11) NOT NULL,
  `file2id` int(11) DEFAULT NULL,
  `file3id` int(11) DEFAULT NULL,
  `remark` text COMMENT '备注',
  `status` int(1) NOT NULL COMMENT '0-待接取 1-待完成 2-待领取 3-已完成 9-已取消',
  `createtime` timestamp NOT NULL,
  `finishtime` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `order`
--

INSERT INTO `order` (`id`, `uid`, `sid`, `filenum`, `file1id`, `file2id`, `file3id`, `remark`, `status`, `createtime`, `finishtime`) VALUES
(1, 1, 1, 1, 1, 2, 3, NULL, 2, '2018-04-20 17:18:12', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `store`
--

CREATE TABLE IF NOT EXISTS `store` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(40) NOT NULL,
  `password` varchar(40) NOT NULL,
  `storename` varchar(40) NOT NULL,
  `place` text,
  `tel` varchar(20) NOT NULL,
  `qq` varchar(20) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0-正常 1-休息',
  `time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=2 ;

--
-- 转存表中的数据 `store`
--

INSERT INTO `store` (`id`, `username`, `password`, `storename`, `place`, `tel`, `qq`, `status`, `time`) VALUES
(1, 'store1', '123', '升升C栋打印店', '升升公寓C栋地下室打印店', '1234567', NULL, 0, '2018-04-20 16:40:22');

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `realname`, `tel`, `time`) VALUES
(1, 'xzfff', '123', '谢泽丰', '13125177868', '2018-03-27 00:45:11'),
(2, 'zmy', '123', '朱孟岳', '123456', '2018-04-20 13:55:04');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
