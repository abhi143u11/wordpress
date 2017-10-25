$(document).ready(function(){
	previewImage();
});

$(document).on('change','li.unit-configuration input, li.panel-style input',function(){
	previewImage();
});

function previewImage()
{
	var config = $('li.unit-configuration input:checked').val();
	config = typeof config === 'undefined' ? 'SNGL' : config;
	
	var style = $('li.panel-style input:checked').val();
	style = typeof style === 'undefined' ? 'P-P' : style;
	
	var imgPath = path + '03-DOOR_04-DUTCH/03-DOOR_04-DUTCH-' + config + '-001a_' + style + '-CLR-ANO-001_Copyright-2011.jpg';
	
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