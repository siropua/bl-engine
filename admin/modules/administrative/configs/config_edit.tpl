<form method="post" action="{$SELF}" class="stdForm">
<h1>Конфиг: {$smarty.get.edit}</h1>

<table class="data">
	<tr>
		<th>Константа</th>
		<th>Тип</th>
		<th>Значение</th>

	</tr>
	{foreach from=$config item=p}
	<tr class="{cycle values='odd,even'}">
		<td title="{$p.name}">{$p.name}<br><span class="descr">{$p.constanta}</span></td>
		<td >{$p.type}</td>
		<td>
		<fieldset>
			
		{if $p.type == 'string'}
		
				<input type="text" id="" name="edit[{$p.constanta}]" class="needed" value="{$p.value|htmlspecialchars}" size="70" >

		{elseif $p.type == 'value'}
		
				<input type="text" id="" name="edit[{$p.constanta}]" class="needed" value="{trim($p.value|htmlspecialchars)}" size="70" >
			
		{elseif $p.type == 'bool'}
		<input type="hidden"  name="edit[{$p.constanta}]" value="used"  >
			<label><input type="checkbox" name="edit[{$p.constanta}_bool]" value="1" {if ($p.value == '1') or ($p.value == 'true')}checked{/if}></label>
		{else}
		Неверный тип
		{/if}
		</fieldset>
</td>
	</tr>
	{/foreach}

</table>

 <button type="submit" id="submitButton" class="okButton">Сохранить</button>
 
  <button type="submit" name="cancel" class="cancelButton">Отмена</button>

</form>
{if isset($dop_const)}
<hr />
Неуказанные константы:
<table class="data">
	<tr>
		<th>Константа</th>
		<th>Значение</th>

	</tr>
	{foreach from=$dop_const item=p}
	<tr class="{cycle values='odd,even'}">
		<td >{$p.0}</td>
		<td >{$p.1}</td>
	</tr>
	{/foreach}

</table>
{/if}