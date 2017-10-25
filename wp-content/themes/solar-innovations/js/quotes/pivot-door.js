$(document).ready(function(){
	previewImage();
});

$(document).on('change','li.unit-configuration input, li.swing input, li.type input, li.exterior-color input',function(){
	previewImage();
});

function previewImage()
{
	var config = $('li.unit-configuration input:checked').val();
	config = typeof config === 'undefined' ? '01-SNGL' : config;
	var swing = $('li.swing input:checked').val();
	swing = typeof swing === 'undefined' ? 'IN' : swing;
	var type = $('li.type input:checked').val();
	type = typeof type === 'undefined' ? 'P1' : type;
	var color = $('li.exterior-color input:checked').val();
	color = typeof color === 'undefined' ? 'MILL-FINISH' : color;
	
	var imgPath = path + '01-DOOR_04-PIVOT/01-DOOR_04-PIVOT_' + config + '_' + swing + '_' + type + '_' + color + '.jpg';
	
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