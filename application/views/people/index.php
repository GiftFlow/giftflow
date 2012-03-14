<div class='two_panels find'>

	<?php echo $menu; ?>

	<div class='right_content'>
		
		<div class="top_filters clearfix">
    
    <div id='options'>
      <a class='type button' id='individual'>Individuals</a>
      <a class='type button' id='nonprofit'>Non-Profits</a>
      <a class='type button' id='business'>Businesses</a>
    </div> 

		</div><!-- close top_filters clearfix-->
  <div class='search_results'>
		<!-- Search Results -->

      <?php if(!empty($results)) { ?>
        <?php echo UI_Results::users(array(
          "results"=> $results,
          'mini' => FALSE
        ));} ?>

	</div>
		<!-- Loading Message -->
		<div class="results_loading" style="display: none;">
			<img src="<?php echo base_url();?>assets/images/loading.gif" alt="Loading" />
		</div>
		
		<!-- Loading Message -->
		<div class="results_empty" style="display: none;">
			<h3>No Results Found</h3>
			<p>Oops! No results were found that matched your query. To create a new need to let others know you're looking for something, <a href="<?php echo site_url("you/needs/add");?>">click here.</a>
		</div>
	
				
	</div><!-- eof div.right_content -->
</div>
<!-- eof div.two_panels -->

<script type='text/javascript'>
$(function(){

    //Namespace wrapper
    var GF = {
      UI: {},
      Data: {},
      Ajax: {}
    };

    //Write preloaded data
    


    GF.Params = (function() {

      var api = {};

      var data = {
        order_by: 'newest',
        type: 'individual',
        limit: 100,
        offset: 0
      };

      api.get = function() {
        return data;
      };

      api.set = function(key,value) {
        data[key] = value;
      };

      return api;
    }());

    GF.UI.loading = function() {
      $("ul.simplePagerNav").remove();
      $("ul.results_list").hide();
      $(".results_empty").hide();
      $(".results_loading").show();
    };
    //Hide loading message, show results
    GF.UI.loaded = function() {
      $(".results_loading").hide();
      $(".results_empty").hide();
      $(".results_list").show();
    };

    GF.UI.noResults = function () {
      $("ul.simplePagerNav").remove();
      $(".results_loading").hide();
      $("ul.results_list").hide();
      $(".results_empty").show();
    };

    //Add results to UI
    GF.UI.setResults = function(data) {
      $.each(data, function(key,val){
        $(".results_list").append($(val.html));
      });
     // paginate();
    };

    //Remove existing results
    GF.UI.clearResults = function() {
      $("ul.results_list").empty();
    };
    
    //Process Ajax data
    GF.Ajax.process = function(data){

      GF.UI.clearResults();
      
      if(data.results) {
        GF.UI.setResults(data.results);
      }else{
        return GF.UI.noResults();
      }

      GF.UI.loaded();
    };

    
    //Send Ajax request
    GF.Ajax.request = function(data){
      GF.UI.loading();
      $.post("<?php echo base_url().'people/browse'; ?>", GF.Params.get(), GF.Ajax.process, "json");
    };

    //jQuaery Listeners
    $("#order_by").change(function(e){
      GF.Params.set("order_by",$("#order_by option:selected").val());
      GF.Ajax.request();
    });

  
    $(".type").click(function() {
        GF.Params.set("type",$(this).attr('id'));
        GF.Ajax.request();
    });



	$(".follow").click(function(){ 
		var id = $(this).attr('rel');
		$.post("<?php echo site_url('people/follow/'); ?>/"+id);
		$(this).after("<div style='float: right;'><span style='float: left; margin-right: 8px; margin-top: 1px;' class='ui-icon ui-icon-check'><\/span><span style='font-size: .9em; color: #666;'>Following<\/span><\/div>");
		$(this).remove();
		return false;
	});

	$(".button").button();
  $('#options').buttonset();
	$(".follow").button( { icons: { primary: 'ui-icon-plusthick'}   } );
	$("#community_sources").buttonTabs();
  
	$("a#already_following").button({ icons: { primary: 'ui-icon-check' } } );
	$("a.button").button();
	
	function paginate(){
		$("ul.simplePagerNav").remove();
		$("ul.results_list").quickPager({ pageSize: 20});
	}
	
	
});
</script>
