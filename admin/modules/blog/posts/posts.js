var mapHasInit = false;
var map;
var marker;

$(function(){
	
	$('.mainpicPreview').hover(function(){
		/* навели мышь на првьюху */
		var p = $(this).offset();
		$('#mainpicPreview').show().html('<img src="'+$(this).attr('rel')+'" />' );
		$('#mainpicPreview').offset({
			top: p.top + 20,
			left: p.left + 20
		});
	}, function(){
		/* убрали мышь с превьюхи */
		$('#mainpicPreview').hide();
	});

	$('button.delete-post').on('click', deletePost);
	
	if(!$('#fPostText').length) return;
	
//	$('#images button').attr('disabled', '');
/*	$('.date-pick').datePicker({startDate:'01.01.1996', renderCallback: renderDeferred, clickInput:true});
	var ft = $('input[name=dp_time]');
	var fd = $('input[name=dp_date]');
	var d = new Date;
	if(!$(ft).val())
		$(ft).val(_zeroPad(d.getHours())+':'+_zeroPad(d.getMinutes()));
	if(!$(fd).val())
		$(fd).val(d.asString());
*/	
	
	
	//if(hasMap) openMap($('#showMapButton'));
	
	$('#saveButton').removeAttr('disabled').click(savePost);

	$('button,a,img').tooltip();
	
});

function deletePost(){

	if(!confirm('Удалить пост без возможности восстановления?')){
		return false;
	}
	
	var $tr = $(this).closest('tr').addClass('deleting');
	var id = $tr.data('id');
	var $btn = $(this).attr('disabled', 'disabled');

	$.post(moduleJSON + 'post/delete', {id:id}, function(data){
		$btn.enable();
		if(typeof data.data.deleted_id != 'undefined'){
			$tr.fadeOut();
			$('b.posts-shown').html($('b.posts-shown').html() - 1);
			$('b.posts-total').html($('b.posts-total').html() - 1);
		}else{
			alert('Не удалось удалить пост!');
			console.log(data);
		}

		return false;
	});	

	return false;
}

function removeMainPic(id, img){
	var lastSRC = img.src;
	img.src = LOADING.bar16.src;
	img.disabled = true;
	$.post(moduleAJAX + 'delMainPic.php', {id:id}, function(data){
		img.src = lastSRC;
		img.disabled = false;
		if(data == 'OK'){
			$('#mainPicContainer').fadeOut();
		}else alert(data);
		
	});		
}

function checkBlogForm(f){
	
	//return false;
	if(!$('#fTitle').val() && !confirm('Не задан заголовок! Точно отправить пост без заголовка?')){
		return false;
	}
	
	if(!tinyMCE.get('fPostText').getContent({format : 'text'}) && 
		!$('#fMainpic').val() && !$('#fMainpic_url').val()){
		alert('Нужен какой-то контент');
		return false;
	}
	
	$('#submitButton').hide().after(LOADING.page);
	
	return true;
}



function clearPostImg(){
	$('#thumbImg').empty();
	$('#fThumb').val('');
}

function listDeferred(b){
	var div = $(b).parents('div:first');
	$(div).html('Загрузка...').load(moduleAJAX+'deferredList.php');
}

function _zeroPad(num) {
		var s = '0'+num;
		return s.substring(s.length-2)
		//return ('0'+num).substring(-2); // doesn't work on IE :(
};

function renderDeferred($td, thisDate, month, year){
	month++;
	var key = 'd' + year +  _zeroPad(month) + _zeroPad(thisDate.getDate());
	if (typeof(deferredList[key]) != 'undefined') {
		$td.addClass('with-deferred')
			.attr('title', deferredList[key]);
	}
}




function openMap(b){
	$(b).hide();
	$('#mapForm').show();
	$('#fGeoAddress').focus();
	mapHasInit = true;
	if (GBrowserIsCompatible()) {
        map = new GMap2(document.getElementById("postMap"));
        
		//map.setMapType(G_HYBRID_MAP);
		map.addControl(new GSmallMapControl());
		map.addControl(new GMapTypeControl());
		map.enableScrollWheelZoom();
		
		GEvent.addListener(map, 'dblclick', function(){
			marker.setLatLng(map.getCenter());
		});
		
		
		center = new GLatLng(geo_lat, geo_lng);
		map.setCenter(center, 14);
		
		if(!marker){
			
			marker = new GMarker(center, {draggable: true});

			GEvent.addListener(marker, "dragend", function() {
				latlng = marker.getLatLng();
				$('#mapLAT').val(latlng.lat());
				$('#mapLNG').val(latlng.lng());
			});
			
			map.addOverlay(marker);
		}
		marker.setLatLng(center);
		$('#mapLAT').val(geo_lat);
		$('#mapLNG').val(geo_lng);
	}
}

function closeMap(){
	$('#mapForm').hide().prev().show();
	$('#fTitle').focus();
}

$(window).unload( function () { 
	if(mapHasInit){
		GUnload();
	}

	} );
	
function addMainTag(tag){
	var t = $('#fTags').val().replace(/(^\s+)|(\s+$)/g, "");
	
	if(t.indexOf(tag) != -1) return false;
	
	if(t.length > 1){
		if(t.substr(t.length - 1, 1) != ',') t += ',';
		t += ' ';
	}
	
	t += tag + ', ';
	$('#fTags').val(t).focus();
	
	return true;
}


function searchLocation(b){
	$('#addressSugg').html(LOADING.page);
	$(b).attr('disabled', 'disabled');
	var geocoder = new GClientGeocoder();
	var address = 'Днепропетровск, ' + $('#fGeoAddress').val();
	geocoder.getLocations(address, function(points){

		$(b).attr('disabled', '');
		if(points.Status.code != 200){
			if(points.Status.code == 500){
				$('#addressSugg').html('Ошибка связи с сервером. Попробуйте поискать еще раз через 2-3 секунды');
				return false;
			}
			$('#addressSugg').html('Не найден заданный адресс. Попробуйте задать другой адрес. <br>Код ошибки: '+points.Status.code);
			return false;
		}
		$('#fSearchAddress').val(address);
		if(points.Placemark.length>1){
			// найдено несколько точек. выводим их список
			output = 'Найдено подходящих адресов: '+points.Placemark.length+'<br>Выберите свой из списка: <ul>';
			for(i in points.Placemark){
				
				pm = points.Placemark[i];
				rawAddress = pm.address.replace("'", "\\'");
				try{LocalityName = typeof(pm.AddressDetails.Country.AdministrativeArea.Locality.LocalityName) == 'undefined' ? '' : pm.AddressDetails.Country.AdministrativeArea.Locality.LocalityName.replace("'", "\\'");}catch(err){
					LocalityName = '';
				}
				output += '<li onclick="selectAddress('+pm.Point.coordinates[0]+', '+pm.Point.coordinates[1]+', '+pm.AddressDetails.Accuracy+', \''+rawAddress+'\', \''+LocalityName+'\');">';
				output += pm.address;
				output += '</li>';
			}
			output += "</ul>";
			$('#addressSugg').html(output);
			$('#addressSugg').find('li').addClass('link').hover(function(){
				$(this).addClass('suggHover');
			}, function(){
				$(this).removeClass('suggHover');
			});
		}else{
			// переходим к найденной точке
			$('#addressSugg').html('');
			pm = points.Placemark[0];
			
			try{LocalityName = typeof(pm.AddressDetails.Country.AdministrativeArea.Locality.LocalityName) == 'undefined' ? '' : pm.AddressDetails.Country.AdministrativeArea.Locality.LocalityName.replace("'", "\\'");}catch(err){
					LocalityName = '';
				}
			
			selectAddress(pm.Point.coordinates[0], pm.Point.coordinates[1], pm.AddressDetails.Accuracy, pm.address, LocalityName);
		}
		

		
	});
	return false;
}


function selectAddress(lng, lat, z, a, l){
	$('#addressSugg').html('');
	zoom = 14;
	if(z > 7){
		zoom = 17;
	}else if(z > 5){
		zoom = 15;
	}else if(z > 3){
		zoom = 12;
	}else zoom = 10;	
	$('#mapWrapper').show('', function(){
		
	});
	
	//alert(a); //alert(l);
	$('#fRAWAddress').val(a);
	
	center = new GLatLng(lat, lng);
	map.setCenter(center, zoom);
	
	if(!marker){
		
		var marker = new GMarker(center, {draggable: true});

        GEvent.addListener(marker, "dragend", function() {
			latlng = marker.getLatLng();
			$('#mapLAT').val(latlng.lat());
			$('#mapLNG').val(latlng.lng());
        });
		
		map.addOverlay(marker);
	}
	marker.setLatLng(center);
	$('#mapLAT').val(lat);
	$('#mapLNG').val(lng);
	
}


function showRefs(postID){
	$('#referersWindow').modal();
	$('#referersList').html('Загрузка источников…').load(moduleAJAX + 'getReferers.php?id=' + postID, function(){
		$(this).find('a').tooltip({
			showURL: false,
			showBody: '  '
		});
	});
	return false;
}

function savePost(){
	$('#saveButton').attr('disabled', 'disabled');
	$.post(moduleAJAX + 'savePost.php', $('#blogPostForm').serialize(), function(data){
		$('#saveButton').attr('disabled', '');
		if(data != 'OK'){
			alert(data);
		}
	});
}