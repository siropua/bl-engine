(function  ($) {

	var defaults = {
		apiURL: '',
		methodAdd: 'add',
		methodRemove: 'remove',
		methodOrder: 'order',
		formData: {},

	};
	var options;

	var methods = {
		init: function  (params) {
			options = $.extend({}, defaults, params);
			console.log('Options:');
			console.log(options);

			return this.each(function(){
				$(this).fileupload({
					dataType: 'json',
					sequentialUploads: true,
					add: function (e, data) {
						data.url = options.apiURL + options.methodAdd;
						data.formData = options.formData;
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
		}
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

	function picsUploadStart () {
		console.log('picsUploadStart');
	}

	function picsAllUploaded () {
		console.log('picsAllUploaded');
	}

	function picUploading () {
		console.log('picUploading');
	}

})(jQuery);