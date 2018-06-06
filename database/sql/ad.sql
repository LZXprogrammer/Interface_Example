CREATE TABLE `promote_ad_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `ad_id` char(13) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告唯一ID',
  `statue` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态：1 测试 2 上线',
  `country_code` char(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '国家代码',
  `iconUrl` varchar(1024) COLLATE utf8_unicode_ci NOT NULL COMMENT '图标 URL 地址',
  `imageUrl` varchar(1024) COLLATE utf8_unicode_ci NOT NULL COMMENT '图片 URL 地址',
  `adImageUrl` varchar(1024) COLLATE utf8_unicode_ci NOT NULL COMMENT '1200 * 628 图片资源 url',
  `bannerImageUrl` varchar(1024) COLLATE utf8_unicode_ci NOT NULL COMMENT 'banner 图片资源 url',
  `adTarget` varchar(1024) COLLATE utf8_unicode_ci NOT NULL COMMENT '打开广告的链接地址',
  `adStarRate` tinyint(4) NOT NULL DEFAULT '0' COMMENT '广告星级比率 1-5 ',
  `adTitle` varchar(150) DEFAULT NULL COMMENT '广告标题',
  `adBody` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '广告描述信息',
  `callAction` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '按钮显示文本',
  `sourceType` varchar(40) NOT NULL DEFAULT 'down' COMMENT '资源类型',
  `update_time` int(10) unsigned NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ad_id` (`ad_id`),
  KEY `update_time` (`update_time`),
  KEY `adStarRate` (`adStarRate`),
  KEY `adTitle` (`adTitle`),
  KEY `sourceType` (`sourceType`)
) ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='广告信息内容表';

CREATE TABLE `promote_app_adunit_info` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `ad_unit_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '版位ID',
  `ad_unit_alias` varchar(50) COLLATE utf8_unicode_ci NOT NULL  COMMENT '版位名称',
  `app_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '应用名称',
  `package_name` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '包名',
  `desc` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '版位描述',
  `update_time` int(10) unsigned NOT NULL COMMENT '最后更新时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `pkg_ad_unit_id` (`package_name`, `ad_unit_id`),
  KEY `package_name` (`package_name`),
  KEY `ad_unit_id` (`ad_unit_id`)
) ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='app和版位对应表';

CREATE TABLE `promote_adunit_adinfo_relation` (
  `ad_unit_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL COMMENT '版位ID',
  `ad_id` char(13) COLLATE utf8_unicode_ci NOT NULL COMMENT '广告唯一ID',
  PRIMARY KEY (`ad_unit_id`, `ad_id`)
) ENGINE=InnoDB CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='版位和广告关系表';


