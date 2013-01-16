<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['email_templates'] = array(
	'transaction_new' => array('en' => '<p><b>Hello {{decider_name}},</b></p><p>{{summary}}</p><p>{{demander_name}} wrote:</p><p>"{{note}}"</p><p><a href="{{return_url}}">Click Here to Login and Respond</a></p>'),
	'email_confirmation' => array('en' => '<p><b>Welcome to GiftFlow!</b></p><p>Please confirm your email by clicking on the link below.</p><br /><a href="{{activation_link}}">{{activation_link}}</a>'),
	'transaction_activated' => array('en' => '<p><b>Hello {{demander_name}},</b></p><p>{{demander_summary}} </p><p><b>{{decider_name}}</b> has accepted your request!</p><p><a href="{{return_url}}">Click Here to Login and Respond</a></p>'),
	'transaction_message' => array('en' => '<p><b>Hello {{recipient_name}},</b></p><p>{{user_screen_name}} has sent you a message regarding <b>{{good_title}}</b>.</p><p><b>{{user_screen_name}}</b> wrote: <br/>"{{message}}"</p><p><a href="{{return_url}}">Click Here to Login and Respond</a></p>'),
	'review_new' => array('en' => '<p><b>Hello {{reviewed_screen_name}},</b></p><p><b>{{reviewer_screen_name}}</b> has written you a review regarding <b>{{good_title}}</b>. </p><p><a href="{{return_url}}">Click here to login and read what they wrote</a></p><p>In case you have not already, do not forget to write {{reviewer_screen_name}} a review yourself!</p>'),
	'reset_password' => array('en' => '<p><b>Hello {{screen_name}},</b></p><p>Click on the link below to reset your password:<br /><a href="{{password_reset_link}}">{{password_reset_link}}</a></p>'),
	'report_error' => array('en' => 'giftflow admin! <br /> someone has encountered an error on giftflow "{{message}}" ON this page: "{{page}}"<br />hope u can figure this out'),
	'contact_giftflow' => array('en' => 'What is up admin? {{name}} has sent you a message from outer space....<br /><br />{{message}}<br/>Their email is {{email}}<br /><br />When you write them back, be sure to set your email to your @giftflow.org account'),
	'watch_match' => array('en' => '<p><b>Hello {{recipient_name}},</b></p><p> A new gift was posted that you may be interested in: <br/><b>{{title}}</b></p><p>Follow this link to see the posting: <a href="{{link}}">{{link}}</a></p>'),
	'thankyou' => array('en' => '<p><b>Hello {{recipient_screen_name}}, </b></p><p><b>{{thanker_screen_name}}</b> has thanked you on GiftFlow for: "{{gift_title}}".</p><p>{{thanker_screen_name}} wrote: </p><p>"{{body}}"</p><p><a href="{{return_url}}">Click here to Accept or Decline.</a></p>'),
	'thankyou_updated' => array('en' => '<p><b>Hello {{thanker_screen_name}},</b></p><p>{{subject}}</p><p>You thanked them for <b>{{gift_title}}</b>.</p><p>If you would like, you can send them a message through your <a href="{{return_url}}">Inbox</a>.</p>'),
	'transaction_reminder' => array('en' => ''),
	'goods_match' => array('en' => ''),
	'user_message' => array('en' => '<p><b>Hello {{recipient_name}},</b></p><p>{{user_screen_name}} has sent you a message.</p><p><b>{{user_screen_name}}</b> wrote: <br/>"{{message}}"</p><p><a href="{{return_url}}"Click Here to Login and Respond</a></p>'),
	'thank_invite' => array('en' => '<p><b>Hello {{recipient_email}},</b></p><p>{{thanker_screen_name}} has thanked you on <a href="'.site_url().'">GiftFlow</a> for: "{{gift_title}}".</p><p>{{thanker_screen_name}} wrote: </p><p>"{{body}}"</p><br /><br /><div style="font-size:12px; color:#666666;"><p>GiftFlow is an online community of giving where users post what they need and what they can give away. <a href="'.site_url("member/register").'">Register</a> and be recognized for your generosity.</p><p>This Thank will not be published online unless you create an account with this email address. </p></div>')

);

/* End of file email_templates.php */
