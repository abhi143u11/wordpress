// use that form name to load another js file that will house the preview image logic
function functionOne(post_title)
{
	switch(post_title) {
		case 'Aluminum Curtain Wall Quote Form':
			var filename = 'aluminum-curtain-wall';
			break;
		
		case 'Wood Curtain Wall Quote Form':
			var filename = 'wood-curtain-wall';
			break;
			
		case 'Canopy Quote Form':
			var filename = 'canopy';
			break;
		
		case 'Clear Glass Wall Quote Form':
			var filename = 'clear-glass-wall';
			break;
			
	    case 'Garden Window Quote Form':
	        var filename = 'garden-window';
	        break;
	    
	    case 'Stacking Glass Wall Quote Form':
	    	var filename = 'stacking-glass-wall';
	    	break;
	    	
	    case 'Vertical Wall Wood Quote Form':
	        var filename = 'vert-wall-wood';
	        break;
	        
		case 'Vinyl Composite Terrace Door Quote Form':
			var filename = 'vinyl-composite-terrace-door';
			break;
		
		case 'G3 Lift Slide Quote Form':
			var filename = 'g3-lift-slide';
			break;
			
		case 'Sliding Glass Door Quote Form':
			var filename = 'sliding-glass-door';
			break;
		
		case 'Dutch Door Quote Form':
			var filename = 'dutch-door';
			break;
		
		case 'Folding Glass Wall Quote Form':
			var filename = 'folding-glass-wall';
			break;
			
		case 'Pool Enclosure Quote Form':
			var filename = 'pool-enclosure';
			break;
		
		case 'Tilt Turn Quote Form':
			var filename = 'tilt-turn';
			break;
		
		case 'Tilt Slide Quote Form':
			var filename = 'tilt-slide';
			break;
		
		case 'Greenhouse Quote Form':
			var filename = 'greenhouse';
			break;
		
		case 'Sunroom / Conservatory Quote Form':
			var filename = 'sunroom-conservatory';
			break;
		
		case 'Skylight Quote Form':
			var filename = 'skylight';
			break;
			
		case 'Walkway Quote Form':
			var filename = 'walkway';
			break;
		
		case 'Standard / Modular Terrace Door Quote Form':
			var filename = 'standard-modular-terrace-door';
			break;
		
		case 'Pivot Door Quote Form':
			var filename = 'pivot-door';
			break;
			
		case 'Simple Structure Quote Form':
			var filename = 'simple-structure';
			break;
			
	    default:
	    	return;
	}
	$('#preview_container').show();
	//console.log('ready...');
	$.getScript('http://www.solarinnovations.com/wp-content/themes/solar-innovations/js/quotes/preview_config.js', function()
	{
		$.getScript('http://www.solarinnovations.com/wp-content/themes/solar-innovations/js/quotes/' + filename + '.js', function(){
		//Callback
		});
	});
}