$(document).ready(function(){
	previewImage();
});

$(document).on('change','li.unit-configuration input, li.exterior-color input',function(){
	previewImage();
});

function previewImage()
{
	var config = $('li.unit-configuration input:checked').val();
	config = typeof config === 'undefined' ? 'STRT' : config;
	var color = $('li.exterior-color input:checked').val();
	color = typeof color === 'undefined' ? 'MILL-FINISH' : color;
	var imgPath = path + '06-GRDN-WNDW/06-GRDN-WNDW-' + config + '_' + color + '.jpg';
	
	preview.hide();
	spinner.show();	
	config == '0' ? preview.attr('src', path + 'preview_image.jpg') : preview.attr('src', imgPath, 'data-new');
	preview.show();
	spinner.hide();
}