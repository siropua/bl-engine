function showCreateForm(){
	// openRWindow('#settingForm', {loadPage: moduleAJAX + 'settingForm.php', title: 'Создание настройки'});
	
	$('#settingForm').modal('show').find('.modal-content').html(pageLoading).load(moduleAJAX + 'form', function(){
		//handleLoadedForm($(this).find('form'));
	});
	
}

function showEditForm(id){
	$('#settingForm').modal('show').find('.modal-content').html(pageLoading).load(moduleAJAX + 'form?id='+id, function(){
		//handleLoadedForm($(this).find('form'));
	}).modal();
	// openRWindow('#settingForm', {loadPage: moduleAJAX + 'settingForm.php?id='+id, title: 'Редактирование настройки'});
}

$(function(){
	$('textarea').focus(function(){
		$(this).attr('rows', '30');
	}).blur(function(){
		$(this).attr('rows', '2');
	});

	$('.btn-create').on('click', showCreateForm);
});