$(function(){
	$('#createArticleModal').on('shown.bs.modal', function () {
		$(this).find('input:visible:first').focus();
	});

	$('.btn-delete').on('click', deleteArticle);
});


function deleteArticle() {
	if(!confirm('Удалить статью? Её невозможно будет восстановить!')) return false;

	var $tr = $(this).closest('tr').addClass('deleting');
	var id = $tr.data('id');
	var $btn = $(this).attr('disabled', 'disabled');

	$.post(moduleJSON + 'article/delete', {id:id}, function(data){
		$btn.enable();
		if(typeof data.data != 'undefined'){
			$tr.fadeOut();
			$('b.posts-shown').html($('b.posts-shown').html() - 1);
			$('b.posts-total').html($('b.posts-total').html() - 1);
		}else{
			alert('Не удалось удалить статью!');
			console.log(data);
		}

		return false;
	});	

}