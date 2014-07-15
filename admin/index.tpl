<!doctype html>  
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ --> 
<!--[if lt IE 7 ]> <html class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js"> <!--<![endif]-->
<head>
  <meta http-equiv="content-Type" content="text/html; charset=UTF-8">

{if $isIOS}
<meta name="apple-mobile-web-app-capable" content="yes" />
{/if}

{if $isIPad}
 	<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0, maximum-scale=1" />
{else}
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
{/if}

<title>{if $page_title}{$page_title}{else}{#site_title#}{/if}</title>

{if $smarty.const.FAVICON_FILE_TYPE}
	<link rel="shortcut icon" type="image/png" href="{$ROOT}favicon.{$smarty.const.FAVICON_FILE_TYPE}" />
{/if}
{if $smarty.const.HAS_APPLE_TOUCH_ICON}
<link rel="apple-touch-icon" href="http://{$smarty.server.HTTP_HOST}/apple-touch-icon.png" />
{/if}

    <style type="text/css">
     
    </style>


{foreach from=$cssFiles item=file}
<link rel="stylesheet" href="{$file}?v={$smarty.const.STATIC_VERSION}">
{/foreach}

<style type="text/css">
.sidebar-nav {
        padding: 9px 0;
      }
</style>

{if $isIPad}
 <link rel="stylesheet" media="screen and (orientation:portrait)" href="ipad-portrait.css">
 <link rel="stylesheet" media="screen and (orientation:landscape)" href="ipad-landscape.css"> 
 <link rel="apple-touch-startup-image" href="{$IMG}ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)" />
 <link rel="apple-touch-startup-image" href="{$IMG}ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)" />
{elseif $isIPhone}
 <link rel="apple-touch-startup-image" href="{$IMG}iphone-startup.png" media="screen and (max-device-width: 320px)" />
{/if}

<script type="text/javascript">
	var rootPath = '{$ROOT}';
	var IMG = '{$IMG}';
	var jsPath = '{$ROOT}js/';
	var AJAXPath = '{$ROOT}ajax/';
	var JSONPath = '{$ROOT}json/';
	var LOGIN_PREG = {$smarty.const.LOGIN_PREG};
	var ROOT_LANG = '{$ROOT_LANG}';
	var curLang = '{$_APP->lang->getCurLang()}';
	{if $_M}
	var moduleAJAX = '{$_M.ajax_url}';
	var moduleJSON = '{$_M.json_url}';
	{/if}
	var pageLoading = 'Загрузка...';

	function NoElasticScroll(e){
  		//e.preventDefault(); 
	}


</script>

<style type="text/css">
#contentwrapper{
	float: left;
	width: 100%;
}

#contentcolumn{
	margin-left: 200px; /*Set left margin to LeftColumnWidth*/
	margin-right: 10px;
}

#leftcolumn{
	float: left;
	width: 200px; 
	margin-left: -100%;
	font-size: .9em;
}


.innertube{
	margin: 10px; 
	margin-top: 0;
}

#adminLeftMenu{
	list-style: none;
	padding: 0;
	margin: 0;
}

#adminLeftMenu li{
	padding: 0 2px;
	margin: 0;
}
#adminLeftMenu li.alm-header{
	font-weight: bold;
	padding-top: 5px;
}

#adminLeftMenu li a{
	text-decoration: none;
	display: block;
	padding: 2px 2px 2px 10px;
}


#adminLeftMenu li a:hover{
	background: #ddd;
	border-radius: 2px;
}

</style>

{foreach from=$_APP_HEAD_RESOURCES item=data}
{if $data.type == 'css'}
	<link rel="stylesheet" href="{$data.key}{if !$data.data}?v={$smarty.const.STATIC_VERSION}{/if}">
{elseif $data.type == 'js'}
	<script type="text/javascript" src="{$data.key}{if !$data.data}?v={$smarty.const.STATIC_VERSION}{/if}"></script>
{else}
	<!-- UNKNOWN RESOURCE {$data.type} -->
{/if}
{/foreach}


{$_APP->getSetting('admin_site_head_area')}


</head>

<body lang="{$_APP->getCurLang()}">


	<nav class="navbar navbar-default" role="navigation">
		
		<a class="btn btn-default navbar-btn" href="{$ROOT}">{$smarty.server.HTTP_HOST}</a> 
		<i class="icon-chevron-right"></i>
		<a href="{$ADMIN_URL}" class="btn btn-default navbar-btn">Admin</a>
		{if $_BREADCRUMBS}
		<i class="icon-chevron-right"></i>
		{foreach from=$_BREADCRUMBS item=b name=bc}
			<a href="{$b.link|default:'#'}" {if !$b.link} disabled="disabled"{/if} class="btn btn-default navbar-btn">{$b.title}</a>
			{if !$smarty.foreach.bc.last}
				<i class="icon-chevron-right"></i>
			{/if}
		{/foreach}
		
		{/if}

		{*

		<ul class="nav navbar-nav">
			<li><p class="navbar-text"><i class="icon-arrow-right"></i></p></li>
			 <li><a href="{$ADMIN_URL}" class="navbar-link">Admin</a></li> {if $_BREADCRUMBS}
			 <li><p class="navbar-text"><i class="icon-arrow-right"></i></p></li>
		{foreach from=$_BREADCRUMBS item=b name=bc}
			<li>
			{if $b.link}
			<a href="{$b.link}" class="navbar-link">{$b.title}</a> 
			{else} 
				<p class="navbar-text">{$b.title}</p>
			{/if}
			{if !$smarty.foreach.bc.last}
				
				<p class="navbar-text"><i class="icon-arrow-right"></i></p>
			{/if}
			</li>
		{/foreach}
		
		{/if}
		</ul>
		*}

		
		<p class="navbar-text pull-right">
              <a href="#" class="navbar-link">{$user.full_name}</a> 
              <a href="{$ROOT}logout?key={md5($user.datereg)}" class="navbar-link icon-remove" title="Выйти"></a>
        </p>
      
    </nav>
		
		
    	


<div id="contentwrapper">
	<div id="contentcolumn">
		<div class="innertube">

		{if $_M.tabs}
			<ul class="nav nav-tabs">
			{foreach from=$_M.tabs item=t key=t_url}
			<li{if $t.active} class="active"{/if}><a href="{$_M.url}{$t_url}/">{if $t.icon_name}<i class="icon-{$t.icon_name}"></i> {elseif $t.icon}<img src="{$_M.res_url}{$t.icon}"> {/if}{$t.name}</a></li>
			{/foreach}
			</ul>
		{/if}

		{if $_APPMSG_ERROR}<div class="errorMsg alert alert-error">{$_APPMSG_ERROR}</div>{/if}
		{if $_APPMSG_NOTICE}<div class="noticeMsgalert alert-info">{$_APPMSG_NOTICE}</div>{/if}
		{if $_APPMSG_OK}<div class="okMsg alert alert-success">{$_APPMSG_OK}</div>{/if}


			{include file=$_APPPAGE_TEMPLATE}
				&nbsp;
		</div>
	</div>
</div>

<div id="leftcolumn">
	<div class="innertube" data-spy="affix" data-offset-top="50">

		<div class="well well-sm sidebar-nav" id="adminAffix">
			<ul id="adminLeftMenu">
				{foreach from=$_ADMIN_MENU item=sectionInfo key=sectionURL name=amfe}
				{* if !$smarty.foreach.amfe.first}<li class="divider"></li>{/if *}
				<li class="alm-header"> {$sectionInfo.name}</li>
					{foreach from=$sectionInfo.modules item=moduleInfo key=moduleURL}
						<li class=""><a href="{$moduleInfo.url}"><img src="{$moduleInfo.icon}" width="16" alt="" /> {$moduleInfo.name}</a></li>
					{/foreach}
				{/foreach}
			</ul>
		</div>

	</div>
</div>

<div class="clearfix"></div>

  <footer class="footer-section">
    <div class="italic-text footer">
		<p>
			&copy; {date('Y')} {$smarty.server.HTTP_HOST}
	
			
			{if $user}<a href="">Выход</a>{else}<a href="{$ROOT}login/">Вход</a>{/if}
		</p>
    </div>
  </footer>






  <!--[if lt IE 7 ]>
    <script src="{$ROOT}js/dd_belatedpng.js"></script>
    <script>DD_belatedPNG.fix('img, .png_bg');   </script>
  <![endif]-->
  
  {* $_APP->getSetting('google_analytics_code') *}
  
</body>
</html>