var editor = null;

$(function(){
	/*editor = $('#add_comment').cleditor({
		width: '100%',
		height: 250,
		controls: "bold italic underline strikethrough subscript superscript | removeformat | bullets numbering | undo redo | " +
                        "rule image link unlink | html"
	});*/
	
	$('.commentBlock .cPlusButton').one('click', rateCommPositive);
	$('.commentBlock .cMinusButton').one('click', rateCommNegative);
});

var lastReply = 0;

function replyTo(id)
{
	/*if(lastReply)
	{
		if(lastReply == id) return false;
		$('#replyForm_'+lastReply).clone().appendTo('#replyForm_' + id);
		$('#replyForm_'+lastReply).empty();	
	}else
	{
		$('#commentForm').clone().appendTo('#replyForm_' + id);
		$('#originalForm').empty();
	}*/
	
	//$('#originalForm').appendTo('#replyForm_' + id);
	
	
	
	var h = $('#originalForm').height();
	$('.repliesDiv').height(1);
	var p = $('#replyForm_' + id).offset();
	$('#replyForm_' + id).height(h);
	$('#commentForm').hide();
	$('#originalForm').offset(p);
	$('#commentForm').fadeIn(100, function(){
		$('#add_comment').focus();
		if(editor) editor.focus();
	});
	
	
	$('#reply_id').attr('value', lastReply = id);
		
	//$('#add_comment').focus();
	return false;
}

function deleteComment(id, prefix, entry_id){
//	$('#commentBlock'+id+' div').addClass('comment2delete'); return false;
	if(1 || confirm('?')){
		$.ajax({
			url: '/ajax/deleteComment.php',
			type: 'post',
			dataType: 'text',
			data: 'id='+id+'&prefix='+prefix+'&entry_id='+entry_id,
			success: function(data){
				$('#commentBlock'+id).fadeOut('slow');
				if(cc=$('#ecc'+entry_id).html())
					$('#ecc'+entry_id).text(cc-1);
			},
			error: function(){
				alert("Can't delete comment");
			}
		});
	}else $('#commentBlock'+id).removeClass('comment2delete');
	return false;
}


function rateCommNegative(){
	rateComm(this, -1);
	
}

function rateCommPositive(){
	rateComm(this, +1);
}

function rateComm(e, rating){
	var postDiv = $(e).closest('.commentBlock');
	if(!postDiv) return false;
	var postID = parseInt($(postDiv).attr('id').replace('commentBlock', ''));
	$(e).removeClass('hand');
	
	$.post(AJAXPath + 'rateComment.php', {
		postID: postID, rating: rating
	}, function(result){
		if(result == 'CANT'){
			alert('Rate exception');
		}else if(result == 'LOGIN'){
			alert('Необходимо залогиниться!');
		}else{
			var n = parseInt(result);
			if(n == result){
				var $ratingLabel = $(postDiv).find('.cRatingLabel');
				$ratingLabel.html(n);
				if((n > 0) && !$ratingLabel.hasClass('positiveRating')){
					$ratingLabel.removeClass('negativeRating');
					$ratingLabel.addClass('positiveRating');
				}else
				if((n < 0) && !$ratingLabel.hasClass('negativeRating')){
					$ratingLabel.removeClass('positiveRating');
					$ratingLabel.addClass('negativeRating');
				}
			}else{
				alert(result);
			}
		}
		
	});
	
	
}