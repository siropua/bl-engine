<form action="{$SELF}" class="form" role="form" method="post">
	<div class="form-group">
		<label for="fContent">Содержание страницы</label>
		<textarea name="content" id="fContent" cols="60" rows="25" class="edtAdv form-control">{$content}</textarea>
	</div>

	<button class="btn btn-success btn-lg" type="submit"><i class="fa fa-save"></i> Сохранить</button>
	<a href="{$_M.url}" class="btn btn-warning btn-lg"><i class="fa fa-undo"></i> Отмена</a>

	<div class="pull-right"><button class="btn btn-danger btn-lg delete-button" data-page="{$page}"><i class="fa fa-trash-o"></i> Удалить страницу</button></div>

</form>

<script type="text/javascript">
	$(function(){
		tinymce.init({
		selector: ".edtAdv",
		language : 'ru',
		plugins: [
						"advlist autolink lists link image charmap print preview anchor",
						"searchreplace visualblocks code fullscreen",
						//"insertdatetime media table contextmenu paste moxiemanager"
					],
		toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
		setup : function(ed) {
              ed.on('change', function(ed, e) {
                  
              });
           }
		});
	});
</script>