SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `categories`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `categories` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(100) NOT NULL ,
  `parent_category_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ci_sessions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` VARCHAR(40) NOT NULL DEFAULT '0' ,
  `ip_address` VARCHAR(16) NOT NULL DEFAULT '0' ,
  `user_agent` VARCHAR(50) NOT NULL ,
  `last_activity` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `user_data` TEXT NOT NULL ,
  PRIMARY KEY (`session_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'Stores session information.' ;


-- -----------------------------------------------------
-- Table `goods`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `goods` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(200) NOT NULL ,
  `type` ENUM('gift','need') NOT NULL COMMENT 'Acceptable Values: \"gift\" and \"need\".' ,
  `description` VARCHAR(5000) NOT NULL ,
  `status` ENUM('active','unavailable','disabled') NOT NULL COMMENT 'Acceptable values: \"active\",\"disabled\"' ,
  `quantity` INT(10) NOT NULL ,
  `shareable` INT(1) NOT NULL DEFAULT '0' COMMENT '1=Gift can be shared\n0=Gift can\'t be shared' ,
  `user_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `location_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `category_id` INT(11) UNSIGNED NULL DEFAULT NULL ,
  `default_photo_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'References the Default Photo. References to all photos stored in the goods_photos table.' ,
  `updated` DATETIME NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `goods_ibfk_2`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `goods_ibfk_3`
    FOREIGN KEY (`category_id` )
    REFERENCES `categories` (`id` )
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `goods_ibfk_1`
    FOREIGN KEY (`location_id` )
    REFERENCES `locations` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `goods_ibfk_4`
    FOREIGN KEY (`default_photo_id` )
    REFERENCES `photos` (`id` )
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 358
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'The `goods` data object is an abstraction which represents b' ;

CREATE INDEX `location_id` ON `goods` (`location_id` ASC) ;

CREATE INDEX `user_id` ON `goods` (`user_id` ASC) ;

CREATE INDEX `default_photo_id` ON `goods` (`default_photo_id` ASC) ;

CREATE INDEX `category_id` ON `goods` (`category_id` ASC) ;

CREATE INDEX `good_type` ON `goods` (`type` ASC) ;

CREATE INDEX `good_title` ON `goods` (`title` ASC) ;


-- -----------------------------------------------------
-- Table `photos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `photos` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `good_id` INT UNSIGNED NULL DEFAULT NULL ,
  `url` VARCHAR(1000) NOT NULL ,
  `thumb_url` VARCHAR(1000) NOT NULL ,
  `caption` VARCHAR(200) NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `photos_ibfk_1`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `photos_ibfk_2`
    FOREIGN KEY (`good_id` )
    REFERENCES `goods` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 83
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'The `photos` data model stores information about photos for ' ;

CREATE INDEX `user_id` ON `photos` (`user_id` ASC) ;

CREATE INDEX `photos_ibfk_2` ON `photos` (`good_id` ASC) ;


-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `users` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `role` ENUM('admin','user') NOT NULL DEFAULT 'user' ,
  `ip_address` CHAR(16) NOT NULL ,
  `email` VARCHAR(40) NOT NULL ,
  `password` VARCHAR(40) NOT NULL ,
  `activation_code` VARCHAR(40) NOT NULL ,
  `forgotten_password_code` VARCHAR(40) NOT NULL ,
  `salt` VARCHAR(50) NOT NULL ,
  `status` ENUM('active','pending','disabled') NULL DEFAULT 'active' ,
  `type` ENUM('individual','institution') NOT NULL DEFAULT 'individual' COMMENT 'Acceptable values: \"Individual\" (default) or \"institution\"' ,
  `first_name` VARCHAR(100) NOT NULL ,
  `last_name` VARCHAR(100) NOT NULL ,
  `screen_name` VARCHAR(100) NOT NULL ,
  `bio` VARCHAR(5000) NOT NULL ,
  `url` VARCHAR(100) NOT NULL COMMENT 'with http://www | ie http://www.brandonsdesign.com' ,
  `occupation` VARCHAR(100) NOT NULL ,
  `phone` VARCHAR(20) NOT NULL COMMENT 'ie (615) 500-7845' ,
  `google_token` VARCHAR(100) NOT NULL COMMENT 'Google Data OAuth access token' ,
  `google_token_secret` VARCHAR(100) NOT NULL COMMENT 'Google Data OAuth secret access token' ,
  `facebook_link` VARCHAR(50) NOT NULL ,
  `facebook_id` VARCHAR(100) NOT NULL ,
  `facebook_token` VARCHAR(500) NOT NULL ,
  `facebook_data` VARCHAR(5000) NOT NULL COMMENT 'JSON data returned by Facebook about this user' ,
  `facebook_photo` INT(10) NOT NULL COMMENT 'Deprecated. Use photo_source instead.' ,
  `registration_type` ENUM('facebook','manual') NOT NULL DEFAULT 'manual' COMMENT 'How did the user register? Acceptable values: \"facebook\" or \"manual\"' ,
  `photo_source` ENUM('facebook','gravatar','giftflow') NOT NULL DEFAULT 'giftflow' ,
  `default_photo_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `default_location_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `updated` DATETIME NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `locations_users_fk`
    FOREIGN KEY (`default_location_id` )
    REFERENCES `locations` (`id` )
    ON DELETE SET NULL
    ON UPDATE NO ACTION,
  CONSTRAINT `photos_users`
    FOREIGN KEY (`default_photo_id` )
    REFERENCES `photos` (`id` )
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 532
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `locations_users_fk` ON `users` (`default_location_id` ASC) ;

CREATE INDEX `photos_users` ON `users` (`default_photo_id` ASC) ;

CREATE INDEX `user_screen_name` ON `users` (`screen_name` ASC) ;

CREATE INDEX `user_first_name` ON `users` (`first_name` ASC) ;

CREATE INDEX `user_last_name` ON `users` (`last_name` ASC) ;


-- -----------------------------------------------------
-- Table `locations`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `locations` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(100) NOT NULL COMMENT 'User-assigned title for this location' ,
  `address` VARCHAR(150) NOT NULL COMMENT 'Full address. Combination of street address, city, state and zip.' ,
  `latitude` FLOAT NOT NULL ,
  `longitude` FLOAT NOT NULL ,
  `street_address` VARCHAR(100) NOT NULL ,
  `city` VARCHAR(100) NOT NULL ,
  `state` VARCHAR(100) NOT NULL ,
  `postal_code` VARCHAR(10) NOT NULL ,
  `country` VARCHAR(100) NOT NULL DEFAULT 'United States' ,
  `user_id` INT(10) UNSIGNED NULL DEFAULT NULL COMMENT 'References `user`. `Users` may have multiple `locations`, but each `location` may only have one `user`.' ,
  `updated` DATETIME NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `locations_users`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 241
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'The `locations` data model stores place information for both' ;

CREATE INDEX `user_id` ON `locations` (`user_id` ASC) ;

CREATE INDEX `locations_users` ON `locations` (`user_id` ASC) ;

CREATE INDEX `latlng` ON `locations` (`latitude` ASC, `longitude` ASC) ;


-- -----------------------------------------------------
-- Table `transactions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `transactions` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `status` ENUM('pending','declined','cancelled','disabled','active','completed') NOT NULL DEFAULT 'pending' ,
  `updated` DATETIME NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 56
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `demands`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `demands` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `type` ENUM('give','take','borrow','share','fulfill') NOT NULL ,
  `transaction_id` INT(10) UNSIGNED NOT NULL ,
  `good_id` INT(10) UNSIGNED NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  `updated` DATETIME NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
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
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `user_id` ON `demands` (`user_id` ASC) ;

CREATE INDEX `transaction_id` ON `demands` (`transaction_id` ASC) ;

CREATE INDEX `good_id` ON `demands` (`good_id` ASC) ;


-- -----------------------------------------------------
-- Table `followings_users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `followings_users` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `following_id` INT(10) UNSIGNED NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `followings_users_ibfk_1`
    FOREIGN KEY (`following_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `followings_users_ibfk_2`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 118
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `following_id` ON `followings_users` (`following_id` ASC) ;

CREATE INDEX `user_id` ON `followings_users` (`user_id` ASC) ;


-- -----------------------------------------------------
-- Table `tags`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tags` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(100) NOT NULL ,
  `count` INT(10) NOT NULL DEFAULT 0 ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 483
DEFAULT CHARACTER SET = utf8;

CREATE UNIQUE INDEX `name` ON `tags` (`name` ASC) ;


-- -----------------------------------------------------
-- Table `goods_tags`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `goods_tags` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `good_id` INT(10) UNSIGNED NOT NULL ,
  `tag_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `goods_tags_ibfk_1`
    FOREIGN KEY (`good_id` )
    REFERENCES `goods` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `goods_tags_ibfk_2`
    FOREIGN KEY (`tag_id` )
    REFERENCES `tags` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 837
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'Relates `goods` with `tags`' ;

CREATE INDEX `good_id` ON `goods_tags` (`good_id` ASC) ;

CREATE INDEX `tag_id` ON `goods_tags` (`tag_id` ASC) ;


-- -----------------------------------------------------
-- Table `threads`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `threads` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `subject` VARCHAR(250) NOT NULL ,
  `updated` DATETIME NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `messages`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `messages` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  `transaction_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `thread_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `type` ENUM('message','activated','declined','cancelled') NOT NULL DEFAULT message ,
  `body` VARCHAR(10000) NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `messages_ibfk_1`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `messages_ibfk_2`
    FOREIGN KEY (`thread_id` )
    REFERENCES `threads` (`id` ),
  CONSTRAINT `messages_transactions_fk`
    FOREIGN KEY (`transaction_id` )
    REFERENCES `transactions` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'Single messages, connected via thread or transaction' ;

CREATE INDEX `thread_id` ON `messages` (`thread_id` ASC) ;

CREATE INDEX `user_id` ON `messages` (`user_id` ASC) ;

CREATE INDEX `transaction_id` ON `messages` (`transaction_id` ASC) ;


-- -----------------------------------------------------
-- Table `message_deliveries`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `message_deliveries` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `message_id` INT(10) UNSIGNED NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `message_deliveries_ibfk_2`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `message_deliveries_ibfk_1`
    FOREIGN KEY (`message_id` )
    REFERENCES `messages` (`id` )
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'When a message is sent, a row is inserted into this table fo' ;

CREATE INDEX `message_id` ON `message_deliveries` (`message_id` ASC) ;

CREATE INDEX `recipient_id` ON `message_deliveries` (`user_id` ASC) ;


-- -----------------------------------------------------
-- Table `event_types`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `event_types` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;

CREATE UNIQUE INDEX `event_type_title` ON `event_types` (`title` ASC) ;


-- -----------------------------------------------------
-- Table `events`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `events` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `notification_type_id` INT(3) UNSIGNED NOT NULL ,
  `data` VARCHAR(10000) NOT NULL ,
  `user_id` INT(10) UNSIGNED NULL ,
  `transaction_id` INT(10) UNSIGNED NULL ,
  `message_id` INT(10) UNSIGNED NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `notifications_ibfk_1`
    FOREIGN KEY (`notification_type_id` )
    REFERENCES `event_types` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `notifications_ibfk_2`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `notifications_ibfk_3`
    FOREIGN KEY (`message_id` )
    REFERENCES `messages` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `notifications_ibfk_4`
    FOREIGN KEY (`transaction_id` )
    REFERENCES `transactions` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `notification_type_id` ON `events` (`notification_type_id` ASC) ;

CREATE INDEX `user_id` ON `events` (`user_id` ASC) ;

CREATE INDEX `notifications_ibfk_3` ON `events` (`message_id` ASC) ;

CREATE INDEX `notifications_ibfk_4` ON `events` (`transaction_id` ASC) ;


-- -----------------------------------------------------
-- Table `redirects`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `redirects` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `url` VARCHAR(100) NOT NULL ,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1227
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'Stores redirects so user can be sent to place they were inte' ;


-- -----------------------------------------------------
-- Table `reviews`
-- -----------------------------------------------------
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

CREATE INDEX `reviews_users_infk_1` ON `reviews` (`reviewer_id` ASC) ;

CREATE INDEX `reviews_users_infk_2` ON `reviews` (`reviewed_id` ASC) ;

CREATE INDEX `reviews_transactions_infk_1` ON `reviews` (`transaction_id` ASC) ;


-- -----------------------------------------------------
-- Table `terms`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `terms` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `type` ENUM('alert_template','language') NOT NULL COMMENT 'Acceptable values: \'alert_template\',\'language\'' ,
  `language` ENUM('english','spanish','german','french','russian','chinese') NOT NULL DEFAULT 'english' COMMENT 'Acceptable values: \'english\',\'spanish\',\'german\',\'french\',\'russian\',\'chinese\'' ,
  `name` VARCHAR(255) NOT NULL ,
  `subject` VARCHAR(200) NOT NULL COMMENT 'title/subject of alert notifications' ,
  `body` TEXT NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 12
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'The `terms` data model stores alert templates and notificati' ;

CREATE UNIQUE INDEX `name` ON `terms` (`name` ASC) ;


-- -----------------------------------------------------
-- Table `threads_users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `threads_users` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `thread_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `user_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `threads_users_ibfk_2`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `threads_users_ibfk_1`
    FOREIGN KEY (`thread_id` )
    REFERENCES `threads` (`id` )
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'Join table relating `threads` to `users`, a many-to-many rel' ;

CREATE INDEX `thread_id` ON `threads_users` (`thread_id` ASC) ;

CREATE INDEX `recipient_id` ON `threads_users` (`user_id` ASC) ;

CREATE INDEX `user_id` ON `threads_users` (`user_id` ASC) ;


-- -----------------------------------------------------
-- Table `transactions_users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `transactions_users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `transaction_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `user_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `transactions_users_infk_3`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` ),
  CONSTRAINT `transactions_users_ibfk_4`
    FOREIGN KEY (`transaction_id` )
    REFERENCES `transactions` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `transactions_users_infk_1` ON `transactions_users` (`user_id` ASC) ;

CREATE INDEX `transactions_users_infk_2` ON `transactions_users` (`transaction_id` ASC) ;


-- -----------------------------------------------------
-- Table `user_openids`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `user_openids` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `openid` VARCHAR(240) NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `user_id`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 15
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;

CREATE INDEX `user_id` ON `user_openids` (`user_id` ASC) ;


-- -----------------------------------------------------
-- Table `user_settings`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `user_settings` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  `notify_messages` INT(1) NOT NULL DEFAULT '1' ,
  `updated` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  CONSTRAINT `user_settings_ibfk_1`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 458
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `user_id` ON `user_settings` (`user_id` ASC) ;


-- -----------------------------------------------------
-- Table `notifications`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `notifications` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `event_id` INT(11) NOT NULL ,
  `user_id` INT(11) NOT NULL ,
  `enabled` TINYINT NOT NULL DEFAULT 1 ,
  `created` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

CREATE INDEX `notifications_users` ON `notifications` (`user_id` ASC) ;

CREATE INDEX `notifications_events` ON `notifications` (`event_id` ASC) ;


-- -----------------------------------------------------
-- Placeholder table for view `events_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `events_view` (`id` INT, `event_type` INT, `data` INT, `user_id` INT, `transaction_id` INT, `message_id` INT, `created` INT);

-- -----------------------------------------------------
-- Placeholder table for view `notifications_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `notifications_view` (`id` INT, `event_id` INT, `event_type` INT, `user_id` INT, `enabled` INT, `created` INT);

-- -----------------------------------------------------
-- Placeholder table for view `demands_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `demands_view` (`demand_id` INT, `demand_type` INT, `transaction_id` INT, `transaction_status` INT, `transaction_created` INT, `demander_id` INT, `demander_screen_name` INT, `demander_email` INT, `demander_photo_source` INT, `demander_photo_url` INT, `demander_photo_thumb_url` INT, `demander_facebook_id` INT, `decider_id` INT, `decider_screen_name` INT, `decider_email` INT, `decider_photo_source` INT, `decider_photo_url` INT, `decider_photo_thumb_url` INT, `decider_facebook_id` INT, `good_id` INT, `good_title` INT, `good_type` INT, `good_shareable` INT, `good_status` INT, `good_category_id` INT, `good_category` INT, `good_photo_id` INT, `good_photo_url` INT, `good_photo_thumb_url` INT, `good_user_id` INT, `good_user_screen_name` INT, `good_user_email` INT);

-- -----------------------------------------------------
-- Placeholder table for view `goods_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `goods_view` (`good_id` INT, `good_type` INT, `good_title` INT, `good_shareable` INT, `good_description` INT, `good_status` INT, `good_created` INT, `location_id` INT, `location_title` INT, `location_address` INT, `location_latitude` INT, `location_longitude` INT, `location_street_address` INT, `location_city` INT, `location_state` INT, `location_postal_code` INT, `location_country` INT, `photo_id` INT, `photo_url` INT, `photo_thumb_url` INT, `photo_caption` INT, `user_id` INT, `user_email` INT, `user_type` INT, `user_first_name` INT, `user_last_name` INT, `user_screen_name` INT, `user_bio` INT, `user_created` INT, `user_occupation` INT, `user_photo_source` INT, `user_facebook_id` INT, `user_photo_id` INT, `user_photo_url` INT, `user_photo_thumb_url` INT, `user_photo_caption` INT, `category_id` INT, `category_name` INT);

-- -----------------------------------------------------
-- View `events_view`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events_view`;
DELIMITER $$
CREATE  OR REPLACE VIEW `events_view` AS
SELECT `E`.`id`, `ET`.`title` AS `event_type`, `E`.`data`, `E`.`user_id`, `E`.`transaction_id`, `E`.`message_id`, `E`.`created` 
FROM `events` AS `E` 
INNER JOIN `event_types` AS `ET` 
ON `E`.`event_type_id` = `ET`.`id`
ORDER BY `E`.`id` ASC
$$
DELIMITER ;

;

-- -----------------------------------------------------
-- View `notifications_view`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `notifications_view`;
DELIMITER $$
CREATE  OR REPLACE VIEW `notifications_view` AS
SELECT `N`.`id`, `N`.`event_id`, `ET`.`title` AS `event_type`, `N`.`user_id`, `N`.`enabled`, `N`.`created` 
FROM `notifications` AS `N` 
INNER JOIN `events` AS `E`
ON `N`.`event_id` = `E`.`id`
INNER JOIN `event_types` AS `ET` 
ON `E`.`event_type_id` = `ET`.`id`
ORDER BY `N`.`id` ASC
$$
DELIMITER ;

;

-- -----------------------------------------------------
-- View `demands_view`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `demands_view`;
DELIMITER $$
CREATE  OR REPLACE VIEW `demands_view` AS select `D`.`id` AS `demand_id`,`D`.`type` AS `demand_type`,`T`.`id` AS `transaction_id`,`T`.`status` AS `transaction_status`,`T`.`created` AS `transaction_created`,`DU`.`id` AS `demander_id`,`DU`.`screen_name` AS `demander_screen_name`,`DU`.`email` AS `demander_email`,`DU`.`photo_source` AS `demander_photo_source`,`DUP`.`url` AS `demander_photo_url`,`DUP`.`thumb_url` AS `demander_photo_thumb_url`,`DU`.`facebook_id` AS `demander_facebook_id`,`OU`.`id` AS `decider_id`,`OU`.`screen_name` AS `decider_screen_name`,`OU`.`email` AS `decider_email`,`OU`.`photo_source` AS `decider_photo_source`,`OUP`.`url` AS `decider_photo_url`,`OUP`.`thumb_url` AS `decider_photo_thumb_url`,`OU`.`facebook_id` AS `decider_facebook_id`,`g`.`good_id` AS `good_id`,`g`.`good_title` AS `good_title`,`g`.`good_type` AS `good_type`,`g`.`good_shareable` AS `good_shareable`,`g`.`good_status` AS `good_status`,`g`.`category_id` AS `good_category_id`,`g`.`category_name` AS `good_category`,`g`.`photo_id` AS `good_photo_id`,`g`.`photo_url` AS `good_photo_url`,`g`.`photo_thumb_url` AS `good_photo_thumb_url`,`g`.`user_id` AS `good_user_id`,`g`.`user_screen_name` AS `good_user_screen_name`,`g`.`user_email` AS `good_user_email` from (((((((`demands` `D` join `transactions` `T` on((`T`.`id` = `D`.`transaction_id`))) join `transactions_users` `TU` on((`T`.`id` = `TU`.`transaction_id`))) join `goods_view` `g` on((`g`.`good_id` = `D`.`good_id`))) join `users` `DU` on((`D`.`user_id` = `DU`.`id`))) join `users` `OU` on(((`TU`.`user_id` = `OU`.`id`) and (`OU`.`id` <> `DU`.`id`)))) left join `photos` `DUP` on((`DU`.`default_photo_id` = `DUP`.`id`))) left join `photos` `OUP` on((`OU`.`default_photo_id` = `OUP`.`id`))) group by `D`.`id`;
$$
DELIMITER ;

;

-- -----------------------------------------------------
-- View `goods_view`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `goods_view`;
DELIMITER $$
CREATE  OR REPLACE VIEW `goods_view` AS select `G`.`id` AS `good_id`,`G`.`type` AS `good_type`,`G`.`title` AS `good_title`,`G`.`shareable` AS `good_shareable`,`G`.`description` AS `good_description`,`G`.`status` AS `good_status`,`G`.`created` AS `good_created`,`L`.`id` AS `location_id`,`L`.`title` AS `location_title`,`L`.`address` AS `location_address`,`L`.`latitude` AS `location_latitude`,`L`.`longitude` AS `location_longitude`,`L`.`street_address` AS `location_street_address`,`L`.`city` AS `location_city`,`L`.`state` AS `location_state`,`L`.`postal_code` AS `location_postal_code`,`L`.`country` AS `location_country`,`P`.`id` AS `photo_id`,`P`.`url` AS `photo_url`,`P`.`thumb_url` AS `photo_thumb_url`,`P`.`caption` AS `photo_caption`,`U`.`id` AS `user_id`,`U`.`email` AS `user_email`,`U`.`type` AS `user_type`,`U`.`first_name` AS `user_first_name`,`U`.`last_name` AS `user_last_name`,`U`.`screen_name` AS `user_screen_name`,`U`.`bio` AS `user_bio`,`U`.`created` AS `user_created`,`U`.`occupation` AS `user_occupation`,`U`.`photo_source` AS `user_photo_source`,`U`.`facebook_id` AS `user_facebook_id`,`UP`.`id` AS `user_photo_id`,`UP`.`url` AS `user_photo_url`,`UP`.`thumb_url` AS `user_photo_thumb_url`,`UP`.`caption` AS `user_photo_caption`,`C`.`id` AS `category_id`,`C`.`name` AS `category_name` from (((((`goods` `G` join `users` `U` on((`G`.`user_id` = `U`.`id`))) join `locations` `L` on((`L`.`id` = `G`.`location_id`))) left join `photos` `P` on((`G`.`default_photo_id` = `P`.`id`))) left join `photos` `UP` on((`U`.`default_photo_id` = `UP`.`id`))) left join `categories` `C` on((`C`.`id` = `G`.`category_id`)));

$$
DELIMITER ;

;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
