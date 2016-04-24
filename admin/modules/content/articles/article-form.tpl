<script type="text/javascript">
	var articleID = {$article.id};
</script>

<form id="content-form" action="{$SELF}" class="form" enctype="multipart/form-data" method="post">
	
	<div class="row a-row">
		<div class="form-group col-sm-10 a-body">
			<input type="text" class="form-control title" name="post[title]" placeholder="Заголовок" value="{$article.title}">
		</div>
	</div>

	<div class="a-items">
		{foreach from=$article.sections item=sec}
		{if $sec.type == 'text'}
			{include file='sections/text.tpl'}
		{elseif $sec.type == 'image'}
			{include file='sections/image.tpl'}
		{/if}
		{/foreach}
		{$sec = false}
	</div>

	<div id="imagePreview"><img src="" alt=""></div>
	

	<hr>

	Добавить: 
		<button class="btn btn-default add-item-text" type="button">Блок текста</button>
		<button class="btn btn-default fileinput-button" type="button"><i class="fa fa-folder-open"></i> Картинку<input type="file" name="secpic" class="" id="secondPics" multiple></button>
		<button class="btn btn-default" type="button">Слайды</button>
		<button class="btn btn-default" type="button">Видео</button>

	<hr>

	<div class="row">
		<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
			<button type="submit" id="submitButton" class="btn btn-success btn-block"><i class="fa fa-check-square"></i><div>Опубликовать</div></button>
		</div>

		<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4">
			<button type="button" id="saveButton" class="btn btn-primary "><i class="fa fa-save"></i><div>Сохранить</div></button>
		</div>


		<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
			<button class="btn btn-warning cancel-edit" data-url="{$_M.url}" type="button"><i class="fa fa-chevron-circle-left"></i><div>Отмена</div></button>


			{if $article.id}
				<button class="btn btn-danger" type="button"><i class="fa fa-trash"></i> <div>Удалить</div></button>
			{/if}

		</div>
	</div>
</form>

<div id="templates">
	
	{include file='sections/text.tpl'}
	{include file='sections/image.tpl'}

</div>