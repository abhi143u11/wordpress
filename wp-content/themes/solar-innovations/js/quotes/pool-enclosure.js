$(document).ready(function(){
	previewImage();
});

$(document).on('change','li.unit-configuration input, li.basewall input, li.exterior-color input, li.overlay input, li.overlay2 input, li.overlay3 input, li.overlay4 input',function(){
	previewImage();
});

function previewImage()
{
	var config = $('li.unit-configuration input:checked').val();
	config = typeof config === 'undefined' ? 'SEDP' : config;
	var basewall = $('li.basewall input:checked').val();
	basewall = typeof basewall === 'undefined' ? '' : basewall;
	basewall = basewall != '' ? basewall + '_' : '';
	var color = $('li.exterior-color input:checked').val();
	color = typeof color === 'undefined' ? 'MILL-FINISH' : color;
	var imgPath = path + '03-STRUCT/03-STRUCT-' + config + '_' + basewall + color + '.jpg';
	
	preview.hide();
	overlay.hide();
	overlay2.hide();
	overlay3.hide();
	overlay4.hide();
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
		
		var imgPath = path + '03-STRUCT/03-STRUCT-' + config + '_' + value + '_' + color + '.png';
		if (fileExists(imgPath)) 
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
		
		var imgPath = path + '03-STRUCT/03-STRUCT-' + config + '_' + value + '_' + color + '.png';
		if (fileExists(imgPath)) 
			overlay2.attr('src', imgPath, 'data-new'); 
		else
			overlay2.attr('src',path + 'blank.png');
		
		spinner.hide();
		preview.show();
		overlay2.show();
	}
	previewOverlay3();
}

function previewOverlay3()
{
	var config = $('li.unit-configuration input:checked').val();
	var color = $('li.exterior-color input:checked').val();
	var value = $('li.toggle-overlay3 input').val() == 'Yes' ? $('li.overlay3 input:checked').val() : '0';
	
	if (!value || typeof config === 'undefined' || typeof color === 'undefined' || value == '0')
	{
		overlay3.attr('src',path + 'blank.png');
		spinner.hide();
		preview.show();
		overlay3.show();
	} else
	{	
		spinner.show();
		preview.hide();
		overlay3.hide();	
		
		var imgPath = path + '03-STRUCT/03-STRUCT-' + config + '_' + value + '_' + color + '.png';
		if (fileExists(imgPath)) 
			overlay3.attr('src', imgPath, 'data-new'); 
		else
			overlay3.attr('src',path + 'blank.png');
		
		spinner.hide();
		preview.show();
		overlay3.show();
	}
	previewOverlay4();
}

function previewOverlay4()
{
	var config = $('li.unit-configuration input:checked').val();
	var color = $('li.exterior-color input:checked').val();
	var value = $('li.toggle-overlay4 input').val() == 'Yes' ? $('li.overlay4 input:checked').val() : '0';
	
	if (!value || typeof config === 'undefined' || typeof color === 'undefined' || value == '0')
	{
		overlay2.attr('src',path + 'blank.png');
		spinner.hide();
		preview.show();
		overlay4.show();
	} else
	{	
		spinner.show();
		preview.hide();
		overlay4.hide();	
		
		var imgPath = path + '03-STRUCT/03-STRUCT-' + config + '_' + value + '_' + color + '.png';
		if (fileExists(imgPath)) 
			overlay4.attr('src', imgPath, 'data-new'); 
		else
			overlay4.attr('src',path + 'blank.png');
		
		spinner.hide();
		preview.show();
		overlay4.show();
	}
}