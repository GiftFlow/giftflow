-- -----------------------------------------------------
-- GiftFlow Database v2.7
-- Last Modified: 06-21-2012
-- See Change Log at /database/stable/gift-server-migration-script.sql
-- -----------------------------------------------------

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
  `ip_address` VARCHAR(45) NOT NULL DEFAULT '0' ,
  `user_agent` VARCHAR(120) NOT NULL ,
  `last_activity` INT(10) UNSIGNED NOT NULL DEFAULT '0' ,
  `user_data` TEXT NOT NULL ,
  PRIMARY KEY (`session_id`),
  INDEX last_activity_idx (`last_activity`) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'Stores session information.' ;


-- -----------------------------------------------------
-- Table `photos`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `photos` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `good_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `url` VARCHAR(1000) NOT NULL ,
  `thumb_url` VARCHAR(1000) NOT NULL ,
  `caption` VARCHAR(200) NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX (`user_id`),
  INDEX (`good_id`),
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


-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
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
  INDEX (`screen_name`),
  INDEX (`first_name`),
  INDEX (`last_name`),
  INDEX (`default_location_id`),
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
  INDEX (`user_id`),
  INDEX latlng (`latitude` ASC, `longitude` ASC),
  INDEX (`address`),
  CONSTRAINT `locations_users`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 241
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'The `locations` data model stores place information for both' ;



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
  INDEX (`location_id` ASC) ,
  INDEX (`user_id` ASC) ,
  INDEX (`default_photo_id` ASC) ,
  INDEX (`category_id` ASC) ,
  INDEX (`type` ASC) ,
  INDEX (`title` ASC) ,
  CONSTRAINT `goods_ibfk_1`
    FOREIGN KEY (`location_id` )
    REFERENCES `locations` (`id` )
    ON UPDATE CASCADE,
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
  CONSTRAINT `goods_ibfk_4`
    FOREIGN KEY (`default_photo_id` )
    REFERENCES `photos` (`id` )
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 358
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'The `goods` data object is an abstraction which represents b' ;


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

  INDEX (`user_id` ASC),
  INDEX (`transaction_id` ASC),
  INDEX (`good_id` ASC),

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
  `type` ENUM('message','activated','declined','cancelled') NOT NULL DEFAULT 'message' ,
  `body` VARCHAR(10000) NOT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX (`thread_id`),
  INDEX (`user_id`),
  INDEX (`transaction_id`),
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


-- -----------------------------------------------------
-- Table `events`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `events` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `event_type_id` INT(10) UNSIGNED NOT NULL ,
  `data` VARCHAR(10000) NOT NULL ,
  `user_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `transaction_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `message_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `created` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX (`user_id`),
  INDEX (`event_type_id`),
  INDEX (`message_id`),
  INDEX (`transaction_id`),
  CONSTRAINT `notifications_ibfk_1`
    FOREIGN KEY (`event_type_id` )
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


-- -----------------------------------------------------
-- Table `followings_users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `followings_users` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `following_id` INT(10) UNSIGNED NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX (`following_id`),
  INDEX (`user_id`),
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


-- -----------------------------------------------------
-- Table `tags`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `tags` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(100) UNIQUE NOT NULL ,
  `count` INT(10) NOT NULL DEFAULT '0' ,
  PRIMARY KEY (`id`),
	INDEX(`name`)
 )
ENGINE = InnoDB
AUTO_INCREMENT = 483
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `goods_tags`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `goods_tags` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `good_id` INT(10) UNSIGNED NOT NULL ,
  `tag_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX(`good_id`),
  INDEX(`tag_id`),
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


-- -----------------------------------------------------
-- Table `notifications`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `notifications` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `event_id` INT(11) NOT NULL ,
  `user_id` INT(11) NOT NULL ,
  `enabled` TINYINT(4) NOT NULL DEFAULT '1' ,
  `created` DATETIME NULL DEFAULT NULL ,
  PRIMARY KEY (`id`),
  INDEX (`user_id`),
  INDEX (`event_id`)
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = latin1;

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
  INDEX (`reviewer_id`),
  INDEX (`reviewed_id`),
  INDEX (`transaction_id`),
  CONSTRAINT `reviews_ibfk_4`
    FOREIGN KEY (`transaction_id` )
    REFERENCES `transactions` (`id` ),
  CONSTRAINT `reviews_ibfk_5`
    FOREIGN KEY (`reviewer_id` )
    REFERENCES `users` (`id` ),
  CONSTRAINT `reviews_ibfk_6`
    FOREIGN KEY (`reviewed_id` )
    REFERENCES `users` (`id` )
)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


-- -----------------------------------------------------
-- Table `terms`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `terms` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `type` ENUM('alert_template','language') NOT NULL COMMENT 'Acceptable values: \'alert_template\',\'language\'' ,
  `language` ENUM('en','es','fr','de','it','nl','sv','no','da','fi','is','ru','et','lv','pl','pt','ja') NOT NULL DEFAULT 'en' ,
  `name` VARCHAR(255) NOT NULL UNIQUE,
  `subject` VARCHAR(200) NOT NULL COMMENT 'title/subject of alert notifications' ,
  `body` TEXT NOT NULL ,
  PRIMARY KEY (`id`),
  INDEX(`name`)
)
ENGINE = InnoDB
AUTO_INCREMENT = 12
DEFAULT CHARACTER SET = utf8, 
COMMENT = '`terms` stores alert templates and notifications' ;


-- -----------------------------------------------------
-- Table `threads_users`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `threads_users` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `thread_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `user_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX (`thread_id`),
  INDEX (`user_id`),
  INDEX (`user_id`),
  CONSTRAINT `threads_users_ibfk_1`
    FOREIGN KEY (`thread_id` )
    REFERENCES `threads` (`id` )
    ON UPDATE CASCADE,
  CONSTRAINT `threads_users_ibfk_2`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8, 
COMMENT = 'Join table relating `threads` to `users`, a many-to-many rel' ;


-- -----------------------------------------------------
-- Table `transactions_users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `transactions_users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `transaction_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  `user_id` INT(10) UNSIGNED NULL DEFAULT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX (`user_id`),
  INDEX (`transaction_id`),
  CONSTRAINT `transactions_users_ibfk_4`
    FOREIGN KEY (`transaction_id` )
    REFERENCES `transactions` (`id` ),
  CONSTRAINT `transactions_users_infk_3`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` ))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- -----------------------------------------------------
-- Table `user_openids`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `user_openids` (
  `id` INT(11) NOT NULL AUTO_INCREMENT ,
  `openid` VARCHAR(240) NOT NULL ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX (`user_id`),
  CONSTRAINT `user_id`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 15
DEFAULT CHARACTER SET = utf8
ROW_FORMAT = COMPACT;


-- -----------------------------------------------------
-- Table `user_settings`
-- -----------------------------------------------------
CREATE  TABLE IF NOT EXISTS `user_settings` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT ,
  `user_id` INT(10) UNSIGNED NOT NULL ,
  `notify_messages` INT(1) NOT NULL DEFAULT '1' ,
  `updated` DATETIME NOT NULL ,
  PRIMARY KEY (`id`) ,
  INDEX (`user_id`),
  CONSTRAINT `user_settings_ibfk_1`
    FOREIGN KEY (`user_id` )
    REFERENCES `users` (`id` )
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 458
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `watches` (
	`id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	`user_id` INT(10) UNSIGNED NOT NULL,
	`keyword` VARCHAR(100) NOT NULL,
	PRIMARY KEY (`id`),
	CONSTRAINT `watches_users`
		FOREIGN KEY (`user_id`)
		REFERENCES `users` (`id`)
		ON DELETE CASCADE
		ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

-- ----------------------------------------------------
-- Table `links`
-- ----------------------------------------------------

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `sender_id` int(10) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `type` enum('thankyou') NOT NULL,
  `transaction_id` int(100) NOT NULL,
  `code` varchar(200) DEFAULT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) 
ENGINE=InnoDB  
DEFAULT CHARSET=utf8;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
