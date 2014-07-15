<div class="row"><button class="btn btn-primary btn-lg add-item" type="button">Добавить</button></div>

<table class="table">
	<thead>
		<tr>
			<th>ID</th>
			{foreach from=$tableStruct item=s}
			{if $s.Field != 'id' and $s.Field != 'ordr'}
			<th>{$s.Comment}</th>
			{/if}
			{/foreach}
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$items item=i}
		<tr data-id="{$i.id}">
			<td>{$i.id}</td>
			{foreach from=$tableStruct item=s}
			{if $s.Field != 'id' and $s.Field != 'ordr'}
			<td>{$i[$s.Field]}</td>
			{/if}
			{/foreach}		
			<td><div class="button-group"><button class="btn btn-default btn-xs edit-item" type="button" title="Редактировать"><i class="fa fa-edit"></i></button>
			<button class="btn btn-default btn-xs delete-item" type="button" title="Удалить"><i class="fa fa-trash-o"></i></button></div></td>
		</tr>
		{/foreach}
	</tbody>
</table>


<!-- Modal -->
<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Добавление</h4>
      </div>
      <form action="{$SELF}" class="form" method="post">
      <div class="modal-body">
			<input type="hidden" name="item[id]" id="fld-id" value="">
			{foreach from=$tableStruct item=s}
			{if $s.Field != 'id' and $s.Field != 'ordr'}
			<div class="form-group">
				<label for="fld-{$s.Field}">{$s.Comment}</label>
				{if $s.Type == 'text' || $s.Type == 'longtext'}
				<textarea name="item[{$s.Field}]" id="fld-{$s.Field}" cols="30" rows="10" class="form-control item-field" data-name="{$s.Field}"></textarea>
				{else}
				<input type="text" name="item[{$s.Field}]" id="fld-{$s.Field}" class="form-control item-field" data-name="{$s.Field}">
				{/if}
			</div>
			{/if}
			{/foreach}
		
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Отменить</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
      </div>
      </form>
    </div>
  </div>
</div>


<script>
	$(function(){
		var $m = $('#itemModal');

		$('.add-item').on('click', function  () {
			$('input,textarea', $m).val('');
			$('h4', $m).html('Добавление');
			$m.modal('show');
		});

		$m.on('shown.bs.modal', function  () {
			$('input:visible:first', $m).focus();
		});

		$('form', $m).on('submit', function  () {
			$('button', $m).disable();
		});

		$('.delete-item').on('click', function  () {
			if(!confirm('Действительно удалить?')) return false;
			$row = $(this).attr('disabled', 'disabled').closest('tr');
			var id = $row.data('id');

			$.post(moduleJSON + 'delete', { id: id }, function  (data) {
				if(data.data == 'OK'){
					$row.fadeOut();
				}else{
					alert('Не удалось удалить! :(');
					$('button', $row).enable();
				}
			})
		});

		$('.edit-item').on('click', function  () {
			$row = $(this).attr('disabled', 'disabled').closest('tr');
			var id = $row.data('id');
			$m.modal('show');
			$('input,textarea,button', $m).disable();

			$.get(moduleJSON + 'get?id='+id, function  (data) {
				$('button', $row).enable();
				if(!data.data){
					alert('Ошибка получения данных!');
					return;
				}
				$('#fld-id').val(id);
				$('.item-field').each(function(){
					$(this).val(data.data[$(this).data('name')]);
				});
				$('input,textarea,button', $m).enable();
			})
		});
	});

</script>