$(document).ready(function(){
	previewImage();
});

$(document).on('change','li.type input, li.unit-configuration input, li.frame-finish input',function(){
	previewImage();
});

function previewImage()
{
	var type = $('li.type input:checked').val();
	type = typeof type === 'undefined' ? 'IN' : type;
	var config = $('li.unit-configuration input:checked').val();
	config = typeof config === 'undefined' ? '01' : config;
	var color = $('li.frame-finish input:checked').val();
	color = typeof color === 'undefined' ? 'DRCRN-WHT' : color;
	
	switch (config) {
		case '01':
			var config1 = '01-SNGL';
			var config2 = '01';
			break;
		
		case '02':
			var config1 = '02-DBL';
			var config2 = '01';
			break;
			
		case '03':
			var config1 = '01-SNGL';
			var config2 = '03';
			break;
			
		case '04':
			var config1 = '02-DBL';
			var config2 = '03';
			break;
	}
	
	var imgPath = path + '01-DOOR/01-DOOR_07-T-DR_' + config1 + '_' + type + '_' + config2 + '_' + color + '.jpg';
	
	preview.hide();
	spinner.show();	
	config == '0' ? preview.attr('src', path + 'preview_image.jpg') : preview.attr('src', imgPath, 'data-new');
	preview.show();
	spinner.hide();
}