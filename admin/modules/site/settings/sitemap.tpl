<form action="{$SELF}" method="post" class="stdForm">
	<textarea name="content" cols="80" rows="20" style="width: 97%;">{$content}</textarea>
	{if $writable}<div class="buttons"><button type="submit" class="saveButton"><img src="{$ADMIN_IMG}save.gif"> Сохранить</button></div>{/if}
</form>

<div class="info_panel">Если вы не понимаете, что это такое - ничего тут не меняйте :)</div>
<div class="info_panel">Учтите, что большинство разделов генерируются в сайтмапе автоматически и в реальном времени. Сюда можно вносить только то, что добавляется на сайт руками</div>

<a href="{$ROOT}sitemap.xml">Посмотреть как выглядит файл</a>