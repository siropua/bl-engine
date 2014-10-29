<!doctype html>  
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ --> 
<!--[if lt IE 7 ]> <html class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html class="no-js ie8"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta http-equiv="content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>{if $page_title}{$page_title}{else}{#site_title#}{/if}</title>



<link rel="stylesheet" href="{$ADMIN_DESIGN_URL}css/main.css">




{foreach from=$cssFiles item=file}
<link rel="stylesheet" href="{$file}?v={$smarty.const.STATIC_VERSION}">
{/foreach}





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


</script>



{foreach from=$_APP_HEAD_RESOURCES item=data}
{if $data.type == 'css'}
	<link rel="stylesheet" href="{$data.key}{if !$data.data}?v={$smarty.const.STATIC_VERSION}{/if}">
{elseif $data.type == 'js'}
	<script type="text/javascript" src="{$data.key}{if !$data.data}?v={$smarty.const.STATIC_VERSION}{/if}"></script>
{else}
	<!-- UNKNOWN RESOURCE {$data.type} -->
{/if}
{/foreach}

<script src="{$ADMIN_DESIGN_URL}js/main.js"></script>


{$_APP->getSetting('admin_site_head_area')}


</head>

<body lang="{$_APP->getCurLang()}">
<div class="admin-container">


<div class="sidebar-menu">
	
	<header class="logo-env">
		<!-- logo -->
		<div class="logo">
			<a href="{$ADMIN_URL}">
				<h1>Admin</h1>
			</a>
		</div>
		
					
		<!-- logo collapse icon -->	
		<div class="sidebar-collapse">
			<a href="#" class="sidebar-collapse-icon with-animation">
				<i class="fa fa-bars"></i>
			</a>
		</div>
		
								
		
		<!-- open/close menu icon  -->
		<div class="sidebar-mobile-menu visible-xs">
			<a href="#" class="with-animation">
				<i class="fa fa-bars"></i>
			</a>
		</div>
		
	</header>



	<ul id="main-menu">
		{foreach from=$_ADMIN_MENU item=sectionInfo key=sectionURL name=amfe}
		<li class="root-level has-sub opened"> <a href="#"><i class="{if $sectionInfo.font_icon}{$sectionInfo.font_icon}{/if}"></i> <span>{$sectionInfo.name}</span></a>
			<ul class="visible">
				{foreach from=$sectionInfo.modules item=moduleInfo key=moduleURL}
					<li class="">
						<a href="{$moduleInfo.url}"><i class="{if $moduleInfo.font_icon}{$moduleInfo.font_icon}{/if}">{if $moduleInfo.icon}<img src="{$moduleInfo.icon}" width="16" alt="" />{/if}</i> 
						<span>{$moduleInfo.name}</span></a>
					</li>
				{/foreach}
			</ul>
		{/foreach}</li>
	</ul>
	<div style="clear: both;"></div>
</div>

<div class="main-content">

	
	<header class="admin-top">
		<ol class="breadcrumb breadcrumb-3 pull-left">
		  <li><a href="{$ADMIN_URL}">Admin</a></li>
			{if $_BREADCRUMBS}
			
				{foreach from=$_BREADCRUMBS item=b name=bc}
					<li {if !$b.link} class="active"{/if}>
						{if $b.link}<a href="{$b.link}">{/if}{$b.title}{if $b.link}</a>{/if}
					</li>
					
				{/foreach}
			
			{/if}	
		</ol>
		<div class="pull-right"><a href="{$ROOT}logout/?key={$user.datereg|md5}">Выход <i class="fa fa-sign-out"></i></a></div>
		<div class="clearfix"></div>
	</header>


	{if $_M.tabs}
			<ul class="nav nav-tabs">
			{foreach from=$_M.tabs item=t key=t_url}
			<li{if $t.active} class="active"{/if}><a href="{$_M.url}{$t_url}/">{if $t.font_icon}<i class="{$t.font_icon}"></i> {elseif $t.icon}<img src="{$_M.res_url}{$t.icon}"> {/if}{$t.name}</a></li>
			{/foreach}
			</ul>
		{/if}

		{if $_APPMSG_ERROR}<div class="errorMsg alert alert-error">{$_APPMSG_ERROR}</div>{/if}
		{if $_APPMSG_NOTICE}<div class="noticeMsgalert alert-info">{$_APPMSG_NOTICE}</div>{/if}
		{if $_APPMSG_OK}<div class="okMsg alert alert-success">{$_APPMSG_OK}</div>{/if}


		{include file=$_APPPAGE_TEMPLATE}
		<div style="clear: both;"></div>
</div>

<div style="clear: both;"></div>

{*

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
		

		
		<p class="navbar-text pull-right">
              <a href="#" class="navbar-link">{$user.full_name}</a> 
              <a href="{$ROOT}logout?key={md5($user.datereg)}" class="navbar-link icon-remove" title="Выйти"></a>
        </p>
      
    </nav>
		
		
    	


<div id="contentwrapper">
	<div id="contentcolumn">
		<div class="innertube">

		
				&nbsp;
		</div>
	</div>
</div>

<div id="leftcolumn">
	<div class="innertube" data-spy="affix" data-offset-top="50">

		<div class="well well-sm sidebar-nav" id="adminAffix">
			
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

*}



</div>

</body>
</html>