DROP TABLE IF EXISTS `#__pin_boards_back`;
ALTER TABLE `#__pin_boards` RENAME TO `#__pin_boards_back`;

DROP TABLE IF EXISTS `#__pin_categories_back`;
ALTER TABLE `#__pin_categories` RENAME TO `#__pin_categories_back`;

DROP TABLE IF EXISTS `#__pin_comments_back`;
ALTER TABLE `#__pin_comments` RENAME TO `#__pin_comments_back`;

DROP TABLE IF EXISTS `#__pin_likes_back`;
ALTER TABLE `#__pin_likes` RENAME TO `#__pin_likes_back`;

DROP TABLE IF EXISTS `#__pin_pins_back`;
ALTER TABLE `#__pin_pins` RENAME TO `#__pin_pins_back`;

DROP TABLE IF EXISTS `#__pin_site_settings_back`;
ALTER TABLE `#__pin_site_settings` RENAME TO `#__pin_site_settings_back`;

DROP TABLE IF EXISTS `#__pin_type_back`;
ALTER TABLE `#__pin_type` RENAME TO `#__pin_type_back`;

DROP TABLE IF EXISTS `#__pin_user_activation_back`;
ALTER TABLE `#__pin_user_activation` RENAME TO `#__pin_user_activation_back`;

DROP TABLE IF EXISTS `#__pin_follow_users_back`;
ALTER TABLE `#__pin_follow_users` RENAME TO `#__pin_follow_users_back`;

DROP TABLE IF EXISTS `#__pin_user_request_back`;
ALTER TABLE `#__pin_user_request` RENAME TO `#__pin_user_request_back`;

DROP TABLE IF EXISTS `#__pin_googlead_back`;
ALTER TABLE `#__pin_googlead` RENAME TO `#__pin_googlead_back`;

DROP TABLE IF EXISTS `#__pin_user_settings_back`;
ALTER TABLE `#__pin_user_settings` RENAME TO `#__pin_user_settings_back`;