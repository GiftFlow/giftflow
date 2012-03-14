SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `gift` ;
CREATE SCHEMA IF NOT EXISTS `gift` DEFAULT CHARACTER SET utf8 ;
USE `gift` ;

-- -----------------------------------------------------
-- Table `gift`.`categories`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`categories` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(100) NOT NULL ,
  `parent_category_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `gift`.`ci_sessions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`ci_sessions` (
  `session_id` VARCHAR(40) NOT NULL DEFAULT '0' ,
  `ip_address` VARCHAR(16) NOT NULL DEFAULT '0' ,
  `user_agent` VARCHAR(50) NOT NULL ,
  `last_activity` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `user_data` TEXT NOT NULL ,
  PRIMARY KEY (`session_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Stores session information.';


-- -----------------------------------------------------
-- Table `gift`.`locations`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`locations` (
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
  `user_id` INT(10) UNSIGNED NOT NULL COMMENT 'References `user`. `Users` may have multiple `locations`, but each `location` may only have one `user`.' ,
  `updated` DATETIME NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id` (`user_id` ASC) ,
  INDEX `locations_users` (`user_id` ASC) ,
  CONSTRAINT `locations_users`
    FOREIGN KEY (`user_id` )
    REFERENCES `gift`.`users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 241
DEFAULT CHARACTER SET = utf8
COMMENT = 'The `locations` data model stores place information for both';


-- -----------------------------------------------------
-- Table `gift`.`photos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`photos` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `good_id` INT UNSIGNED NULL DEFAULT NULL ,
  `url` VARCHAR(1000) NOT NULL ,
  `thumb_url` VARCHAR(1000) NOT NULL ,
  `caption` VARCHAR(200) NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id` (`user_id` ASC) ,
  INDEX `photos_ibfk_2` (`good_id` ASC) ,
  CONSTRAINT `photos_ibfk_1`
    FOREIGN KEY (`user_id` )
    REFERENCES `gift`.`users` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `photos_ibfk_2`
    FOREIGN KEY (`good_id` )
    REFERENCES `gift`.`goods` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 83
DEFAULT CHARACTER SET = utf8
COMMENT = 'The `photos` data model stores information about photos for ';


-- -----------------------------------------------------
-- Table `gift`.`users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`users` (
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
  INDEX `locations_users_fk` (`default_location_id` ASC) ,
  INDEX `photos_users` (`default_photo_id` ASC) ,
  CONSTRAINT `locations_users_fk`
    FOREIGN KEY (`default_location_id` )
    REFERENCES `gift`.`locations` (`id` )
    ON DELETE SET NULL
    ON UPDATE NO ACTION,
  CONSTRAINT `photos_users`
    FOREIGN KEY (`default_photo_id` )
    REFERENCES `gift`.`photos` (`id` )
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 532
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `gift`.`goods`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`goods` (
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
  INDEX `location_id` (`location_id` ASC) ,
  INDEX `user_id` (`user_id` ASC) ,
  INDEX `default_photo_id` (`default_photo_id` ASC) ,
  INDEX `category_id` (`category_id` ASC) ,
  INDEX `goods_ibfk_4` (`default_photo_id` ASC) ,
  CONSTRAINT `goods_ibfk_2`
    FOREIGN KEY (`user_id` )
    REFERENCES `gift`.`users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `goods_ibfk_3`
    FOREIGN KEY (`category_id` )
    REFERENCES `gift`.`categories` (`id` )
    ON DELETE SET NULL
    ON UPDATE CASCADE,
  CONSTRAINT `goods_ibfk_1`
    FOREIGN KEY (`location_id` )
    REFERENCES `gift`.`locations` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `goods_ibfk_4`
    FOREIGN KEY (`default_photo_id` )
    REFERENCES `gift`.`photos` (`id` )
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 358
DEFAULT CHARACTER SET = utf8
COMMENT = 'The `goods` data object is an abstraction which represents b';


-- -----------------------------------------------------
-- Table `gift`.`transactions`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`transactions` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `status` ENUM('pending','declined','cancelled','disabled','active','completed') NOT NULL DEFAULT 'pending',
  `updated` DATETIME NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 56
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `gift`.`demands`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`demands` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `type` ENUM('give','take','borrow','share','fulfill') NOT NULL ,
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
    REFERENCES `gift`.`goods` (`id` )
    ON DELETE CASCADE,
  CONSTRAINT `demands_transactions_ibfk_1`
    FOREIGN KEY (`transaction_id` )
    REFERENCES `gift`.`transactions` (`id` )
    ON DELETE CASCADE,
  CONSTRAINT `demands_users_ibfk_1`
    FOREIGN KEY (`user_id` )
    REFERENCES `gift`.`users` (`id` )
    ON DELETE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `gift`.`followings_users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`followings_users` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `following_id` INT(10) UNSIGNED NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `following_id` (`following_id` ASC, `user_id` ASC) ,
  INDEX `user_id` (`user_id` ASC) ,
  CONSTRAINT `followings_users_ibfk_1`
    FOREIGN KEY (`following_id` )
    REFERENCES `gift`.`users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `followings_users_ibfk_2`
    FOREIGN KEY (`user_id` )
    REFERENCES `gift`.`users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 118
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `gift`.`tags`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`tags` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name` (`name` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 483
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `gift`.`goods_tags`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`goods_tags` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `good_id` INT(10) UNSIGNED NOT NULL ,
  `tag_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `good_id` (`good_id` ASC, `tag_id` ASC) ,
  INDEX `tag_id` (`tag_id` ASC) ,
  CONSTRAINT `goods_tags_ibfk_1`
    FOREIGN KEY (`good_id` )
    REFERENCES `gift`.`goods` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `goods_tags_ibfk_2`
    FOREIGN KEY (`tag_id` )
    REFERENCES `gift`.`tags` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 837
DEFAULT CHARACTER SET = utf8
COMMENT = 'Relates `goods` with `tags`';


-- -----------------------------------------------------
-- Table `gift`.`threads`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`threads` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `subject` VARCHAR(250) NOT NULL ,
  `updated` DATETIME NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `gift`.`messages`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`messages` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  `transaction_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `thread_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `body` VARCHAR(10000) NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `thread_id` (`thread_id` ASC) ,
  INDEX `user_id` (`user_id` ASC) ,
  INDEX `transaction_id` (`transaction_id` ASC) ,
  CONSTRAINT `messages_ibfk_1`
    FOREIGN KEY (`user_id` )
    REFERENCES `gift`.`users` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `messages_ibfk_2`
    FOREIGN KEY (`thread_id` )
    REFERENCES `gift`.`threads` (`id` ),
  CONSTRAINT `messages_transactions_fk`
    FOREIGN KEY (`transaction_id` )
    REFERENCES `gift`.`transactions` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Single messages, connected via thread or transaction';


-- -----------------------------------------------------
-- Table `gift`.`message_deliveries`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`message_deliveries` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `message_id` INT(10) UNSIGNED NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `message_id` (`message_id` ASC) ,
  INDEX `recipient_id` (`user_id` ASC) ,
  CONSTRAINT `message_deliveries_ibfk_2`
    FOREIGN KEY (`user_id` )
    REFERENCES `gift`.`users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `message_deliveries_ibfk_1`
    FOREIGN KEY (`message_id` )
    REFERENCES `gift`.`messages` (`id` )
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'When a message is sent, a row is inserted into this table fo';


-- -----------------------------------------------------
-- Table `gift`.`notification_types`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`notification_types` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(100) NOT NULL ,
  `news_template` VARCHAR(1000) NOT NULL ,
  `email_template` VARCHAR(1000) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 2
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `gift`.`notifications`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`notifications` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `notification_type_id` INT(10) UNSIGNED NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  `opened` DATETIME NULL DEFAULT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `notification_type_id` (`notification_type_id` ASC) ,
  INDEX `user_id` (`user_id` ASC) ,
  CONSTRAINT `notifications_ibfk_1`
    FOREIGN KEY (`notification_type_id` )
    REFERENCES `gift`.`notification_types` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `notifications_ibfk_2`
    FOREIGN KEY (`user_id` )
    REFERENCES `gift`.`users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `gift`.`redirects`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`redirects` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `url` VARCHAR(100) NOT NULL ,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 1227
DEFAULT CHARACTER SET = utf8
COMMENT = 'Stores redirects so user can be sent to place they were inte';


-- -----------------------------------------------------
-- Table `gift`.`reviews`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`reviews` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `rating` ENUM('positive','neutral','negative') NOT NULL ,
  `body` VARCHAR(1000) NOT NULL ,
  `transaction_id` INT(11) UNSIGNED NOT NULL ,
  `reviewer_id` INT(11) UNSIGNED NOT NULL ,
  `reviewed_id` INT(11) UNSIGNED NOT NULL ,
  `updated` DATETIME NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `reviews_transactions_infk_1` (`transaction_id` ASC) ,
  INDEX `reviews_users_infk_1` (`reviewer_id` ASC) ,
  INDEX `reviews_users_infk_2` (`reviewed_id` ASC) ,
  CONSTRAINT `reviews_ibfk_6`
    FOREIGN KEY (`reviewed_id` )
    REFERENCES `gift`.`users` (`id` ),
  CONSTRAINT `reviews_ibfk_4`
    FOREIGN KEY (`transaction_id` )
    REFERENCES `gift`.`transactions` (`id` ),
  CONSTRAINT `reviews_ibfk_5`
    FOREIGN KEY (`reviewer_id` )
    REFERENCES `gift`.`users` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `gift`.`terms`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`terms` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `type` ENUM('alert_template','language') NOT NULL COMMENT 'Acceptable values: \'alert_template\',\'language\'' ,
  `language` ENUM('english','spanish','german','french','russian','chinese') NOT NULL DEFAULT 'english' COMMENT 'Acceptable values: \'english\',\'spanish\',\'german\',\'french\',\'russian\',\'chinese\'' ,
  `name` VARCHAR(255) NOT NULL ,
  `subject` VARCHAR(200) NOT NULL COMMENT 'title/subject of alert notifications' ,
  `body` TEXT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name` (`name` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 12
DEFAULT CHARACTER SET = utf8
COMMENT = 'The `terms` data model stores alert templates and notificati';


-- -----------------------------------------------------
-- Table `gift`.`threads_users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`threads_users` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `thread_id` INT(10) UNSIGNED NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `thread_id` (`thread_id` ASC) ,
  INDEX `recipient_id` (`user_id` ASC) ,
  INDEX `user_id` (`user_id` ASC) ,
  CONSTRAINT `threads_users_ibfk_2`
    FOREIGN KEY (`user_id` )
    REFERENCES `gift`.`users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `threads_users_ibfk_1`
    FOREIGN KEY (`thread_id` )
    REFERENCES `gift`.`threads` (`id` )
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Join table relating `threads` to `users`, a many-to-many rel';


-- -----------------------------------------------------
-- Table `gift`.`transactions_users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`transactions_users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` INT(10) UNSIGNED NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `transactions_users_infk_1` (`user_id` ASC) ,
  INDEX `transactions_users_infk_2` (`transaction_id` ASC) ,
  CONSTRAINT `transactions_users_infk_3`
    FOREIGN KEY (`user_id` )
    REFERENCES `gift`.`users` (`id` ),
  CONSTRAINT `transactions_users_ibfk_4`
    FOREIGN KEY (`transaction_id` )
    REFERENCES `gift`.`transactions` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `gift`.`user_openids`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`user_openids` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `openid` VARCHAR(240) NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id` (`user_id` ASC) ,
  CONSTRAINT `user_id`
    FOREIGN KEY (`user_id` )
    REFERENCES `gift`.`users` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 15
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `gift`.`user_settings`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `gift`.`user_settings` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  `notify_messages` INT(1) NOT NULL DEFAULT '1' ,
  `updated` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id` (`user_id` ASC) ,
  CONSTRAINT `user_settings_ibfk_1`
    FOREIGN KEY (`user_id` )
    REFERENCES `gift`.`users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 458
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Placeholder table for view `gift`.`goods_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gift`.`goods_view` (`good_id` INT, `good_type` INT, `good_title` INT, `good_description` INT, `good_status` INT, `good_created` INT, `location_id` INT, `location_title` INT, `location_address` INT, `location_latitude` INT, `location_longitude` INT, `location_street_address` INT, `location_city` INT, `location_state` INT, `location_postal_code` INT, `location_country` INT, `photo_id` INT, `photo_url` INT, `photo_thumb_url` INT, `photo_caption` INT, `user_id` INT, `user_email` INT, `user_type` INT, `user_first_name` INT, `user_last_name` INT, `user_screen_name` INT, `user_bio` INT, `user_created` INT, `user_occupation` INT, `user_photo_source` INT, `user_facebook_id` INT, `user_photo_id` INT, `user_photo_url` INT, `user_photo_thumb_url` INT, `user_photo_caption` INT, `category_id` INT, `category_name` INT);

-- -----------------------------------------------------
-- View `gift`.`goods_view`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `gift`.`goods_view`;
USE `gift`;
CREATE  OR REPLACE VIEW `goods_view` AS select `G`.`id` AS `good_id`,`G`.`type` AS `good_type`,`G`.`title` AS `good_title`,`G`.`description` AS `good_description`,`G`.`status` AS `good_status`,`G`.`created` AS `good_created`,`L`.`id` AS `location_id`,`L`.`title` AS `location_title`,`L`.`address` AS `location_address`,`L`.`latitude` AS `location_latitude`,`L`.`longitude` AS `location_longitude`,`L`.`street_address` AS `location_street_address`,`L`.`city` AS `location_city`,`L`.`state` AS `location_state`,`L`.`postal_code` AS `location_postal_code`,`L`.`country` AS `location_country`,`P`.`id` AS `photo_id`,`P`.`url` AS `photo_url`,`P`.`thumb_url` AS `photo_thumb_url`,`P`.`caption` AS `photo_caption`,`U`.`id` AS `user_id`,`U`.`email` AS `user_email`,`U`.`type` AS `user_type`,`U`.`first_name` AS `user_first_name`,`U`.`last_name` AS `user_last_name`,`U`.`screen_name` AS `user_screen_name`,`U`.`bio` AS `user_bio`,`U`.`created` AS `user_created`,`U`.`occupation` AS `user_occupation`,`U`.`photo_source` AS `user_photo_source`,`U`.`facebook_id` AS `user_facebook_id`,`UP`.`id` AS `user_photo_id`,`UP`.`url` AS `user_photo_url`,`UP`.`thumb_url` AS `user_photo_thumb_url`,`UP`.`caption` AS `user_photo_caption`,`C`.`id` AS `category_id`,`C`.`name` AS `category_name` from (((((`goods` `G` join `users` `U` on((`G`.`user_id` = `U`.`id`))) join `locations` `L` on((`L`.`id` = `G`.`location_id`))) left join `photos` `P` on((`G`.`default_photo_id` = `P`.`id`))) left join `photos` `UP` on((`U`.`default_photo_id` = `UP`.`id`))) left join `categories` `C` on((`C`.`id` = `G`.`category_id`)));


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
