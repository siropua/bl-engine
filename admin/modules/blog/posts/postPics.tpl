{if $postPicsData.pics}
	<ul class="sortable">
		{foreach from=$postPicsData.pics item=p}
		<li class="" data-id="{$p.id}">
			<div class="pull-left">
				<img src="{$postPicsData.res_url}t-{$p.filename}" alt="" class="img-rounded photo">
			</div>

			<div class="text">
				{if $p.id == $postPicsData.mainpic} 
					<span class="label label-success label-main">главная</span>
				{/if}
				<span class="photo-description{if !$p.text} no-text{/if}">{$p.text|strip_tags|truncate:150|default:'(нет описания)'}</span>
			</div>

			<div class="pull-right buttons">
				<div class="btn-group">
					<button class="btn btn-xs btn-default btn-move-pic-up" type="button"><i class="fa fa-arrow-up" title="Поднять вверх"></i></button>
					<button class="btn btn-xs btn-default btn-move-pic-down" type="button"><i class="fa fa-arrow-down" title="Опустить вниз"></i></button>
					{if $p.id != $postPicsData.mainpic}
					<button class="btn btn-xs btn-primary btn-set-as-main" type="button" title="Назначить главной"><i class="fa fa-asterisk"></i></button>
					{/if}
					<button class="btn btn-xs btn-danger btn-delete-pic" type="button"><i class="fa fa-trash-o"></i></button>

				</div>
			</div>
			<div class="clearfix"></div>
		</li>
		{/foreach}
	</ul>
{else}
	<div class="no-pics">Пока нет картинок. <br> Загрузите их, используя кнопки ниже</div>
{/if}
