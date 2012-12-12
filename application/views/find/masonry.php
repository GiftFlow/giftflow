<div class='row find_header'>
	<?php if($type == 'need') { ?>
		<h1 class='need'>Give!</h1>
		<p class='nicebigtext'>Earn some gratitude. Browse these needs to find where you can help.</p>
	<?php } else { ?>
		<h1>Get!</h1>
		<p class='nicebigtext'>Enjoy the generosity of your community. Request what you want.</p>
	<?php } ?>
</div>
<div class='nav_wrapper'>
	<div class='row chunk' id='masonry_nav' data-spy='affix' data-offset-top='140'>
			<!-- Goods dropdown -->
			<div class='btn-group span2'>
				<button class='btn btn-large parent_cat' type='button' id='cat_'>Goods</button>
				<button class='btn btn-large dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
				<ul class='dropdown-menu goods_menu'>
					<?php foreach($categories as $cat) { ?>
						<li class='subcategory' id='cat_<?php echo $cat->id; ?>'><?php echo $cat->name; ?></li>
					<?php } ?>
				</ul>
			</div>
			<!-- services dropdown -->
			<div class='btn-group span2'>
				<button class='btn btn-large parent_cat' id='cat_11' type='button'>Deeds</button>
				<button class='btn btn-large dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
				<ul class='dropdown-menu deeds_menu'>
					<?php foreach($categories as $cat) { ?>
						<li class='subcategory' id='cat_<?php echo $cat->id; ?>'><?php echo $cat->name; ?></li>
					<?php } ?>
				</ul>
			</div>
			<div class='search_elements span8'>
				<form name='find_goods' class='find_form'id="find_goods" action="" method='get'>
					<div class='input-append'>
						<input type='text' size='16' placeholder="<?php if($type == 'people') { echo 'Name'; } else { echo 'Keyword'; } ?>" class='masonry_input' id="q" name='q' value='<?php echo $args["q"];?>' />
						<button class='btn btn-large' type='submit' id="find"><i class='icon-search'></i> Find</button>
					</div>
				</form>
					<select name="sort" id="order_by" class='input-small'>
						<option value="newest" selected>Newest</option>
						<option value="nearby">Nearby</option>
					</select>
					<button class='btn btn-large' type='button' href="<?php echo site_url('you/add_good/'.$type);?>"><i class='icon icon-plus'></i>Post <?php echo ucfirst($type); ?></button>
			</div>
	</div>
</div><!-- close row -->

<div class='row brick_wall'>
	<?php if($display == 'results') { ?>
		<?php foreach($results as $obj) { ?>
			<?php echo $obj->html; ?>
		<?php } ?>
	<?php } ?>

		<!-- Loading Message -->
		<div class="results_empty" style="<?php if($display == 'results') { echo 'display:none'; } ?>">
			<h3>No Results Found</h3>
			<p>Oops! No results were found that matched your query.</p>
			<?php if($args['type'] != 'people') { ?>
				<p></p>
				<a href="<?php echo site_url('you/watches'); ?>" class='btn btn-info btn-large'>Add Watch</a>
				<p></p>
				<p>Add a watch keyword to be notified when someone posts a match!</p>
			<?php }?>
		</div>
	
</div>
<div id='ajax_loader' style='display:none;'>
	<center>	
		<?php if($type =='need') { ?>
			<img src="<?php echo site_url('assets/images/285needloader.gif');?>"/>
		<?php } else { ?>
			<img src="<?php echo site_url('assets/images/285loader.gif');?>"/>
		<?php } ?>
	</center>
</div>



<script type='text/javascript'>

$(function() {

	$('.brick_wall').masonry({
		itemSelector: '.brick',
			columnWidth: 160,
			isFitWidth:true
	}).imagesLoaded(function() {
		$('.brick_wall').masonry('reload');
	});


	$('.nav_wrapper').height($('#masonry_nav').height());


	// GF Namespace wrapper
	GF.UI = {};
	GF.Data = {};
	GF.Ajax = {};

	//Infinite scroll flag

	 GF.UI.scroll_load = false;
	
	// Write pre-loaded data
	GF.Data = <?php echo $results_json; ?>;
	
	GF.Params = (function(){
	
		var api = {};
		
		var data = {
			type: "<?php echo $args['type']; ?>",
			q: '',
			profile_type:"<?php echo $args['profile_type'];?>",
			order_by: "<?php echo $args['order_by'];?>",
			category_id: "<?php echo $args['category_id'];?>",
			limit: 50,
			offset: 0,
			radius: 100,
		};
		
		api.get = function(){
			return data;
		};
		
		api.set = function(key,value){
			data[key] = value;
		};

		api.changetype = function () {
			data.order_by = '';
			data.category_id = '';
			data.profile_type = '';
			data.q = '';
		};
		
		return api;
		
	}());

	GF.UI.noResults = function(){
		console.log("NO RESULTS");
		$('.results_empty').show();
	};
	GF.UI.clearResults = function() {
		$('.brick_wall').empty();
	};
	
	// Add results to UI
	GF.UI.setResults = function(data){
		$.each(data, function(key, val){
			$(".brick_wall").append($(val.html));
		});
		$('.brick_wall').masonry('reload');
		//reset back to false each time
		GF.UI.scroll_load = false;
	};

	// Process AJAX Data
	GF.Ajax.process = function(data){
		if(!GF.UI.scroll_load) {
			GF.UI.clearResults();
		}
		if(data.results.length > 0){
			GF.UI.setResults(data.results);
		} else {
			return GF.UI.noResults();
		}
	}

	// Send AJAX request
	GF.Ajax.request = function(data){
		$.post("<?php echo $this->config->item('base_url') .'find/ajaxRequest'; ?>", GF.Params.get(), GF.Ajax.process, "json");
		if(!GF.UI.scroll_load) {
			$("html, body").animate({ scrollTop: 0 }, 1000);
		}
	};

	//Set Event Listeners
	$('#find_goods').submit(function() {
		console.log($('#q').val());
		GF.Params.set('q', $('#q').val());
		GF.Ajax.request(); 
	});
	$('.parent_cat').click(function() {
		GF.Params.set('category_id',$(this).attr('id').substr(4));
		GF.Ajax.request();
	});
	$('li.subcategory').click(function() {
		var cat = $(this).attr('id').substr(4);
		GF.Params.set('category_id',cat);
		GF.Ajax.request();
	});
	$('#order_by').change(function(e) {
		GF.Params.set('order_by',$('#order_by option:selected').val());
		GF.Ajax.request();
	});


	//Infinite ScrollerZ
	$(window).scroll(function()
	{
	   if(!GF.UI.scroll_load && $(window).scrollTop() == $(document).height() - $(window).height())
	   {
		console.log('ahhhh');
	      $('#ajax_loader').show();
			GF.UI.scroll_load = true;
			GF.Ajax.request();
	   }
	});
});


</script>
