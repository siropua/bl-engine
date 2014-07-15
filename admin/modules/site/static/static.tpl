<div class="row">
	<div class="pull-right"><button type="button" data-toggle="modal" data-target="#createTPL" class="btn btn-primary btn"><i class="fa fa-plus"></i> создать страницу</button></div>
</div>

<h3>Список страниц:</h3>
<ul>
	{foreach from=$files item=f}
	<li><a href="?edit={$f}">{$f}</a></li>
	{/foreach}
</ul>

<!-- Создание страницы -->
<div class="modal fade" id="createTPL" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title" id="myModalLabel">Создание страницы</h4>
      </div>
      <form action="{$SELF}" method="post" class="form create-tpl">
      	<div class="modal-body">
      	  <div class="form-group">
      	  	<label for="fURL">Введите URL страницы</label>
      	  	<br>
      	  	http://{$smarty.server.HTTP_HOST}{$ROOT}<input name="newURL" type="text" class="form-control w-auto">.html
      	  	<p class="help-block">В качестве URL можно задавать символы латинского алфавита (a-z), цифры и знак «тире» (-).</p>
      	  </div>
      	</div>
      	<div class="modal-footer">
      	  <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
      	  <button class="btn btn-primary" type="submit">Создать</button>
      	</div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->