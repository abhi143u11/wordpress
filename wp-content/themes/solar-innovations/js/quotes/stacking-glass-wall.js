$(document).ready(function(){
	previewImage();
});

$(document).on('change','li.unit-configuration input, li.number-of-panels input, li.exterior-color input',function(){
	previewImage();
});

function previewImage()
{
	var config = $('li.unit-configuration input:checked').val();
	config = typeof config === 'undefined' ? 'ST-PAR' : config;
	var pad = "00";
	var panels = $('li.number-of-panels input').val();
	panels = panels == '' ? '3' : panels;
	panels = pad.substring(0, pad.length - panels.length) + panels;
	var color = $('li.exterior-color input:checked').val();
	color = typeof color === 'undefined' ? 'MILL-FINISH' : color;
	var imgPath = path + '01-DOOR/01-DOOR-STK-DR-' + config + '_' + panels + 'L_' + color + '.jpg';
	
	preview.hide();
	spinner.show();
	config == '0' ? preview.attr('src', path + 'preview_image.jpg') : preview.attr('src', imgPath, 'data-new');
	preview.show();
	spinner.hide();		
}