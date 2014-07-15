<form action="{$_M.url}" class="form form-inline" method="get">
	<div class="form-group">
		<input type="text" name="q" value="{$smarty.get.q|escape:html}" placeholder="Имя или e-mail" autofocus="1" class="form-control">
	</div>

	<button class="btn btn-info" type="submit">Фильтровать</button>
	{if $smarty.get.q}
	<a href="{$_M.url}" class="btn btn-warning">Сбросить</a>
	{/if}
</form>

<table class="table table-hover users-table">
	<thead>
		<tr>
			<th>ID</th>
			<th>E-Mail</th>
			<th>Дата реги</th>
			<th>$</th>
			<th>Активность</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		{foreach from=$users item=u}
		<tr data-id="{$u.id}">
			<td>{$u.id}</td>
			<td>
			{if $u.premium_till > time()} <i class="fa fa-star" title="Прем до {$u.premium_till|FormatDateTime}"></i> {/if}
			<a href="?edit={$u.id}">{$u.email}</a></td>
			<td>{$u.datereg|FormatDateTime}</td>
			<td>{if $u.total_payed}<span class="label label-success">{$u.total_payed}$</span>{else}&nbsp;{/if}</td>
			<td>{if $u.hits}{$u.hits}, <a href="{$u.lastpage|truncate:100}">{$u.lastpage}</a>
			<small>{$u.last_online|FormatDateTime}</small>{else}&mdash;{/if}</td>
			<td><div class="btn-group">
				<a href="?edit={$u.id}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
				<button title="Удалить" class="btn btn-xs btn-danger btn-delete"><i class="fa fa-trash-o"></i></button>
			</div></td>
		</tr>
		{foreachelse}
		<tr>
			<td colspan="6" align="center">Пользователей с таким критерием не найдено<br>
			<a href="{$_M.url}" class="btn btn-warning btn-xs">Сбросить параметры поиска</a></td>
		</tr>
		{/foreach}
	</tbody>
</table>