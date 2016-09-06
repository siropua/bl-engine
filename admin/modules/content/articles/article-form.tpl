<script type="text/javascript">
	var articleID = {$article.id};
</script>

<form id="content-form" action="{$SELF}" class="form" enctype="multipart/form-data" method="post">
<input type="hidden" value="{$article.id}" name="id">
	
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
		{elseif $sec.type == 'gallery'}
			{include file='sections/gallery.tpl'}
		{/if}
		{/foreach}
		{$sec = false}
	</div>

	<div id="imagePreview"><img src="" alt=""></div>
	

	<hr>

	<div class="addContentButtons">Добавить: 
			<button class="btn btn-default add-item-text" type="button">Блок текста</button>
			<button class="btn btn-default fileinput-button" type="button"><i class="fa fa-image"></i> Картинку<input type="file" name="secpic" multiple></button>
			<button class="btn btn-default fileinput-button" type="button"><i class="fa fa-sort fa-rotate-90"></i> Галерею<input type="file" name="gallery_create" multiple></button>
			</div>
			
		<div class="panel panel-default" id="photosProgress">
			<div class="panel-body">
			<div class="progress">
				<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;">
				0%
				</div>
			</div>
			Загрузка фоточек… <span class="bitrate-info"></span>
			</div>
			
		</div>

	<hr>

	<div class="row">
		<div class="col-sm-6 form-group">
			<label>Теги</label>
			<input type="text" name="post[tags]" value="{$article.tags}" class="form-control">
		</div>
		<div class="col-sm-6 form-group">
			<label>Оригинал статьи</label>
			<input type="text" name="post[original_link]" value="{$article.original_link}" class="form-control">
		</div>
	</div>

	<div class="row">
		<div class="col-sm-4 form-group">
			<label>Дата публикации</label>
			<input type="text" name="post[public_date]" value="{$article.public_date}" class="form-control">
		</div>
		<div class="col-sm-4 form-group">
			<label>Доступно до</label>
			<input type="text" name="post[show_till]" value="{$article.show_till}" class="form-control">
		</div>
	</div>

	<div class="row">
		<div class="col-sm-4 form-group">
			<label><input type="checkbox" name="is_allow_comments" value="1"> Разрешить комментарии</label>
		
		</div>
		
	</div>

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
	{include file='sections/gallery.tpl'}

</div>