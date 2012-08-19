<?php
/*
The reCaptcha server keys and API locations

Obtain your own keys from:
http://www.recaptcha.net
*/

$config['recaptcha'] = array(
   'public'=>RECAPTCHA_PUBLIC_KEY,
   'private'=>RECAPTCHA_PRIVATE_KEY,
   'RECAPTCHA_API_SERVER' =>'http://api.recaptcha.net',
   'RECAPTCHA_API_SECURE_SERVER'=>'https://api-secure.recaptcha.net',
   'RECAPTCHA_VERIFY_SERVER' =>'api-verify.recaptcha.net',
   'RECAPTCHA_API_SERVER' =>'http://www.google.com/recaptcha/api',
   'RECAPTCHA_API_SECURE_SERVER'=>'https://www.google.com/recaptcha/api',
   'RECAPTCHA_VERIFY_SERVER' =>'www.google.com',
   'RECAPTCHA_SIGNUP_URL' => 'https://www.google.com/recaptcha/admin/create',
   'theme' => 'clean'
 );
