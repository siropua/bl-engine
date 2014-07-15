<h3>{$post.title} <small>в виде HTML</small></h3>

<a href="{$_M.url}" class="btn btn-primary">К списку постов</a>
<p></p>
<textarea class="form-control" name="" id="" cols="30" rows="10" style="width: 100%; height: 500px;">{if $post.preview}{$post.preview}&lt;br&gt;{/if}{if $post.mainpic_filename}
&lt;img src="http://{$smarty.server.HTTP_HOST}{$post.res_url}{$post.mainpic_filename}"&gt;
{/if}

{$post.text|htmlspecialchars}

{if $post.attached_pics > 1}
{foreach from=$post.pics item=p}{if $p.filename != $post.mainpic_filename}
{if strip_tags($p.text)}{$p.text|htmlspecialchars}{* &lt;br&gt; *}{/if}
	&lt;img src="http://{$smarty.server.HTTP_HOST}{$post.res_url}{$p.filename}"&gt;
	&lt;br&gt;&lt;br&gt;
{/if}
{/foreach}
{/if}

{if $post.resume}{$post.resume|escape:html}&lt;br&gt;{/if}

<div>Оригинал записи находится на сайте <a href="http://{$smarty.server.HTTP_HOST}{$post.post_url}">{$smarty.server.HTTP_HOST} / {$post.title|htmlspecialchars}</a></div>
</textarea>