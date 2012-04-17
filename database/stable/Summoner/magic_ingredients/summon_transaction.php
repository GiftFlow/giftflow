<?php

$t = 0; //Transaction id
$r = -1; //Reviewer id
$rd = 0; //Reviewed id
$g = 0; //Good id

for ($count = 0; $count < $Transactions_total; $count++)
{
	$review = FALSE;
	unset($write);
	$g++;
	$l = rand(1, $Locations_total);

	$location = ""; // TODO: put a value here?
	
	$t++; //increment transaction id
	$UseCase = rand(1, 4); //rand(1,4); //randomizes type of demand
	$k = rand(1, 8); //randomizes transaction status
	
	$t_status = "active";
	
	if ($k == 1 || $k == 2)
	{
		$t_status = "pending";
	}
	elseif ($k == 3)
	{
		$t_status = "declined";
	}
	elseif ($k == 4)
	{
		$t_status = "cancelled";
	}
	elseif ($k == 5 || $t == 6)
	{
		$t_status = "active";
	}
	elseif ($k == 7 || $t == 8)
	{
		$t_status = "completed";
		$r += 2;
		$rd += 2;
		$review = TRUE; //flag for whether or not to generate reviews
	}
	
	
	//Transaction_id 
	//pending(1-500)
	//declined(501-700)
	//cancelled (701-800)
	//disabled (801-900)
	//active (901-1500)
	//completed(1501-2000)
	$uone = rand(1, $Users_total); //User_id (user one, makes demand)
	$utoo = rand(1, $Users_total); //User_id (user two, recieves demand)
	$gf = rand(1, $Goods_total); //good for fulfill use cases
	
	$created = date('Y-m-d', strtotime('-' . mt_rand(100, 1000) . ' days'));
	$updated = date('Y-m-d', strtotime('-' . mt_rand(0, 90) . ' days'));
	
	
	//goods
	// Gifts 1-1000 
	//Needs 1001-2000	
	
	//Check for identical User ids
	if ($uone == $utoo)
	{
		$utoo = rand(1, $Users_total);
	}
	
	//Insert transaction, starting wherever $l is set
	// $transaction = inserter("transactions",array(
// 		"id"=>$t,
// 		"status"=>$t_status,
// 		"created"=>$created,
// 		"updated"=>$updated
// 	));
	
	$transaction = "INSERT INTO transactions (id, status, created, updated) VALUES ('$t','$t_status','$created','$updated');\n";
	
	if ($UseCase == 1)
	{
		//Use Case "Take" - $uone "takes" $utoo's Gift
		
		$user = "UPDATE users SET default_location_id='$l' WHERE id='$utoo';\n";
		
		$tu1 = inserter("transactions_users",array(
			"transaction_id"=>$t,
			"user_id"=>$uone
		));
		
		$tu2 = inserter("transactions_users",array(
			"transaction_id"=>$t,
			"user_id"=>$utoo
		));
		
		// @todo convert to using updater and inserters
		$good = "UPDATE goods SET user_id='$utoo', type='gift', location_id='$l' WHERE id='$g';\n";
		
		$demand = "INSERT INTO demands (type, transaction_id, good_id, user_id, updated, created) VALUES ('take','$t','$g','$uone','$updated','$created');\n";
		
		if ($review)
		{
			$review1 = "UPDATE reviews SET transaction_id='$t', reviewer_id='$uone', reviewed_id='$utoo', created='$created', updated='$updated' WHERE id='$r';\n";
			
			$review2 = "UPDATE reviews SET transaction_id='$t', reviewer_id='$utoo', reviewed_id='$uone', created='$created', updated='$updated' WHERE id='$rd';\n";
			$write = $transaction . $location . $user . $tu1 . $tu2 . $good . $demand . $review1 . $review2;
		}
		
		elseif (!$review)
		{
			$write = $transaction . $location . $user . $tu1 . $tu2 . $good . $demand;
		}
		
		fwrite($b, $write);
	}
	elseif ($UseCase == 2)
	{
		//Use Case "Give to Need"	$utoo offers to give to $uone's Need
		
		$user = "UPDATE users SET default_location_id='$l' WHERE id='$uone';\n";
		
		$tu1 = "INSERT INTO transactions_users (transaction_id, user_id) VALUES ('$t', '$uone');\n";
		
		$tu2 = "INSERT INTO transactions_users (transaction_id, user_id) VALUES ('$t', '$utoo');\n";
		
		$good = "UPDATE goods SET user_id='$uone', type='need', location_id='$l' WHERE id='$g';\n";
		
		$demand = "INSERT INTO demands (type, transaction_id, good_id, user_id, updated, created) VALUES ('give','$t','$g','$utoo','$updated','$created');\n";
		
		if ($review)
		{
			$review1 = "UPDATE reviews SET transaction_id='$t', reviewer_id='$uone',  reviewed_id='$utoo', created='$created', updated='$updated' WHERE id='$r';\n";
			
			$review2 = "UPDATE reviews SET transaction_id='$t', reviewer_id='$utoo',  reviewed_id='$uone', created='$created', updated='$updated' WHERE id='$rd';\n";
			$write = $transaction . $location . $user . $tu1 . $tu2 . $good . $demand . $review1 . $review2;
		}
		
		elseif (!$review)
		{
			$write = $transaction . $location . $user . $tu1 . $tu2 . $good . $demand;
		}
		
		fwrite($b, $write);
	}
	elseif ($UseCase == 3)
	{
		//Use Case "Offer Gift"	$uone offers Gift to $utoo
		
		$user = "UPDATE users SET default_location_id='$l' WHERE id='$uone';\n";
		
		$tu1 = "INSERT INTO transactions_users (transaction_id, user_id) VALUES ('$t', '$uone');\n";
		
		$tu2 = "INSERT INTO transactions_users (transaction_id, user_id) VALUES ('$t', '$utoo');\n";
		
		$good = "UPDATE goods SET user_id='$uone', type='gift', location_id='$l' WHERE id='$g';\n";
		
		$demand = "INSERT INTO demands (type, transaction_id, good_id, user_id, updated, created) VALUES ('give','$t','$g','$uone','$updated','$created');\n";
		
		if ($review)
		{
			$review1 = "UPDATE reviews SET transaction_id='$t', reviewer_id='$uone', reviewed_id='$utoo', created='$created', updated='$updated' WHERE id='$r';\n";
			
			$review2 = "UPDATE reviews SET transaction_id='$t', reviewer_id='$utoo', reviewed_id='$uone', created='$created', updated='$updated' WHERE id='$rd';\n";
			$write = $transaction . $location . $user . $tu1 . $tu2 . $good . $demand . $review1 . $review2;
		}
		
		elseif (!$review)
		{
			$write = $transaction . $location . $user . $tu1 . $tu2 . $good . $demand;
		}
		
		
		fwrite($b, $write);
	}
	elseif ($UseCase == 4)
	{
		//Use Case "fulfill" $uone's $g fulfill $utoo's $gf --- $g and $gf are a gift and a need, either way
		// good_id 1-1000 is a gift, good_id 1001-2000 is a Need (data already entered into database)
		
		$gift = rand(1, 2);
		
		$user = "UPDATE users SET default_location_id='$l' WHERE id='$uone';\n  UPDATE users SET default_location_id='$l' WHERE id='$utoo';\n";
		
		$tu1 = "INSERT INTO transactions_users (transaction_id, user_id) VALUES ('$t', '$uone');\n";
		
		$tu2 = "INSERT INTO transactions_users (transaction_id, user_id) VALUES ('$t', '$utoo');\n";
		
		
		if ($gift == 1)
		{
			$good = "UPDATE goods SET user_id='$uone', type='gift', location_id='$l' WHERE id='$g';\n";
			$good .= "UPDATE goods SET user_id='$utoo', type='need', location_id='$l' WHERE id='$gf';\n";
			
			$demand = "INSERT INTO demands (type, transaction_id, good_id, user_id, updated, created) VALUES ('give','$t','$g','$uone','$updated','$created');\n";
			$demand .= "INSERT INTO demands (type, transaction_id, good_id, user_id, updated, created)  VALUES  ('fulfill','$t','$gf','$uone','$updated','$created');\n";
			
			
		}
		elseif ($gift == 2)
		{
			$good = "UPDATE goods SET user_id='$uone', type='need', location_id='$l' WHERE id='$g';\n";
			$good .= "UPDATE goods SET user_id='$utoo', type='gift', location_id='$l' WHERE id='$gf';\n";
			
			$demand = "INSERT INTO demands (type, transaction_id, good_id, user_id, updated, created) VALUES ('take','$t','$g','$uone','$updated','$created');\n";
			$demand .= "INSERT INTO demands (type, transaction_id, good_id, user_id, updated, created) VALUES ('fulfill','$t','$gf','$uone','$updated','$created');\n";
		}
		
		if ($review)
		{
			$review1 = "UPDATE reviews SET transaction_id='$t', reviewer_id='$uone',  reviewed_id='$utoo', created='$created', updated='$updated' WHERE id='$r';\n";
			
			$review2 = "UPDATE reviews SET transaction_id='$t', reviewer_id='$utoo',  reviewed_id='$uone', created='$created', updated='$updated' WHERE id='$rd';\n";
			$write = $transaction . $location . $user . $tu1 . $tu2 . $good . $demand . $review1 . $review2;
		}
		
		elseif (!$review)
		{
			$write = $transaction . $location . $user . $tu1 . $tu2 . $good . $demand;
		}
		
		fwrite($b, $write);
	}
}
?>
