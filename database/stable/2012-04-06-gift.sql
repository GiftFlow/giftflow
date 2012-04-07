SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';


-- -----------------------------------------------------
-- Table `categories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `categories` ;

CREATE  TABLE IF NOT EXISTS `categories` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(100) NOT NULL ,
  `parent_category_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 19
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `ci_sessions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `ci_sessions` ;

CREATE  TABLE IF NOT EXISTS `ci_sessions` (
  `session_id` VARCHAR(40) NOT NULL DEFAULT '0' ,
  `ip_address` VARCHAR(16) NOT NULL DEFAULT '0' ,
  `user_agent` VARCHAR(50) NOT NULL ,
  `last_activity` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `user_data` TEXT NOT NULL ,
  PRIMARY KEY (`session_id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COMMENT = 'Stores session information.' ;


-- -----------------------------------------------------
-- Table `goods`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `goods` ;

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
  INDEX `location_id` (`location_id` ASC) ,
  INDEX `user_id` (`user_id` ASC) ,
  INDEX `default_photo_id` (`default_photo_id` ASC) ,
  INDEX `category_id` (`category_id` ASC) ,
  INDEX `good_type` (`type` ASC) ,
  INDEX `good_title` (`title` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 889
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'The `goods` data object is an abstraction which represents b' ;


-- -----------------------------------------------------
-- Table `transactions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `transactions` ;

CREATE  TABLE IF NOT EXISTS `transactions` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `status` ENUM('pending','declined','cancelled','disabled','active','completed') NOT NULL DEFAULT 'pending' ,
  `updated` DATETIME NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 311
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `users` ;

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
  `type` ENUM('individual','nonprofit','business') NOT NULL DEFAULT 'individual' COMMENT 'Acceptable values: \"Individual\" (default) or \"institution\"' ,
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
  `language` ENUM('en','es','fr','de','it','nl','sv','no','da','fi','is','ru','et','lv','pl','pt','ja') NOT NULL DEFAULT 'en' ,
  `timezone` VARCHAR(100) NOT NULL DEFAULT 'America/New_York' ,
  `default_photo_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `default_location_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `updated` DATETIME NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `locations_users_fk` (`default_location_id` ASC) ,
  INDEX `photos_users` (`default_photo_id` ASC) ,
  INDEX `user_screen_name` (`screen_name` ASC) ,
  INDEX `user_first_name` (`first_name` ASC) ,
  INDEX `user_last_name` (`last_name` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 1414
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `demands`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `demands` ;

CREATE  TABLE IF NOT EXISTS `demands` (
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
AUTO_INCREMENT = 256
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `event_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `event_types` ;

CREATE  TABLE IF NOT EXISTS `event_types` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 17
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `messages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `messages` ;

CREATE  TABLE IF NOT EXISTS `messages` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  `transaction_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `thread_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `type` ENUM('message','activated','declined','cancelled') NOT NULL DEFAULT 'message' ,
  `body` VARCHAR(10000) NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `thread_id` (`thread_id` ASC) ,
  INDEX `user_id` (`user_id` ASC) ,
  INDEX `transaction_id` (`transaction_id` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 338
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'Single messages, connected via thread or transaction' ;


-- -----------------------------------------------------
-- Table `events`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events` ;

CREATE  TABLE IF NOT EXISTS `events` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `event_type_id` INT(10) UNSIGNED NOT NULL ,
  `data` VARCHAR(10000) NOT NULL ,
  `user_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `transaction_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `message_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id` (`user_id` ASC) ,
  INDEX `notifications_ibfk_3` (`message_id` ASC) ,
  INDEX `notifications_ibfk_4` (`transaction_id` ASC) ,
  INDEX `event_type` (`event_type_id` ASC) ,
  CONSTRAINT `events_ibfk_1`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `notifications_ibfk_12`
    FOREIGN KEY (`event_type_id` )
    REFERENCES `event_types` (`id` )
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
AUTO_INCREMENT = 2559
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `followings_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `followings_users` ;

CREATE  TABLE IF NOT EXISTS `followings_users` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `following_id` INT(10) UNSIGNED NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id` (`user_id` ASC) ,
  INDEX `following_id` (`following_id` ASC) ,
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
AUTO_INCREMENT = 316
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `goods_tags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `goods_tags` ;

CREATE  TABLE IF NOT EXISTS `goods_tags` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `good_id` INT(10) UNSIGNED NOT NULL ,
  `tag_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `tag_id` (`tag_id` ASC) ,
  INDEX `good_id` (`good_id` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 2019
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'Relates `goods` with `tags`' ;


-- -----------------------------------------------------
-- Table `locations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `locations` ;

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
  INDEX `user_id` (`user_id` ASC) ,
  INDEX `locations_users` (`user_id` ASC) ,
  INDEX `latlng` (`latitude` ASC, `longitude` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 725
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'The `locations` data model stores place information for both' ;


-- -----------------------------------------------------
-- Table `notifications`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `notifications` ;

CREATE  TABLE IF NOT EXISTS `notifications` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `event_id` INT(11) NOT NULL ,
  `user_id` INT(11) NOT NULL ,
  `enabled` TINYINT(1) NOT NULL DEFAULT '1' ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `notifications_users` (`user_id` ASC) ,
  INDEX `notifications_events` (`event_id` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 676
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `photos`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `photos` ;

CREATE  TABLE IF NOT EXISTS `photos` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `good_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `url` VARCHAR(1000) NOT NULL ,
  `thumb_url` VARCHAR(1000) NOT NULL ,
  `caption` VARCHAR(200) NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id` (`user_id` ASC) ,
  INDEX `photos_ibfk_2` (`good_id` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 228
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'The `photos` data model stores information about photos for ' ;


-- -----------------------------------------------------
-- Table `redirects`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `redirects` ;

CREATE  TABLE IF NOT EXISTS `redirects` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `url` VARCHAR(100) NOT NULL ,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
AUTO_INCREMENT = 5137
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'Stores redirects so user can be sent to place they were inte' ;


-- -----------------------------------------------------
-- Table `reviews`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `reviews` ;

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
  INDEX `reviews_transactions_infk_1` (`transaction_id` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 27
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `tags`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tags` ;

CREATE  TABLE IF NOT EXISTS `tags` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(100) NOT NULL ,
  `count` INT(10) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name` (`name` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 1053
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `terms`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `terms` ;

CREATE  TABLE IF NOT EXISTS `terms` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `type` ENUM('alert_template','language') NOT NULL COMMENT 'Acceptable values: \'alert_template\',\'language\'' ,
  `language` ENUM('en','es','fr','de','it','nl','sv','no','da','fi','is','ru','et','lv','pl','pt','ja') NOT NULL DEFAULT 'en' ,
  `name` VARCHAR(255) NOT NULL ,
  `subject` VARCHAR(200) NOT NULL COMMENT 'title/subject of alert notifications' ,
  `body` TEXT NOT NULL ,
  PRIMARY KEY (`id`) ,
  UNIQUE INDEX `name` (`name` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 20
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'The `terms` data model stores alert templates and notificati' ;


-- -----------------------------------------------------
-- Table `threads`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `threads` ;

CREATE  TABLE IF NOT EXISTS `threads` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `subject` VARCHAR(250) NOT NULL ,
  `updated` DATETIME NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `threads_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `threads_users` ;

CREATE  TABLE IF NOT EXISTS `threads_users` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `thread_id` INT(10) UNSIGNED NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `thread_id` (`thread_id` ASC) ,
  INDEX `recipient_id` (`user_id` ASC) ,
  INDEX `user_id` (`user_id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'Join table relating `threads` to `users`, a many-to-many rel' ;


-- -----------------------------------------------------
-- Table `transactions_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `transactions_users` ;

CREATE  TABLE IF NOT EXISTS `transactions_users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `transaction_id` INT(10) UNSIGNED NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `transactions_users_infk_1` (`user_id` ASC) ,
  INDEX `transactions_users_infk_2` (`transaction_id` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 507
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `user_openids`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_openids` ;

CREATE  TABLE IF NOT EXISTS `user_openids` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `openid` VARCHAR(240) NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id` (`user_id` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 15
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `user_settings`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_settings` ;

CREATE  TABLE IF NOT EXISTS `user_settings` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  `notify_messages` INT(1) NOT NULL DEFAULT '1' ,
  `updated` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `user_id` (`user_id` ASC) )
ENGINE = InnoDB
AUTO_INCREMENT = 1321
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Placeholder table for view `demands_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `demands_view` (`demand_id` INT, `demand_type` INT, `transaction_id` INT, `transaction_status` INT, `transaction_created` INT, `demander_id` INT, `demander_screen_name` INT, `demander_email` INT, `demander_photo_source` INT, `demander_photo_url` INT, `demander_photo_thumb_url` INT, `demander_facebook_id` INT, `decider_id` INT, `decider_screen_name` INT, `decider_email` INT, `decider_photo_source` INT, `decider_photo_url` INT, `decider_photo_thumb_url` INT, `decider_facebook_id` INT, `good_id` INT, `good_title` INT, `good_type` INT, `good_shareable` INT, `good_status` INT, `good_category_id` INT, `good_category` INT, `good_photo_id` INT, `good_photo_url` INT, `good_photo_thumb_url` INT, `good_user_id` INT, `good_user_screen_name` INT, `good_user_email` INT);

-- -----------------------------------------------------
-- Placeholder table for view `events_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `events_view` (`id` INT, `event_type` INT, `data` INT, `user_id` INT, `transaction_id` INT, `message_id` INT, `created` INT);

-- -----------------------------------------------------
-- Placeholder table for view `goods_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `goods_view` (`good_id` INT, `good_type` INT, `good_title` INT, `good_shareable` INT, `good_description` INT, `good_status` INT, `good_created` INT, `location_id` INT, `location_title` INT, `location_address` INT, `location_latitude` INT, `location_longitude` INT, `location_street_address` INT, `location_city` INT, `location_state` INT, `location_postal_code` INT, `location_country` INT, `photo_id` INT, `photo_url` INT, `photo_thumb_url` INT, `photo_caption` INT, `user_id` INT, `user_email` INT, `user_type` INT, `user_first_name` INT, `user_last_name` INT, `user_screen_name` INT, `user_bio` INT, `user_created` INT, `user_occupation` INT, `user_photo_source` INT, `user_facebook_id` INT, `user_photo_id` INT, `user_photo_url` INT, `user_photo_thumb_url` INT, `user_photo_caption` INT, `category_id` INT, `category_name` INT);

-- -----------------------------------------------------
-- Placeholder table for view `notifications_view`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `notifications_view` (`id` INT, `event_id` INT, `event_type` INT, `user_id` INT, `enabled` INT, `created` INT);



-- -----------------------------------------------------
-- View `demands_view`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `demands_view` ;
DROP TABLE IF EXISTS `demands_view`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `demands_view` AS select `D`.`id` AS `demand_id`,`D`.`type` AS `demand_type`,`T`.`id` AS `transaction_id`,`T`.`status` AS `transaction_status`,`T`.`created` AS `transaction_created`,`DU`.`id` AS `demander_id`,`DU`.`screen_name` AS `demander_screen_name`,`DU`.`email` AS `demander_email`,`DU`.`photo_source` AS `demander_photo_source`,`DUP`.`url` AS `demander_photo_url`,`DUP`.`thumb_url` AS `demander_photo_thumb_url`,`DU`.`facebook_id` AS `demander_facebook_id`,`OU`.`id` AS `decider_id`,`OU`.`screen_name` AS `decider_screen_name`,`OU`.`email` AS `decider_email`,`OU`.`photo_source` AS `decider_photo_source`,`OUP`.`url` AS `decider_photo_url`,`OUP`.`thumb_url` AS `decider_photo_thumb_url`,`OU`.`facebook_id` AS `decider_facebook_id`,`g`.`good_id` AS `good_id`,`g`.`good_title` AS `good_title`,`g`.`good_type` AS `good_type`,`g`.`good_shareable` AS `good_shareable`,`g`.`good_status` AS `good_status`,`g`.`category_id` AS `good_category_id`,`g`.`category_name` AS `good_category`,`g`.`photo_id` AS `good_photo_id`,`g`.`photo_url` AS `good_photo_url`,`g`.`photo_thumb_url` AS `good_photo_thumb_url`,`g`.`user_id` AS `good_user_id`,`g`.`user_screen_name` AS `good_user_screen_name`,`g`.`user_email` AS `good_user_email` from (((((((`demands` `D` join `transactions` `T` on((`T`.`id` = `D`.`transaction_id`))) join `transactions_users` `TU` on((`T`.`id` = `TU`.`transaction_id`))) join `goods_view` `g` on((`g`.`good_id` = `D`.`good_id`))) join `users` `DU` on((`D`.`user_id` = `DU`.`id`))) join `users` `OU` on(((`TU`.`user_id` = `OU`.`id`) and (`OU`.`id` <> `DU`.`id`)))) left join `photos` `DUP` on((`DU`.`default_photo_id` = `DUP`.`id`))) left join `photos` `OUP` on((`OU`.`default_photo_id` = `OUP`.`id`))) group by `D`.`id`;

-- -----------------------------------------------------
-- View `events_view`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `events_view` ;
DROP TABLE IF EXISTS `events_view`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `events_view` AS select `E`.`id` AS `id`,`ET`.`title` AS `event_type`,`E`.`data` AS `data`,`E`.`user_id` AS `user_id`,`E`.`transaction_id` AS `transaction_id`,`E`.`message_id` AS `message_id`,`E`.`created` AS `created` from (`events` `E` join `event_types` `ET` on((`E`.`event_type_id` = `ET`.`id`))) order by `E`.`id`;

-- -----------------------------------------------------
-- View `goods_view`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `goods_view` ;
DROP TABLE IF EXISTS `goods_view`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `goods_view` AS select `G`.`id` AS `good_id`,`G`.`type` AS `good_type`,`G`.`title` AS `good_title`,`G`.`shareable` AS `good_shareable`,`G`.`description` AS `good_description`,`G`.`status` AS `good_status`,`G`.`created` AS `good_created`,`L`.`id` AS `location_id`,`L`.`title` AS `location_title`,`L`.`address` AS `location_address`,`L`.`latitude` AS `location_latitude`,`L`.`longitude` AS `location_longitude`,`L`.`street_address` AS `location_street_address`,`L`.`city` AS `location_city`,`L`.`state` AS `location_state`,`L`.`postal_code` AS `location_postal_code`,`L`.`country` AS `location_country`,`P`.`id` AS `photo_id`,`P`.`url` AS `photo_url`,`P`.`thumb_url` AS `photo_thumb_url`,`P`.`caption` AS `photo_caption`,`U`.`id` AS `user_id`,`U`.`email` AS `user_email`,`U`.`type` AS `user_type`,`U`.`first_name` AS `user_first_name`,`U`.`last_name` AS `user_last_name`,`U`.`screen_name` AS `user_screen_name`,`U`.`bio` AS `user_bio`,`U`.`created` AS `user_created`,`U`.`occupation` AS `user_occupation`,`U`.`photo_source` AS `user_photo_source`,`U`.`facebook_id` AS `user_facebook_id`,`UP`.`id` AS `user_photo_id`,`UP`.`url` AS `user_photo_url`,`UP`.`thumb_url` AS `user_photo_thumb_url`,`UP`.`caption` AS `user_photo_caption`,`C`.`id` AS `category_id`,`C`.`name` AS `category_name` from (((((`goods` `G` join `users` `U` on((`G`.`user_id` = `U`.`id`))) join `locations` `L` on((`L`.`id` = `G`.`location_id`))) left join `photos` `P` on((`G`.`default_photo_id` = `P`.`id`))) left join `photos` `UP` on((`U`.`default_photo_id` = `UP`.`id`))) left join `categories` `C` on((`C`.`id` = `G`.`category_id`)));

-- -----------------------------------------------------
-- View `notifications_view`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `notifications_view` ;
DROP TABLE IF EXISTS `notifications_view`;
CREATE  OR REPLACE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `notifications_view` AS select `N`.`id` AS `id`,`N`.`event_id` AS `event_id`,`ET`.`title` AS `event_type`,`N`.`user_id` AS `user_id`,`N`.`enabled` AS `enabled`,`N`.`created` AS `created` from ((`notifications` `N` join `events` `E` on((`N`.`event_id` = `E`.`id`))) join `event_types` `ET` on((`E`.`event_type_id` = `ET`.`id`))) order by `N`.`id`;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
