

<button type="button" class="btn btn-default btn-create">Создать настройку...</button>

<form action="{$SELF}" method="post" class="stdForm" class="form">

	<table cellpadding="2" class="data">
		{foreach from=$fields item=f}<tr>
		{if $f.type=='string'}
			<td align="right"><label>{$f.name} <span class="link" onclick="showEditForm('{$f.id}');" title="  ID: {$f.id}">[e]</span></label>:</td>
			<td><input class="form-control" type="text" name="fields[{$f.id}]" value="{$f.value|escape:'html'}" size="40"></td>
		
		{elseif $f.type=='int'}
			<td align="right"><label>{$f.name} <span class="link" onclick="showEditForm('{$f.id}');" title="  ID: {$f.id}">[e]</span></label>:</td>
			<td><input type="text" name="fields[{$f.id}]" value="{$f.value|escape:'html'}" size="4"></td>			
		
		{elseif $f.type=='bool'}
			<td align="right"><input type="checkbox" id="fID{$f.id}" name="fields[{$f.id}]" value="1"{if $f.value} checked{/if}></td>
			<td><label for="fID{$f.id}">{$f.name} <span class="link" onclick="showEditForm('{$f.id}');" title="  ID: {$f.id}">[e]</span></label></td>			
		
		{else}
			<td colspan="2"><label>{$f.name} <span class="link" onclick="showEditForm('{$f.id}');" title="  ID: {$f.id}">[e]</span></label>:<br>
			<textarea cols="60" style="width: 100%;" rows="2" name="fields[{$f.id}]">{$f.value|escape:html}</textarea>
			
		</tr>
		{/if}</tr>
		{/foreach}
	</table>
	
	<div class="buttons">
		<button type="submit" class="btn btn-success">Сохранить</button>
		
	</div>
</form>


<div class="modal fade" id="settingForm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      
        
      
      
    </div>
  </div>
</div>
