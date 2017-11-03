$(document).ready(function(){
	previewImage();
});

$(document).on('change','li.exterior-color input',function(){
	previewImage();
});

function previewImage()
{
	var color = $('li.exterior-color input:checked').val();
	color = typeof color === 'undefined' ? 'ANOD-CLEAR' : color;
	var imgPath = path + '01-DOOR_08/01-DOOR_08-TILT-TURN_01-SNGL_' + color + '.jpg';
	
	preview.hide();
	spinner.show();	
	$.ajax({
	    type: 'HEAD',
	    url: imgPath,
	    context: this,
	    success: function() {
	        preview.attr('src', imgPath, 'data-new');
			preview.show();
			spinner.hide();
	    },  
	    error: function() {
	        preview.attr('src', path + 'preview_image.jpg');
	        preview.show();
			spinner.hide();
	    }
	});
}