<?php

/////Summon Tags

	///set number of tags
		for($i=1;$i<$Tags_total; $i++) {
		unset($name_array,$tag);
		
		$num_tag= rand(1,2);
			for($c=0; $c<$num_tag; $c++) {
				$z = rand(1,50000);
				$name_array1[$c] = $words1[$z];
			}
		$num_tag= rand(1,2);
			for($c=0; $c<$num_tag; $c++) {
				$z = rand(1,50000);
				$name_array2[$c] = $words2[$z];
			}
		$name_array = array_merge($name_array1, $name_array2);
				
		$name = implode(" ", $name_array);
		
		
		$tag = array(
			"name"=>$name
			);
		$tags[$i] = $tag;
		}
		
	$numTag=$i;

?>