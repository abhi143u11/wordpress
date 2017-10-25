$(document).ready(function(){
	previewImage();
});

$(document).on('change','li.swing input, li.unit-configuration input, li.exterior-color input',function(){
	previewImage();
});

function previewImage()
{
	var swing = $('li.swing input:checked').val();
	swing = typeof swing === 'undefined' ? 'IN' : swing;
	var config = $('li.unit-configuration input:checked').val();
	config = typeof config === 'undefined' ? '01-SNGL-01' : config;
	var num;
	
	if (config == '01-SNGL-01') {
		config = '01-SNGL';
		num = '01';
	} else {
		switch (config) {
			case '01-SNGL_01':
				config = '01-SNGL';
				num = '01';
				break;
				
			case '01-SNGL_02':
				config = '01-SNGL';
				num = '02';
				break;
				
			case '01-SNGL_03':
				config = '01-SNGL';
				num = '03';
				break;
				
			case '01-SNGL_04':
				config = '01-SNGL';
				num = '04';
				break;
				
			case '01-SNGL_05':
				config = '01-SNGL';
				num = '05';
				break;
				
			case '02-DBL_01':
				config = '02-DBL';
				num = '01';
				break;
				
			case '02-DBL_02':
				config = '02-DBL';
				num = '02';
				break;
				
			case '02-DBL_03':
				config = '02-DBL';
				num = '03';
				break;
				
			case '02-DBL_05':
				config = '02-DBL';
				num = '05';
				break;
			
			default:
				config = '0';
				num = '0';
				break;
		}
	}
	
	var color = $('li.exterior-color input:checked').val();
	color = typeof color === 'undefined' ? 'MILL-FINISH' : color;
	
	var imgPath = path + '01-DOOR_07-T-DR/01-DOOR_07-T-DR_' + config + '_' + swing + '_' + num + '_' + color + '.jpg';

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