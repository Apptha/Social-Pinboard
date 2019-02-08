CREATE TABLE IF NOT EXISTS `#__pin_boards` (
  `board_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `board_name` varchar(150) NOT NULL,
  `board_description` text NOT NULL,
  `board_category_id` int(11) NOT NULL,
  `board_access` tinyint(1) NOT NULL,
  `cuser_id` VARCHAR( 255 ) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_date` datetime NOT NULL,
  `published` TINYINT( 1 ) NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`board_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__pin_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(150) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `published` TINYINT( 1 ) NOT NULL,
  `created_date` datetime NOT NULL,
  `category_image` VARCHAR( 255 ) NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__pin_comments` (
  `pin_comments_id` int(11) NOT NULL AUTO_INCREMENT,
  `pin_id` int(11) NOT NULL,
  `pin_user_comment_id` int(11) NOT NULL,
  `pin_comment_text` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pin_comments_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__pin_likes` (
  `pin_like_id` int(11) NOT NULL AUTO_INCREMENT,
  `pin_id` int(11) NOT NULL,
  `pin_like_user_id` int(11) NOT NULL,
  `pin_like` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pin_like_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__pin_pins` (
  `pin_id` int(11) NOT NULL AUTO_INCREMENT,
  `pin_board_id` int(11) NOT NULL,
  `pin_user_id` int(11) NOT NULL,
  `pin_category_id` int(11) NOT NULL,
  `pin_description` text NOT NULL,
  `pin_type_id` int(11) NOT NULL,
  `pin_url` varchar(100) NOT NULL,
  `pin_image` varchar(150) NOT NULL,
  `pin_repin_id` int(11) NOT NULL,
  `pin_real_pin_id` int(11) NOT NULL,
  `pin_repin_count` int(11) NOT NULL,
  `pin_likes_count` int(11) NOT NULL,
  `pin_comments_count` int(11) NOT NULL,
  `pin_views` int(11) NOT NULL,
  `link_type` VARCHAR( 255 ) NOT NULL,
  `status` tinyint(1) NOT NULL,
   `rgbcolor` TEXT NOT NULL,
  `published` TINYINT( 1 ) NOT NULL,
  `gift` int(11) NOT NULL,
  `price` VARCHAR( 255 ) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pin_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__pin_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(150) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__pin_user_settings` (
  `pin_user_settings_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `facebook_profile_id` bigint(20) NOT NULL,
  `twitter_id` bigint(20) NOT NULL,
  `first_name` varchar(150) NOT NULL,
  `last_name` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `about` text NOT NULL,
  `location` varchar(100) NOT NULL,
  `website` varchar(100) NOT NULL,
  `user_image` varchar(255) NOT NULL,
  `count` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `published` TINYINT( 1 ) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`pin_user_settings_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__pin_site_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_target_limit` varchar(255) NOT NULL,
  `setting_api_id` varchar(255) NOT NULL,
  `pin_page_limit` varchar(255) NOT NULL,
  `setting_facebookapi` varchar(300) NOT NULL,
  `setting_facebooksecret` varchar(300) NOT NULL,
  `setting_gmailclientid` varchar(300) NOT NULL,
  `setting_gmailclientsecretkey` varchar(300) NOT NULL,
  `setting_twitterapi` varchar(300) NOT NULL,
  `setting_twittersecret` varchar(300) NOT NULL,
  `setting_yahooconsumerkey` varchar(300) NOT NULL,
  `setting_yahooconsumersecretkey` varchar(300) NOT NULL,
  `setting_yahoooauthdomain` text NOT NULL,
  `setting_yahoooappid` varchar(300) NOT NULL,
  `setting_request_approval` tinyint(2) NOT NULL,
  `setting_user_registration` tinyint(2) NOT NULL,
   `setting_show_request` tinyint(2) NOT NULL,
  `lkey` VARCHAR( 255 ) NOT NULL,
  `created_date` DATETIME NOT NULL ,
   `setting_currency` VARCHAR( 255 ) NOT NULL,

  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__pin_user_activation` (
  `email` varchar(255) NOT NULL,
  `activationcode` int(11) NOT NULL,
  `created date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__pin_follow_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `follow_user_id` int(11) NOT NULL,
  `follow_user_board` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `follow_categories` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__pin_googlead` (
  `ad_id` int(11) NOT NULL AUTO_INCREMENT,
  `adclient` text NOT NULL,
  `adslot` varchar(50) NOT NULL,
  `adwidth` int(11) NOT NULL,
  `adheight` int(11) NOT NULL,
  `pin_ad_position` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_date` date NOT NULL,
  `updated_date` datetime NOT NULL,
  PRIMARY KEY (`ad_id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE  `#__pin_user_request` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`email_id` VARCHAR( 255 ) NOT NULL ,
`approval_status` INT( 11 ) NOT NULL ,
`published` TINYINT( 1 ) NOT NULL,
PRIMARY KEY (  `id` )) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__pin_follow_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `follow_user_id` int(11) NOT NULL,
  `follow_user_board` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `follow_categories` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`))ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__pin_googlead` (
  `ad_id` int(11) NOT NULL AUTO_INCREMENT,
  `adclient` text NOT NULL,
  `adslot` varchar(50) NOT NULL,
  `adwidth` int(11) NOT NULL,
  `adheight` int(11) NOT NULL,
  `pin_ad_position` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_date` date NOT NULL,
  `updated_date` datetime NOT NULL,
  PRIMARY KEY (`ad_id`)
)ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `#__pin_user_activation` (
  `email` varchar(255) NOT NULL,
  `activationcode` int(11) NOT NULL,
  `created date` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `#__pin_userreports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `report_sender` int(11) NOT NULL,
  `report_receiver` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `#__pin_user_block` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_block_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

INSERT INTO `#__pin_site_settings` (`id`, `setting_yahoooappid`, `setting_yahoooauthdomain`, `setting_yahooconsumersecretkey`, `setting_yahooconsumerkey`, `setting_facebookapi`, `setting_facebooksecret`,`setting_gmailclientid`,`setting_gmailclientsecretkey`,`setting_twitterapi`,`setting_twittersecret`,`setting_user_registration`, `setting_show_request`,`created_date`,`setting_currency`) VALUES
(1, 'Your Yahoo APP ID', 'Your Yahoo Oauth Domain Name', 'Your Yahoo Consumer Secret key', 'Your Yahoo Consumer key', 'Your facebook application id', 'Your facebook secret key','Your Gmail Client ID','Your Gmail Client Secret Key', 'Twitter Consumer Key','Twitter Consumer Secret Key','0','0',NOW(),'$');

INSERT INTO `#__pin_categories` (`category_id`, `category_name`,`status`, `created_date`,  `updated_date`,`category_image`) VALUES
(1, 'Arts', 1, NOW(), NOW(),'arts.png'),
(2, 'Education', 1, NOW(),NOW(),'education.jpg'),
(3, 'Food', 1, NOW(),NOW(), 'food.jpg'),
(4, 'Drinks', 1, NOW(),NOW(), 'drinks.jpg'),
(5, 'Hair and Beauty', 1, NOW(), NOW(),'handb.jpg');