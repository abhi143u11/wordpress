$(document).ready(function(){
	previewImage();
});

$(document).on('change','li.lift-configuration input, li.exterior-color input',function(){
	previewImage();
});

function previewImage()
{
	var config = $('li.lift-configuration input').val();
	config = config == '' ? 'OX' : config;
	config = config.replace(/R/ig, '');
	config = config.replace(/L/ig, '');
	config = config.replace(/P/ig, '');
	config = config.toUpperCase();
	var color = $('li.exterior-color input:checked').val();
	color = typeof color === 'undefined' ? 'ANOD-CLEAR' : color;
	var imgPath = path + '01-DOOR-LIFT-SLD/01-DOOR-LIFT-SLD-' + config + '_' + color + '.jpg';
	
	switch (config) {
		case 'OX':
		case 'OXX':
		case 'OXXO':
			break;
			
		default:
			imgPath = path + 'preview_image.jpg';
			break;
	}
	
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