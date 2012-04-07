GF.Tags = (function(){

	var api = {};
	
	/**
	*	Get last item from CSV tag string
	*	@param string list
	*/
	var getLastTag = function(list){
		var indexOfLastComma = list.lastIndexOf(",");
		return $.trim(list.substr(indexOfLastComma+1));
	};
	
	/**
	*	Add new tag to CSV tag string
	*	@param string newTag
	*/
	var append = function(newTag, input){
	
		// Split up CSV string into an array
		var tagsArray = input.val().split(", ");
		
		// Update last item's array entry
		tagsArray[tagsArray.length-1] = $.trim(newTag);
		
		// Implode array into string using comma delimiters
		var newTags = tagsArray.join(", ")+", ";
		
		// Set input field's value to be newly formed string
		input.val(newTags);
	};
	
	/**
	*	Turn normal text input element into super-duper tag autocomplete input.
	*
	*	Usage:
	*		GF.Tags.initialize($("input#tags"));
	*
	*	@param jQuery ui
	*/
	api.initialize = function(input){
	
		input.autocomplete({
		
			source: function(request, response){
				// Extracts last tag from raw CSV string then sends POST request
				var query = getLastTag(request.term);
				$.post(GF.siteURL("ajax/tags"), { term: query }, function(data) { 
					response(data) 
				}, 'json');
			},
			select: function( event, ui ){
				// Append selected item to list, override default behavior
				append(ui.item.value, input);
				return false;
			},
			focus: function ( event, ui ){
				// When list item focused, don't change text field's value
				return false;
			}
		});
	};
	
	return api;
	
}());