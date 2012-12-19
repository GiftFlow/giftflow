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
	<div class='row-fluid chunk' id='masonry_nav' data-spy='affix' data-offset-top='140'>
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
			<div class='span4 search_elements'>
				<form name='find_goods' class='find_form'id="find_goods" action="#" method='get'>
					<div class='input-append'>
						<input type='text' size='16' placeholder="<?php if($type == 'people') { echo 'Name'; } else { echo 'Keyword'; } ?>" class='masonry_input' id="q" name='q' value='<?php echo $args["q"];?>' />
						<button class='btn btn-large' type='submit' id="find"><i class='icon-search'></i> Find</button>
					</div>
				</form>
			</div>
			<div class='span2 search_elements'>
				<select name="sort" id="order_by" class='input-small'>
					<option value="newest">Newest</option>
					<option value="nearby">Nearby</option>
				</select>
			</div>
			<div class='btn-group span2'>
				<a class='btn btn-large' href="<?php echo site_url('you/add_good/'.$type);?>"><i class='icon icon-plus'></i> Post <?php echo ucfirst($type); ?></a>
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
			<h3 class='need'>Loading...</h3>
			<img src="<?php echo site_url('assets/images/285needloader.gif');?>"/>
		<?php } else { ?>
			<h3>Loading...</h3>
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
	$('#masonry_nav').css('left', $('.brick_wall').offset().left);



	// GF Namespace wrapper
	GF.UI = {};
	GF.Data = {};
	GF.Ajax = {};

	//Infinite scroll flag

	 GF.UI.scroll_load = false;
	GF.UI.more_available = false;
	
	// Write pre-loaded data
	if(GF.Data.total_results == <?php echo $args['limit']; ?>) {
		GF.UI.more_available = true;
	}
	
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

		//check if more results might be available
		var params = GF.Params.get();
		if(data.results.length == params.limit) {
			GF.UI.more_available = true;
		} else {
			GF.UI.more_available = false;
		}

		if(!GF.UI.scroll_load) {
			GF.UI.clearResults();
		}
		if(data.results.length > 0){
			GF.UI.setResults(data.results);
		} else {
			console.log('nomore!');
			GF.UI.noResults();
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
	   if(GF.UI.more_available && !GF.UI.scroll_load && $(window).scrollTop() == $(document).height() - $(window).height())
	   {
		console.log('ahhhh');
	      $('#ajax_loader').show();
			GF.UI.scroll_load = true;
			
			var params = GF.Params.get();
			var new_offset = params.offset + params.limit;

			console.log(new_offset);
			
			GF.Params.set('offset', new_offset);	
			
			GF.Ajax.request();
	   }
	});
});


</script>
