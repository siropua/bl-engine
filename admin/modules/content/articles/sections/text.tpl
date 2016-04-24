<div class="row a-row a-item a-item-text" data-type="text"{if $sec} data-id="{$sec.id}"{/if}>
	<div class="form-group col-sm-10 a-body">
			<div class="editable content" data-name="text"{if $sec} id="sections[{$sec.id}][text_data]"{/if}>{$sec.text_data}</div>
	</div>
	<div class="form-group col-sm-2 a-settings">
		<span class="a-row-type">текст</span>
		<div>
			{include file='a-item-setting-buttons.tpl'}							
		</div>
	</div>
</div>