<?php
/////Summon Tags
///set number of goods_tags
for ($i = 0; $i < $Goods_tags_total; $i++)
{
	unset($good, $tag);
	$good = rand(1, $numG);
	$tag = rand(1, $numTag);
	$good_tag = array(
		"good_id" => $good,
		"tag_id" => $tag
	);
	$goods_tags[$i] = $good_tag;
}
$numGoodTag = $i;
?>