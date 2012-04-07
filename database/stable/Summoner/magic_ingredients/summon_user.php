<?php
///// CREATE AND WRITE USER	
///SET number of users	
for ($i = 0; $i < $Users_total; $i++)
{
	unset($created, $updated, $bio, $first, $last, $screen_name, $email);
	
	//Create Bio
	$num_bio = rand(5, 50);
	for ($c = 0; $c < $num_bio; $c++)
	{
		$z = rand(1, 50000);
		$bio_array[$c] = $words2[$z];
	}
	$bio = implode(" ", $bio_array);
	
	
	$x = rand(1, 1000);
	$first = $first_name[$x];
	
	$y = rand(1, 1000);
	$last = $surname[$y];
	
	$screen_name = $first . " " . $last;
	$email = $first . $last . "@gondor.com";
	$created = date('Y-m-d', strtotime('-' . mt_rand(500, 2000) . ' days'));
	$updated = date('Y-m-d', strtotime('-' . mt_rand(0, 130) . ' days'));
	$salt = "64f3735fbc544aefe51685131f5f3e24";
	$password = "03ede3233279cf0ac9dddc18c8b08a1fb453181f";
	$locale = rand(1, $Locations_total);
	
	
	$user = array(
		"password" => $password,
		"salt" => $salt,
		"role" => "user",
		"photo_source" => 'giftflow',
		"default_location_id" => $locale,
		"activation_code" => '0',
		"status" => 'active',
		"type" => 'individual',
		"first_name" => $first,
		"last_name" => $last,
		"screen_name" => $screen_name,
		"email" => $email,
		"bio" => $bio,
		"created" => $created,
		"updated" => $updated
	);
	
	$users[$i] = $user;
	
	
}
$numU = $i;

?>