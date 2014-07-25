<form action="{$SELF}" method="get">
<table width="100%">
			<tr>
				<td>Показано <b class="posts-shown">{count($posts)}</b> {$posts|count|numlabel:'пост':'поста':'постов'}
			{if count($posts)!=$total}из <b class="posts-total">{$total}</b>{/if}</td>
			<td>{if $pages}<div class="pages">Страницы: {$pages}</div>{/if}</td></td>
			</tr>
</table>
<table class="data table table-stripped table-condensed">
	<thead>
		<tr>
			<th>&nbsp;</th>
			<th><select class="form-control" name="blog_id">
			<option value="">все рубрики</option>
			{html_options options=$blogs selected=$quicky.get.blog_id}
			</select></th>
			<th><select name="status" class="form-control">
				<option value="">все новости</option>
				<option value="posted"{if $quicky.get.status=="posted"} selected{/if}>запощенные</option>
				<option value="deferred"{if $quicky.get.status=="deferred"} selected{/if}>отложенные</option>
			</select></th>
			<th><input type="text" name="q" value="{$quicky.get.q|escape:html}" class="form-control" style="width: 97%;"></th>
			<th colspan="3">&nbsp;</th>
			<th><input type="text" class="form-control" style="width: 97%;" name="tags" disabled></th>
			<th class="buttons"><button type="submit" class="btn btn-default" title="Фильтровать"><i class="fa fa-filter"></i></button></th>
		</tr>
	
	<tr>
		<th>ID</th>
		<th>Рубрика/Автор</th>
		<th>Дата</th>
		<th>Заголовок</th>
		<th><i class="fa fa-comments"></i></th>
		<th><i class="fa fa-eye"></i></th>
		<th><i class="fa fa-globe" title="Количество заходов по внешним ссылкам"></i></th>
		<th>Тэги</th>
		<th>&nbsp;</th>
	</tr>
	</thead>
	{foreach from=$posts item=p}
	<tr class="{cycle values='odd,even'}{if $p.status=='deferred'} deferred{/if}" id="tr{$p.id}" data-id="{$p.id}">
		<td align="center"><a href="?edit={$p.id}">{$p.id}</a></td>
		<td>{$p.blog_name} / {$p.owner_nick}</td>
		<td style="font-size: .8em;">{if $p.datepost == $p.dateadd}{$p.datepost|FormatDateTime}{else}
			<div title="дата написания поста"><span class="descr">нап:</span>
			{$p.dateadd|FormatDateTime}</div>
			<div title="дата показа поста"><span class="descr">пок:</span>
			{$p.datepost|FormatDateTime}</div>
		{/if}
		<div>{if $p.status == 'deffered'}
				<span class="label label-default">отложен</span>
			{elseif $p.status == 'draft'}
				<span class="label label-info">черновик</span>
			{/if}</div>
		</td>
		<td>

		{if $p.visible}<img src="{$ADMIN_IMG}star.png" alt="[на главной]" title="Новость на главной">{/if}
		{if $p.mainpic}<i src="{$ADMIN_IMG}image.png" alt="[есть картинка]" rel="{$p.res_url}t-{$p.mainpic}" class="mainpicPreview fa fa-picture-o"></i>{/if} 
		{if $p.video_type != ''}<i class="fa fa-file-video-o" title="Видео-новость"></i>{/if}
		
		{if $p.geo_lat}
		<img src="{$ADMIN_IMG}map-pin.png" width="16" height="16" alt="+" title="Пост указан на карте">
		{/if}
		
		<a href="?edit={$p.id}">{$p.title}</a>
		{if $p.source_url}<span title="Источник:  {$p.source_url|escape:html|truncate:200}">&copy;</span>{/if}
		<div>
			<small class="mute"><a href="{$ROOT}{$p.blog_url}/{$p.url}.html">{$ROOT}{$p.blog_url}/{$p.url}.html</a></small>
		</div>
		</td>
		<td align="center">{if $p.allow_comments}{$p.comments|ifzero:'&nbsp;'}{else}<span class="no" title="Комментарии к записи отключены">x</span>{/if}</td>
		<td align="center">{$p.views|ifzero:'&nbsp;'}</td>
		<td align="center"><a href="?refs={$p.id}" onclick="return showRefs({$p.id});">{$p.ref_clicks|ifzero:''}</a></td>
		<td class="descr">{if $p.tags_cache}
		{$tags = unserialize($p.tags_cache)}
		{foreach from=$tags item=t name=fet}
		{$t}{if !last}, {/if}
		{/foreach}
		{else}&nbsp;
		{/if}</td>
		<td><div class="btn-group action-buttons">
			<nowrap>
				<a href="?ashtml={$p.id}" title="В виде HTML" class="btn btn-xs btn-default"><i class="fa fa-code"></i></a><button type="button" title="Удалить пост" class="btn btn-xs btn-danger delete-post"><i class="fa fa-trash-o"></i></button>
			</nowrap>
		</div>
		</td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="9">{if $pages}<div class="pages">{$pages}</div>{/if}</td>
	</tr>	
</table>
</form>
<div id="mainpicPreview"></div>
