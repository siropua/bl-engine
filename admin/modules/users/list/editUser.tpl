<a href="{$_M.url}" class="btn btn-info"><i class="fa fa-chevron-left"></i> Вернуться к списку юзеров</a>

<a href="#" class="btn btn-{if $isAdmin}success{else}default{/if}"><i class="fa fa-certificate"></i> админ-права</a>

<h1>{$editUser.full_name}
	{$subsC = count($subs)}
	<small>
	{if $subsC}<span class="label label-primary">{$subsC}&nbsp;{$subsC|numlabel:'подписка':'подписки':'подписок'}</span>{/if}
	{if $editUser.total_payed}
	<span class="label label-success">{$editUser.total_payed}$</span>
	{/if}
	</small>
</h1>



<div class="row">
	<div class="col-lg-3 col-md-5 col-sm-6">
		<form action="{$SELF}" class="form" role="form" method="post">
			
			<h3>Основные данные</h3>
			<div class="form-group">
				<label for="">Е-Мейл пользователя</label>
				<input type="email" name="u[email]" required="required" value="{$editUser.email|escape:html}" class="form-control">
			</div>
		
			<div class="form-group">
				<label for="">Имя пользователя</label>
				<input type="text" name="u[full_name]" required="required" value="{$editUser.full_name|escape:html}" class="form-control">
			</div>

			<div class="form-group">
				<label for="">Прем</label>
				<input name="u_prem_date" type="text" size="8" maxlength="10" class="form-control date-pick" value="{if $editUser.premium_till}{date('Y-m-d', $editUser.premium_till)}{/if}">
				<script type="text/javascript">
				$(function () {
					$('.date-pick').pickmeup({
						hide_on_select	: true,
						before_show		: function () {
							var $this	= $(this);
							$this.pickmeup('set_date', $this.val());
						},
						change			: function (formatted) {
							$(this).val(formatted);
						},
						format  : 'Y-m-d'
					});
				})
				</script>
			</div>
		
			<button class="btn btn-primary" type="submit"><i class="fa fa-save"></i> Сохранить данные</button>
		
		</form>



	</div>
	<div class="col-lg-3 col-md-4 col-sm-6">
		<form action="{$SELF}" class="form" role="form" method="post">
			
			<h3>Пароль</h3>
			<div class="form-group">
				<label for="">Новый пароль</label>
				<input type="password" name="p[p1]" required="required" class="form-control">
			</div>
		
			<div class="form-group">
				<label for="">Повторите пароль</label>
				<input type="password" name="p[p2]" required="required" class="form-control">
			</div>
		
			<button class="btn btn-primary" type="submit"><i class="fa fa-key"></i> Сменить пароль</button>
		
		</form>
	</div>

	<div class="col-lg-3 col-md-3 col-sm-6">
		<h3>Соц-сети</h3>
		{if $userSocials}
		<ul>
		{foreach from=$userSocials item=s key=sID}
			<li>{$s.network_name}: {$s.name|default:$s.login}</li>
		{/foreach}
		</ul>
		{else}
		<div class="alert alert-info">Не подключено</div>
		{/if}

	</div>
</div>