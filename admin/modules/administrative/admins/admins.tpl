<table class="data">
	<tr>
		<th>ID</th>
		<th>Логин</th>
		<th>Имя</th>
		<th>&nbsp;</th>
	</tr>
	{foreach from=$list item=l}
	<tr class="{cycle values='odd,even'}">
		<td>{$l.id}</td>
		<td><a href="?edit={$l.id}">{$l.email}</a></td>
		<td>{$l.full_name}</td>
		<td><img src="{$ADMIN_IMG}key.png" alt="пароль" title="  Поменять пароль" width="16" height="16" class="link" onclick="changePass({$l.id}, '{$l.email}');" /></td>
	</tr>
	{/foreach}
</table>

<div class="rWindow" id="passForm" style="width: 30em;">
<h1>Смена пароля</h1>
<h2 id="fLogin"></h2>
<form action="{$SELF}" method="post" class="stdForm">
	<fieldset>
		<label>Новый пароль</label>
		<input type="text" name="newpass[pass]" id="fNewPass" class="needed" size="15" maxlength="50" /> 
		<img src="{$_MODULE_BASE}wand.png" alt="*" title="Придумать пароль за меня" onclick="genPass();" class="hand" />
	</fieldset>
	<input type="hidden" name="newpass[user_id]" id="fUserID" value="">
	<button type="submit" class="okButton">Сменить</button>
	<button type="button" class="closeButton cancelButton">Отмена</button>
</form>
</div>