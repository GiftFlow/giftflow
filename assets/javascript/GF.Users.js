GF.Users = (function(){

	var api = {};
	
	/**
	*	/**
	*	Turn normal text input element into super-duper tag autocomplete input.
	*
	*	Usage:
	*		GF.Locations.initialize($("input#tags"));
	*
	*	@param jQuery ui
	*/
	api.initialize = function(input){
	
		input.autocomplete({
		
			source: function(request, response){
				// Extracts last tag from raw CSV string then sends POST request
				
				$.post(GF.siteURL("ajax/users"), { term: request.term }, function(data) { 
					response(data);
				}, 'json');
			},
			select: function( event, ui ){
				// Append selected item to list, override default behavior
				input.val(ui.item.value);
				return false;
			}
		});
	};
	
	return api;
	
}());
