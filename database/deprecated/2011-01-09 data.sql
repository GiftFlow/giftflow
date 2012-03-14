-- phpMyAdmin SQL Dump
-- version 3.1.3.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 06, 2011 at 03:29 AM
-- Server version: 5.1.33
-- PHP Version: 5.2.9

SET FOREIGN_KEY_CHECKS=0;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `gift`
--

--
-- Dumping data for table `categories`
--


--
-- Dumping data for table `ci_sessions`
--

INSERT INTO `ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('26f24e6f0b92c84cf8f0624357a754f6', '0.0.0.0', 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.6; en', 1294305343, 'a:19:{s:9:"logged_in";s:1:"1";s:7:"role_id";s:1:"1";s:5:"level";s:3:"100";s:7:"user_id";s:2:"47";s:5:"email";s:29:"brandonscottjackson@gmail.com";s:8:"username";s:0:"";s:11:"screen_name";s:15:"Brandon Jackson";s:10:"first_name";s:0:"";s:9:"last_name";s:0:"";s:17:"location_latitude";s:7:"41.3115";s:18:"location_longitude";s:8:"-72.9356";s:23:"location_street_address";s:13:"216 Dwight St";s:16:"location_address";s:39:"216 Dwight St, New Haven, CT 06511, USA";s:13:"location_city";s:9:"New Haven";s:14:"location_state";s:2:"CT";s:6:"status";s:0:"";s:15:"photo_thumb_url";s:68:"http://localhost/giftflow/assets/images/default_user_photo_thumb.jpg";s:9:"photo_url";s:62:"http://localhost/giftflow/assets/images/default_user_photo.jpg";s:8:"profiler";s:1:"1";}');

--
-- Dumping data for table `comments`
--


--
-- Dumping data for table `followings_users`
--

INSERT INTO `followings_users` (`id`, `following_id`, `user_id`) VALUES
(7, 35, 47),
(12, 35, 122),
(10, 47, 34),
(11, 47, 122),
(8, 101, 47);

--
-- Dumping data for table `goods`
--

INSERT INTO `goods` (`id`, `title`, `type`, `description`, `status`, `quantity`, `user_id`, `updated`, `location_id`, `category_id`, `default_photo_id`, `created`) VALUES
(1, 'Sofa', 'gift', '', 'active', 0, 35, '0000-00-00 00:00:00', 74, 0, 0, '2010-04-13 16:24:32'),
(2, 'Red Lamp', 'gift', '', 'active', 0, 35, '0000-00-00 00:00:00', 75, 0, 0, '2010-04-13 16:26:57'),
(3, 'Suitcase', 'gift', '', 'active', 0, 35, '0000-00-00 00:00:00', 75, 0, 0, '2010-04-13 16:27:57'),
(31, 'Couch', 'gift', 'Do you need a place to stay? Well you''re in luck. I have an extra couch.', 'active', 0, 47, '0000-00-00 00:00:00', 107, 0, 0, '2010-05-09 04:27:15'),
(55, 'sweater', 'need', '', 'active', 0, 47, '0000-00-00 00:00:00', 100, 0, 0, '2010-05-10 20:43:01'),
(57, 'computer', 'gift', '', 'active', 0, 101, '0000-00-00 00:00:00', 103, 0, 0, '2010-05-31 15:50:02'),
(58, 'hat', 'gift', 'it is big', 'active', 0, 101, '0000-00-00 00:00:00', 104, 0, 0, '2010-05-31 15:51:28'),
(60, 'shoes', 'need', '', 'active', 0, 101, '0000-00-00 00:00:00', 105, 0, 0, '2010-05-31 15:52:33'),
(61, 'shoes', 'need', '', 'active', 0, 101, '0000-00-00 00:00:00', 103, 0, 0, '2010-05-31 15:52:43'),
(62, 'shoes', 'need', '', 'active', 0, 101, '0000-00-00 00:00:00', 103, 0, 0, '2010-05-31 15:52:51'),
(63, 'shoes', 'need', '', 'active', 0, 101, '0000-00-00 00:00:00', 103, 0, 0, '2010-05-31 16:00:23'),
(65, 'watch', 'need', '', 'active', 0, 101, '0000-00-00 00:00:00', 106, 0, 0, '2010-06-06 02:47:53'),
(66, 'watch', 'need', '', 'active', 0, 101, '0000-00-00 00:00:00', 106, 0, 0, '2010-06-06 02:48:13'),
(85, 'two', 'need', '', 'active', 0, 101, '0000-00-00 00:00:00', 106, 0, 0, '2010-06-08 00:53:23'),
(90, 'sweater', 'need', '', 'active', 0, 47, '0000-00-00 00:00:00', NULL, 0, 0, '2010-06-16 21:07:25'),
(91, 'hug', 'gift', '', 'active', 0, 34, '0000-00-00 00:00:00', 109, 0, 0, '2010-06-17 14:33:15'),
(94, 'Grateful dead album', 'gift', '', 'active', 0, 47, '0000-00-00 00:00:00', 110, 0, 0, '2010-07-12 03:46:59'),
(95, 'Foo', 'gift', '', 'active', 0, 47, '2010-12-31 06:12:42', 111, 0, 0, '2010-12-31 06:12:42'),
(96, 'Barcelona Chair', 'gift', '', 'active', 0, 47, '2010-12-31 06:13:13', 111, 0, 0, '2010-12-31 06:13:13'),
(97, 'Eames Rocker', 'gift', '', 'active', 0, 34, '2010-12-31 06:17:50', 112, 0, 0, '2010-12-31 06:17:50'),
(98, 'Saarinen Womb Chair', 'gift', '', 'active', 0, 34, '2010-12-31 06:18:23', 113, 0, 0, '2010-12-31 06:18:23'),
(99, 'Paul Rudolph Sofa', 'gift', '', 'active', 0, 34, '2010-12-31 06:19:00', 113, 0, 0, '2010-12-31 06:19:00'),
(100, 'Eames Lounge Chair', 'gift', '', 'active', 0, 34, '2010-12-31 06:19:40', 112, 0, 0, '2010-12-31 06:19:40'),
(101, 'Programming', 'gift', '', 'active', 0, 47, '2011-01-05 22:30:39', 114, 0, 0, '2011-01-05 22:30:39');

--
-- Dumping data for table `goods_tags`
--

INSERT INTO `goods_tags` (`id`, `good_id`, `tag_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 2, 1),
(5, 2, 4),
(6, 2, 5),
(7, 2, 6),
(8, 3, 7),
(9, 3, 8),
(10, 3, 9),
(60, 31, 2),
(59, 31, 32),
(61, 31, 33),
(30, 55, 18),
(33, 57, 23),
(34, 57, 24),
(35, 58, 11),
(37, 60, 11),
(38, 61, 11),
(39, 62, 11),
(40, 63, 11),
(42, 65, 11),
(43, 66, 11),
(45, 90, 18),
(46, 91, 27),
(70, 94, 34),
(71, 94, 35),
(72, 94, 36),
(73, 95, 37),
(74, 96, 1),
(75, 96, 38),
(77, 97, 1),
(78, 97, 38),
(76, 97, 39),
(79, 98, 1),
(82, 98, 38),
(80, 98, 40),
(81, 98, 41),
(83, 98, 42),
(84, 99, 1),
(85, 99, 38),
(86, 99, 43),
(87, 100, 1),
(89, 100, 38),
(88, 100, 39),
(90, 100, 44),
(91, 100, 45),
(92, 101, 46),
(93, 101, 47),
(94, 101, 48);

--
-- Dumping data for table `goods_transactions`
--


--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`id`, `title`, `address`, `latitude`, `longitude`, `street_address`, `city`, `state`, `postal_code`, `user_id`, `updated`, `created`) VALUES
(69, '', '142 Temple St, New Haven, CT 06510, USA', 41.3056, -72.9277, '142 Temple St', 'New Haven', 'CT', '6510', 0, '2010-03-29 17:56:14', '2010-03-29 17:56:14'),
(70, '', 'New Haven, CT, USA', 41.3082, -72.9282, '', 'New Haven', 'CT', '', 0, '2010-03-31 16:59:52', '2010-03-29 18:01:07'),
(71, '', 'New Haven, CT, USA', 41.3082, -72.9282, '', 'New Haven', 'CT', '', 0, '2010-03-31 17:03:01', '2010-03-31 17:03:01'),
(72, '', '6780 Bethesda-Arno Rd, Thompson''s Station, TN 37179, USA', 35.7777, -86.7744, '6780 Bethesda-Arno Rd', 'Thompson''s Station', 'TN', '37179', 0, '2010-03-31 21:43:21', '2010-03-31 21:43:21'),
(73, '', 'Franklin, TN, USA', 35.9251, -86.8689, '', 'Franklin', 'TN', '', 0, '2010-04-04 19:15:37', '2010-04-04 19:15:37'),
(74, '', '330 College St, New Haven, CT 06511, USA', 41.308, -72.9282, '330 College St', 'New Haven', 'CT', '6511', 0, '2010-04-13 16:24:33', '2010-04-13 16:24:33'),
(75, '', 'New Haven, CT, USA', 41.3082, -72.9282, '', 'New Haven', 'CT', '', 0, '2010-04-13 16:26:57', '2010-04-13 16:26:57'),
(87, 'New Haven, CT', 'New Haven, CT, USA', 41.3082, -72.9282, '', 'New Haven', 'CT', '', 0, '2010-05-09 02:35:25', '2010-05-09 02:35:25'),
(100, 'New Haven, CT', 'New Haven, CT, USA', 41.3082, -72.9282, '', 'New Haven', 'CT', '', 0, '2010-05-09 17:54:31', '2010-05-09 17:54:31'),
(101, 'New York, NY', 'New York, NY, USA', 40.7143, -74.006, '', 'New York', 'NY', '', 0, '2010-05-09 17:54:57', '2010-05-09 17:54:57'),
(102, 'St. A''s', '483 College St, New Haven, CT 06511, USA', 41.3109, -72.9259, '483 College St', 'New Haven', 'CT', '6511', 0, '2010-05-10 16:27:11', '2010-05-09 17:55:19'),
(103, 'San Francisco, CA', 'San Francisco, CA, USA', 37.7749, -122.419, '', 'San Francisco', 'CA', '', 0, '2010-05-31 15:50:02', '2010-05-31 15:50:02'),
(104, 'San Jose, CA', 'San Jose, CA, USA', 37.3394, -121.895, '', 'San Jose', 'CA', '', 101, '2010-05-31 15:51:28', '2010-05-31 15:51:28'),
(105, 'San Diego, CA', 'San Diego, CA, USA', 32.7153, -117.157, '', 'San Diego', 'CA', '', 0, '2010-05-31 15:52:34', '2010-05-31 15:52:34'),
(106, 'New Haven, CT', 'New Haven, CT, USA', 41.3082, -72.9282, '', 'New Haven', 'CT', '', 0, '2010-05-31 16:05:39', '2010-05-31 16:05:39'),
(107, 'Summer Home', '216 Dwight St, New Haven, CT 06511, USA', 41.3115, -72.9356, '216 Dwight St', 'New Haven', 'CT', '6511', 47, '2010-07-11 22:16:12', '2010-06-16 20:37:36'),
(109, 'New Haven, CT', 'New Haven, CT, USA', 41.3082, -72.9282, '', 'New Haven', 'CT', '', 34, '2010-06-17 14:33:15', '2010-06-17 14:33:15'),
(110, 'San Francisco, CA', 'San Francisco, CA, USA', 37.7749, -122.419, '', 'San Francisco', 'CA', '', 47, '2010-07-12 03:46:59', '2010-07-12 03:45:30'),
(111, 'New Haven, CT', 'New Haven, CT, USA', 41.3082, -72.9282, '', 'New Haven', 'CT', '', 47, '2010-12-31 06:13:13', '2010-12-31 06:12:42'),
(112, 'New Haven, CT 2', '397 Crown St, New Haven, CT 06511, USA', 41.3082, -72.9348, '397 Crown St', 'New Haven', 'CT', '06511', 34, '2010-12-31 06:19:40', '2010-12-31 06:17:50'),
(113, 'New Haven, CT 3', '180 York St, New Haven, CT 06511, USA', 41.3085, -72.9315, '180 York St', 'New Haven', 'CT', '06511', 34, '2010-12-31 06:19:00', '2010-12-31 06:18:23'),
(114, 'Franklin, TN', 'Franklin, TN, USA', 35.9251, -86.8689, '', 'Franklin', 'TN', '', 47, '2011-01-05 22:30:39', '2011-01-05 22:30:39');


--
-- Dumping data for table `notification_types`
--

INSERT INTO `notification_types` (`id`, `title`, `news_template`, `email_template`) VALUES
(1, 'requested_gift', '{name} requested <a href=''{gift_permalink}''>{gift_title}</a>', '');

--
-- Dumping data for table `openids`
--

INSERT INTO `user_openids` (`id`, `openid`, `user_id`) VALUES
(1, 'https://www.google.com/accounts/o8/id?id=AItOawle1Du9r5KRTMTaYnVrMLiVccJAHz1E20s', 47);

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `user_id`, `url`, `thumb_url`, `caption`, `created`) VALUES
(37, 34, 'http://localhost/giftflow/uploads/37.png', 'http://localhost/giftflow/uploads/thumbnails/37.jpg', '', '2010-04-13 00:49:21'),
(38, 35, 'http://localhost/giftflow/uploads/38.jpg', 'http://localhost/giftflow/uploads/thumbnails/38.jpg', '', '2010-04-13 16:30:03'),
(39, NULL, 'http://localhost/giftflow/uploads/39.png', 'http://localhost/giftflow/uploads/thumbnails/39.jpg', '', '2010-04-17 03:04:19'),
(57, NULL, 'http://localhost/giftflow/uploads/57.png', 'http://localhost/giftflow/uploads/thumbnails/57.jpg', '', '2010-04-17 21:53:44'),
(61, NULL, 'http://localhost/giftflow/uploads/61.png', 'http://localhost/giftflow/uploads/thumbnails/61.jpg', '', '2010-04-17 22:29:57'),
(62, NULL, 'http://localhost/giftflow/uploads/62.png', 'http://localhost/giftflow/uploads/thumbnails/62.jpg', '', '2010-04-19 23:19:26');

--
-- Dumping data for table `redirects`
--


--
-- Dumping data for table `roles`
--

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(41, 'a&a'),
(43, 'architecture'),
(19, 'argyle'),
(13, 'blue'),
(45, 'chair'),
(42, 'chairs'),
(11, 'clothing'),
(23, 'computer'),
(31, 'consulting'),
(2, 'couch'),
(12, 'denim'),
(30, 'design'),
(39, 'eames'),
(20, 'fish'),
(22, 'food'),
(1, 'furniture'),
(35, 'hippies'),
(32, 'hospitality'),
(28, 'hug'),
(10, 'jeans'),
(15, 'jkldsajfladsf'),
(24, 'junk'),
(5, 'lamp'),
(9, 'leather'),
(4, 'lighting'),
(33, 'lodging'),
(44, 'lounge'),
(27, 'love'),
(8, 'luggage'),
(37, 'metasyntactic-variable programming'),
(38, 'modernism'),
(34, 'music'),
(25, 'number'),
(48, 'php'),
(26, 'prestige'),
(47, 'programming'),
(6, 'red'),
(14, 'size-30'),
(46, 'skills'),
(3, 'sofa'),
(21, 'sticks'),
(40, 'stolen'),
(7, 'suitcase'),
(18, 'sweater'),
(17, 'this is a new tag'),
(16, 'trousers'),
(29, 'ui'),
(36, 'west coast');

--
-- Dumping data for table `terms`
--

INSERT INTO `terms` (`id`, `type`, `language`, `name`, `subject`, `body`) VALUES
(10, 'alert_template', 'english', 'Email Confirmation', 'Please confirm your account', 'Hello!\r\n\r\nWelcome to GiftFlow. Please click the link below to activate your account\r\nand verify this email address.\r\n\r\n{{activation_link}}\r\n\r\nThanks!\r\nThe GiftFlow Team'),
(11, 'alert_template', 'english', 'demand_take_new', 'Someone has requested your gift!', 'Hey!\r\n\r\n{{user_name}} has requested your gift, {{good_title}}! Click the link below to respond.\r\n\r\nhttp://www.giftflow.org/gift/{{good_id}}\r\n\r\nThanks,\r\nThe GiftFlow Team\r\n');
--
-- Dumping data for table `threads`
--

--
-- Dumping data for table `threads_users`
--


--
-- Dumping data for table `transactions`
--


--
-- Dumping data for table `transactions_users`
--

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role`, `ip_address`, `email`, `password`, `activation_code`, `forgotten_password_code`, `salt`, `status`, `type`, `first_name`, `last_name`, `screen_name`, `bio`, `url`, `occupation`, `phone`, `google_token`, `google_token_secret`, `facebook_link`, `facebook_id`, `facebook_token`, `facebook_data`, `facebook_photo`, `registration_type`, `photo_source`, `default_photo_id`, `default_location_id`, `updated`, `created`) VALUES
(34, 'admin', '0.0.0.0', 'brandonsdesign@gmail.com', '29161876ea40dd6379b7d1a4365ea0e91dae0bc7', '0', '0', 'a71e520426516cf24f69e5699393d6f8b6a0cb80', NULL, '', '', '', 'Hans Schoenburg', '', '', '', '', '', '', '', '', '', '', 0, 'manual', 'giftflow', 37, 109, '2010-06-17 14:33:15', '2010-03-31 16:24:41'),
(35, 'user', '0.0.0.0', 'recycling@yale.edu', 'c1d9f9b96cc04603aa5c068a6504d983267e8c3b', '0', '0', 'cf303a17c790379cf2f204202b9cf7b2f6513659', NULL, 'institution', '', '', 'Yale Recycling', 'Yale Recycling plans, implements, and coordinates Yale''s recycling efforts. The actions of Yale University''s faculty, staff and students in the conduct of the University''s activities affirm our commitment to protect and enhance the environment through our teaching, research, service, and administrative operations. Our decisions and actions will be guided by the University''s Mission Statement, reflective of the University''s resources, and informed by the Yale University Framework for Campus Planning. ', 'http://www.yale.edu/recycling/', '', '203.432.6852', '', '', '', '', '', '', 0, 'manual', 'giftflow', 38, 0, '2010-04-13 16:30:08', '2010-04-13 16:10:23'),
(38, 'user', '0.0.0.0', 'example@example.com', '4c0b99354d566c6bcfb5541d4ddace2f33f7516f', '4bdb354abf351', '0', '66e905536d97b4d3ef3d0b55c5608721840ac14f', NULL, '', '', '', 'example@example.com', '', '', '', '', '', '', '', '', '', '', 0, 'manual', 'giftflow', 0, 0, '2010-04-30 19:53:46', '2010-04-30 19:53:46'),
(47, 'admin', '0.0.0.0', 'brandonscottjackson@gmail.com', '9378ede382144052bd5474c3a6f54e12b797a6c7', '', '0', '8a8a8fc1252fdafab622fa6622626fb3a4a5db60', NULL, 'individual', '', '', 'Brandon Jackson', 'I am a sociology student at Yale.', '', '', '', '', '', '', '', '', '', 0, 'manual', 'giftflow', 0, 107, '2010-07-21 02:14:10', '2010-04-30 20:38:46'),
(100, 'admin', '127.0.0.1', 'jonojuggles+user@gmail.com', '2731886214b91432d0002c75d13e31ed9718b1e9', '0', '0', '8806709c33c6d6da1635420a9501b44a472a02ea', NULL, 'individual', '', '', 'jonojuggles+user@gmail.com', '', '', '', '', '', '', '', '', '', '', 0, 'manual', 'giftflow', 0, 0, '2010-05-20 19:51:21', '2010-05-20 19:51:21'),
(101, 'user', '127.0.0.1', 'jonojuggles+admin@gmail.com', '2731886214b91432d0002c75d13e31ed9718b1e9', '0', '0', '8806709c33c6d6da1635420a9501b44a472a02ea', NULL, 'individual', '', '', 'jonojuggles+admin@gmail.com', '', '', '', '', '', '', '', '', '', '', 0, 'manual', 'giftflow', 0, 104, '2010-05-21 14:36:29', '2010-05-21 14:31:44'),
(104, 'user', '0.0.0.0', 'user@giftflow.org', '8edaaf9c1fd8e117ba0858f2242adac9e1a9a2de', '384b6d9a1c3359bb78829e3977ad96e706214586', '0', 'a3db346863ef32707e2f29adf9ce1c569594e4b8', NULL, 'individual', '', '', 'user@giftflow.org', '', '', '', '', '', '', '', '', '', '', 0, 'manual', 'giftflow', 0, 0, '2010-06-20 16:54:11', '2010-06-20 16:54:11'),
(105, 'admin', '0.0.0.0', 'brandon@giftflow.org', 'e49e2c80d58bbcb1f8181feac880bb4f7c0ed7c9', 'b82e81e87e53cdbc1890d87a0b8ea945efdc1fa6', '0', '099161bb481b4429f3134e47156e3447fae01fa3', NULL, 'individual', '', '', 'brandon@giftflow.org', '', '', '', '', '', '', '', '', '', '', 0, 'manual', 'giftflow', 0, 0, '2010-07-03 19:07:32', '2010-07-03 19:07:31'),
(121, 'admin', '0.0.0.0', 'brandon.jackson@yale.edu', 'dc42d0df2c730d2497096b0a3ed23ffd173d3bc6', '0', '0', 'fc728d29bbd8aa8c8ba3921dae3558559e672f3c', 'active', 'individual', '', '', 'brandon.jackson@yale.edu', '', '', '', '', '', '', '', '', '', '', 0, 'manual', 'giftflow', 0, 0, '2010-07-23 18:38:55', '2010-07-23 18:38:43'),
(122, 'admin', '0.0.0.0', 'admin@giftflow.org', '1d6512010d2a6fecd80e03c2d318d176364e3cf8', '', '0', '7d1c7a489c2ef52d08135c1d59039679561c5d00', NULL, 'individual', '', '', 'admin@giftflow.org', '', '', '', '', '', '', '', '', '', '', 0, 'manual', 'giftflow', 0, 0, '2010-09-01 06:45:12', '2010-09-01 06:45:12');

--
-- Dumping data for table `user_settings`
--

INSERT INTO `user_settings` (`id`, `user_id`, `notify_messages`, `updated`) VALUES
(8, 34, 1, '2010-03-31 16:24:42'),
(9, 35, 1, '2010-04-13 16:10:23'),
(12, 38, 1, '2010-04-30 19:53:46'),
(21, 47, 1, '2010-04-30 20:38:46'),
(27, 121, 1, '2010-07-23 18:38:43'),
(28, 122, 1, '2010-09-01 06:45:12');

SET FOREIGN_KEY_CHECKS=1;
