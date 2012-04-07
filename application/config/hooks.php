<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

$hook['user_new'][] = array(
	'class'    => 'Event_logger',
	'function' => 'user_new',
	'filename' => 'event_logger.php',
	'filepath' => 'libraries'
);

$hook['good_new'][] = array(
	'class'    => 'Event_logger',
	'function' => 'good_new',
	'filename' => 'event_logger.php',
	'filepath' => 'libraries'
);

$hook['good_edited'][] = array(
	'class'    => 'Event_logger',
	'function' => 'good_edited',
	'filename' => 'event_logger.php',
	'filepath' => 'libraries'
);

$hook['follower_new'][] = array(
	'class'    => 'Event_logger',
	'function' => 'follower_new',
	'filename' => 'event_logger.php',
	'filepath' => 'libraries'
);
$hook['following_new'][] = array(
	'class'    => 'Event_logger',
	'function' => 'following_new',
	'filename' => 'event_logger.php',
	'filepath' => 'libraries'
);

$hook['transaction_new'][] = array(
	'class'    => 'Event_logger',
	'function' => 'transaction_new',
	'filename' => 'event_logger.php',
	'filepath' => 'libraries'
);

$hook['transaction_new'][] = array(
	'class'    => 'Notify',
	'function' => 'alert_transaction_new',
	'filename' => 'notify.php',
	'filepath' => 'libraries'
);

$hook['transaction_new'][] = array(
	'class'    => 'Market',
	'function' => 'updated',
	'filename' => 'market.php',
	'filepath' => 'libraries'
);

$hook['transaction_activated'][] = array(
	'class'    => 'Event_logger',
	'function' => 'transaction_activated',
	'filename' => 'event_logger.php',
	'filepath' => 'libraries'
);

$hook['transaction_activated'][] = array(
	'class'    => 'notify',
	'function' => 'alert_transaction_activated',
	'filename' => 'notify.php',
	'filepath' => 'libraries'
);

$hook['transaction_declined'][] = array(
	'class'    => 'Event_logger',
	'function' => 'transaction_declined',
	'filename' => 'event_logger.php',
	'filepath' => 'libraries'
);

$hook['transaction_declined'][] = array(
	'class'    => 'Market',
	'function' => 'updated',
	'filename' => 'market.php',
	'filepath' => 'libraries'
);


$hook['transaction_cancelled'][] = array(
	'class'    => 'Event_logger',
	'function' => 'transaction_cancelled',
	'filename' => 'event_logger.php',
	'filepath' => 'libraries'
);

$hook['transaction_cancelled'][] = array(
	'class'    => 'Market',
	'function' => 'updated',
	'filename' => 'market.php',
	'filepath' => 'libraries'
);

$hook['transaction_message'][] = array(
	'class'    => 'Event_logger',
	'function' => 'transaction_message',
	'filename' => 'event_logger.php',
	'filepath' => 'libraries'
);

$hook['transaction_message'][] = array(
	'class'    => 'Market',
	'function' => 'updated',
	'filename' => 'market.php',
	'filepath' => 'libraries'
);

$hook['transaction_message'][] = array(
	'class'    => 'Notify',
	'function' => 'alert_transaction_message',
	'filename' => 'notify.php',
	'filepath' => 'libraries'
);

$hook['transaction_viewed'][] = array(
	'class'    => 'Notify',
	'function' => 'transaction_viewed',
	'filename' => 'notify.php',
	'filepath' => 'libraries'
);

$hook['transaction_completed'][] = array(
	'class'    => 'Event_logger',
	'function' => 'transaction_completed',
	'filename' => 'Event_logger.php',
	'filepath' => 'libraries'
);

$hook['review_new'][] = array(
	'class'    => 'Event_logger',
	'function' => 'review_new',
	'filename' => 'Event_logger.php',
	'filepath' => 'libraries'
);

$hook['review_new'][] = array(
	'class'    => 'Notify',
	'function' => 'review_new',
	'filename' => 'notify.php',
	'filepath' => 'libraries'
);


$hook['user_registration_manual'][] = array(
	'class'    => 'Notify',
	'function' => 'alert_user_registration_manual',
	'filename' => 'notify.php',
	'filepath' => 'libraries'
);

$hook['userdata_updated'][] = array(
	'class'    => 'Auth',
	'function' => 'new_session',
	'filename' => 'auth.php',
	'filepath' => 'libraries'
);

$hook['reset_password'][] = array(
	'class'    => 'Notify',
	'function' => 'reset_password',
	'filename' => 'notify.php',
	'filepath' => 'libraries'
);
$hook['reset_password'][] = array(
	'class'    => 'Event_logger',
	'function' => 'reset_password',
	'filename' => 'event_logger.php',
	'filepath' => 'libraries'
);
$hook['new_password'][] = array(
	'class'    => 'Event_logger',
	'function' => 'new_password',
	'filename' => 'event_logger.php',
	'filepath' => 'libraries'
);

$hook['hide_welcome'][] = array(
	'class'    => 'Event_logger',
	'function' => 'hide_welcome',
	'filename' => 'event_logger.php',
	'filepath' => 'libraries'
);

$hook['report_error'][] = array(
	'class'    => 'Notify',
	'function' => 'report_error',
	'filename' => 'notify.php',
	'filepath' => 'libraries'
);
$hook['contact_giftflow'][] = array(
	'class'    => 'Notify',
	'function' => 'contact_giftflow',
	'filename' => 'notify.php',
	'filepath' => 'libraries'
);

/*
*	PHP Quick Profiler Hooks
*/
$hook['pre_system'][] = array(
	'class'    => NULL,
	'function' => 'pqp_static',
	'filename' => 'pqp_pi.php',
	'filepath' => 'plugins',
	'params'   => 'load_pqp'
);

$hook['pre_controller'][] = array(
	'class'    => NULL,
	'function' => 'pqp_static',
	'filename' => 'pqp_pi.php',
	'filepath' => 'plugins',
	'params'   => 'pqp_pre_controller'
);

$hook['post_controller_constructor'][] = array(
	'class'    => NULL,
	'function' => 'pqp_static',
	'filename' => 'pqp_pi.php',
	'filepath' => 'plugins',
	'params'   => 'pqp_post_controller_constructor'
);

$hook['post_controller'][] = array(
	'class'    => NULL,
	'function' => 'pqp_static',
	'filename' => 'pqp_pi.php',
	'filepath' => 'plugins',
	'params'   => 'pqp_post_controller'
);


/* End of file hooks.php */
/* Location: ./system/application/config/hooks.php */