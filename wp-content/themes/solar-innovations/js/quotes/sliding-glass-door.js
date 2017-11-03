$(document).ready(function(){
	previewImage();
});

$(document).on('change','li.unit-configuration input, li.exterior-color input',function(){
	previewImage();
});

function previewImage()
{
	var config = $('li.unit-configuration input').val();
	config = config == '' ? 'OX' : config;
	config = config.replace(/R/ig, '');
	config = config.replace(/L/ig, '');
	config = config.toUpperCase();
	var type = config.indexOf('P') === -1 ? 'MULTI' : 'POCKET';
	var color = $('li.exterior-color input:checked').val();
	color = typeof color === 'undefined' ? 'ANOD-CLEAR' : color;
	
	switch (type) {
		case 'MULTI':
			switch (config) {
				case 'OOX':
					config = 'XOO';
					break;
				
				case 'OXX':
					config = 'XXO';
					break;
					
				case 'OX':
					config = 'XO';
					break;
					
				case 'OOOXOOO':
				case 'OOXOO':
				case 'OOXX':
				case 'OOXXX':
				case 'OXO':
				case 'OXXO':
				case 'OXXXO':
				case 'OXXXXO':
				case 'XO':
				case 'XOO':
				case 'XOOO':
				case 'XOOOOX':
				case 'XOOOX':
				case 'XOOX':
				case 'XOX':
				case 'XX':
				case 'XXO':
				case 'XXOXX':
				case 'XXOXXX':
				case 'XXX':
				case 'XXXO':
				case 'XXXOOO':
				case 'XXXX':
				case 'XXXXX':
					break;
					
				default:
					imgPath = path + 'preview_image.jpg';
					break;
			}
			break;
		case 'POCKET':
			switch (config) {
				case 'OOOXP':
				case 'OOXP':
				case 'OXP':
				case 'OXXP':
				case 'OXXXP':
				case 'PXOOOOXP':
				case 'PXOOOXP':
				case 'PXOOXP':
				case 'PXOXP':
				case 'PXXOXXP':
				case 'PXXP':
				case 'PXXXOXXXP':
				case 'PXXXP':
				case 'PXXXXXXP':
				case 'XP':
				case 'XXP':
				case 'XXXP':
				case 'XXXXP':
					break;
				default:
					imgPath = path + 'preview_image.jpg';
					break;
			}
			break;
		default:
			imgPath = path + 'preview_image.jpg';
			break;
	}
	
	var imgPath = path + '01-DOOR-SLD/01-DOOR-SLD-DR-' + type + '-' + config + '_' + color + '.jpg';
	
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