<!-- <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Modal title</h4>
      </div> -->

<form action="{$MODULE_ROOT}" method="post" class="stdForm">
<div class="modal-body">
	<fieldset>
		<label>ID настройки</label>
		<input type="text" class="form-control" name="s[id]" size="20" maxlength="50" value="{$s.id|escape:html}"{if $s.id} disabled{/if}>
	</fieldset>
	<fieldset>
		<label>Заголовок настройки</label>
		<input type="text" class="form-control" name="s[name]" size="40" maxlength="150" value="{$s.name|escape:html}">
	</fieldset>
	<fieldset>
		<label>Тип данных</label>
		<select name="s[type]" class="form-control">
		{html_options options=$settingsTypes selected=$s.type}
		</select>
	</fieldset>
	
	{if $s.id}<input type="hidden" value="{$s.id}" name="s[editID]">{/if}
	
	</div>

	<div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
        <button type="submit" class="btn btn-primary">Сохранить</button>
    </div>

</form>
