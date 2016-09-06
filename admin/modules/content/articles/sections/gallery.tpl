<div class="row a-row a-item a-item-gallery" data-type="gallery"{if $sec} data-id="{$sec.id}" id="item{$sec.id}" {/if}>
	<div class="form-group col-sm-10 a-body">
			<div class="editable content content-pre" data-name="text"{if $sec} id="sections[{$sec.id}][text_data]"{/if}>{$sec.text_data}</div>
			<div class="gallery">{if $sec.files}
				<ul>
					{foreach from=$sec.files item=f}
					<li><img src="{$article.res_url}{$f.file}"></li>
					{/foreach}
				</ul>
			{else}
				<div class="loading">
					<i class="fa fa-spinner fa-spin fa-3x fa-fw margin-bottom"></i>
				</div>
			{/if}</div>
			<div class="editable content content-post" data-name="text"{if $sec} id="sections[{$sec.id}][text_data1]"{/if}>{$sec.text_data1}</div>
	</div>
	<div class="form-group col-sm-2 a-settings">
		<span class="a-row-type">галерея</span>
		<div>
			{include file='a-item-setting-buttons.tpl'}	

			<button class="btn btn-default btn-sm btn-action-2slider fileinput-button"><i class="fa fa-file-image-o"></i><i class="fa fa-plus-square-o text-success"></i><input type="file" name="gallery" multiple></button>				
		</div>
	</div>
</div>