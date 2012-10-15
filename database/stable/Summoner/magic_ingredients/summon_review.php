<?php 
 //Generates random number of words
 
//Set number of reviews
 for($a=0; $a<$Reviews_total; $a++) {
 
 unset($created,$updated,$rating,$body,$review);
 
 	$created = date('Y-m-d', strtotime( '-'.mt_rand(500,2000).' days'));
	$updated = date('Y-m-d', strtotime( '-'.mt_rand(0,130).' days'));
 
 	$r = rand (1,3);
 	
 		if($r==1) {
 			$rating="positive";
 			}
 		if($r==2){
 			$rating="negative";
 			}
 		if($r==3){
 			$rating="neutral";
 			}
 			
	 $num_review= rand(5,25);
 
		for($i=0; $i<$num_review; $i++) {
			$x = rand(1,50000);
			$review_array1[$i] = $words1[$x];
			}
		for($i=0; $i<$num_review; $i++) {
			$x = rand(1,50000);
			$review_array2[$i] = $words2[$x];
			}
		
		$review_array = array_merge($review_array1, $review_array2);
		
   		$body =	implode(" ", $review_array);
   
   
   		$review = array(
			"rating"=>$rating,
			"body"=>$body,
			"created"=>$created,
			"updated"=>$updated
			);
			
		$reviews[$a] = $review;
  		
	}
	
	$numR = $a;
?>