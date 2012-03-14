<?php if($show_form) { ?>
<p> welcome to giftflow organizing. providing organizers with the data they need</p>

<p>Gift/Need posters:</p>
<p>Enter a location here and click submit. It will generate  a list of the newest gifts and needs within 100 miles of you</p>

<div id='input_location'>

	<form id='location' method = 'post'>
		<label for='location'> Input location </label>
		<input type='text' name='location'/>
		<input type='submit' value='input'>
	</form>

	
	
</div>
<?php } ?>	
<div>
	<h2><img src="<?php echo site_url(); ?>/assets/images/giftflowlogoBIG.png" width='500px'/> <?php if(!empty($L)) { print_r($L->address); } ?></h2>
	
</div>


<div id = 'lists' style='color: black; !important'>
	<div class='left_content' style='width: 450px; float:left;'>
		<h1>Gifts</h1>
			<?php if(!empty($gifts)){ ?>
						<?php echo UI_Results::goods(array(
							"results"=>$gifts,
							"include"=>array("location", "created", 'no_pic'),
							"mini"=>FALSE
						));?>
					<?php } ?>
	</div>
	
	<div class='right_content' style='width:450px; float:right;'>
		<h1> Needs </h3>
				<?php if(!empty($needs)){ ?>
						<?php echo UI_Results::goods(array(
							"results"=>$needs,
							"include"=>array("location", "created", 'no_pic'),
							"mini"=>FALSE
						));?>
					<?php } ?>
	</div>
	
</div>

<script type='text/javascript'>
$(function(){
	$('.title').css('color','black');


});
</script>