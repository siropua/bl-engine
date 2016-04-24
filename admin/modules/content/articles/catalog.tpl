<button class="btn btn-primary create-item"><i class="fa fa-plus"></i> создать корневой элемент</button>

<table class="table">
	<thead>
		<tr>
			<th>Раздел</th>
			<th>URL</th>
			<th>Ключевые слова</th>
			<th width="50">Статей</th>
			<th width="150">&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$catalogItems item=i}
			{include file='catalog-item.tpl' item=$i}
		{/foreach}
	</tbody>
</table>



<div class="modal fade" id="catalogItemModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Элемент каталога</h4>
      </div>
      <form action="{$SELF}" class="form" method="post">
      	<input type="hidden" name="parent_id" value="">
      	<input type="hidden" name="id" value="">
        <div class="modal-body">

          <div class="form-group form-group-title">
            <label>Название</label>
            <input type="text" class="form-control" name="title" required="required">
          </div>
          <div class="form-group form-group-url">
            <label>URL <span></span></label>
            <input type="text" class="form-control" name="url">
          </div>

          <div class="form-group">
            <label>Ключевые слова</label>
            <input type="text" class="form-control" name="keywords">
          </div>

          <div class="form-group">
            <label>Краткое описание</label>
            <textarea type="text" class="form-control" name="description" rows="2"></textarea>
          </div>


        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
          <button type="submit" class="btn btn-primary">Создать</button>
        </div>
      </form>
    </div>
  </div>
</div>