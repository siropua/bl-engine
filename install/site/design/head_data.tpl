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

{if $page_descr}<meta name="description" content="{$page_descr}">
{/if}
{if $page_kws}<meta name="keywords" content="{$page_kws}">
{/if}

<link title="RSS" type="application/rss+xml" rel="alternate" href="http://{$smarty.server.HTTP_HOST}/rss/" />

{if $smarty.const.FAVICON_FILE_TYPE}
	<link rel="shortcut icon" type="image/png" href="{$ROOT}favicon.{$smarty.const.FAVICON_FILE_TYPE}" />
{/if}
{if $smarty.const.HAS_APPLE_TOUCH_ICON}
<link rel="apple-touch-icon" href="http://{$smarty.server.HTTP_HOST}/apple-touch-icon.png" />
{/if}



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
	<link rel="stylesheet" href="{$data.key}{if !$data.value}?v={$smarty.const.STATIC_VERSION}{/if}">
{elseif $data.type == 'js'}
	<script type="text/javascript" src="{$data.key}{if !$data.value}?v={$smarty.const.STATIC_VERSION}{/if}"></script>
{else}
	<!-- UNKNOWN RESOURCE {$data.type} -->
{/if}
{/foreach}



{$_APP->getSetting('site_head_area')}
