<div class="two_panels find">

	<!-- Sidebar Menu -->
	<?php echo $menu;?>
	<?php if($type =='people') { echo $people_menu; } else { echo $category_menu; } ?>
	   </ul>	<!-- closing ul opened in $menu -->

	<div class='right_content'>
		<!-- Search Form Module -->

		<div class='row'>
			<div class='span3'>
				<span class="filter_title">
				  Search
				</span>

				<form name='find_goods' id="find_goods" action="" method='post'>
					<div class='input-append'>
						<input type='text' class='find_form span2' id="q" name='q' value='<?php echo $args["q"];?>' />
						<button class='btn' type='submit' id="find"><i class='icon-search'></i> Find</button>
					</div>
				</form>
			</div>
			<div class='span4'>
				<span class='filter_title'>
					 Location
				</span>

				<form name='changeLocation' id="editLocation" method="post" action="">
					<div class='input-append'>
						<input id ='location' class='find_form span2' type="text"  value="<?php if(!empty($args['location'])) { echo $args['location']->address; } ?>" name="location" />
						<button id='changeLocation' class='btn'><i class= 'icon-refresh'></i> Change</button>
					</div>
				</form>

			</div>
			<div class='span2'>
				<a class='btn btn-large btn-success' id='add_good_button' href="<?php echo site_url('you/add_good/?type='.$args['type']);?>"><i class='icon-plus icon-white'></i> Add <?php echo ucfirst($args['type']); ?></a>
			</div>
		</div><!-- close row -->

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
			<p>Oops! No results were found that matched your query. To create a new need to let others know you're looking for something, <a href="<?php echo site_url("you/needs/add");?>">click here.</a>
		</div>
	
	</div>
	<!-- eof.right_content -->

</div>
<!-- close two panels -->

<script type="text/javascript">

$(function(){


	// GF Namespace wrapper
	var GF = {
		UI: {},
		Data: {},
		Ajax: {},
	};
	
	function paginate(){
		$("ul.simplePagerNav").remove();
		$("ul.results_list").quickPager({ pageSize: 10});
	}
	
	// Write pre-loaded data
	GF.Data = <?php echo $results_json; ?>;
	
	GF.Params = (function(){
	
		var api = {};
		
		var data = {
			order_by: "newest",
			category_id: "<?php echo $args['category_id'];?>",
			limit: 100,
		  offset: 0,
		  location: "<?php if(!empty($args['location'])) { echo $args['location']->address; } ?>",
			radius: 100,
		};
		
		api.get = function(){
			console.log(data);
			return data;
		};
		
		api.set = function(key,value){
			data[key] = value;
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
		$("#location").val(locationString);
	};
	
	
	// Process AJAX Data
	GF.Ajax.process = function(data){
		GF.UI.clearResults();

		if(data.center) {
			GF.UI.setLocation(data.center.address);
		}


		if(data.results){
			GF.UI.setResults(data.results);
		} else {
			return GF.UI.noResults();
		}
		
		GF.UI.loaded();
	}
	
	// Send AJAX request
	GF.Ajax.request = function(data){
		GF.UI.loading();
		$.post("<?php echo current_url();?>", GF.Params.get(), GF.Ajax.process, "json");
	};
	
	GF.Ajax.processNewLocation = function(data){
		locate = $('#location').val();
		console.log(locate);
		GF.Params.set('location',locate);
		GF.Ajax.request();
	};
	
	// jQuery Listeners
	$("#order_by").change(function(e){
		GF.Params.set("order_by",$("#order_by option:selected").val());
		GF.Ajax.request();
	});
	$('#radius').change(function(e) {
		GF.Params.set('radius',$('#radius option:selected').val());
		GF.Ajax.request();
	});
	
	$("ul#categories li a").click(function(e){
		$("ul#categories li a").css('color','#999');
		GF.Params.set("category_id",$(this).attr("rel"));
		GF.Params.set("q",'');
		$('#q').val('');
		GF.Ajax.request();		
		$(this).css('color','#6CB6E2');
		return false;
	});
	
	$("#find_goods").submit(function(e){
		GF.Params.set("q",$('#q').val());
		GF.Ajax.request();
		return false;
	});	
	
	$("#editLocation").submit(function(e) {
		GF.Ajax.processNewLocation();
		return false;
	});
	 
	$('#find_link').click(function() {
		$(this).hide();
		$("ul.results_list").empty();
		$(".results_empty").hide();
		$('#search_bar').show();
	});
	
});
		
</script>
		
