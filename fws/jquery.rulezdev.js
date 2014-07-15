jQuery.fn.disable = function(options){
	$(this).attr('disabled', 'disabled');
}

jQuery.fn.enable = function(options){
	$(this).removeAttr('disabled');
}