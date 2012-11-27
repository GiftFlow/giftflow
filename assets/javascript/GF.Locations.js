GF.Locations = (function(){

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
				
				$.post(GF.siteURL("ajax/locations"), { term: request.term }, function(data) { 
					response(data);
				}, 'json');
			},
			select: function( event, ui ){
				// Append selected item to list, override default behavior
				input.val(ui.item.value);


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
