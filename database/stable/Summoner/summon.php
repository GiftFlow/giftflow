<?php

	// CHOOSE HOW MUCH DATA YOU WANT
	//  You cannot have more transactions than goods
	$Transactions_total = 20;
	$Goods_total = 100;
	$Users_total = 50;
	$Reviews_total = 100;
	$Tags_total = 25;
	$Goods_tags_total = 100;
	$Locations_total = 75;
	
	ini_set("memory_limit","1024M");
	$balrog = 'Balrog.sql';
	$b = fopen($balrog, 'w') or die("can't open balrog");
	$checks = "SET FOREIGN_KEY_CHECKS=0;\n";
	fwrite($b, $checks);
	
	//Import text files
	$words1 = file("magic_ingredients/words1.txt");
	$words2 = file("magic_ingredients/words2.txt");
	$first_name = file("magic_ingredients/first_names.txt");
	$surname = file("magic_ingredients/surnames.txt");
		
	// Display output?
	$print_output = FALSE;
	
	function inserter($table, $array)
	{
		$order = array(
			"\r\n",
			"\n",
			"\r"
		);
		$replace = '';
		foreach ($array as $key => $val)
		{
			$array[$key] = trim($array[$key]);
			$array[$key] = str_replace($order, $replace, $array[$key]);
			// perform other processing
			// enclose in single quotes
			$array[$key] = "'" . $array[$key] . "'";
		}
		$SQL = "INSERT INTO " . $table . " ";
		$SQL .= "(" . implode(array_keys($array), ",") . ") ";
		$SQL .= "VALUES (" . implode(array_values($array), ",") . ");\n";
	
		// print the output if the user wants to see it
    //echo $SQL;
		
		return $SQL;
	}
	
	// INSERT a bunch of locations
	include("magic_ingredients/summon_location.php");
	$auto = "ALTER TABLE locations AUTO_INCREMENT = 1;\n";
	fwrite($b, $auto);
	foreach ($places as $key => $val)
	{
		$write = inserter('locations', $val);
		fwrite($b, $write);
	}
	/////Summon Users		
	include("magic_ingredients/summon_user.php");
	$auto = "ALTER TABLE users AUTO_INCREMENT = 1;\n";
	fwrite($b, $auto);
	foreach ($users as $key => $val)
	{
		$write = inserter('users', $val);
		fwrite($b, $write);
	}
	
	// Summon Goods
	include("magic_ingredients/summon_good.php");
	$auto = "ALTER TABLE goods AUTO_INCREMENT = 1;\n";
	fwrite($b, $auto);
	foreach ($goods as $key => $val)
	{
		$write = inserter('goods', $val);
		fwrite($b, $write);
	}
	
	// Summon Reviews
	include("magic_ingredients/summon_review.php");
	$auto = "ALTER TABLE reviews AUTO_INCREMENT = 1;\n";
	fwrite($b, $auto);
	foreach ($reviews as $key => $val)
	{
		$write = inserter('reviews', $val);
		fwrite($b, $write);
	}
	// Summon Tags
	include("magic_ingredients/summon_tag.php");
	$auto = "ALTER TABLE tags AUTO_INCREMENT = 1;\n";
	fwrite($b, $auto);
	foreach ($tags as $key => $val)
	{
		$write = inserter('tags', $val);
		fwrite($b, $write);
	}
	
	// Summon Goods_tags
	include("magic_ingredients/summon_good_tag.php");
	$auto = "ALTER TABLE goods_tags AUTO_INCREMENT = 1;\n";
	fwrite($b, $auto);
	foreach ($goods_tags as $key => $val)
	{
		$write = inserter('goods_tags', $val);
		fwrite($b, $write);
	}
	
	//Insert transactions
	$auto = "ALTER TABLE transactions AUTO_INCREMENT = 1;\n";
	fwrite($b, $auto);
	include("magic_ingredients/summon_transaction.php");
	fclose($b);
	
	echo "Summon Completed: Beware of Balrog\n";

?>
