<article id="article{$article.id}" class="article">
	<nav class="breadcrumbs"><ul>
		{foreach from=$catalogParents item=u}
			<li><a href="{$ROOT}{$u.url}/">{$u.title}</a></li>
		{/foreach}
	</ul></nav>
	<h1>{$article.title}</h1>
	<div class="content">{$article.text}</div>
</article>