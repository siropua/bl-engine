$(function(){
	$('.create-item').on('click', createItem);
	$('.edit-item').on('click', editItem);
	$('.delete-item').on('click', deleteItem);
	$('#catalogItemModal').on('shown.bs.modal', function () {
		$(this).find('input:visible:first').focus();
	})
});


function createItem() {
	var $parentTR = $(this).closest('tr.catalog-item');
	var parentID = 0;
	if($parentTR && $parentTR.length)
	{
		parentID = $parentTR.data('id');
	}
	var modal = clearModal();
	$('input[name=parent_id]', modal).val(parentID);
	modal.modal('show');
}

function editItem() {
	var $parentTR = $(this).closest('tr.catalog-item');
	if(!$parentTR) return false;
	var modal = clearModal();
	$('input[name=id]', modal).val($parentTR.data('id'));
	$('input[name=title]', modal).val($parentTR.find('.name-descr strong').html());
	$('textarea[name=description]', modal).val($parentTR.find('.name-descr small').html());
	$('input[name=keywords]', modal).val($parentTR.find('.keywords').html());
	$('input[name=url]', modal).val($parentTR.find('.url span').html());

	modal.modal('show');
}

function deleteItem() {
	var id = $(this).closest('tr.catalog-item').data('id');
	if(!id) return false;
	if(!confirm('Удалить раздел?\nВНИМАНИЕ! Это невозможно будет отменить!\nВсе подразделы будут также удалены, а все статьи из раздела и подразделов останутся, но переместятся в корневую папку!')) return false;

	$form = $('<form action="" method="post"><input type="hidden" name="delete_id" value="'+id+'"></form>');
	$form.submit();
}

function clearModal() {
	var modal = $('#catalogItemModal');
	$('input,textarea', modal).val('');
	return modal;
}