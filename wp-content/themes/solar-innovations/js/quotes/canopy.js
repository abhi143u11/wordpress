$(document).ready(function(){
	previewImage();
});

$(document).on('change','li.unit-configuration input, li.exterior-color input, li.overlay input, li.overlay2 input',function(){
	previewImage();
});

function previewImage()
{
	var config = $('li.unit-configuration input:checked').val();
	config = typeof config === 'undefined' ? 'SEDP' : config;
	var color = $('li.exterior-color input:checked').val();
	color = typeof color === 'undefined' ? 'MILL-FINISH' : color;
	var imgPath = path + '04-CAN/04-CAN-' + config + '_' + color + '.jpg';
	
	preview.hide();
	overlay.hide();
	overlay2.hide();
	spinner.show();	
	$.ajax({
	    type: 'HEAD',
	    url: imgPath,
	    context: this,
	    success: function() {
	        preview.attr('src', imgPath, 'data-new');
			preview.show();
			spinner.hide();
			previewOverlay();
	    },  
	    error: function() {
	        preview.attr('src', path + 'preview_image.jpg');
	        preview.show();
			spinner.hide();
	    }
	});
}

function previewOverlay()
{
	var config = $('li.unit-configuration input:checked').val();
	var color = $('li.exterior-color input:checked').val();
	var value = $('li.overlay input:checked').val();
	
	if (!value || typeof config === 'undefined' || typeof color === 'undefined' || typeof value === 'undefined')
	{
		overlay.attr('src',path + 'blank.png');
		spinner.hide();
		preview.show();
		overlay.show();
	} else
	{	
		spinner.show();
		preview.hide();
		overlay.hide();	
		
		var imgPath = path + '04-CAN/04-CAN-' + config + '_' + value + '_' + color + '.png';
		if (overlay.attr('src') != imgPath && fileExists(imgPath)) 
			overlay.attr('src', imgPath, 'data-new'); 
		else 
			overlay.attr('src',path + 'blank.png');
		
		spinner.hide();
		preview.show();
		overlay.show();
	}
	previewOverlay2();
}

function previewOverlay2()
{
	var config = $('li.unit-configuration input:checked').val();
	var color = $('li.exterior-color input:checked').val();
	if ($('li.toggle_overlay2 input:checked').val() == 'Yes')
		var value = $('li.overlay2 input:checked').val();
		
	if (!value || typeof config === 'undefined' || typeof color === 'undefined' || typeof value === 'undefined')
	{
		overlay2.attr('src',path + 'blank.png');
		spinner.hide();
		preview.show();
		overlay2.show();
	} else
	{	
		spinner.show();
		preview.hide();
		overlay2.hide();	
		
		var imgPath = path + '04-CAN/04-CAN-' + config + '_' + value + '_' + color + '.png';
		if (overlay2.attr('src') != imgPath && fileExists(imgPath)) 
			overlay2.attr('src', imgPath, 'data-new'); 
		else
			overlay2.attr('src',path + 'blank.png');
		
		spinner.hide();
		preview.show();
		overlay2.show();
	}
}