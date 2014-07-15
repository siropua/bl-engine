<div class="row">
	<div class="col-lg-5 col-sm-7 col-md-6 col-xs-12">
		
		<form action="{$SELF}" method="post" id="passForm" class="stdForm form">

			<div class="form-group">
				<label for="f_pass" accesskey="P">Новый пароль:</label>
				<input type="password" class="needed form-control" required="required" name="password" size="20" maxlength="50" id="f_pass" value="">
			</div>	
			<div class="form-group">
				<label for="f_pass2">Новый пароль еще раз:</label>
				<input type="password" class="needed form-control" required="required" name="password2"  size="20" maxlength="50" id="f_pass2" value="">
			</div>
			
			<button type="submit" class="btn btn-lg btn-primary">Сменить пароль</button>
			<a href="{$ADMIN_URL}" class="btn btn-lg btn-default">Отмена</a>
			
		</form>
	</div>
</div>