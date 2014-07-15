<form  method="post" action="{$SELF}" class="stdForm">
</br>
<fieldset>
<legend>Добавить новый</legend>

	<fieldset>
		<label>Константа(engl)</label>
		<input type="text" id="constanta" name="add[constant]" class="needed"  onkeyup="poisk(this.value, this.id);" value="" size="70" >
	</fieldset>
	<fieldset>
		<label>Имя константы(рус)</label>
		<input type="text" id="" name="add[name]" class="needed" value="" size="70">
	</fieldset>
	
	<fieldset>
		<label>Тип</label>
		<select name="add[type]">
			<option value="string" selected>string</option>
			<option value="value">value</option>
			<option value="bool">bool</option>
		</select>
	</fieldset>
	
	 <button type="submit" name="" id="submitButton" class="okButton">Добавить </button>
	
</fieldset>
<table class="data">
	<tr>
		<th>Константа&nbsp;</th>
		<th>Имя&nbsp;</th>
		<th>Тип&nbsp;</th>
		<th>&nbsp;</th>

	</tr>

	{foreach from=$const key=k item=p}
	<tr class="{cycle values='odd,even'}">
		<td>&nbsp;{$k}</td>
		<td>&nbsp;{$p.name}</td>
		<td>&nbsp;{$p.type}</td>
		<td>
			<a href="?edit={$k}"><img alt="редактировать" title="редактировать" src="{$ADMIN_IMG}edit.png"></a>
			<a href="?del={$k}"><img src="{$ADMIN_IMG}del.gif" onclick='return confirm("Точно удалить??")' alt="удалить" title="" class="hand">
		</td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="9">{if $pages}<div class="pages">{$pages}</div>{/if}</td>
	</tr>	
</table>

</form>

