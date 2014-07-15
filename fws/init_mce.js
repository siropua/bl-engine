tinyMCE.init({
	mode : "textareas",
	theme : "simple",
	language: 'ru',
	editor_selector: 'edtSimple',
	height: '80',
	cleanup: false,
	convert_urls: false
});

tinyMCE.init({
	mode : "textareas",
	theme : "advanced",
	language: 'ru',
	convert_urls: false,
	plugins : "inlinepopups,style,advhr,advimage,advlink,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,images,wordcount",
	theme_advanced_buttons1 : "cut,copy,paste,pastetext,pasteword,|,bold,italic,underline,strikethrough,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,justifyleft,justifycenter,justifyright,justifyfull,link,unlink,anchor,image,images,cleanup",
	theme_advanced_buttons2 : "forecolor,backcolor,formatselect,fontselect,fontsizeselect,hr,removeformat,visualaid,|,sub,sup,|,charmap,media,|,visualchars,nonbreaking,preview,code,fullscreen",
	theme_advanced_buttons3 : "",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_statusbar_location : "bottom",
	extended_valid_elements : "a[name|href|target|title|onclick|class|rel],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name|style],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style],code[class]",
	editor_selector: 'edtAdv',
	theme_advanced_resizing : true,
	height: '300',
	width: '100%'
	
});

