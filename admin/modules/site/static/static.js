$(function(){
	$('#createTPL form').on('submit', function(){

		console.log($('input[name=newURL]', this).val());
		if(!$('input[name=newURL]', this).val().match(/^[a-z][a-z0-9-]*$/i)){
			alert('Неправильно указан URL страницы!');
			return false;
		}

		return true;
	});


	$('button.delete-button').on('click', function(){
		if(!confirm('Удалить эту страницу без возможности восстановления?')){
			return false;
		}

		$(this).disable();
		var page = $(this).data('page');

		$.post(moduleAJAX+'delete', {page: page}, function(data){
			if(data == 'OK'){
				document.location = '?ok';
			}else{
				alert('Не удалось удалить страницу! Возможно не хватает прав');
				console.log(data);
			}
		});
	});
})