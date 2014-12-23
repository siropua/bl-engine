$(function(){
    tinymce.init({
	selector: ".edtAdv",
	language: 'ru',
	plugins: [
	    "advlist autolink lists link image charmap print preview anchor",
	    "searchreplace visualblocks code fullscreen textcolor"
        ],
        toolbar: "undo redo | styleselect | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });
                
});