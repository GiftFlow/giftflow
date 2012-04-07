<!-- Main Module -->
<form name='edit_good' method='post'>
<div class="gift_module" id="view_gift">
	<div class="top"></div>
	<div class="middle">
		<div id='edit_gift_form'>
			<h1>Editing <?php echo $G->title; ?></h1>			
			<div class='main'>
			
				<!-- Edit Info -->
				<div id='edit_info'>
					<p id='edit_title'>
						<label>Title</label>
						<input class="big-border" type="text" name="title" value='<?php echo $G->title; ?>' />
					</p>
					<p id='edit_description'>
						<label>Description</label>
						<textarea rows="5" class="big-border" name="description"><?php echo $G->description; ?></textarea>
					</p>
					<p id='edit_category'>
						<label>Category</label>
						<select name='category' id='category' title='Category' class="required">
							<option></option>
							<?php foreach($categories as $key=>$val){ ?>
								<option <?php if($val->id == $G->category->id) { echo "selected='yes'";} ?>value="<?php echo $val->id;?>">
									<?php echo $val->name; ?>
								</option>
							<?php } ?>
						</select>
					</p>
				</div>

				<!-- Edit Location -->
				<div id='edit_location'>
					<label>Location</label>
					<input type='text' class="big-border" id="location" name="location" value="<?php echo $G->location->address; ?>" />
					
					<?php if(!empty($U->location->all)) { ?>
						
						<!-- Your Locations -->
						<div id='your_locations' style='display: none;'>
							<ul>
							<?php for($i=0; ($i<count($U->location->all) && $i<5); $i++){?>
								<?php $val = $U->location->all[$i]; ?>
							
								<li class='ui-menu-item'>
									<a href='#' rel="<?php echo $val->address; ?>">
										<?php echo $val->title; ?><br />
										<span class='location_details' >
											<?php echo $val->address;?>
										</span>
									</a>
								</li>
							<?php  } ?>
							</ul>
						</div>
					<?php } ?>
				</div>
			</div>
						
			<!-- Buttons -->
			<div class='clear' style='display: block; padding: 20px 0px;'>
				<input type='hidden' value='edit' name='method' />
				<input type='hidden' value='<?php echo $G->id; ?>' name='good_id' />
				<input type='submit' value='' class='blue' /> 
				
				<a id="cancel_edit" class='button secondary' href="<?php echo site_url('gifts/'.$G->id); ?>">Cancel</a>
				
				<a id="delete_gift" class='button css_right' href="<?php echo site_url('gifts/'.$G->id.'/disable'); ?>">Delete</a>
			
			</div>
			<div class='clear'></div>
			</form>
		</div>		
	</div>
	
	<div class='bottom'></div>
</div>

<!-- Tag Editor -->
<div class="sidebar">		
	<div class="top">
		<h2>Tags</h2>
	</div>
	<div class="center">
	
		<!-- Rich Tag Editor -->
		<p id='tag_editor' style='display: none;'>

			<input type='text' name='tag_editor_input' class="big-border" id='tag_editor_input' value='' />
			
			<input type='hidden' id='tag_list' name='tags' value='<?php echo implode($G->tags,","); ?>' />
			
			<a href="#" id='add_this_tag' class='button'>
				<span class='ui-icon ui-icon-circle-plus'></span>
			</a>
			
			<ul class='tag_cloud'>
			</ul>
		</p>
		
		<!-- Text-Field Tag Editor (if no javascript) -->
		<p id='old_tag_editor'>
			<label>Tags</label>
			<input type='text' name='tags' id='old_tags' value='<?php echo implode($G->tags,","); ?>' />
		</p>
	
	</div>
	<div class="bottom"></div>
</div>
</form>


<script type='text/javascript'>
var tags = [];
$(function(){
	
	// Style buttons
	$('.button').button();
	$(".buttonset").buttonset();
	
	// Confirm delete
	$(".edit_dialog #delete").click(function(){ 
		var go = confirm('Are you sure you want to delete this photo?');
		if(go){
			$.post('<?php echo site_url('ajax/delete_photo'); ?>', { 'id': $(this).parent().parent().attr('id') }, delete_photo, 'json' );
		}
	});	
	
	/*
	* Edit Tags
	*/ 
	
	editTags();
	
	function editTags()
	{
		var selected = false;
		$("#old_tag_editor").hide();
		$("#old_tags").attr("disabled","disabled");
		$("#tag_editor").show();
		var existing = $("input#tag_list").val().split(",");
		$.each(existing, function(key, val) { 
			if(val!="") addTag(val);
		});
		$("input#tag_editor_input").autocomplete({
			source: function(request, response)
			{
				$.post("<?php echo site_url("ajax/tags"); ?>", { term: request.term }, function(data) { response(data) }, 'json');
			},
			select: function( event, ui )
			{
				addTag(ui.item.value);
				selected = true;
			},
			change: function( event, ui )
			{
				if(selected){
					$(this).val('');
				}
				selected = false;
			},
			focus: function ( event, ui )
			{
				return false;
			}
		});
		$("input#tag_editor_input").keydown(function(event){if (event.keyCode == 13){ addTag($(this).val()); return false; }});
		$("ul.tag_cloud li a.delete_tag").live('click', function(){ 
		 	var tag = $.trim($(this).parent().text());
		 	$.post("<?php echo site_url("ajax/remove_tag"); ?>", {old_tag: tag, good_id: <?php echo $G->id;?> }, function(data) {console.log(data);}, 'json');
		 	var key = tags.lastIndexOf(tag);
			tags.splice(key, 1);
		 	$(this).parent().slideUp('fast');
		 	$("#tag_list").val(tags);
			return false;
		});
		$("a#add_this_tag").click(function(){
			var new_tag = $("input#tag_editor_input").val();
			addTag(new_tag);
			return false;
		});
	}
	
	function addTag( tag )
	{
		if(tag==""){
			return false;
		}
		tags.push(tag);	
		$("#tag_editor_input").val('');
		$("#tag_list").val(tags);
		$("ul.tag_cloud").append("<li><a class='tag'>"+tag+"<\/a> <a href='#' class='delete_tag'><\/a><\/li>");
		$("ul.tag_cloud li:last a.delete_tag").button({icons:{primary: 'ui-icon-trash'}, text: false});
		$.post("<?php echo site_url("ajax/add_tag"); ?>", { new_tag: tag, good_id: <?php echo $G->id;?> }, function(data) {console.log(data);}, 'json');

	}
	
	/*
	*  Edit Location
	*/
	
	$("#location").focus(function(){
		$("#your_locations").slideDown(); 
	});
	$("#your_locations_link").click(function(){
		$("#your_locations").show();
		$(this).hide();
		return false;
	});
	$("#your_locations li a").live('click', function(){
		$("#location").val($(this).attr('rel'));
		$("#your_locations").hide();
		return false;
	});
	
	/*
	* Ignore request
	*/ 
	
	$("input:submit").button();
	
	$("#delete_gift").click(function(){
		var answer = confirm('Are you sure you want to delete this gift? Doing so will cancel all transactions involving this gift');
		if(answer) return true;
		else return false;
	});
	
});
</script>