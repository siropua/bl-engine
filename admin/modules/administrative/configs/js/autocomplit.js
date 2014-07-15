
function poisk(name_street, pole){
	var availableTags = [];

		$.getJSON(moduleAJAX + "autofill.php", {queryString: ""+name_street+""}, function(data){
			if(data != null && data.length >0) {

				availableTags =data;
				$( "#" + pole).autocomplete({
					source: availableTags
				});
			}
		});

}


	

	
