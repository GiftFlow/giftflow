
<div class='row' id='find_page'>

	<div class='span2 chunk' id='find_sidebar'>
				<p>Select what you are looking for: </p>
				<div class='btn-group find_type_buttons'>
					<button type='button' class='btn type_control' id='type_gift' value ='gift'>Gifts</button>
					<button type='button' class='btn type_control' id='type_need' value ='need'>Needs</button>
					<button type='button' class='btn type_control' id='type_people' value ='people'>Users</button>
				</div>
				<div id='people_menu' class='find_people find_menu' style='display:none;'>
					<span class='filter_title'>Choose a member type.</span>
					<div class='btn-group'>
						<button class='ptype btn <?php if($args["profile_type"] == "people") {echo "disabled";}?> ' value='individual'>People</button>
						<button class='ptype btn <?php if($args["profile_type"] == "nonprofit") {echo "disabled";}?> ' value='nonprofit'>Nonprofits</button>
						<button class='ptype btn <?php if($args["profile_type"] == "business") {echo "disabled";}?> ' value='business'>Businesses</button>
					</div>
				</div>
				<div id='goods_menu' class='find_gift find_need find_menu' style='display:none;'>
					<p class='nicebigtext'>
						Categories
					</p>
					
					<ul id="categories">
						<li>
							<a href="<?php echo site_url("find/{$type}s");?>">
								All Categories
							</a>
						</li>
						
					<?php foreach($categories as $key=>$val){ ?>
						<li>
							<a id="category_<?php echo $val->id;?>" href="<?php echo site_url("find/{$type}s")."/?category_id=".$val->id;?>" rel="<?php echo $val->id;?>">
								<?php echo $val->name; ?>
							</a>
						</li>
					<?php } ?>
					
					</ul>
			</div>
	</div>

	<div class='span9'>
	<!-- Search Form Module -->
	<div class='row' id='findNav'>
		<div class='findBar span9 chunk goodsBar'>

		<span class='navForm clearfix'>

		
			<form name='changeLocation' class='find_form' id="editLocation" method="post" action="">
				<div class='input-append'>
					<input id ='find_location' size='16' class='input-medium' type="text"  value="<?php if(!empty($args['location'])) { echo $args['location']->address; } ?>" name="location" />
					<button id='changeLocation' type='submit' class='btn btn-medium'><i class= 'icon-refresh'></i> Change</button>
				</div>
			</form>

			<form name='find_goods' class='find_form'id="find_goods" action="" method='post'>
			<div class='input-append'>
				<input type='text' size='16' placeholder="<?php if($type == 'people') { echo 'Name'; } else { echo 'Keyword'; } ?>" class='input-medium' id="q" name='q' value='<?php echo $args["q"];?>' />
				<button class='btn btn-medium' type='submit' id="find"><i class='icon-search'></i> Find</button>
			</div>
		</form>
	

			<select name="radius" id="radius" class='find_form span2'>
				<option value="10">10 miles</option>
				<option value="100" selected>100 miles</option>
				<option value="1000">1000 miles</option>
				<option value="100000">Global</option>
			</select>

				</span>
		</div>
	</div><!-- close row -->
	<div class='row chunk'>

		<span id="order_by_label">
				Sort By
			</span>
			<select name="order_by" id="order_by" class='input-small'>
			<option value="newest"<?php if($args['order_by'] == 'newest') { echo "selected"; }?>>Newest</option>
			<option value="nearby" <?php if($args['order_by'] =='location_distance') { echo "selected"; } ?>>Nearby</option>
			</select>
	</span>
		<!-- Search Results -->
		<ul class='results_list'>
		<?php if( $display == 'results' ) { ?>
			<?php foreach($results as $obj) { ?>
				<?php echo $obj->html; ?>
			<?php } ?>
		<?php } ?>
		</ul>
		
		<!-- Loading Message -->
		<div class="results_loading" style="display: none;">
			<img src="<?php echo base_url();?>assets/images/loading.gif" alt="Loading" />
		</div>
		
		<!-- Loading Message -->
		<div class="results_empty" style="display: none;">
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
	<!-- eof.right_content -->

</div>
</div>
<!-- close two panels -->

<script type="text/javascript">

$(function(){

	//preload category and types
	var preload_type = "<?php echo $args['type']; ?>";
	var preload_category = "<?php echo $args['category_id']; ?>";
	$('#category_'+preload_category).css('color','#6CB6E2');
	console.log(preload_category);

	$('.find_'+preload_type).show();
	$('#type_'+preload_type).addClass('disabled');

	GF.Locations.initialize($('input#find_location'));

	$('#find_location').click( function () {
		$(this).val('');
	});

	paginate();

	// GF Namespace wrapper
	GF.UI = {};
	GF.Data = {};
	GF.Ajax = {};
	

	function paginate(){
		$("ul.simplePagerNav").remove();
		$("ul.results_list").quickPager({ pageSize: 10});
	}
	
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
			limit: 100,
			offset: 0,
			location: "<?php if(!empty($args['location'])) { echo $args['location']->address; } ?>",
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
			console.log('HAPPENING');
		};
		
		return api;
		
	}());

	// Hide results, show loading icon
	GF.UI.loading = function(){
		$("ul.simplePagerNav").remove();
		$("ul.results_list").hide();
		$(".results_empty").hide();
		$(".results_loading").show();
	};
	
	// Hide loading message, show results
	GF.UI.loaded = function(){
		$(".results_loading").hide();
		$(".results_empty").hide();
		$("ul.results_list").show();
	};
	
	GF.UI.noResults = function(){
		$("ul.simplePagerNav").remove();
		$(".results_loading").hide();
		$("ul.results_list").hide();
		$(".results_empty").show();
	};
	
	// Add results to UI
	GF.UI.setResults = function(data){
		$.each(data, function(key, val){
			$(".results_list").append($(val.html));
		});

		paginate();
	};
	
	// Remove existing results
	GF.UI.clearResults = function(){
		$("ul.results_list").empty();
	};
	
	// Set UI Location String
	GF.UI.setLocation = function(locationString){
		$("#find_location").val(locationString);
	};
	
	
	// Process AJAX Data
	GF.Ajax.process = function(data){
		GF.UI.clearResults();
		if(data.center) {
			GF.UI.setLocation(data.center.address);
		}
		if(data.results.length > 0){
			GF.UI.setResults(data.results);
		} else {
			return GF.UI.noResults();
		}
		GF.UI.loaded();
	}
	
	// Send AJAX request
	GF.Ajax.request = function(data){
		$('#args').text(JSON.stringify(GF.Params.get()));
		GF.UI.loading();
		//GF.Params.set("type", "<?php echo $type; ?>");
		$.post("<?php echo $this->config->item('base_url') .'find/ajaxRequest'; ?>", GF.Params.get(), GF.Ajax.process, "json");
	};
	
	//submits search with user submitted location string
	GF.Ajax.processNewLocation = function(data){
		locate = $('#location').val();
		GF.Params.set('location',locate);
		GF.Ajax.request();
	};
	
	// jQuery Listeners
	$("#order_by").change(function(e){
		GF.Params.set("order_by",$("#order_by option:selected").val());
		GF.Ajax.request();
	});

	//changes search radius, redoes search
	$('#radius').change(function(e) {
		GF.Params.set('radius',$('#radius option:selected').val());
		GF.Ajax.request();
	});

	//click on a category - clears keyword field
	$("ul#categories li a").click(function(e){
		$("ul#categories li a").css('color','#999');
		GF.Params.set("category_id",$(this).attr("rel"));
		GF.Params.set("q",'');
		$('#q').val('');
		GF.Ajax.request();		
		$(this).css('color','#6CB6E2');
		return false;
	});
	
	//submit find keyword
	$("#find_goods").submit(function(e){
		GF.Params.set("q",$('#q').val());
		GF.Ajax.request();
		return false;
	});	

	//submit new location	
	$("#editLocation").submit(function(e) {
		GF.Ajax.processNewLocation();
		return false;
	});

	//changing profile types - user_search only
	$('.ptype').click(function(e) {
		$('.ptype').removeClass('disabled');
		$(this).addClass('disabled');
		GF.Params.set('profile_type',$(this).attr('value'));
		GF.Ajax.request();
		return false;
	});


	//Code for switching between search types

	$('.type_control').click(function() {
		$('.type_control').removeClass('disabled');
		$(this).addClass('disabled');

		var type = $(this).val();
		var old_type = GF.Params.get('type');

		if(old_type == 'people' || type == 'people') {
			GF.Params.changetype();
		};

		var menu = 'find_'+type
		$('.find_menu').hide();
		$('.'+menu).show();

		GF.Params.set('type', type);
		GF.Ajax.request();

	});

});
		
</script>
		
