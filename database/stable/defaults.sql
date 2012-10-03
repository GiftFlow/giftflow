
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
(15, 'new_password'),
(16, 'email'),
(17, 'thankyou'),
(18, 'thankyou_updated');

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
(12, 'alert_template', 'en', 'transaction_new', '{{subject}}', '<p><b>Hello {{decider_name}},</b></p>\r\n<p>\r\n{{summary}}\r\n</p>\r\n<p>\r\n{{demander_name}} wrote:<br/>\r\n"{{note}}"\r\n</p>\r\n<p>\r\n<a href=''{{return_url}}''>Click Here to Login and Respond</a>\r\n</p>\r\n'),
(13, 'alert_template', 'en', 'email_confirmation', '{{subject}}', '<p><b>Welcome to GiftFlow!</b></p>\r\n<p>\r\nPlease confirm your email by clicking on the link below.\r\n</p>\r\n<br />\r\n{{activation_link}}'),
(14, 'alert_template', 'en', 'transaction_activated', '{{subject}}', '<p><b>Hello {{demander_name}},</b></p>\r\n<p>\r\n{{demander_summary}}\r\n </p>\r\n<p>\r\n<b>{{decider_name}}</b> has accepted your request!\r\n</p>\r\n<p>\r\n<a href=''{{return_url}}''>Click Here to Login and Respond</a>\r\n</p>'),
(15, 'alert_template', 'en', 'transaction_message', '{{subject}}', '<p><b>Hello {{recipient_name}},</b></p>\r\n<p>\r\n{{user_screen_name}} has sent you a message regarding <b>{{good_title}}</b>.\r\n</p>\r\n<p>\r\n<b>{{user_screen_name}}</b> wrote: \r\n<br/>\r\n"{{message}}"\r\n</p>\r\n<p>\r\n<a href=''{{return_url}}''>Click Here to Login and Respond</a>\r\n</p>'),
(16, 'alert_template', 'en', 'review_new', '{{subject}}', '<p><b>Hello {{reviewed_screen_name}},</b></p>\r\n\r\n<p>\r\n<b>{{reviewer_screen_name}}</b> has written you a review regarding <b>{{good_title}}</b>. \r\n</p>\r\n<p>\r\n<a href=''{{return_url}}''>Click here to login and read what they wrote</a>\r\n</p>\r\n<p>In case you have not already, do not forget to write {{reviewer_screen_name}} a review yourself!\r\n</p>'),
(17, 'alert_template', 'en', 'reset_password', '{{subject}}', '<p><b>Hello {{screen_name}},</b></p>\r\n\r\n<p>\r\nClick on the link below to reset your password:\r\n<br />\r\n{{password_reset_link}}\r\n</p>'),
(18, 'alert_template', 'en', 'report_error', '{{subject}}', 'yo admin! \r\n\r\nSOUND THE ALARM - someone has encountered an error on giftflow\r\n\r\n{{message}} \r\n\r\nON this page: {{page}}\r\n\r\nhope u can figure this out quick\r\n-h'),
(19, 'alert_template', 'en', 'contact_giftflow', '{{subject}}', 'What is up admin? {{name}} has sent you a message from outer space....<br /><br />\r\n\r\n{{message}}\r\n<br/>\r\nTheir email is {{email}}\r\n<br /><br />\r\nWhen you write them back, be sure to set your email to your @giftflow.org account'),
(20, 'alert_template', 'en', 'watch_match', '{{subject}}', '<p><b>Hello {{recipient_name}},</b></p>\r\n<p>\r\n A new gift was posted that you may be interested in: <br/>\r\n<b>{{title}}</b>\r\n</p>\r\n<p>\r\nFollow this link to see the posting: {{link}}\r\n</p>'),
(21, 'alert_template', 'en', 'thankyou', '{{subject}}', '<p><b>\r\nHello {{recipient_screen_name}}, \r\n</b></p>\r\n<p>\r\n<b>{{screen_name}}</b> has thanked you on GiftFlow for: "{{gift_title}}".\r\n</p>\r\n<p>\r\nThey wrote: <br />\r\n"{{body}}"\r\n</p>\r\n\r\n<p>\r\n<a href=''{{return_url}}''>Click here to Accept or Decline.</a>\r\n</p>'),
(23, 'alert_template', 'en', 'thankyou_updated', '{{subject}}', '<p><b>Hello {{screen_name}},</b></p>\r\n<p>\r\n{{subject}}\r\n</p>\r\n<p>\r\nYou thanked them for <b>{{gift_title}}</b>.\r\n</p>\r\n<p>\r\nIf you would like, you can send them a message through your <a href=''{{return_url}}''>Inbox</a>.\r\n</p>');
(24, 'alert_template', 'en', 'transaction_reminder', '{{subject}}', '');
(25, 'alert_template', 'en', 'goods_match', '{{subject}}', '');

