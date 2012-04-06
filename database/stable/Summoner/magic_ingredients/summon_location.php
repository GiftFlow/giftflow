<?php

//Summon Locations array with geocoding
include("locations.php");

for ($i = 0; $i < $Locations_total; $i++)
{
	///Generate location
	$here = rand(0, 87);
	$city = $locations[$here]['city'];
	$latpre = $locations[$here]['lat'];
	$lngpre = $locations[$here]['lng'];
	$state = $locations[$here]['state'];
	$address = $locations[$here]['address'];
	$person = rand(1, $Users_total);
	$created = date('Y-m-d', strtotime('-' . mt_rand(500, 2000) . ' days'));
	$updated = date('Y-m-d', strtotime('-' . mt_rand(0, 130) . ' days'));
	
	
	///Randomize latlng
	
	$x = rand(1, 4);
	$y = rand(1, 10);
	$z = $y / 10;
	
	if ($x == 1)
	{
		$lat = $latpre - $z;
		$lng = $lngpre - $z;
	}
	if ($x = 2)
	{
		$lat = $latpre + $z;
		$lng = $lngpre + $z;
	}
	if ($x == 3)
	{
		$lat = $latpre + $z;
		$lng = $lngpre - $z;
	}
	if ($x = 4)
	{
		$lat = $latpre - $z;
		$lng = $lngpre + $z;
	}
	
	$place = array(
		"title" => $city,
		"city" => $city,
		"address" => $address,
		"state" => $state,
		"latitude" => $lat,
		"longitude" => $lng,
		"user_id" => $person,
		"created" => $created,
		"updated" => $updated
	);
	
	$places[$i] = $place;
}
?>