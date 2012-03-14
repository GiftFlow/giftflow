TRUNCATE TABLE `event_types`;
TRUNCATE TABLE `categories`;

INSERT INTO `event_types` (`id`, `title`) VALUES
(1, 'transaction_new'),
(2, 'transaction_completed'),
(3, 'transaction_message'),
(4, 'user_new'),
(5, 'transaction_cancelled'),
(6, 'transaction_activated'),
(7, 'review_new'),
(8, 'good_new'),
(9, 'good_edited'),
(10, 'following_new'),
(11, 'follower_new'),
(12, 'transaction_declined'),
(13, 'hide_welcome'),
(14, 'reset_password'),
(15, 'new_password');

INSERT INTO `categories` (`id`, `name`, `parent_category_id`) VALUES
(1, 'Arts & Crafts', NULL),
(2, 'Books', NULL),
(3, 'Classes & Workshops', NULL),
(4, 'Clothing & Shoes', NULL),
(5, 'Electronics & Computers', NULL),
(6, 'Furniture', NULL),
(7, 'Home & Garden', NULL),
(8, 'Movies', NULL),
(9, 'Music', NULL),
(10, 'Office Supplies', NULL),
(11, 'Skills & Services', NULL),
(12, 'Sports & Recreation', NULL),
(13, 'Tools', NULL),
(14, 'Transportation', NULL),
(15, 'Video Games', NULL),
(16, 'Other', NULL);


INSERT INTO `terms` (`id`, `type`, `language`, `name`, `subject`, `body`) VALUES
(12, 'alert_template', 'en', 'transaction_new', '{{subject}}', 'Hello {{decider_name}}, <br /><br />\r\nThis is an automatic notification from GiftFlow. \r\n <br /><br />\r\n{{summary}}\r\n <br /><br />\r\nThey included a message to you:<br />\r\n{{note}}\r\n\r\n'),
(13, 'alert_template', 'en', 'email_confirmation', '{{subject}}', 'Welcome to GiftFlow! <br /><br />\r\nPlease confirm your email by clicking on the link below.\r\n <br /><br />{{activation_link}}'),
(14, 'alert_template', 'en', 'transaction_activated', '{{subject}}', 'Hello {{demander_name}}, <br /><br />\r\n{{demander_summary}}\r\n <br /><br />\r\n{{decider_name}} has accepted your request!\r\n <br /><br />\r\nThey wrote {{message}}'),
(15, 'alert_template', 'en', 'transaction_message', '{{subject}}', 'Hello {{recipient_name}},\r\n<br /><br />\r\n{{user_screen_name}} has sent you a message regarding {{good_title}}\r\n<br /><br />\r\nThey wrote:\r\n{{message}}\r\n'),
(16, 'alert_template', 'en', 'review_new', '{{subject}}', 'Hello {{reviewed_screen_name}},\r\n <br /><br />\r\n{{reviewer_screen_name}} just wrote a review about you regarding: <br />{{good_title}}. \r\n <br /><br />\r\nYou can read it if you have already written a review yourself. Otherwise, write them a review right now. '),
(17, 'alert_template', 'en', 'reset_password', '{{subject}}', 'Hello {{screen_name}},<br /><br />\r\nClick on the link below to reset your password: <br /><br />\r\n{{password_reset_link}}\r\n');
