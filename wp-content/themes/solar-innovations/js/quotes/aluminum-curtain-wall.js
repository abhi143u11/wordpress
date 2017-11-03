$(document).ready(function(){
	previewImage();
});

$(document).on('change','li.bays-wide input, li.bays-high input, li.exterior-color input',function(){
	previewImage();
});

function previewImage()
{
	var pad = "00";
	var wide = $('li.bays-wide input').val();
	wide = wide == '' ? '1' : wide;
	wide = pad.substring(0, pad.length - wide.length) + wide;
	var high = $('li.bays-high input').val();
	high = high == '' ? '1' : high;
	high = pad.substring(0, pad.length - high.length) + high;
	var color = $('li.exterior-color input:checked').val();
	color = typeof color === 'undefined' ? 'MILL-FINISH' : color;
	
	var imgPath = path + '05-VERT-WALL/05-VERT-WALL-' + wide + 'W-' + high + 'H_' + color + '.jpg';
	
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