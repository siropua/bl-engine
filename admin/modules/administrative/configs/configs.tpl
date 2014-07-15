<table class="data">

	<tr>
		<th>Файл конфига</th>

	</tr>
	{foreach from=$list item=p}
	<tr class="{cycle values='odd,even'}">
		<td><a href="?edit={basename($p)}" style="font-size:2em">{basename($p)}</a></td>
	</tr>
	{/foreach}
	
</table>