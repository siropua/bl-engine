$(function(){
	$('input.subCB').change(function(){
		if(this.checked)
			$(this).parents('.moduleLI:first').find('input.moduleCB')
				.attr('checked', 'checked').change();
	});
	
	$('input.moduleCB').change(function(){
		if(this.checked)
			$(this).parents('.sectionBlock:first').find('input.sectionCB')
				.attr('checked', 'checked');
		else
			$(this).parents('.moduleLI:first').find('input')
				.removeAttr('checked');
	});
	
	/** выключаем главную галку - выключаем все зависящее **/
	$('input.sectionCB').change(function(){
		if(!this.checked)
			$(this).parents('.sectionBlock:first').find('input')
				.removeAttr('checked');
	});
});

function changePass(id, login){
	$('#passForm #fLogin').html(login);
	$('#fUserID').val(id);
	$('#passForm').modal();	
}

function genPass(){
	$('#fNewPass').val(generatePassword(true, true));
}

function generatePassword(randomLength, noPunction){
    var length = 6;
    var sPassword = "";
 
    if(randomLength){
        length = Math.random(); 
        length = parseInt(length * 100);
        length = (length % 7) + 6
    }
 
	var numI;
    for(i=0; i < length; i++){
        numI = getRandomNum();
        if (noPunction) { while (checkPunc(numI)) { numI = getRandomNum(); } }
        sPassword = sPassword + String.fromCharCode(numI);
    }
 
    return sPassword;
}
 


function getRandomNum(){
    var rndNum = Math.random();
    rndNum = parseInt(rndNum * 1000);
    rndNum = (rndNum % 94) + 33;
 
    return rndNum;
}
 
function checkPunc(num){
    if ((num >=33) && (num <=47)) { return true; }
    if ((num >=58) && (num <=64)) { return true; }
    if ((num >=91) && (num <=96)) { return true; }
    if ((num >=123) && (num <=126)) { return true; }
 
    return false;
}