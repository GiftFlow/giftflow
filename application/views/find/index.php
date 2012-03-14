<div class="two_panels find">

	<!-- Sidebar Menu -->
	<?php echo $menu;?>

	<div class='right_content'>
		<!-- Search Form Module -->
    <span class='location_header'>
       Location:  <span id="location" class="filter_title" title="Click to Edit Your Location">
          	<?php if(!empty($args["location"]->address)){ echo $args["location"]->address; } ?>
        </span>
        <form id="editLocation" method="post" action="<?php echo site_url("ajax/relocate");?>" style="display: none;">
          <input id ='locate_input' type="text" value="" name="location" />
        </form>
    </span>
    <a class='button' href="<?php echo site_url('you/add_good/?type='.$args['type']);?>" id='add_button'>Add <?php echo ucfirst($args['type']); ?></a>

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
      location:''
		};
		
		api.get = function(){
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
		$("#location").text(locationString);
	};
	
	
	// Process AJAX Data
	GF.Ajax.process = function(data){
	
		GF.UI.clearResults();
		GF.UI.setLocation(data.center.address);


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
    $('#location').text(data.address);
    locate = $('#editLocation input:text').val();
    GF.Params.set('location',locate);
		$("#editLocation input:text").val("").blur();
		GF.Ajax.request();
	};
	
	// jQuery Listeners
	$("#order_by").change(function(e){
		GF.Params.set("order_by",$("#order_by option:selected").val());
		GF.Ajax.request();
	});
	
	$("ul#categories li a").click(function(e){
		$('#current_category').text('Category: '+(this.text));
    $("ul#categories li a").css('color','#AAAAAA');
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

	$("#location").tipTip({
		defaultPosition: "right",
		delay: 0,
    fadein: 0,
    keepAlive:'true'
	});
	
	$("#location").click(function(){
		$(this).hide();
		$("#editLocation").css('display','inline');
		$("#editLocation input:text").focus();
	});
	
  $("#editLocation").ajaxForm({
		success: GF.Ajax.processNewLocation
	});
	
	$("#editLocation input:text").blur(function(){
		$("#editLocation").hide();
		$("#location").show();
	});
	
	$('#find_link').click(function() {
		$(this).hide();
		$("ul.results_list").empty();
		$(".results_empty").hide();
		$('#search_bar').show();
	});
	
	
		
		
	$('.button').button();
});
		
</script>
		
