<tr class="catalog-item" data-id="{$item.id}">
	<td class="name-descr">
		<div style="padding-left: {$item.level*30 - 30}px;"><strong>{$item.title}</strong>
		{if !$item.is_visible} <i class="fa fa-eye-slash"></i> {/if}
		<small>{$item.description}</small></div>
	</td>
	<td class="url">/<span>{$item.url}</span>/ <a href="{$ROOT}{$item.url}/" target="_blank"><i class="fa fa-external-link"></i></a></td>
	<td class="keywords">{$item.keywords}</td>
	<td>{$item.articles_count}</td>
	<td>
		<button class="btn btn-xs btn-primary create-item"><i class="fa fa-plus"></i></button>
		<button class="btn btn-xs btn-default edit-item"><i class="fa fa-edit"></i></button>
		<button class="btn btn-xs btn-danger delete-item"><i class="fa fa-trash"></i></button></td>
</tr>
{if $item.childNodes}
	{foreach from=$item.childNodes item=i}
		{include file='catalog-item.tpl' item=$i}
	{/foreach}
{/if}