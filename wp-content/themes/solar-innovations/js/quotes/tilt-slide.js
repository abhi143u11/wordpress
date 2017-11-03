$(document).ready(function(){
	previewImage();
});

$(document).on('change','li.unit-configuration input, li.exterior-color input',function(){
	previewImage();
});

function previewImage()
{
	var config = $('li.unit-configuration input:checked').val();
	config = typeof config === 'undefined' ? 'OX' : config;
	config = config.replace(/R/ig, '');
	config = config.replace(/L/ig, '');
	config = config.toUpperCase();
	var color = $('li.exterior-color input:checked').val();
	color = typeof color === 'undefined' ? 'ANOD-CLEAR' : color;
	
	switch (config) {
		case 'OX':	
		case 'XO':
			config = '01-OX';
			break;
			
		case 'OXO':
			config = '02-' + config;
			break;
			
		case 'OXXO':
			config = '03-' + config;
			break;
	}
	
	var imgPath = path + '01-DOOR_06-TILT-SLD/01-DOOR_06-TILT-SLD_' + config + '_' + color + '.jpg';
	
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