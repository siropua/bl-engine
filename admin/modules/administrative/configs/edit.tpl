<form  method="post" action="{$SELF}" class="stdForm">
</br>
<fieldset>
<legend>Добавить новый</legend>


	<fieldset>
		<label>Имя константы(рус)</label>
		<input type="text" id="" name="edit[name]" class="needed" value="{$constanta.name}" size="70" >
	</fieldset>
	<fieldset>
		<label>Тип</label>
		<select name="edit[type]">
			<option value="string" {if $constanta.type == 'selected'}selected{/if}>string</option>
			<option value="value" {if $constanta.type == 'value'}selected{/if}>value</option>
			<option value="bool" {if $constanta.type == 'bool'}selected{/if}>bool</option>
		</select>
	</fieldset>
	 <button type="submit" name="" id="submitButton" class="okButton">Сохранить</button>
	
</fieldset>

</form>