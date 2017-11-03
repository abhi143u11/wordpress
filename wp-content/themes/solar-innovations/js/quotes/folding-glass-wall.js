$(document).ready(function(){
	previewImage();
});

$(document).on('change','li.fold input, li.unit-configurations input, li.number-of-panels input, li.exterior-color input',function(){
	previewImage();
});

function previewImage()
{
	var fold = $('li.fold input:checked').val();
	fold = typeof fold === 'undefined' ? 'IN' : fold;
	var config = $('li.unit-configurations input:checked').val();
	config = typeof config === 'undefined' ? 'ALL-W' : config; //ALL-W
	var pad = "00";
	var panels = $('li.number-of-panels input').val();
	panels = panels == '' ? '2' : panels; //2
	panels = pad.substring(0, pad.length - panels.length) + panels;
	
	if (config == '0') {
		$('li.unit-configurations input:checked').each(function(){
			if (config == '0' && $(this).val() != '0')
				config = $(this).val();
		});
	}
	
	switch (config) {
		case 'DDR-END':
			if (panels % 2)
				var left = parseInt(panels);
			else
				var left = parseInt(panels) - 1;
			var panels_string = left + 'L-1R';
			break;
			
		case 'DDR-MID':
		case 'NPST-CRNR':
			if (panels % 2)
				panels = parseInt(panels) + 1;
			var num = parseInt(panels) / 2;
			var panels_string = num + 'L-' + num + 'R';
			break;
		
		case 'SDR-HIN':
			var left = parseInt(panels) - 1;
			var panels_string = left + 'L-1R';
			break;
		
		case 'SDR-LAS':
			if (panels % 2)
				panels = parseInt(panels);
			else
				panels = parseInt(panels) - 1;
			var panels_string = '0' + panels + 'L';
			break;
			
		case 'SGMT-RAD':
			var panels_string = panels + 'L';
			break;
		
		case 'SPL-W':
		case 'ALL-W':
			var split = 0;
			$('li.unit-configurations input:checked').each(function(){
				if (!split)
					split = $(this).val() == 'SGMT-RAD' ? 1 : 0;
			});
			
			if (config == 'SPL-W') {
				if (panels % 2)
					panels = parseInt(panels) + 1;
				var num = parseInt(panels) / 2;
				if (!split)
					var panels_string = '0' + num + 'L-0' + num + 'R';
				else
					var panels_string = num + 'L-' + num + 'R';
			} else if (config == 'ALL-W') {
				var panels_string = panels + 'L';
			}
			
			if (split)
				config = 'SGMT-RAD';
			break;
			
		default:
			var panels_string = 'panelsstring';
			break;
	}
	
	var color = $('li.exterior-color input:checked').val();
	color = typeof color === 'undefined' ? 'MILL-FINISH' : color;
	
	var imgPath = path + '01-DOOR-FGW/01-DOOR-FGW-' + config + '_' + panels_string + '_' + fold + '_' + color + '.jpg';
	
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