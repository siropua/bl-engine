<script>
/*	// список отложенных записей
	var deferredList = {ldelim}{rdelim};
	{foreach from=$deferredList item=l key=k}
	deferredList['{$k}'] = '{$l|escape:quotes}';
	{/foreach}
	var save_folder = '{$save_folder}';
	var _MCE_IMAGE_SETTINGS = {ldelim}
		connector: '/js/my_connector/',
		custom_params: 'obj_id={$save_folder}'
	{rdelim};
	
	var geo_lat = {if $blogItem.geo_lat}{$blogItem.geo_lat}{else}48.45{/if};
	var geo_lng = {if $blogItem.geo_lng}{$blogItem.geo_lng}{else}35.033173{/if};
	var hasMap = {if $blogItem.geo_lat}true{else}false{/if}	
	*/
</script>


{$post_mode = $blogItem.post_type}{if !$post_mode}{$post_mode='text'}{/if}

<form action="{$SELF}" method="post" id="blogPostForm" enctype="multipart/form-data" class="form blogPostForm post-mode-{$post_mode}" role="form" data-mode="{$post_mode}">
	
<input type="hidden" name="todo" value="save" id="fTodo" />
<input type="hidden" name="postID" value="{$blogItem.id}" class="post-id" />
<input type="hidden" value="{$blogItem.res_url}" id="res-url" />
<input type="hidden" name="is_new" value="{if $_APP->path(5) == 'add'}1{else}0{/if}">
	
	<div class="form-group post-mode"><div class="row">
		<div class="col-lg-1 col-xs-2">
			Режим поста:
		</div>
		<div class="btn-group col-lg-11 col-xs-10" data-toggle="buttons">
			<label class="btn btn-default">
				<input type="radio" name="post_mode" value="text"{if $post_mode=='text'} checked="checked"{/if}><i class="fa fa-file-text"></i>  Текст
			</label>
			<label class="btn btn-default">
				<input type="radio" name="post_mode" value="photo"{if $post_mode=='photo'} checked="checked"{/if}><i class="fa fa-picture"></i> Фото-пост
			</label>
			<label class="btn btn-default">
				<input type="radio" name="post_mode" value="video"{if $post_mode=='video'} checked="checked"{/if}><i class="fa fa-youtube-play"></i> Видео-пост
			</label>
			<label class="btn btn-default">
				<input type="radio" name="post_mode" value="import"{if $post_mode=='import'} checked="checked"{/if}><i class="fa fa-external-link"></i> Импорт
			</label>
		</div>		
	</div></div>
	<script type="text/javascript">
	$(function () {
		$('.post-mode label:first').click();
	})
	</script>

	
	{if count($blogs) > 1}<div class="form-group">
		<label>Рубрика</label>
		<select name="blog_id" class="form-control">
			{foreach from=$blogs item=b}
			<option value="{$b.id}">{$b.name}</option>
			{/foreach}
		</select>
	</div>
	{else}
		<input type="hidden" name="blog_id" value="{$blogs[0].id}">
	{/if}

	<div class="row">
		<div class="form-group col-md-10 col-sm-9">
			<label for="fTitle">Заголовок</label>
			<input type="text" id="fTitle" name="title" value="{$blogItem.title}" size="60" maxlength="255" class="changed-flag needed form-control" autofocus="1">
		</div>
		<div class="form-group col-md-2 col-sm-3">
			<label for="">Автор</label>
			<button class="btn btn-default form-control" type="button"><i class="fa fa-user"></i> {$user.full_name}</button>
		</div>
		

	</div>

	


	
	

	<div class="row main-post-content">

	<div class="col-lg-8 col-md-12" id="postTexts">
		<div >
					<div class="form-group" id="fsPostPreview" style="{if !$blogItem.preview || ($blogItem.preview == $blogItem.text)}display: none;{/if}">
						<label>Превью новости:</label>
						<textarea id="fPreview" name="preview" rows="3"  cols="60" class="changed-flag edtSimple form-control">{$blogItem.preview|escape:html}</textarea>
					</div>
				
					<div class="form-group" id="fsPostText">
						<label for="fPostText" id="lPostText"><i class="fa fa-file-text"></i> Полный текст поста</label> {if !$blogItem.main_text || ($blogItem.main_text == $blogItem.original_text)}
					<span><button type="button" class="btn btn-default btn-xs" onclick="$(this).parent().hide(); $('#fsPostPreview').slideDown('fast', function(){ $('#fPreview').focus(); });">Задать превью…</button></span>
					{/if}
				
						<textarea id="fPostText" name="text" rows="20" cols="60" class="changed-flag edtAdv form-control">{$blogItem.text|escape:html}</textarea>
				
						
					</div>	
				
					<div class="form-group" id="fsPostResume" style="{if !$blogItem.resume AND (count($postPicsData.pics) < 2)}display: none;{/if}">
						<label>Резюме: </label>
						<textarea id="fResume" name="resume" rows="3"  cols="60" class="changed-flag edtSimple form-control">{$blogItem.resume|escape:html}</textarea>
						<p class="help-block">Резюме обычно нужно в фото-постах, добавляется в самый конец поста, после ката.</p>
					</div>
		</div>	
	</div>

	<div class="col-lg-4 col-md-12" id="postPictures">
		<div>
			<label for="fPostText" id="lPostText"><i class="fa fa-picture-o"></i> Картинки поста <i class="fa fa-refresh reload-pic-button"></i></label>
			
			
			
			
			<p>Добавить картинки:</p>
			<div class="form-group">
			
				<span class="btn btn-default btn-lg fileinput-button" title="Загрузить с диска">
					<i class="fa fa-folder-open"></i>
					<input type="file" name="secpic" class="" id="secondPics" multiple>         
			    		</span>
			
				<a class="btn btn-default btn-lg" title="Загрузить из URL" data-toggle="modal" href="#picListModal"><i class="fa fa-globe"></i></a>
				<button class="btn btn-default btn-lg" title="Импорт из Instagram" disabled="disabled"><i class="fa fa-instagram"></i></button>
				<button class="btn btn-default btn-lg" title="Импорт из Foursquare" disabled="disabled"><i class="fa fa-foursquare"></i></button>
			</div>
			
			<div class="row pics">{include file='postPics.tpl'}</div>
		</div>
	</div> <!-- / pics -->


	</div> <!-- / row pics&text -->
	

	<div class="form-group">
		<label for="fTags"><i class="fa fa-tags"></i> Тэги</label>
		<input type="text" class="changed-flag" id="fTags" placeholder="Один или несколько тегов через запятую" name="tags" value="{implode(', ', $blogItem.tags)}" size="60" maxlength="255" />
		
		<div id="mainTags">
			{if $mainTags}Темы: 
			{foreach from=$mainTags item=t}
				<a href="#" onclick="addMainTag('{$t.name}'); return false;">{$t.name}</a>
			{/foreach}
			{/if}
		</div>
		<script type="text/javascript">
			$(function () {
				$('#fTags').autoSuggest([{ value:'bla bla' }], {
					asHtmlID: 'tags',
					startText: '{implode(', ', $blogItem.tags)}',
					preFill: '{implode(', ', $blogItem.tags)}'
				});

			});
		</script>
		
	</div>


	<div class="row">
		
		<div class="col-md-4 col-sm-6">
			<div class="form-group ">
				<label>
					<input type="checkbox" name="visible" value="1"{if $blogItem}{if $blogItem.visible} checked{/if}{else} checked{/if}>
					на главной
				</label>
			</div>
			
			<div class="form-group ">
				<label>
					<input type="checkbox" name="disable_comments" value="1"{if $blogItem && !$blogItem.allow_comments} checked{/if}>
					запретить комментарии
				</label>
			</div>


			<div class="form-group ">
				<label><input type="checkbox" name="is_datepost" value="1" id="cbIsDatepost" onchange="$('#datepostControls').css('display', this.checked ? 'block':'none');"{if $blogItem.datepost!=$blogItem.dateadd} checked{/if}>&nbsp;задним числом</label>
				<div id="datepostControls" style="{if $blogItem.datepost!=$blogItem.dateadd}{else}display:none;{/if}">
					<table>
						<tr>
							<td><input name="dp_date" type="text" size="8" maxlength="10" class="date-pick" value="{if $blogItem}{date('Y-m-d', $blogItem.datepost)}{/if}"></td>
							<td><input name="dp_time" type="text" size="4" maxlength="5" value="{if $blogItem}{date('H:i', $blogItem.datepost)}{/if}" style="text-align: center;"></td>
						</tr>
						<tr align="center">
							<td style="font-size: .8em; color: #888;">дд.мм.ггг</td>
							<td style="font-size: .8em; color: #888;">чч:мм</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="clearfix"></div>
		</div> <!-- /checkboxes -->

		<div class="col-md-3 col-sm-6">
			<div class="form-group">
				<label for="fAuthorRating">Оценка автора</label>
				<select name="author_rating" class="form-control" id="fAuthorRating">
					<option value="">без оценки</option>
					{for $i=1 to 10}<option value="{$i}"{if $i == $blogItem.author_rating} selected{/if}>{$i} из 10</option>{/for}
				</select>
			</div>
			<div class="clearfix"></div>
		</div>

		<div class="col-md-5 col-sm-6">
			<div class="form-group">
				<label><i class="fa fa-youtube-play"></i> Видео ссылка</label>
				<input type="url" name="video_link" size="60" maxlength="255" value="{$blogItem.video_link}" class="changed-flag form-control" placeholder="Можно использовать: Youtube">
				
			</div>
			
			<div class="form-group">
				<label><i class="fa fa-share"></i> Источник</label>
				<input type="text" name="source_url" size="60" maxlength="255" value="{$blogItem.source_url}" class="changed-flag form-control" placeholder="Ссылка-оригинал, откуда взят текст">
				
			</div>
		</div>
	</div>
	

	<input type="hidden" name="comments" value="1"/>

	<div class="row">
		<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
			<button type="submit" id="submitButton" class="btn btn-success btn-block"><i class="fa fa-check-square"></i><div>Отправить</div></button>
		</div>

		<div class="col-lg-2 col-md-3 col-sm-3 col-xs-4">
			<button type="button" id="saveButton" disabled="disabled" class="btn btn-primary "><i class="fa fa-save"></i><div>Сохранить</div></button>
		</div>


		<div class="col-lg-3 col-md-3 col-sm-4 col-xs-6">
			<button class="btn btn-warning cancel-edit" data-url="{$_M.url}" type="button"><i class="fa fa-chevron-circle-left"></i><div>Отмена</div></button>


			{if $blogItem.id}
			
			
			<button class="btn btn-danger" type="button" onclick="if(confirm('{#delete#}?')){ldelim}document.getElementById('fTodo').value='delete';document.getElementById('blogPostForm').submit();{rdelim}"><i class="fa fa-trash"></i> <div>Удалить</div></button>
			

			{/if}

		</div>

		

	</div>

</form>






<div style="clear: both;"></div>





<div class="modal fade" id="picListModal" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Список URLов</h4>
        </div>
        <form action="#" method="post">
        <div class="modal-body">
          <textarea name="picList" id="picList" cols="30" rows="10" class="form-control"></textarea>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> Отмена</button>
          <button type="button" class="btn btn-success"><i class="fa fa-download"></i> Загрузить</button>
        </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->
  
{* редактирование описания *}
<div class="modal fade" id="editPicDescr" tabindex="-1" role="dialog"  aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Редактирование описания</h4>
        </div>
        <div class="modal-body">
          <div class="editPicDescr">
          	<div class="photoRow">
          		
          	</div>
          	<input type="hidden" id="picDescrID" name="picDescrID" value="">
          	<textarea name="picDescr" id="picDescr" cols="30" rows="10" class="form-control"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i> Отмена</button>
          <button type="button" class="btn btn-success savePicDescr"><i class="fa fa-save"></i> Сохранить</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div><!-- /.modal -->

<script type="text/javascript">
	$('#picListModal').on('shown.bs.modal', function () {
  		$('textarea', this).focus();
	});

	var isNewPost = {if $blogItem}true{else}false{/if};
</script>