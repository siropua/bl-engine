<div class="row a-row a-item a-item-image" data-type="image"{if $sec} data-id="{$sec.id}"{/if}>
	<div class="form-group col-sm-10 a-body">
			<div class="editable content content-pre" data-name="text"{if $sec} id="sections[{$sec.id}][text_data]"{/if}>{$sec.text_data}</div>
			<div class="image" data-name="text">{if $sec.string_data}
				<img src="{$article.res_url}{$sec.string_data}">
			{else}
				<div class="loading">
					<i class="fa fa-spinner fa-spin fa-3x fa-fw margin-bottom"></i>
				</div>
			{/if}</div>
			<div class="editable content content-post" data-name="text"{if $sec} id="sections[{$sec.id}][text_data1]"{/if}>{$sec.text_data1}</div>
	</div>
	<div class="form-group col-sm-2 a-settings">
		<span class="a-row-type">картинка</span>
		<div>
			{include file='a-item-setting-buttons.tpl'}					
		</div>
	</div>
</div>