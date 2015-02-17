<!-- <a href="{$_MODULE_ROOT}">&laquo; Вернуться к списку администраторов</a><br><br> -->
<form action="{$SELF}" method="post" class="form">
<table class="data">
<tr>
	<th colspan="2">Карточка администратора</th>
</tr>
<tr><td valign="top">
<h2>Данные</h2>

	<fieldset>
		<label>Имя</label>
		<input type="text" name="data[full_name]" size="40" maxlength="255" id="fObjName" class="focused needed" value="{$admin_data.full_name}">
		<div class="descr">
			Описание администратора
		</div>
	</fieldset>	

	<fieldset>
		<label>Е-Mail для входа</label>
		<input type="text" name="data[email]" value="{$admin_data.email}" size="20" maxlength="50" class="needed"{if $editMode} disabled{/if}>
		<div class="descr">Уникальное и обязательно поле. По сути идентификатор.</div>
	</fieldset>
	
	{if !$editMode}
	<fieldset>
		<label>Пароль</label>
		<input type="password" name="data[p1]" size="20" maxlength="50" id="fP1" class="needed" value="{$admin_data.p1}">
	</fieldset>	
	<fieldset>
		<label>Повторить пароль</label>
		<input type="password" name="data[p2]" size="20" maxlength="50" id="fP2" class="needed" value="{$admin_data.p1}">
	</fieldset>	
	{/if}
	
	</td><td valign="top">
	
	
	<div class="header">Права</div>
	<div>
		<label title="  Если эта галочка отключена — администратор не сможет даже войти в админку."><input type="checkbox" name="can[admin]" {if $admin_data.can.admin}checked{/if} value="1"> доступ к администрации</label>
	</div>
	
	<div>
		<label style="color: red;" title="Суперадминистратор.  Администратор, у которого включена эта галочка может всё. Вообще всё."><input type="checkbox" name="can[{$smarty.const.ACCEPT_ALL_RIGHT}]" {if $admin_data.can[$smarty.const.ACCEPT_ALL_RIGHT]}checked{/if} onclick="if(this.checked)$('#advRights').slideUp();else $('#advRights').slideDown();"> суперадмин</label>
	</div>
	
	<table{if $admin_data.can[$smarty.const.ACCEPT_ALL_RIGHT]} style="display: none;"{/if} id="advRights"><tr><td>
	<div id="autoRights">
		{foreach from=$rights item=s key=sName}
		<div class="sectionBlock">
		<div class="header"><label><input type="checkbox" name="can[admin/{$sName}]" class="sectionCB" value="1"{if $admin_data.can["admin/$sName"]} checked{/if}>&nbsp;{$s.name}</label></div>
		<ul class="modulesBlock">
			{foreach from=$s.modules item=m key=mName}
			<li class="moduleLI"><label><input type="checkbox" name="can[admin/{$sName}/{$mName}]" class="moduleCB" value="1"{if $admin_data.can["admin/$sName/$mName"]} checked{/if}>&nbsp;{$m.name}</label>
			{if $m.tabs || $m.rights}<ul class="tabsNrights">
			{foreach from=$m.tabs item=t key=tName}
				<li><label><input type="checkbox" name="can[admin/{$sName}/{$mName}/{$tName}]" class="subCB" value="1"{if $admin_data.can["admin/$sName/$mName/$tName"]} checked{/if}>&nbsp;{$t.name}</label></li>
			{/foreach}
			{foreach from=$m.rights item=rightsCaption key=rName}
				<li><label><input type="checkbox" name="can[admin/{$sName}/{$mName}/{$rName}]" class="subCB" value="1"{if $admin_data.can["admin/$sName/$mName/$rName"]} checked{/if}>&nbsp;{$rightsCaption}</label></li>
			{/foreach}
			</ul>
			{/if}
			</li>
			{/foreach}
		</ul>
		</div>
		{/foreach}
	</div>
	

	
	</td></tr></table>
	
</td></tr>

	<tr>
		<th colspan="2"><div class="buttons">
		<button type="submit" class="saveButton">Сохранить</button>
	</div></th>
	</tr>
</td></tr></table>
</form>
