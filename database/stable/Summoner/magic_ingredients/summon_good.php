<?php
//SET NUMBER OF GOODS
for ($i = 0; $i < $Goods_total; $i++)
{
	unset($good);
	//Set Title
	$num_title = rand(1, 5);
	for ($c = 0; $c < $num_title; $c++)
	{
		$z = rand(1, 50000);
		$title_array[$c] = $words1[$z];
	}
	$title = implode(" ", $title_array);
	
	//Set Type
	$t = rand(1, 2);
	
	if ($t == 1)
	{
		$type = "gift";
	}
	elseif ($t == 2)
	{
		$type = "need";
	}
	
	//Set Description
	$num_desc = rand(1,25);
	for ($c = 0; $c < $num_desc; $c++)
	{
		$desc_array1[$c] = $words1[array_rand($words1)];
	}
	for ($c = 0; $c < $num_desc; $c++)
	{
		$desc_array2[$c] = $words2[array_rand($words2)];
	}
	$desc_array = array_merge($desc_array1, $desc_array2);
	$description = implode(" ", $desc_array);
	
	//Set user_id
	$uid = rand(1, $Users_total);
	
	//Created and Updated
	$created = date('Y-m-d', strtotime('-' . mt_rand(500, 2000) . ' days'));
	$updated = date('Y-m-d', strtotime('-' . mt_rand(0, 130) . ' days'));
	
	//Set category id
	$Cid = rand(1, 16);
	
	// Set location is
	$platz = rand(1, $Locations_total);
	
	//Good array	
	$good = array(
		"title" => $title,
		"type" => $type,
		"description" => $description,
		"status" => 'active',
		"user_id" => $uid,
		"location_id" => $platz,
		"created" => $created,
		"updated" => $updated,
		"quantity" => '1',
		"shareable" => '1',
		"category_id" => $Cid,
		"default_photo_id" => '1'
	);
		
	$goods[$i] = $good;
}

$numG = $i;
?>
