<?php
/**
 * Author: Alin Marcu
 * Copyright 2018 Alin Marcu
 * Author URI: https://deconf.com
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
?>
<script>
<<<<<<< HEAD
var dnt = false;
var gaProperty = '<?php echo $data['uaid']?>';
var gaDntOptout =  '<?php echo $data['gaDntOptout']?>';
var gaOptout =  '<?php echo $data['gaOptout']?>';
var disableStr = 'ga-disable-' + gaProperty;
if(gaDntOptout && (window.doNotTrack === "1" || navigator.doNotTrack === "1" || navigator.doNotTrack === "yes" || navigator.msDoNotTrack === "1")) {
	dnt = true;
}
if (dnt || (document.cookie.indexOf(disableStr + '=true') > -1 && gaOptout)) {
	window[disableStr] = true;
}
function gaOptout() {
	document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';
	window[disableStr] = true;
}
</script>
=======
var gadwpDnt = false;
var gadwpProperty = '<?php echo $data['uaid']?>';
var gadwpDntFollow = <?php echo $data['gaDntOptout'] ? 'true' : 'false'?>;
var gadwpOptout = <?php echo $data['gaOptout'] ? 'true' : 'false'?>;
var disableStr = 'ga-disable-' + gadwpProperty;
if(gadwpDntFollow && (window.doNotTrack === "1" || navigator.doNotTrack === "1" || navigator.doNotTrack === "yes" || navigator.msDoNotTrack === "1")) {
	gadwpDnt = true;
}
if (gadwpDnt || (document.cookie.indexOf(disableStr + '=true') > -1 && gadwpOptout)) {
	window[disableStr] = true;
}
function gaOptout() {
	var expDate = new Date;
	expDate.setFullYear(expDate.getFullYear( ) + 10);
	document.cookie = disableStr + '=true; expires=' + expDate.toGMTString( ) + '; path=/';
	window[disableStr] = true;
}
</script>
>>>>>>> 01cd3400df28de7997230e7b4299d723a1154df5
