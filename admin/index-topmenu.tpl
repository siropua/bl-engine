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
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }

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
	var curLang = '{$_APP->getCurLang()}';

	function NoElasticScroll(e){
  		//e.preventDefault(); 
	}




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




<script type="text/javascript">
/*
$(function() {
  // Setup drop down menu
  $('.dropdown-toggle').dropdown();
 
  // Fix input element click problem
  $('.dropdown input, .dropdown label').click(function(e) {
    e.stopPropagation();
  });
});
*/
</script>

{$_APP->getSetting('admin_site_head_area')}


</head>

<body lang="{$_APP->getCurLang()}">


	<div class="global-nav navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar pull-left" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="{$ROOT}">{$smarty.server.HTTP_HOST}</a>
        	 <p class="navbar-text pull-right">
              <a href="#" class="navbar-link">{$user.full_name}</a> <a href="{$ROOT}logout?key={md5($user.datereg)}" class="icon-remove icon" title="Выйти"></a>
            </p>
       
             
            <ul class="nav">
            {foreach from=$_ADMIN_MENU item=sectionInfo key=sectionURL name=amfe}
				<li class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" href="#">{if $sectionInfo.icon}<img src="{$sectionInfo.icon}" width="16" height="16" alt="" /> {/if}{$sectionInfo.name} <b class="caret"></b>
					<ul class="dropdown-menu">
					{foreach from=$sectionInfo.modules item=moduleInfo key=moduleURL}
						<li class="">
							<a href="{$moduleInfo.url}/"><img src="{$moduleInfo.icon}" width="16" alt="" /> {$moduleInfo.name}</a>
						</li>
					{/foreach}
					</ul></a>
				</li>
			{/foreach}
            </ul>
         
        </div>
      </div>
    </div>

    	{if $_APPMSG_ERROR}<div class="errorMsg alert alert-error">{$_APPMSG_ERROR}</div>{/if}
		{if $_APPMSG_NOTICE}<div class="noticeMsgalert alert-info">{$_APPMSG_NOTICE}</div>{/if}
		{if $_APPMSG_OK}<div class="okMsg alert alert-success">{$_APPMSG_OK}</div>{/if}


	<div class="container-fluid">

		<div class="row-fluid">

			<div class="span12">

		<!-- ul class="breadcrumb">
		  <li><a href="#">Home</a> <span class="divider">/</span></li>
		  <li><a href="#">Library</a> <span class="divider">/</span></li>
		  <li class="active">Data</li>
		</ul -->

				{include file=$_APPPAGE_TEMPLATE}
				&nbsp;
				
			</div>
		</div>
	
		<hr />
		<footer class="pull-right">
			<p>&copy; {date('Y')} {$smarty.server.HTTP_HOST}
	
			
			{if $user}<a href="">Выход</a>{else}<a href="{$ROOT}login/">Вход</a>{/if}
			</p>
		</footer>
	</div>




  <!--[if lt IE 7 ]>
    <script src="{$ROOT}js/dd_belatedpng.js"></script>
    <script>DD_belatedPNG.fix('img, .png_bg');   </script>
  <![endif]-->
  
  {$_APP->getSetting('google_analytics_code')}
  
</body>
</html>