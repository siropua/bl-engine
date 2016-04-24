String.prototype.trunc =
     function(n,useWordBoundary){
         var toLong = this.length>n,
             s_ = toLong ? this.substr(0,n-1) : this;
         s_ = useWordBoundary && toLong ? s_.substr(0,s_.lastIndexOf(' ')) : s_;
         return  toLong ? s_ + '&hellip;' : s_;
      };

var articleChanged = false;
var postMonitorer;
var postUpdatedMonI;
var nextID = 100;

$(function(){
	
	$('.add-item-text').on('click', loadTextItem);	
	$('#saveButton').on('click', save);
	$('.btn-action-delete').on('click', deleteSection)
	$('#content-form').on('submit', function () {
		tinyMCE.execCommand("mceCleanup");
		tinyMCE.triggerSave();
		tinyMCE.execCommand("mceCleanup");
		tinyMCE.triggerSave();
		return true;
	});

	initItemEditor('.a-items .editable', false);

	$('#content-form').fileupload({
        dataType: 'json',
        sequentialUploads: false,
        add: function (e, fileForm) {
        	$.post(moduleJSON + 'section/new', {type: 'image', article_id: articleID}, function(data)
			{
				if(typeof data.data.id == 'undefined') return false;
				var id = data.data.id;
				var $imageItem = $('#templates .a-item-image').clone()
					.data('id', id)
					.attr('id', 'item'+id)
					.appendTo('.a-items');

				var reader = new FileReader();            
	            reader.onload = function (e) {
	                $('<img>').attr('src', e.target.result).appendTo($imageItem.find('.image'));
	            }
	            
	            reader.readAsDataURL(fileForm.files[0]);


				$('.content-pre', $imageItem).attr('id', 'sections['+id+'][text_data]');
				$('.content-post', $imageItem).attr('id', 'sections['+id+'][text_data1]');
				initItemEditor('#item'+id+' .editable', false);

				fileForm.url = moduleJSON + 'section/image';
			
	    		fileForm.formData = { id: id };
	    		fileForm.submit(); 
			});
        },
        
        submit: function (e, data) {
        },
        send: function (e, data) {
        },
        change: function (e, data) {		
        	
        	
        },
        done: pictureUploaded,
        disableImageResize: false,
    	imageMaxWidth: 800,
    	imageMaxHeight: 800,
    	imageCrop: true, // Force cropped images
    	previewMaxWidth: 100,
        previewMaxHeight: 100,
        previewCrop: true

    })
 	.bind('fileuploadstart', picsUploadStart)
 	.bind('fileuploadstop', picsAllUploaded)
 	.bind('fileuploadprogressall', picUploading)
 	.on('fileuploadprocessalways', function (e, data) {
 		console.log('fileuploadprocessalways');
    });

 

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

});

function deleteSection() {
	if(!confirm('Удалить секцию и всё её содержимое?\nЭто невозможно будет отменить!')) return false;
	var $item = $(this).closest('.a-item');
	$.post(moduleJSON + 'section/delete', {id: $item.data('id')}, function(data) {
		if(typeof data.data != 'undefined' && data.data == 'OK')
			$item.fadeOut();
	})
}

function loadTextItem() {
	createSection('text', addItemText);
}

function addItemText(data) {
	var id = data.id;

	$('#templates .a-item-text').clone().data('id', id).attr('id', 'item'+id).appendTo('.a-items').find('.editable').attr('id', 'sections['+id+'][text_data]');

	initItemEditor('#item'+id+' .editable', true);
}

function initItemEditor(selector, focus) {
	tinymce.init({
	  selector: selector,
	  inline: true,
	  plugins: [
	    'advlist autolink lists link image charmap print preview anchor',
	    'searchreplace visualblocks code fullscreen',
	    'insertdatetime media table contextmenu paste'
	  ],
	  setup : function(ed) {
          ed.on('change', function(ed, e) {
              postUpdated();
          });
          ed.on('init', function(ed) {
	        if(focus)ed.target.focus();
	      });
          //console.log(ed);
       },
	  language : 'ru',
	  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image'
	});	
}

function postUpdated() {
	tinymce.triggerSave();
}


function createSection(name, callback) {
	$.post(moduleJSON + 'section/new', {type: name, article_id: articleID}, function(data)
	{
		if(typeof data.data.id == 'undefined')
		{
			alert('Ошибка создания секции');
			return false;
		}
		callback(data.data);
	});
}

function save() {
	var saveData = {
		sections: {},
	};
	$items = $('.a-items .a-item');
	$items.each(function (item) {
		var contents = $('.content', this);
		if(contents.length < 1) return;
		var dataItem = {};
		for(var i = 0; i <= contents.length; i++)
		{
			if(typeof contents[i] != 'object') continue;
			dataItem[$(contents[i]).data('name')] = $(contents[i]).html();
		}
		saveData['sections'][$(this).data('id')] = dataItem;
	});

	// $.post(moduleJSON + 'article/save', )

	console.log(saveData);
}





function pictureUploaded(e, data) {

	//console.log('DONE:');
    console.log(data);
    if(typeof data.result == 'undefined'){
    	alert('Result is undefined!');
    	return false;
    }

    if(data.result.status != 200){
    	alert('Fail to upload!');
    	return false;
    }

    var d = data.result.data;

    if(typeof d.file == 'undefined'){
    	alert('Cant upload a pic!');
    	return false;
    }

    var $img = $('<img>').attr('src', d.url + d.file);
    $('#item' + d.id).find('.image').html($img);

}

function picsAllUploaded(e, data){
	
}

function	picsUploadStart(e, data){
	$('#postPictures .pics').prepend('<div class="pics-loading"><p>загрузка картинок<span></span>...</p><div class="progress progress-striped active"><div class="progress-bar"  role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div></div></div>');
	//console.log(data);
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

