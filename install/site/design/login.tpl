<div id="loginPanel" class="row">
	<div class="span6">
	<form action="{$SELF}" method="post" class="stdForm well" id="loginForm">
	<input type="hidden" name="ref" value="{$ref|escape:html}">
		<h1>Вход</h1>
		{if $loginError}
		<span id="loginError">{$loginError}</span>
		{/if}
		<fieldset>
			<label>Логин</label>
			<input type="text" name="login2site[login]" class="needed focused" id="fLogin" value="{$smarty.post.login|escape:html}">
		</fieldset>
		<fieldset>
			<label>Пароль</label>
			<input type="password" name="login2site[password]" class="needed" >
		</fieldset>
		<fieldset>
			<label><input type="checkbox" name="login2site[save_me]" value="1" checked>&nbsp;Запомнить меня</label>
		</fieldset>
		<div class="buttons">
			<button type="submit" value="Вход..." class="btn btn-primary"><i class="icon-ok icon-white"></i> Войти</button>
			<a href="{$ROOT}" class="btn"><i class="icon-remove"></i> Отмена</a>
		</div>
	</form>
	
	
	<div id="loginNotes">
	
	<p>Благодаря этой прекрасной странице вы можете войти на этот сайт.</p>
	<p>Например для того, чтобы оставить комментарий. Больше, пожалуй, тут сделать нечего.</p>
	<!-- p>Если вы все еще не зарегистрированны &mdash; существует простая <a href="{$ROOT}register/">регистрация</a>.</p>
	<p>А если вы зарегистрированны, но забыли пароль, используйте страницу <a href="{$ROOT}amnesia/">восстановления пароля</a></p -->
	
	</div>
	</div>
	<div style="clear: both;"></div>
</div>
