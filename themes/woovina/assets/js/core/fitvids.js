var $j = jQuery.noConflict();

$j(document).on('ready', function() {
	"use strict";
    // Responsive Video
	woovinaInitFitVids();
});

/* ==============================================
RESPONSIVE VIDEOS
============================================== */
function woovinaInitFitVids() {
	"use strict"

	$j('.responsive-video-wrap, .responsive-audio-wrap').fitVids();

}