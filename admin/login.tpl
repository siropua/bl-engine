<div id="loginPanel" class="row">
	<div class="col-md-6 col-md-offset-3 col-sm-6 col-xs-11">
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h2 class="panel-title">Вход</h2>
		</div>
		<form action="{$SELF}" method="post" class="stdForm panel-body" id="loginForm" role="form">
		<input type="hidden" name="ref" value="{$ref|escape:html}">
			
			{if $loginError}
			<span id="loginError">{$loginError}</span>
			{/if}
			<div class="form-group">
				<label>Логин</label>
				<input type="text" name="login2site[login]" class="needed focused form-control input-lg" required="required" id="fLogin" value="{$smarty.post.login|escape:html}" >
			</div class="form-group">

			<div class="form-group">
				<label>Пароль</label>
				<input type="password" name="login2site[password]" class="needed form-control input-lg" >
			</div class="form-group">

			<div class="form-group">
				<label><input type="checkbox" name="login2site[save_me]" value="1" checked>&nbsp;Запомнить меня</label>
			</div class="form-group">
			<div class="buttons">
				<button type="submit" value="Вход..." class="btn btn-success"><i class="icon-ok"></i> Войти</button>
				<a href="{$ROOT}" class="btn"><i class="icon-remove"></i> Отмена</a>
			</div>
		</form> <!-- /panel-body -->

		
	</div> <!-- /panel -->

	
	
	
	</div>
	<div class="clearfix"></div>
</div>