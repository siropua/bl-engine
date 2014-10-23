$(function(){
	$('li.has-sub a').on('click', function  () {
		var $li = $(this).closest('li');
		$li.toggleClass('opened');
		$('ul', $li).toggleClass('visible');
	})
})