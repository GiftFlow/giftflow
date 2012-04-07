<?php 
echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<rss version="2.0"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:admin="http://webns.net/mvcb/"
	xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:georss="http://www.georss.org/georss">
	<channel>
		<title><?php echo $feed_title; ?></title>
		<link>http://www.giftflow.org</link>
		<description><?php echo $feed_description; ?></description>
		
		<?php foreach($giftflow as $key=>$gift ): ?>
			<item>
				<title>
					<?php echo ucfirst($gift->title); ?>
				</title>
				<link>
					<?php echo site_url("gifts/".$gift->id); ?>
				</link>
				<description><![CDATA[ 
					<?php if(!empty($gift->description)) { 
						echo "Description: ".substr(ucfirst($gift->description), 0, 150)."...<br />";
					}?>
					<?php echo "From: ".$gift->user->screen_name; ?><br />
					<?php echo "Location: ".$gift->location->address; ?>
				]]></description>
				<?php if(!empty($gift->location->latitude) && !empty($gift->location->longitude)){ ?>
					<georss:point>
						<?php echo $gift->location->latitude.' '.$gift->location->longitude;?>
					</georss:point>
				<?php } ?>
			</item>
		<?php endforeach; ?>
	</channel>
</rss>