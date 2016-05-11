<article id="article{$article.id}" class="article">
	<nav class="breadcrumbs"><ul>
		{foreach from=$catalogParents item=u}
			<li><a href="{$ROOT}{$u.url}/">{$u.title}</a></li>
		{/foreach}
	</ul></nav>
	<h1>{$catalogItem.title}</h1>
	<div class="content">
		{if $articles}<h3>Список статей:</h3>
		<ul>
			{foreach from=$articles item=a}
				<li><a href="{$ROOT}{$a.url}">{$a.title}</a></li>
			{/foreach}
		</ul>
		{/if}
		{if $catalogChilds}<h3>Разделы:</h3>
		<ul>
			{foreach from=$catalogChilds item=c}
				{if $c.level == $catalogItem.level+1 && $c.is_visible == 1}<li><a href="{$ROOT}{$c.url}">{$c.title}</a></li>{/if}
			{/foreach}
		</ul>{/if}
	</div>
</article>