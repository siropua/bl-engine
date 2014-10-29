(function  ($) {

	var defaults = {
		apiURL: '',
		
		methodAdd: 'add',
		methodRemove: 'remove',
		methodOrder: 'order',
		methodAsmain: 'asmain',
		methodGettext: 'gettext',
		methodSettext: 'settext',
		methodPics: 'getpics',

		formData: {},
		pageID: 0,
	};
	var options;

	var methods = {
		init: function  (params) {
			options = $.extend({}, defaults, params);

			return this.each(function(){
				$(this).fileupload({
					dataType: 'json',
					sequentialUploads: true,
					add: function (e, data) {
						data.url = options.apiURL + options.methodAdd;
						data.formData = options.formData;
						data.formData.pageID = options.pageID;
						data.submit();
					},
					submit: function (e, data) {
					},
					send: function (e, data) {
					},
					change: function (e, data) {
					},
					disableImageResize: false,
					imageMaxWidth: 800,
					imageMaxHeight: 800,
					imageCrop: true // Force cropped images
				})
				.bind('fileuploadstart', picsUploadStart)
				.bind('fileuploadstop', picsAllUploaded)
				.bind('fileuploadprogressall', picUploading);
			});




			$(this).on('click', '.btn-delete-pic', deletePic); 
			$(this).on('click', '.btn-set-as-main', setAsMainPic);
			$(this).on('click', '.load-secpic-url', loadPicFromURL);
			$(this).on('click', '#gallery .photo-description', changePhotoDescription);
			$(this).on('click', '#gallery .photo-description', changePhotoDescription);
			$('#gallery', this).sortable({
				items: '.gallery-elem',
		//		placeholder: 'sortable-placeholder',
				update: function(e, u){ updatePicSort(); }
			});
		},

	};
	
	$.fn.rGallery = function  (method) {
		if ( methods[method] ) {
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Метод с именем ' +  method + ' не существует для jQuery.rGallery' );
		}    
	}

	function getPageID () {
		return options.pageID;
	}

	function picsUploadStart () {
		$('#modal-load').modal('show');
	}

	function picsAllUploaded () {
		reloadPics();
	}

	function picUploading (data) {
		var bitRateInfo = 'Progress:&nbsp;';
		if(typeof data.total != 'undefined' && data.total > 0){
			// show_loading_bar({
			// 	pct: Math.round(data.loaded / data.total * 100),
			// 	delay: 0.5
			// });
		}
		if(typeof data.total != 'undefined' && data.total > 0){
			if(data.total/1000 > 2000000){
				bitRateInfo = ' '+Math.round(data.total/10000)/100+'&nbsp;Gb.';
			}else if(data.total/1000 > 20000){
				bitRateInfo = ' '+Math.round(data.total/1000000)+'&nbsp;Mb.';
			}else if(data.total/1000 > 2000){
				bitRateInfo = ' '+Math.round(data.total/100000)/10+'&nbsp;Mb.';
			}else if(data.total < 10000){
				bitRateInfo = ' '+(Math.round(data.total/10)/100)+'&nbsp;Kb.';
			}else{
				bitRateInfo = ' '+Math.round(data.total/1000)+'&nbsp;Kb';
			}
		}else{
			bitRateInfo = 'Loading...';
		}
		if(typeof data.bitrate != 'undefined' && data.bitrate > 0){
			if(data.bitrate/1000 > 2000){
				bitRateInfo = bitRateInfo +' ('+Math.round(data.bitrate/100000)/10+'&nbsp;Mb/s)';
			}else if(data.bitrate < 10000){
				bitRateInfo = bitRateInfo +' ('+(Math.round(data.bitrate/10)/100)+'&nbsp;Kb/s)';
			}else{
				bitRateInfo = bitRateInfo +' ('+Math.round(data.bitrate/1000)+'&nbsp;Kb/s)';
			}
		}
		$('#bitrate-info').html(bitRateInfo);
	}


	function deletePic(){
		if(!confirm('Удалить картинку?')) return false;
		var el = $(this).closest('[data-id]').css({opacity: .5});
		var id = el.data('id');

		$.post(JSONPath + getControllerName() + '/removepic', {id: id, pageID: getPageID(), hotelID: getHotelID()}, function(data){
			try{
				if(data.data == 'OK'){
					var t = new TimelineLite({
						onComplete: function(){
							el.slideUp(function(){
								el.remove();
								$('.gallery-env').find('.clear').remove()
								$('.gallery-env div:nth-child(6n)').after('<div class="clear"></div>');
								var b = $('.btn-delete-pic');
								b.css('display', 'block');
								if (b.length > 1) b.eq(0).css('display', 'none');
							});
						}
					});
					el.addClass('no-animation');
					t.append( TweenMax.to(el, .2, {css: {scale: 0.95}}) );
					t.append( TweenMax.to(el, .5, {css: {autoAlpha: 0, transform: "translateX(100px) scale(.95)"}}) );
				}else{
					alert('Не удалось удалить картинку');
				}
			}catch(e){
				alert('Ошибка на сервере!');
			}
		});
	}

	function setAsMainPic(){
		var $btn = $(this);
		var $li = $btn.closest('li');
		var id = $li.data('id');
		var $lastMain = $('#gallery .label-main').closest('li');

		$li.css({opacity: .5});
		$.post(options.apiURL + options.methodAsmain, {id: id, objectID: getPageID()}, function(data){
			try{
				//var d = $.parseJSON(data);
				if(data.data == 'OK'){
					$li.css({opacity: 1});
					$btn.prependTo($('.buttons .btn-group', $lastMain));
					$('.label-main', $lastMain).prependTo($('.text', $li));
				}else{
					alert('Не удалось поменять главную картинку');
					console.log(data);
				}
			}catch(e){
				alert('Ошибка на сервере!');
				console.log(data);
			}
		});
	}

	function updatePicSort(){
		$('#gallery').sortable('disable');
		var l = [];
		$('.gallery-elem').each(function(){
			l.push($(this).data('id'));
		});
		$.post(options.apiURL + options.methodOrder, {pageID: getPageID(), pics: l.join(',')}, function(data){
			$('.gallery-env').find('.clear').remove()
			$('.gallery-env div:nth-child(6n)').after('<div class="clear"></div>');
			var b = $('.btn-delete-pic');
			b.css('display', 'block');
			$('#gallery').sortable('enable');
		});
	}


	function changePhotoDescription(){
		var id = $(this).closest('li').data('id');

		$.getJSON(options.apiURL + options.methodGettext + '?id=' + id, function(data){
			if(data.status == 200 && (typeof data.data.pic != 'undefined')){

				$('#picDescrID').val(data.data.pic.id);
				tinymce.get('picDescr').setContent(data.data.pic.text);

				$('#editPicDescr .photoRow').html('<img src="'+data.data.res_url+''+data.data.pic.filename+'">')
				$('#editPicDescr').modal('show');
			}
			console.log(data);
			
		});	
	}


	function savePicDescr(){
		var id = $('#picDescrID').val();
		$.post(options.apiURL + options.methodSettext +  '?id=' + id, 
			{text: tinymce.get('picDescr').getContent()}, 
			function(data){
				var text = tinymce.get('picDescr').getContent({format: 'text'});

				$('#gallery li[data-id='+id+'] .photo-description')
					.removeClass('no-text')
					.html(text.trunc(150, true));

				$('#editPicDescr').modal('hide');
			}
		);
	}

	function pictureUploaded(e, data) {
		if(typeof data.result == 'undefined'){
			alert('Result is undefined!');
			return false;
		}
		if(data.result.status != 200){
			alert('Fail to upload!');
			return false;
		}
		var d = data.result.data;
		if(typeof d.pic == 'undefined'){
			alert('Cant upload a pic!');
			return false;
		}
		var $img = $('<img>').data('id', d.pic.id).addClass('photo').attr('src', getResURL() + 'thumb-' + d.pic.basename);
		var $li = $('<li>');
		$li.append($img);
		$picsContainer = $('#gallery');
		if(!$picsContainer.find('ul')){
			$picsContainer.html('<ul></ul>');
		}
		$('ul', $picsContainer).append($li);
	}

	function reloadPics(){
		$('#bitrate-info').html('Reload images ...');
		$('#gallery').load(options.apiURL + options.methodPics + '?id=' + getPageID(), function(){
			$('#gallery').sortable('enable');
			$('#modal-load').modal('hide');
			$('#bitrate-info').html('');
		});
	}

	function loadPicFromURL(){
		var re = /^(http|https):\/\/.+/;
		var $url =  $('#secpicURL').val();
		$('.load-secpic.validate-has-error').removeClass('validate-has-error');
		if (!re.test($url)){
			$('#secpicURL').closest('div').addClass('validate-has-error');
			return false;
		}
		$('#bitrate-info').html('Loading...');
		$('#modal-load').modal('show');
		$.post(JSONPath + getControllerName() + '/loadpic', {pageID: getPageID(), hotelID: getHotelID(), typeID: getTypeID(), secpicURL: $url}, function(data){
			if (data.data == 'OK'){
				$('#modal-load').modal('hide');
				$('#bitrate-info').html('');
				$('#secpicURL').val('');
				reloadPics();
			}else{
				$('#bitrate-info').html('<span style="color:red;">ERROR! Can\'t load photo.</span>');
				setTimeout(function(){
					$('#modal-load').modal('hide');
				}, 3000);
			}
		});
	}



})(jQuery);