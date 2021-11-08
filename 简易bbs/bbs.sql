-- 表的结构 `info`
CREATE TABLE IF NOT EXISTS `info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- 转存表中的数据 `info`
INSERT INTO `info` (`id`, `title`, `keywords`, `description`) VALUES
(1, '观沧海', '观海听涛', '东临碣石');

-- --------------------------------------------------------




-- 表的结构 `member`
CREATE TABLE IF NOT EXISTS `member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL,
  `pw` varchar(32) NOT NULL,
  `photo` varchar(255),
  `register_time` datetime NOT NULL,
  `last_time` datetime,
  `sign` varchar(255),
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

INSERT INTO `member` (`id`, `name`, `pw`, `photo`, `register_time`, `last_time`,`sign`) VALUES
(1, '测试1', 'e10adc3949ba59abbe56e057f20f883e', '', '2021-11-07 16:11:27', '0000-00-00 00:00:00',''),
(2, '测试2', 'e10adc3949ba59abbe56e057f20f883e', '', '2021-11-07 16:15:22', '0000-00-00 00:00:00','');

-- --------------------------------------------------------




-- 表的结构 `father_module`
CREATE TABLE IF NOT EXISTS `father_module` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_name` varchar(66) NOT NULL,
  `sort` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  AUTO_INCREMENT=20 ;


-- 转存表中的数据 `father_module`
INSERT INTO `father_module` (`id`, `module_name`, `sort`) VALUES
(1, '走遍中国', 0),
(2, '环球旅行', 0);


-- --------------------------------------------------------




-- 表的结构 `son_module`
CREATE TABLE IF NOT EXISTS `son_module` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `father_module_id` int(10) unsigned NOT NULL,
  `module_name` varchar(66) NOT NULL,
  `info` varchar(255) NOT NULL,
  `member_id` int(10) unsigned NOT NULL DEFAULT '0',
  `sort` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;



-- 转存表中的数据 `son_module`
INSERT INTO `son_module` (`id`, `father_module_id`, `module_name`, `info`, `member_id`, `sort`) VALUES
(1, 1, '云南', '彩云之南', 1, 0),
(2, 1, '西藏', '青藏高原', 0, 0),
(3, 2, '埃及', '古老神秘的金字塔', 0, 0);


-- --------------------------------------------------------






-- 表的结构 `content`
CREATE TABLE IF NOT EXISTS `content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `module_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `time` datetime NOT NULL,
  `member_id` int(10) unsigned NOT NULL,
  `times` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=39 ;

-- 转存表中的数据 `content`

INSERT INTO `content` (`id`, `module_id`, `title`, `content`, `time`, `member_id`, `times`) VALUES
(1, 1, '帖子1', '帖子1帖子1帖子1帖子1帖子1帖子1帖子1帖子1帖子1帖子1帖子1', '2021-11-07 18:15:27', 1, 3),
(2, 2, '帖子2', '尼罗河尼罗河尼罗河尼罗河尼罗河尼罗河尼罗河', '2021-11-07 18:19:17', 1, 4);


-- --------------------------------------------------------




-- 表的结构 `reply`
CREATE TABLE IF NOT EXISTS `reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `content_id` int(10) unsigned NOT NULL,
  `quote_id` int(10) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `time` datetime NOT NULL,
  `member_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;


-- 转存表中的数据 `reply`

INSERT INTO `reply` (`id`, `content_id`, `quote_id`, `content`, `time`, `member_id`) VALUES
(1, 1, 0, '测试测试测试测试测试', '2021-11-07 18:22:44', 1),
(2, 1, 1, '少时诵诗书所所所所少时诵诗书所所所所', '2021-11-07 18:23:56', 2),
(3, 1, 2, '积极积极军军军军军军军军军军军军', '2021-11-07 18:24:41',2),
