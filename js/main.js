$(function(){	
	 if($('input.focused:first,textarea.focused:first').length == 1){
		$('input.focused:first,textarea.focused:first').focus();
	}
	
	 $('form.stdForm').submit(function(){
		if(!stdFormCheck(this)) return false;	
	 });
	 
	$('button.closeButton').click(function(){
		$.modal.close();
	});
});


function stdFormCheck(form){
	$(form).find('.wrong').removeClass('wrong');
	$(form).find('.needed').each(function(){
		if($(this).val() == '') $(this).addClass('wrong');
	});
	if($(form).find('.wrong').length>0) {
		$(form).find('.wrong:first').focus();
		return false;
	}
	
	var b = $(form).find('button.saveButton,button.okButton').attr('disabled', 'disabled').addClass('disabled');
	if($(b).val()) $(b).html($(b).val());

	return true;
}


var modalHelper = {
	showNew: function(){
		$('#simplemodal-container').find('input:visible:first').focus();
		$('#simplemodal-container').find('button.closeButton').one('click', function(){
			$.modal.close();
		});
	},
	show: function(){
		$('#simplemodal-container').find('input:visible:first').focus();
		$('#simplemodal-container').css('height', 'auto');
	}
}


function checkLoginForm(form, ajax){
	if(typeof(ajax) == 'undefined' || typeof(ajax) == null) ajax = false;
	if(!stdFormCheck(form)) return false;
	if(typeof(LOGIN_PREG) == 'undefined') return true;
	
	var login = $(form).find('input[name=login]').val();
	
	if(!login.match(LOGIN_PREG)){
		$(form).find('input[name=login]').addClass('wrong').focus();
		return false;
	}
	
	$(form).find('button').disable();
	
	if(ajax){
		$(form).find('.loginError').html('');
		$.post(AJAXPath + 'login.php', $(form).serialize(), function(result){
			if(result == 'OK'){
				document.location = document.location;
			}else{
				$(form).find('.loginError').html(result);
				$(form).find('button').enable();
			}
		});
		return false;
	}
	return true;
}

// usage: log('inside coolFunc', this, arguments);
// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function(){
  log.history = log.history || [];   // store logs to an array for reference
  log.history.push(arguments);
  if(this.console) console.log( Array.prototype.slice.call(arguments) );
};

