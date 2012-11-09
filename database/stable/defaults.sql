
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
(11, 'follower_new'),
(12, 'transaction_declined'),
(13, 'hide_welcome'),
(14, 'reset_password'),
(15, 'new_password'),
(16, 'email'),
(17, 'thankyou'),
(18, 'thankyou_updated'),
(19, 'thank_invite'),
(20, 'user_message');


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

