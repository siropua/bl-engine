<!doctype html>  
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ --> 
<!--[if lt IE 7 ]> <html class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js"> <!--<![endif]-->
<head>
  {include file='head_data.tpl'}
</head>
<body lang="{$_APP->getCurLang()}">
<div class="container">

	<header>
		<h1><a href="{$ROOT}">{$smarty.server.HTTP_HOST}</a></h1>
		{if $_APPMSG_ERROR}<div class="errorMsg alert alert-error">{$_APPMSG_ERROR}</div>{/if}
		{if $_APPMSG_NOTICE}<div class="noticeMsgalert alert-info">{$_APPMSG_NOTICE}</div>{/if}
		{if $_APPMSG_OK}<div class="okMsg alert alert-success">{$_APPMSG_OK}</div>{/if}
	</header>

	<section id="content">
	{include file=$_APPPAGE_TEMPLATE}
	</section>

	<footer>
	&copy; {date('Y')} {$smarty.server.HTTP_HOST}
	<br/>
	<a href="{$ROOT}rss/">RSS</a> | 
	{if $user}<a href="{$ROOT}logout?key={md5($user.datereg)}">Выход</a>{else}<a href="{$ROOT}login/">Вход</a>{/if}
	</footer>
</div>




  <!--[if lt IE 7 ]>
    <script src="{$ROOT}js/dd_belatedpng.js"></script>
    <script>DD_belatedPNG.fix('img, .png_bg');   </script>
  <![endif]-->
  
  {$_APP->getSetting('google_analytics_code')}
  
</body>
</html>