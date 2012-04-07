-- --------------------------------------------------------------------
-- v0.5 to v1
-- --------------------------------------------------------------------

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='';

ALTER TABLE `comments` DROP FOREIGN KEY `comments_ibfk_1` ;

ALTER TABLE `goods_transactions` DROP FOREIGN KEY `goods_transactions_ibfk_1` , DROP FOREIGN KEY `goods_transactions_ibfk_2` ;

ALTER TABLE `messages` DROP FOREIGN KEY `messages_ibfk_1` ;

ALTER TABLE `transactions_users` DROP FOREIGN KEY `transactions_users_ibfk_1` ;

ALTER TABLE `categories` COLLATE = utf8_general_ci ;

ALTER TABLE `ci_sessions` COLLATE = utf8_general_ci , COMMENT = 'Stores session information.' ;

ALTER TABLE `comments` COLLATE = utf8_general_ci , DROP COLUMN `author_id` , ADD COLUMN `user_id` INT(10) UNSIGNED NOT NULL  AFTER `id` , 
  ADD CONSTRAINT `comments_ibfk_1`
  FOREIGN KEY (`user_id` )
  REFERENCES `users` (`id` )
  ON UPDATE CASCADE
, DROP INDEX `user_id` 
, ADD INDEX `user_id` (`user_id` ASC) , COMMENT = 'Currently unused since the commenting system has not yet been built.' ;

ALTER TABLE `followings_users` COLLATE = utf8_general_ci, DROP INDEX `following_id`, ADD INDEX `following_id` (`following_id` ASC) ;

ALTER TABLE `goods` COLLATE = utf8_general_ci , DROP COLUMN `winning_transaction_id` , ADD COLUMN `status` ENUM('open','closed') NOT NULL COMMENT 'Acceptable values: \"open\", \"closed\"'  AFTER `description` , ADD COLUMN `updated` DATETIME NOT NULL  AFTER `default_photo_id` , CHANGE COLUMN `title` `title` VARCHAR(200) NOT NULL  AFTER `id` , CHANGE COLUMN `user_id` `user_id` INT(10) UNSIGNED NOT NULL  AFTER `quantity` ;

ALTER TABLE `goods` CHANGE COLUMN `default_photo_id` `default_photo_id` INT(10) NOT NULL COMMENT 'References the Default Photo. References to all photos stored in the goods_photos table.'  AFTER `category_id`;

ALTER TABLE `goods` CHANGE COLUMN `type` `type` ENUM('gift','need') NOT NULL COMMENT 'Acceptable Values: \"gift\" and \"need\".'  
, ADD INDEX `default_photo_id` (`default_photo_id` ASC) 
, ADD INDEX `category_id` (`category_id` ASC) 
, DROP INDEX `title` , COMMENT = 'The `goods` data object is an abstraction which represents both gifts and needs.' ;

ALTER TABLE `goods_photos` COLLATE = utf8_general_ci ;

ALTER TABLE `goods_tags` COLLATE = utf8_general_ci , COMMENT = 'Relates `goods` with `tags`', DROP INDEX `good_id`, ADD INDEX `good_id` (`good_id` ASC);

ALTER TABLE `goods_transactions` COLLATE = utf8_general_ci , CHANGE COLUMN `request_id` `transaction_id` INT(10) UNSIGNED NOT NULL, CHANGE COLUMN `gift_id` `good_id` INT(10) UNSIGNED NOT NULL;

ALTER TABLE `goods_transactions`
  ADD CONSTRAINT `goods_transactions_ibfk_1`
  FOREIGN KEY (`good_id` )
  REFERENCES `goods` (`id` )
  ON UPDATE CASCADE, 
  ADD CONSTRAINT `goods_transactions_ibfk_2`
  FOREIGN KEY (`transaction_id` )
  REFERENCES `transactions` (`id` )
  ON UPDATE CASCADE
, DROP INDEX `gift_id` 
, ADD INDEX `gift_id` (`good_id` ASC) 
, DROP INDEX `request_id` 
, ADD INDEX `request_id` (`transaction_id` ASC) ;

ALTER TABLE `locations` COLLATE = utf8_general_ci , 
DROP COLUMN `country_id` , 
CHANGE COLUMN `user_id` `user_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'References `user`. `Users` may have multiple `locations`, but each `location` may only have one `user`.'  
AFTER `zip` , CHANGE COLUMN `title` `title` VARCHAR(100) NOT NULL COMMENT 'User-assigned title for this location'  , 
CHANGE COLUMN `address` `address` VARCHAR(100) NOT NULL COMMENT 'Full address. Combination of street address, city, state and zip.'  , 
CHANGE COLUMN `street_address` `street_address` VARCHAR(100) NOT NULL  , 
CHANGE COLUMN `city` `city` VARCHAR(25) NOT NULL  , 
CHANGE COLUMN `zip` `zip` VARCHAR(10) NOT NULL  , 
  ADD CONSTRAINT `locations_users`
  FOREIGN KEY (`user_id` )
  REFERENCES `users` (`id` )
  ON DELETE NO ACTION
  ON UPDATE NO ACTION
, ADD INDEX `user_id` (`user_id` ASC) 
, ADD INDEX `locations_users` (`user_id` ASC) 
, DROP INDEX `country_id` , COMMENT = 'The `locations` data model stores place information for both `users` and `goods`. Currently only supports locations in the United States.' ;

ALTER TABLE `messages` COLLATE = utf8_general_ci , DROP COLUMN `subject` , CHANGE COLUMN `author_id` `user_id` INT(10) UNSIGNED NOT NULL, CHANGE COLUMN `transaction_id` `transaction_id` INT(10) UNSIGNED NULL DEFAULT NULL  , 
  ADD CONSTRAINT `messages_ibfk_1`
  FOREIGN KEY (`user_id` )
  REFERENCES `users` (`id` )
  ON DELETE CASCADE
  ON UPDATE CASCADE, 
  ADD CONSTRAINT `messages_transactions_fk`
  FOREIGN KEY (`transaction_id` )
  REFERENCES `transactions` (`id` )
  ON DELETE CASCADE
  ON UPDATE CASCADE
, ADD INDEX `user_id` (`user_id` ASC) 
, ADD INDEX `transaction_id` (`transaction_id` ASC) 
, DROP INDEX `author_id` ;

ALTER TABLE `message_deliveries` COLLATE = utf8_general_ci , DROP COLUMN `deleted` , DROP COLUMN `opened` , CHANGE COLUMN `recipient_id` `user_id` INT(10) UNSIGNED NOT NULL , ADD COLUMN `created` DATETIME NOT NULL  AFTER `user_id` , DROP FOREIGN KEY `message_deliveries_ibfk_2` ;

ALTER TABLE `message_deliveries` 
  ADD CONSTRAINT `message_deliveries_ibfk_2`
  FOREIGN KEY (`user_id` )
  REFERENCES `users` (`id` )
  ON DELETE CASCADE
  ON UPDATE CASCADE
, COMMENT = 'When a message is sent, a row is inserted into this table for each of the recipients. When a recipient views their inbox, the messaging library looks to see if there are any unread messages by looking for rows in this table. When the recipient opens the message, the row is then deleted.' ;

ALTER TABLE `notifications` COLLATE = utf8_general_ci ;

ALTER TABLE `notification_types` COLLATE = utf8_general_ci ;

ALTER TABLE `openids` COLLATE = utf8_general_ci , 
  ADD CONSTRAINT `user_id`
  FOREIGN KEY (`user_id` )
  REFERENCES `users` (`id` )
  ON DELETE CASCADE
  ON UPDATE NO ACTION;

ALTER TABLE `photos` COLLATE = utf8_general_ci , COMMENT = 'The `photos` data model stores information about photos for both users and goods.' ;

ALTER TABLE `redirects` COLLATE = utf8_general_ci , COMMENT = 'Stores redirects so user can be sent to place they were intending to go to after registering or logging in.' ;

ALTER TABLE `roles` COLLATE = utf8_general_ci ;

ALTER TABLE `tags` COLLATE = utf8_general_ci ;

ALTER TABLE `terms` COLLATE = utf8_general_ci , ENGINE = InnoDB , CHANGE COLUMN `type` `type` ENUM('alert_template','language') NOT NULL COMMENT 'Acceptable values: \'alert_template\',\'language\''  , CHANGE COLUMN `language` `language` ENUM('english','spanish','german','french','russian','chinese') NOT NULL DEFAULT 'english' COMMENT 'Acceptable values: \'english\',\'spanish\',\'german\',\'french\',\'russian\',\'chinese\''  , COMMENT = 'The `terms` data model stores alert templates and notification templates.' ;

ALTER TABLE `threads` COLLATE = utf8_general_ci ;

ALTER TABLE `threads_users` COLLATE = utf8_general_ci , CHANGE COLUMN `recipient_id` `user_id` INT(10) UNSIGNED NOT NULL  AFTER `thread_id` , DROP FOREIGN KEY `threads_users_ibfk_2` ;

ALTER TABLE `threads_users` 
  ADD CONSTRAINT `threads_users_ibfk_2`
  FOREIGN KEY (`user_id` )
  REFERENCES `users` (`id` )
  ON DELETE CASCADE
  ON UPDATE CASCADE
, ADD INDEX `user_id` (`user_id` ASC) 
,  COMMENT = 'Join table relating `threads` to `users`, a many-to-many relationship' ;

ALTER TABLE `transactions` COLLATE = utf8_general_ci , CHANGE COLUMN `status` `status` ENUM('pending','ignored','active','completed') NULL DEFAULT NULL COMMENT 'Acceptable values: \'pending\',\'ignored\',\'active\',\'completed\''  , CHANGE COLUMN `giver_confirmed` `giver_confirmed` INT(1) NOT NULL COMMENT 'Boolean. Set to 1 when the giver confirms that a transaction has been completed.'  , CHANGE COLUMN `giver_rating` `giver_rating` ENUM('negative','neutral','positive') NULL DEFAULT NULL COMMENT 'Rating left by giver for recipient. Acceptable values: \"negative\",\"neutral\" or \"positive\".'  , CHANGE COLUMN `giver_feedback` `giver_feedback` VARCHAR(1000) NOT NULL COMMENT 'Feedback left by the giver for the recipient.'  , CHANGE COLUMN `recipient_rating` `recipient_rating` ENUM('negative','neutral','positive') NOT NULL COMMENT 'Rating left by recipient for the giver. Acceptable values: \"negative\",\"neutral\" or \"positive\".'  , CHANGE COLUMN `recipient_confirmed` `recipient_confirmed` INT(1) NOT NULL COMMENT 'Boolean. Set to 1 when the recipient confirms that a transaction has been completed.'  , CHANGE COLUMN `recipient_feedback` `recipient_feedback` VARCHAR(1000) NOT NULL COMMENT 'Feedback left by recipient for the giver.'  ;

ALTER TABLE `transactions_users` COLLATE = utf8_general_ci , CHANGE COLUMN `recipient_id` `user_id` INT(10) UNSIGNED NOT NULL  AFTER `id` , 
  ADD CONSTRAINT `transactions_users_ibfk_1`
  FOREIGN KEY (`user_id` )
  REFERENCES `users` (`id` )
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `users` COLLATE = utf8_general_ci , DROP COLUMN `aim` , DROP COLUMN `birthday_year` , DROP COLUMN `birthday_day` , DROP COLUMN `birthday_month` , DROP COLUMN `username` , ADD COLUMN `photo_source` ENUM('facebook','gravatar','giftflow') NOT NULL DEFAULT 'giftflow'  AFTER `registration_type` , CHANGE COLUMN `email` `email` VARCHAR(40) NOT NULL  AFTER `ip_address`;

ALTER TABLE `users` CHANGE COLUMN `activation_code` `activation_code` VARCHAR(40) NOT NULL  , CHANGE COLUMN `forgotten_password_code` `forgotten_password_code` VARCHAR(40) NOT NULL ;

ALTER TABLE `users` CHANGE COLUMN `status` `status` ENUM('active','pending','disabled') NULL DEFAULT 'active'  , CHANGE COLUMN `type` `type` ENUM('individual','institution') NOT NULL DEFAULT 'individual' COMMENT 'Acceptable values: \"Individual\" (default) or \"institution\"'  ;

ALTER TABLE `users` CHANGE COLUMN `facebook_data` `facebook_data` VARCHAR(10000) NOT NULL COMMENT 'JSON data returned by Facebook about this user'  , CHANGE COLUMN `facebook_photo` `facebook_photo` INT(10) NOT NULL COMMENT 'Deprecated. Use photo_source instead.'  , CHANGE COLUMN `registration_type` `registration_type` ENUM('facebook','manual') NOT NULL DEFAULT 'manual' COMMENT 'How did the user register? Acceptable values: \"facebook\" or \"manual\"', CHANGE COLUMN `default_photo_id` `default_photo_id` INT(10) UNSIGNED NULL DEFAULT NULL  , CHANGE COLUMN `default_location_id` `default_location_id` INT(10) UNSIGNED NULL DEFAULT NULL;

ALTER TABLE `users` 
 ADD INDEX `locations_users_fk` (`default_location_id` ASC) 
, ADD INDEX `roles_users` (`role_id` ASC) 
, ADD INDEX `photos_users` (`default_photo_id` ASC) ;
ALTER TABLE `users`
  ADD CONSTRAINT `locations_users_fk`
  FOREIGN KEY (`default_location_id` )
  REFERENCES `locations` (`id` )
  ON DELETE SET NULL
  ON UPDATE NO ACTION;
ALTER TABLE `users` 
  ADD CONSTRAINT `roles_users`
  FOREIGN KEY (`role_id` )
  REFERENCES `roles` (`id` )
  ON DELETE CASCADE
  ON UPDATE NO ACTION;
ALTER TABLE `users` 
  ADD CONSTRAINT `photos_users`
  FOREIGN KEY (`default_photo_id` )
  REFERENCES `photos` (`id` )
  ON DELETE SET NULL
  ON UPDATE NO ACTION;

ALTER TABLE `user_settings` COLLATE = utf8_general_ci , CHANGE COLUMN `notify_messages` `notify_messages` INT(1) NOT NULL DEFAULT '1'  AFTER `user_id` ;

DROP TABLE IF EXISTS `permissions` ;

DROP TABLE IF EXISTS `google_account_details` ;

DROP TABLE IF EXISTS `account_openid` ;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


-- --------------------------------------------------------------------
-- v1 to v1.1
-- --------------------------------------------------------------------
DROP TABLE `comments`;
RENAME TABLE `openids` TO `user_openids` ;

-- DROP OLD TRANSACTION AND MESSAGE DATA --
DROP TABLE `goods_transactions`;
DROP TABLE `transactions_users`;
ALTER TABLE `messages` DROP FOREIGN KEY `messages_transactions_fk` ;
DROP TABLE `transactions`;
SET SQL_SAFE_UPDATES=0;
TRUNCATE TABLE `message_deliveries`;
TRUNCATE TABLE `messages`;
TRUNCATE TABLE `threads_users`;
TRUNCATE TABLE `threads`;

-- CONVERT ROLE_IDs to ROLES --
ALTER TABLE `users` DROP FOREIGN KEY `user_ibfk_3` ;
ALTER TABLE `users` DROP FOREIGN KEY `roles_users` ;
ALTER TABLE `users` ADD `role` ENUM( 'admin', 'user' ) NOT NULL DEFAULT 'user' AFTER `role_id`;
UPDATE `users` AS U JOIN `roles` AS R ON U.role_id = R.id SET U.`role`='admin' WHERE R.level=100;
DROP TABLE `roles`;

-- FIX OLD DATA ANAMOLIES --
UPDATE `users` SET status='active';
UPDATE `users` SET activation_code='' WHERE activation_code=0;
UPDATE `users` SET forgotten_password_code='' WHERE forgotten_password_code=0;
UPDATE `locations` SET zip='06520' WHERE zip='6520';
UPDATE `locations` SET zip='06511' WHERE zip='6511';
UPDATE `locations` SET zip='06510' WHERE zip='6510';
UPDATE `terms` SET name='demand_take_new' WHERE id=11;
SET SQL_SAFE_UPDATES=1;

-- --------------------------------------------------------------------
-- v1.1 to v2
-- --------------------------------------------------------------------

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ALLOW_INVALID_DATES';

ALTER TABLE `users` DROP FOREIGN KEY `user_ibfk_2` , DROP FOREIGN KEY `user_ibfk_1` ;

ALTER TABLE `locations` DROP FOREIGN KEY `locations_users` ;

ALTER TABLE `goods` DROP FOREIGN KEY `goods_ibfk_2` ;

ALTER TABLE `messages` DROP FOREIGN KEY `messages_ibfk_1` , DROP FOREIGN KEY `messages_ibfk_2` ;

ALTER TABLE `categories` DROP COLUMN `created` , ADD COLUMN `parent_category_id` INT(10) UNSIGNED NULL DEFAULT NULL  AFTER `name` ;

ALTER TABLE `ci_sessions` CHANGE COLUMN `session_id` `session_id` VARCHAR(40) NOT NULL DEFAULT '0'  , CHANGE COLUMN `ip_address` `ip_address` VARCHAR(16) NOT NULL DEFAULT '0'  , CHANGE COLUMN `user_agent` `user_agent` VARCHAR(50) NOT NULL  , CHANGE COLUMN `user_data` `user_data` TEXT NOT NULL  ;

ALTER TABLE `photos` CHANGE COLUMN `url` `url` VARCHAR(1000) NOT NULL  , CHANGE COLUMN `thumb_url` `thumb_url` VARCHAR(1000) NOT NULL  , CHANGE COLUMN `caption` `caption` VARCHAR(200) NOT NULL  ;

ALTER TABLE `users` DROP COLUMN `role_id` , CHANGE COLUMN `bio` `bio` VARCHAR(5000) NOT NULL  , CHANGE COLUMN `google_token` `google_token` VARCHAR(100) NOT NULL COMMENT 'Google Data OAuth access token'  , CHANGE COLUMN `google_token_secret` `google_token_secret` VARCHAR(100) NOT NULL COMMENT 'Google Data OAuth secret access token'  , CHANGE COLUMN `facebook_token` `facebook_token` VARCHAR(500) NOT NULL  , CHANGE COLUMN `facebook_data` `facebook_data` VARCHAR(5000) NOT NULL COMMENT 'JSON data returned by Facebook about this user'  
, DROP INDEX `roles_users` 
, DROP INDEX `user_ibfk_3` 
, DROP INDEX `user_ibfk_2` 
, DROP INDEX `user_ibfk_1` ;

ALTER TABLE `locations` CHANGE COLUMN `zip` `postal_code` VARCHAR(10) NOT NULL  AFTER `state` , ADD COLUMN `country` VARCHAR(100) NOT NULL DEFAULT 'United States'  AFTER `postal_code` , CHANGE COLUMN `address` `address` VARCHAR(150) NOT NULL COMMENT 'Full address. Combination of street address, city, state and zip.'  , CHANGE COLUMN `city` `city` VARCHAR(100) NOT NULL  , CHANGE COLUMN `state` `state` VARCHAR(100) NOT NULL  , 
  ADD CONSTRAINT `locations_users`
  FOREIGN KEY (`user_id` )
  REFERENCES `users` (`id` )
  ON DELETE CASCADE
  ON UPDATE CASCADE;

ALTER TABLE `goods` ADD COLUMN `shareable` INT(1) NOT NULL DEFAULT 0 COMMENT '1=Gift can be shared\n0=Gift can\'t be shared'  AFTER `quantity` , CHANGE COLUMN `updated` `updated` DATETIME NOT NULL  AFTER `default_photo_id` , CHANGE COLUMN `description` `description` VARCHAR(5000) NOT NULL  , CHANGE COLUMN `status` `status` ENUM('active','unavailable','disabled') NOT NULL COMMENT 'Acceptable values: \"active\",\"disabled\"'  , CHANGE COLUMN `user_id` `user_id` INT(10) UNSIGNED NULL DEFAULT NULL  , CHANGE COLUMN `category_id` `category_id` INT(11) UNSIGNED NULL DEFAULT NULL  , CHANGE COLUMN `default_photo_id` `default_photo_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'References the Default Photo. References to all photos stored in the goods_photos table.'  , 
  ADD CONSTRAINT `goods_ibfk_2`
  FOREIGN KEY (`user_id` )
  REFERENCES `users` (`id` )
  ON DELETE CASCADE
  ON UPDATE CASCADE, 
  ADD CONSTRAINT `goods_ibfk_3`
  FOREIGN KEY (`category_id` )
  REFERENCES `categories` (`id` )
  ON DELETE SET NULL
  ON UPDATE CASCADE;

ALTER TABLE `tags` DROP COLUMN `created` ;

ALTER TABLE `threads` CHANGE COLUMN `subject` `subject` VARCHAR(250) NOT NULL  ;

CREATE  TABLE IF NOT EXISTS `transactions` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `status` ENUM('pending','declined','cancelled','disabled','active','completed') NOT NULL DEFAULT 'pending' ,
  `updated` DATETIME NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 56
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

ALTER TABLE `messages` CHANGE COLUMN `transaction_id` `transaction_id` INT(10) UNSIGNED NULL DEFAULT NULL  AFTER `user_id` , 
  ADD CONSTRAINT `messages_ibfk_1`
  FOREIGN KEY (`user_id` )
  REFERENCES `users` (`id` )
  ON DELETE RESTRICT
  ON UPDATE CASCADE, 
  ADD CONSTRAINT `messages_ibfk_2`
  FOREIGN KEY (`thread_id` )
  REFERENCES `threads` (`id` )
  ON DELETE RESTRICT, 
  ADD CONSTRAINT `messages_transactions_fk`
  FOREIGN KEY (`transaction_id` )
  REFERENCES `transactions` (`id` )
  ON DELETE RESTRICT, COMMENT = 'Single messages, connected via thread or transaction' ;

ALTER TABLE `notifications` CHANGE COLUMN `opened` `opened` DATETIME NULL DEFAULT NULL  AFTER `user_id` ;

ALTER TABLE `user_openids` DROP COLUMN `created`;

CREATE  TABLE IF NOT EXISTS `demands` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `type` ENUM('give','take','borrow','share','fulfill') NOT NULL,
  `transaction_id` INT(10) UNSIGNED NOT NULL ,
  `good_id` INT(10) UNSIGNED NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  `updated` DATETIME NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id` (`user_id` ASC) ,
  INDEX `transaction_id` (`transaction_id` ASC) ,
  INDEX `good_id` (`good_id` ASC) ,
  CONSTRAINT `demands_goods_ibfk_1`
    FOREIGN KEY (`good_id` )
    REFERENCES `goods` (`id` )
    ON DELETE CASCADE,
  CONSTRAINT `demands_transactions_ibfk_1`
    FOREIGN KEY (`transaction_id` )
    REFERENCES `transactions` (`id` )
    ON DELETE CASCADE,
  CONSTRAINT `demands_users_ibfk_1`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

CREATE  TABLE IF NOT EXISTS `reviews` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `rating` ENUM('positive','neutral','negative') NOT NULL ,
  `body` VARCHAR(1000) NOT NULL ,
  `transaction_id` INT(11) UNSIGNED NULL DEFAULT NULL ,
  `reviewer_id` INT(11) UNSIGNED NULL DEFAULT NULL ,
  `reviewed_id` INT(11) UNSIGNED NULL DEFAULT NULL ,
  `updated` DATETIME NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `reviews_users_infk_1` (`reviewer_id` ASC) ,
  INDEX `reviews_users_infk_2` (`reviewed_id` ASC) ,
  INDEX `reviews_transactions_infk_1` (`transaction_id` ASC) ,
  CONSTRAINT `reviews_ibfk_6`
    FOREIGN KEY (`reviewed_id` )
    REFERENCES `users` (`id` ),
  CONSTRAINT `reviews_ibfk_4`
    FOREIGN KEY (`transaction_id` )
    REFERENCES `transactions` (`id` ),
  CONSTRAINT `reviews_ibfk_5`
    FOREIGN KEY (`reviewer_id` )
    REFERENCES `users` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE  TABLE IF NOT EXISTS `transactions_users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` INT(10) UNSIGNED NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`),
 INDEX `transactions_users_infk_1` (`user_id` ASC) ,
 INDEX `transactions_users_infk_2` (`transaction_id` ASC) ,
  CONSTRAINT `transactions_users_infk_3`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` ),
  CONSTRAINT `transactions_users_ibfk_4`
    FOREIGN KEY (`transaction_id` )
    REFERENCES `transactions` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_general_ci;

ALTER TABLE `user_settings` DROP COLUMN `photo_source` ;

-- -----------------------------------------------------------------------------
-- v2 to v2.1
-- -----------------------------------------------------------------------------

ALTER TABLE `users` DROP FOREIGN KEY `photos_users` ;

ALTER TABLE `goods` 
  ADD CONSTRAINT `goods_ibfk_4`
  FOREIGN KEY (`default_photo_id` )
  REFERENCES `photos` (`id` )
  ON DELETE SET NULL
  ON UPDATE CASCADE
, ADD INDEX `goods_ibfk_4` (`default_photo_id` ASC) ;

ALTER TABLE `photos` ADD COLUMN `good_id` INT(10) UNSIGNED NULL DEFAULT NULL  AFTER `user_id` , 
  ADD CONSTRAINT `photos_ibfk_2`
  FOREIGN KEY (`good_id` )
  REFERENCES `goods` (`id` )
  ON DELETE CASCADE
  ON UPDATE CASCADE
, ADD INDEX `photos_ibfk_2` (`good_id` ASC) ;

ALTER TABLE `users` 
  ADD CONSTRAINT `photos_users`
  FOREIGN KEY (`default_photo_id` )
  REFERENCES `photos` (`id` )
  ON DELETE SET NULL
  ON UPDATE CASCADE;

DROP TABLE IF EXISTS `goods_photos` ;

ALTER TABLE `locations` 
ADD INDEX `latlng` (`latitude` ASC, `longitude` ASC) ;

ALTER TABLE `users` 
ADD INDEX `user_screen_name` (`screen_name` ASC) 
, ADD INDEX `user_first_name` (`first_name` ASC) 
, ADD INDEX `user_last_name` (`last_name` ASC) ;

ALTER TABLE `goods` 
ADD INDEX `good_type` (`type` ASC) 
, ADD INDEX `good_title` (`title` ASC) 
, DROP INDEX `goods_ibfk_4` ;

ALTER TABLE `tags` ADD COLUMN `count` INT(10) NOT NULL DEFAULT 0  AFTER `name` ;

-- -----------------------------------------------------------------------------
-- v2.1 to v2.2
-- 2011-02-01 to 2011-07-28
-- -----------------------------------------------------------------------------

ALTER TABLE `messages` ADD COLUMN `type` ENUM('message','activated','declined','cancelled') NOT NULL DEFAULT 'message'  AFTER `thread_id`;

ALTER TABLE `notifications` DROP FOREIGN KEY `notifications_ibfk_1` , DROP FOREIGN KEY `notifications_ibfk_2` ;
ALTER TABLE  `notifications` DROP INDEX  `notification_type_id`;

DROP TABLE IF EXISTS `notification_types`;
DROP TABLE IF EXISTS `events`;
DROP TABLE IF EXISTS `notifications`;

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `event_type_id` int(10) unsigned NOT NULL,
  `data` varchar(10000) NOT NULL,
  `user_id` int(10) unsigned DEFAULT NULL,
  `transaction_id` int(10) unsigned DEFAULT NULL,
  `message_id` int(10) unsigned DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `notification_type_id` (`event_type_id`),
  KEY `user_id` (`user_id`),
  KEY `notifications_ibfk_3` (`message_id`),
  KEY `notifications_ibfk_4` (`transaction_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

CREATE TABLE IF NOT EXISTS `event_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_users` (`user_id`),
  KEY `notifications_events` (`event_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

CREATE OR REPLACE VIEW `events_view` 
AS select `E`.`id` AS `id`,`ET`.`title` AS `event_type`,`E`.`data` AS `data`,`E`.`user_id` AS `user_id`,`E`.`transaction_id` AS `transaction_id`,`E`.`message_id` AS `message_id`,`E`.`created` AS `created` from (`events` `E` join `event_types` `ET` on((`E`.`event_type_id` = `ET`.`id`))) order by `E`.`id`;

CREATE OR REPLACE VIEW `notifications_view` 
AS select `N`.`id` AS `id`,`N`.`event_id` AS `event_id`,`ET`.`title` AS `event_type`,`N`.`user_id` AS `user_id`,`N`.`enabled` AS `enabled`,`N`.`created` AS `created` from ((`notifications` `N` join `events` `E` on((`N`.`event_id` = `E`.`id`))) join `event_types` `ET` on((`E`.`event_type_id` = `ET`.`id`))) order by `N`.`id`;

ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`event_type_id`) REFERENCES `event_types` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `notifications_ibfk_3` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `notifications_ibfk_4` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
  
CREATE  OR REPLACE VIEW `goods_view` AS select `G`.`id` AS `good_id`,`G`.`type` AS `good_type`,`G`.`title` AS `good_title`,`G`.`shareable` AS `good_shareable`,`G`.`description` AS `good_description`,`G`.`status` AS `good_status`,`G`.`created` AS `good_created`,`L`.`id` AS `location_id`,`L`.`title` AS `location_title`,`L`.`address` AS `location_address`,`L`.`latitude` AS `location_latitude`,`L`.`longitude` AS `location_longitude`,`L`.`street_address` AS `location_street_address`,`L`.`city` AS `location_city`,`L`.`state` AS `location_state`,`L`.`postal_code` AS `location_postal_code`,`L`.`country` AS `location_country`,`P`.`id` AS `photo_id`,`P`.`url` AS `photo_url`,`P`.`thumb_url` AS `photo_thumb_url`,`P`.`caption` AS `photo_caption`,`U`.`id` AS `user_id`,`U`.`email` AS `user_email`,`U`.`type` AS `user_type`,`U`.`first_name` AS `user_first_name`,`U`.`last_name` AS `user_last_name`,`U`.`screen_name` AS `user_screen_name`,`U`.`bio` AS `user_bio`,`U`.`created` AS `user_created`,`U`.`occupation` AS `user_occupation`,`U`.`photo_source` AS `user_photo_source`,`U`.`facebook_id` AS `user_facebook_id`,`UP`.`id` AS `user_photo_id`,`UP`.`url` AS `user_photo_url`,`UP`.`thumb_url` AS `user_photo_thumb_url`,`UP`.`caption` AS `user_photo_caption`,`C`.`id` AS `category_id`,`C`.`name` AS `category_name` from (((((`goods` `G` join `users` `U` on((`G`.`user_id` = `U`.`id`))) join `locations` `L` on((`L`.`id` = `G`.`location_id`))) left join `photos` `P` on((`G`.`default_photo_id` = `P`.`id`))) left join `photos` `UP` on((`U`.`default_photo_id` = `UP`.`id`))) left join `categories` `C` on((`C`.`id` = `G`.`category_id`)));

CREATE VIEW `demands_view` AS select `D`.`id` AS `demand_id`,`D`.`type` AS `demand_type`,`T`.`id` AS `transaction_id`,`T`.`status` AS `transaction_status`,`T`.`created` AS `transaction_created`,`DU`.`id` AS `demander_id`,`DU`.`screen_name` AS `demander_screen_name`,`DU`.`email` AS `demander_email`,`DU`.`photo_source` AS `demander_photo_source`,`DUP`.`url` AS `demander_photo_url`,`DUP`.`thumb_url` AS `demander_photo_thumb_url`,`DU`.`facebook_id` AS `demander_facebook_id`,`OU`.`id` AS `decider_id`,`OU`.`screen_name` AS `decider_screen_name`,`OU`.`email` AS `decider_email`,`OU`.`photo_source` AS `decider_photo_source`,`OUP`.`url` AS `decider_photo_url`,`OUP`.`thumb_url` AS `decider_photo_thumb_url`,`OU`.`facebook_id` AS `decider_facebook_id`,`g`.`good_id` AS `good_id`,`g`.`good_title` AS `good_title`,`g`.`good_type` AS `good_type`,`g`.`good_shareable` AS `good_shareable`,`g`.`good_status` AS `good_status`,`g`.`category_id` AS `good_category_id`,`g`.`category_name` AS `good_category`,`g`.`photo_id` AS `good_photo_id`,`g`.`photo_url` AS `good_photo_url`,`g`.`photo_thumb_url` AS `good_photo_thumb_url`,`g`.`user_id` AS `good_user_id`,`g`.`user_screen_name` AS `good_user_screen_name`,`g`.`user_email` AS `good_user_email` from (((((((`demands` `D` join `transactions` `T` on((`T`.`id` = `D`.`transaction_id`))) join `transactions_users` `TU` on((`T`.`id` = `TU`.`transaction_id`))) join `goods_view` `g` on((`g`.`good_id` = `D`.`good_id`))) join `users` `DU` on((`D`.`user_id` = `DU`.`id`))) join `users` `OU` on(((`TU`.`user_id` = `OU`.`id`) and (`OU`.`id` <> `DU`.`id`)))) left join `photos` `DUP` on((`DU`.`default_photo_id` = `DUP`.`id`))) left join `photos` `OUP` on((`OU`.`default_photo_id` = `OUP`.`id`))) group by `D`.`id`;

-- -----------------------------------------------------------------------------
-- v2.2 to v2.3
-- 2011-07-28 to 2011-09-02
-- Overwrites the bug infested 2011-08-08
-- -----------------------------------------------------------------------------
ALTER TABLE  `terms` CHANGE  `language`  `language` ENUM(  'en',  'es',  'fr',  'de',  'it' , 'nl' , 'sv' , 'no', 'da', 'fi' , 'is' , 'ru' , 'et' , 'lv', 'pl' , 'pt' , 'ja') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT  'en';

ALTER TABLE `events` DROP FOREIGN KEY `notifications_ibfk_1`;
DROP INDEX `notification_type_id` ON `events`;

CREATE INDEX `event_type` ON `events` (`event_type_id` ASC) ;

ALTER TABLE `events` ADD CONSTRAINT `notifications_ibfk_12` FOREIGN KEY (`event_type_id` ) REFERENCES `event_types` (`id` )  ON UPDATE CASCADE;

-- -----------------------------------------------------------------------------
-- v2.3 to v2.4
-- 2011-09-02 to 2011-09-07
-- Adds language and timezone fields to user database table, 
-- drops message_delivies
-- -----------------------------------------------------------------------------
ALTER TABLE  `users` 
	ADD  `language` ENUM('en','es','fr','de','it','nl','sv','no','da','fi','is','ru','et','lv','pl','pt','ja') NOT NULL DEFAULT 'en' AFTER `photo_source`,
	ADD  `timezone` VARCHAR( 100 ) NOT NULL DEFAULT 'America/New_York' AFTER  `language`;
	
DROP TABLE `message_deliveries`;

-- ---------------------------------------------------------------------------
-- v2.4 to 2.5
-- 2011-09-02 to 2011-11-02
-- WTF is up with these euro-dates?
-- Add nonprofit and business to user type enum
-- ---------------------------------------------------------------------------
ALTER TABLE users MODIFY COLUMN type ENUM('individual','nonprofit', 'business');



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;