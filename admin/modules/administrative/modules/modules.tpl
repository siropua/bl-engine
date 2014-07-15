{if $done}
	<script>
		document.location='{$ADMIN_URL}';
	</script>
	<div class="info_panel">
		Recompile successful. 
	</div>
	{if $errors}
		Errors occurred:
	<pre style="background:#ffaaaa;">
	{$errors}
	</pre>;	
	{/if}
{else}
	<form action="{$MODULE_SELF}" method="post">
	<input type="hidden" name="sure" value="1" />
		<button type="submit">Recompile</button>
	</form>
{/if}