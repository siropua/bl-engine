<form action="{$SELF}" method="post" class="stdForm">
	<textarea name="content" cols="80" rows="20" style="width: 97%;">{$content}</textarea>
	{if $writable}<div class="buttons"><button type="submit" class="saveButton"><img src="{$ADMIN_IMG}save.gif"> Сохранить</button></div>{/if}
</form>

<div class="info_panel">Если вы не понимаете, что это такое - ничего тут не меняйте :)</div>

<a href="{$ROOT}robots.txt">Посмотреть как выглядит файл</a>