$(function(){
	$('#amnesiaForm input:first').focus();
});

function amnesia(f){
	var email = $(f).find('input[name=a_email]').val();
	/*if(!email.match(/^[a-z0-9._\-]+@[a-z0-9._\-]+\.[a-z]{2,4}$/)) {
		alert('Ошибка в почтовом адресе');
		return false;
	} */
	$(f).find('button').attr('disabled', 'disabled');
	$('#errMsg').hide();
	$.ajax({
		url: AJAXPath + 'amnesia.php', type: 'post', dataType: 'text',
		data: $(f).serialize(),
		complete: function(){
			$(f).find('button').attr('disabled', '');
		},
		error: function(){
			alert('Произошла непредвиденная ошибка. Попробуйте перегрузить страницу и воспользоваться формой заново.');
		},
		success: function(data){
			if(data == 'NOUSER'){
				$('#errMsg').html('Пользователь с таким логином не может восстановить пароль! Обратитесь к администрации.').addClass('warning').fadeIn('fast');
				return;
			}else
				$(f).html(data);
		}
	});
	return false;
}

function checkAmnesia(f){
	e=f.elements;
	if(e['new_pass'].value.length<6){
		alert('Пароль должен быть длинной не менее 6 символов');
		e['new_pass'].focus();
		return false;
	}
	if(e['new_pass'].value != e['new_pass2'].value){
		alert('Пароли не совпадают');
		e['new_pass'].focus();
		return false;
	}
	
	return true;
}