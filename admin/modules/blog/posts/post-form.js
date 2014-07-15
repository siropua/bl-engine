String.prototype.trunc =
     function(n,useWordBoundary){
         var toLong = this.length>n,
             s_ = toLong ? this.substr(0,n-1) : this;
         s_ = useWordBoundary && toLong ? s_.substr(0,s_.lastIndexOf(' ')) : s_;
         return  toLong ? s_ + '&hellip;' : s_;
      };

var postChanged = false;
var postMonitorer;
var postUpdatedMonI;

$(function(){
	tinymce.init({
		selector: "#fPostText",
		setup : function(ed) {
              ed.on('change', function(ed, e) {
                  postUpdated();
              });
           },
        language : 'ru',
		plugins: [
						"advlist autolink lists link image charmap print preview anchor",
						"searchreplace visualblocks code fullscreen",
						//"insertdatetime media table contextmenu paste moxiemanager"
					],
		toolbar: "undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
	});

	tinymce.init({
		selector: ".edtSimple",
		setup : function(ed) {
              ed.on('change', function(ed, e) {
                  postUpdated();
              });
           },
			statusbar: false,
			
	        language : 'ru',
			plugins: [
							"advlist autolink lists link image charmap print preview anchor",
							"searchreplace visualblocks code fullscreen",
							//"insertdatetime media table contextmenu paste moxiemanager"
						],
			menubar : false,
			toolbar: "undo redo | styleselect | bold italic | charmap | anchor | code | link"
	});

	

	$('#saveButton').disable();

	$("#submitButton").on('click', publishPost);

	$(document).ajaxError(ajaxError);

	postMonitorer = setInterval(checkPostStatus, 10000);
	$('.changed-flag').on('keyup', postUpdatedMon);
	$('#fMainpic').on('change', postUpdatedMon);
	$('.cancel-edit').on('click', cancelEdit);

	$('.blogPostForm').fileupload({
        dataType: 'json',
        sequentialUploads: true,
        add: function (e, data) {
            
			data.url = moduleJSON + 'post/attach';
			var id = getPostID();

			if(!id){
				console.log('Creating post to attach pic!');
	        	$.ajax({
	        		url: moduleJSON + 'post/save',
	        		type: 'post',
	        		async: false,
	        		//dataType: 'json',
	        		data: data.form.serialize(),
	        		complete: function (result) {

	            		if(typeof result.responseJSON.data.post != 'undefined'){
	            			// пост создан, всё ок
	            			
	            			postCreated(result.responseJSON.data.post)
	            			
	            			data.formData = {
	            				postID: result.responseJSON.data.post.id
	            			};
	            			
	            			data.submit();
	            		}else {
	            			console.log('CANT CREATE POST!');
	            		}
	        		}
	        	});
	        	
        	}else{
        		// post already created. just attach
        		console.log('Post already created. Attaching to ' + id)
        		data.formData = { postID: id };
        		data.submit();
        	}

  
        },
        
        submit: function (e, data) {
        },
        send: function (e, data) {
        },
        change: function (e, data) {		
        	
        	//setPostID('----');
        },
        done: pictureUploaded,
        disableImageResize: false,
    	imageMaxWidth: 800,
    	imageMaxHeight: 800,
    	imageCrop: true // Force cropped images

    })
 	.bind('fileuploadstart', picsUploadStart)
 	.bind('fileuploadstop', picsAllUploaded)
 	.bind('fileuploadprogressall', picUploading);

 	$('.reload-pic-button').css({cursor: 'pointer'}).on('click', reloadPics);
 	$(document).on('click', '.btn-delete-pic', deletePic);
 	$(document).on('click', '.btn-set-as-main', setAsMainPic);
 	$(document).on('click', '#postPictures .photo-description', changePhotoDescription);


 	var $d = $('.date-pick');
	if($d.length)$d.pickmeup({
		
		hide_on_select	: true,
		before_show		: function () {
			var $this	= $(this);
			$this.pickmeup('set_date', $this.val());
		},
		change			: function (formatted) {
			$(this).val(formatted);
		},
		format  : 'Y-m-d'
	});

	activateSortable();


	tinymce.init({
			selector: "#picDescr",
			statusbar: false,
			
	        language : 'ru',
			plugins: [
							"advlist autolink lists link image charmap print preview anchor",
							"searchreplace visualblocks code fullscreen",
							//"insertdatetime media table contextmenu paste moxiemanager"
						],
			menubar : false,
			toolbar: "undo redo | styleselect | bold italic | charmap | anchor | code | link"
		});

	$('#editPicDescr').on('shown.bs.modal', function(){
		tinymce.execCommand('mceFocus', true, 'picDescr');
	});

	$(document).on('focusin', function (e) {
	    if ($(event.target).closest(".mce-window").length) {
	        e.stopImmediatePropagation();
	    }
    });

    $('#editPicDescr .savePicDescr').on('click', savePicDescr);

    $('div.post-mode input').on('change', changePostMode);

    $(document).on('click', 'button.btn-move-pic-up', movePicUp);
    $(document).on('click', 'button.btn-move-pic-down', movePicDown);

});


function activateSortable(){
	$('.sortable').sortable().bind('sortupdate', updatePicSort);
}


function postCreated(post){
	setPostID(post.id);
	$('#res-url').val(post.res_url);
	console.log('Post created, ID: ' + post.id);
	console.log('Post resource url: ' + post.res_url);
}

function postUpdatedMon(){
	clearTimeout(postUpdatedMonI);
	postUpdatedMonI = setTimeout(postUpdated, 3000);
}


function postUpdated (e) {
	console.log('post changed!');
	$('#saveButton').enable();
	postChanged = true;
	if(getPostID() == ''){
		createPost();
	}else if(getPostID() == 'creating'){

	}
}


function getPostID() {
	return $('input[name=postID]').val();
}

function createPost() {
	$('input[name="postID"]').val('creating');

	console.log('Creating post...');
	
	$.post(moduleJSON + 'post/save', getPostData(), function (data) {
		if(postChanged) $('#saveButton').enable();
		if(data.status == 200){
			data = data.data;
			if(data.ok){
				
				postCreated(data.post);
			}
		}
		
	});
	postChanged = false;
}

function ajaxError () {
	console.log('error');
}

function checkPostStatus () {
	console.log('checkPostStatus!');
	if(postChanged) savePost();
}

function savePost () {	
	console.log('Saving post...');
	$('#saveButton').disable();

	
	$.post(moduleAJAX + 'post/save', getPostData(), function (data) {
		if(postChanged) $('#saveButton').enable();
		console.log(data);
	});
	postChanged = false;
}

function getPostData () {
	tinymce.get("fPostText").save();
	var f = $('form#blogPostForm').serialize();

	return f;
}

function setPostID(id){
	console.log('Setted post ID: ' + id);
	$('input[name=postID]').val(id);
}

function getResURL(){
	return $('#res-url').val();
}


function pictureUploaded(e, data) {

	//console.log('DONE:');
    //console.log(data);
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

    

    //console.log('pic uploaded!');

    //console.log(d);

    var $img = $('<img>').attr('src', getResURL() + 't-' + d.pic.basename);
    var $li = $('<li>');
    $li.append($img);

    $picsContainer = $('#postPictures .pics');

    if(!$picsContainer.find('ul')){
    	// первая картинка
    	$picsContainer.html('<ul></ul>');
    }

    $('ul', $picsContainer).append($li);

}

function picsAllUploaded(e, data){
	reloadPics();
}

function	picsUploadStart(e, data){
	$('#postPictures .pics').prepend('<div class="pics-loading"><p>загрузка картинок<span></span>...</p><div class="progress progress-striped active"><div class="progress-bar"  role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div></div>');
	console.log(data);
}

var bitRateInfo = '';
function picUploading(e, data){
	console.log(data);
	$('#postPictures .progress .progress-bar').css({width: Math.round(data.loaded / data.total * 100)+'%'});

	if((typeof data.total != 'undefined' && data.total > 0)){
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
			bitRateInfo = '';
	}

	if((typeof data.bitrate != 'undefined' && data.bitrate > 0)){
		if(data.bitrate/1000 > 2000){
			bitRateInfo = bitRateInfo +' ('+Math.round(data.bitrate/100000)/10+'&nbsp;Mb/s)';
		}else if(data.bitrate < 10000){
			bitRateInfo = bitRateInfo +' ('+(Math.round(data.bitrate/10)/100)+'&nbsp;Kb/s)';
		}else{
			bitRateInfo = bitRateInfo +' ('+Math.round(data.bitrate/1000)+'&nbsp;Kb/s)';
		}
	}else{
		//bitRateInfo = '';
	}


	$('.pics-loading span').html(bitRateInfo);
}


function publishPost(e){
	$(this).disable();


	$('#fTodo').val('publish');

	$('#blogPostForm').submit();
}


function cancelEdit(){
	$(this).disable();
	var url = $(this).data('url');
	
	if($('input[name=is_new]').val() == 1){
		var id = parseInt(getPostID());
		if(id){
			$.post(moduleJSON + 'post/delete', {id:id}, function(data){
				document.location = url;
			});
			return false;
		}
	}
	document.location = url;
}

function reloadPics(){
	$('.sortable').sortable('destroy');
	$('#postPictures .btn').addClass('disabled');
	$('#postPictures .progress .progress-bar').addClass('progress-bar-success');
	$('#postPictures .reload-pic-button').addClass('rotation');
	$('#postPictures .pics').prepend('<div class="loading-pics">Обновление картинок...</div>');
	$('#postPictures .pics').load(moduleAJAX + 'pics/get/' + getPostID(), function(){
		$('#postPictures .reload-pic-button').removeClass('rotation');
		$('#postPictures .btn').removeClass('disabled');
		activateSortable();
	});
}


function deletePic(){
	if(!confirm('Удалить картинку?')) return false;
	var $btn = $(this);
	var $li = $btn.closest('li');
	var id = $li.data('id');

	$li.css({opacity: .5});
	$.post(moduleJSON + 'pics/remove', {id: id, postID: getPostID()}, function(data){
		try{
			//var d = $.parseJSON(data);
			if(data.data == 'OK'){
				$li.fadeOut();
			}else{
				alert('Не удалось удалить картинку');
				console.log(data);
			}
		}catch(e){
			alert('Ошибка на сервере!');
			console.log(data);
		}
	});
}

function setAsMainPic(){
	var $btn = $(this);
	var $li = $btn.closest('li');
	var id = $li.data('id');
	var $lastMain = $('#postPictures .label-main').closest('li');

	$li.css({opacity: .5});
	$.post(moduleJSON + 'pics/asmain', {id: id, postID: getPostID()}, function(data){
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
	$('.sortable').sortable('disable');

	var l = [];
	$('li', $('#postPictures .sortable')).each(function(){
		l.push($(this).data('id'));
	});
	l = l.join(',');

	$.post(moduleJSON + 'pics/order', {postID: getPostID(), pics: l}, function(data){
		console.log(data);
		$('.sortable').sortable('enable');
	});

}


function changePhotoDescription(){
	var id = $(this).closest('li').data('id');

	$.getJSON(moduleJSON + 'pics/gettext?id=' + id, function(data){
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
	$.post(moduleJSON + 'pics/settext?id=' + id, 
		{text: tinymce.get('picDescr').getContent()}, 
		function(data){
			var text = tinymce.get('picDescr').getContent({format: 'text'});

			$('#postPictures li[data-id='+id+'] .photo-description')
				.removeClass('no-text')
				.html(text.trunc(150, true));

			$('#editPicDescr').modal('hide');
		}
	);
}


function changePostMode (inp) {
	var newPostMode = $('input[name=post_mode]:checked').val();
	if(!newPostMode) return;
	console.log($('#blogPostForm').data('mode'));

	$('#blogPostForm').attr('data-mode', newPostMode); // because .data dont change dom

	if(newPostMode == 'photo'){
		// PHOTO
		$('#postPictures').attr('class', 'col-lg-8 col-md-12');
		$('#postTexts').attr('class', 'col-lg-4 col-md-12');
		$('#postPictures .sortable li').attr('class', 'col-lg-6 col-md-12');

	}else{
		$('#postPictures').attr('class', 'col-lg-4 col-md-12');
		$('#postTexts').attr('class', 'col-lg-8 col-md-12');
		$('#postPictures .sortable li').attr('class', '');

	}
}


function movePicUp () {
	var $li = $(this).closest('li');
	if($li.prev().length){
		$li.insertBefore($li.prev());
		updatePicSort();
	}
}

function movePicDown () {
	var $li = $(this).closest('li');
	if($li.next().length){
		$li.insertAfter($li.next());
		updatePicSort();
	}
}