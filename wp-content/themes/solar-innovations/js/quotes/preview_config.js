var path = 'http://www.solarinnovations.com/wp-content/themes/solar-innovations/img/';
var spinner = $('#preview_spinner');
var preview = $('#preview_image');
var overlay = $('#overlay_image');
var overlay2 = $('#overlay_image2');
var overlay3 = $('#overlay_image3');
var overlay4 = $('#overlay_image4');

preview.on('load', function() {
	//setInterval(function () {
		spinner.hide();
		overlay.show();
		preview.show();
	//}, 500);
});

function fileExists(url)
{
    var http = new XMLHttpRequest();
    http.open('HEAD', url, false);
    http.send();
    //console.log(http.status == 200);
    return http.status == 200;
}