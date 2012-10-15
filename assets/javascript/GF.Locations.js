GF.Locations = (function(){

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
	var append = function(newTag, input)
	{
		input.val(newTag);
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
				$.post(GF.siteURL("ajax/locations"), { term: query }, function(data) { 
					response(data);
				}, 'json');
			},
			select: function( event, ui ){
				// Append selected item to list, override default behavior
				$('#location').val(ui.item.value);


				//working with Find index.php scripts here
				if(GF.Ajax) {
					GF.Params.set('location', ui.item.value);
					GF.UI.setLocation(ui.item.value);
					GF.Ajax.request();
				}
				return false;
			}
		});
	};
	
	return api;
	
}());
