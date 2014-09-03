<table class="table">
	<thead>
		<tr>
			<th>ID</th>
			<th>Название</th>
			<th>URL</th>
			<th>Описание</th>
			<th>Постов</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	{$posts = 0}
	{foreach from=$list item=l name=bl}
	<tr class="{cycle values='odd,even'}" id="tr{$l.id}">
		<th>{$l.id}</th>
		<td>{$l.name}</td>
		<td>{$l.url}</td>
		<td>{$l.description}</td>
		<td align="center">{$l.posts}</td>
		<td>
		
			{if !$smarty.foreach.bl.first}<a href="?move=up&id={$l.id}" class="btn btn-xs btn-default"><i class="fa fa-arrow-up"></i>{/if}
			{if !$smarty.foreach.bl.last}<a href="?move=down&id={$l.id}" class="btn btn-xs btn-default"><i class="fa fa-arrow-down"></i>{/if}
		
		
			<a href="?edit={$l.id}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
			
			<a href="" class="btn btn-xs btn-danger" onclick="if(confirm('Удалить рубрику? Точно? Это невозможно будет отменить.')) deleteBlog({$l.id}, this);"><i class="fa fa-trash-o"></i></a>
		
		</td>
	</tr>
	{$posts = $posts + $l.posts}
	{/foreach}
	<tr>
		<th colspan="4">&nbsp;</th>
		<th>{$posts}</th>
		<th>&nbsp;</th>
	</tr>
</table>