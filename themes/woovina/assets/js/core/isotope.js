var $j = jQuery.noConflict();

$j(document).on('ready', function() {
	"use strict";
	// Masonry grids
	woovinaMasonryGrids();
});

$j(window).on('orientationchange', function() {
	"use strict";
	// Masonry grids
	woovinaMasonryGrids();
});

/* ==============================================
MASONRY
============================================== */
function woovinaMasonryGrids() {
	"use strict"

	$j('.blog-masonry-grid').each(function() {

		var $this               = $j(this),
			$transitionDuration = '0.0',
			$layoutMode         = 'masonry';

		// Load isotope after images loaded
		$this.imagesLoaded(function() {
			$this.isotope({
				itemSelector       : '.isotope-entry',
				transformsEnabled  : true,
				isOriginLeft       : woovinaLocalize.isRTL ? false : true,
				transitionDuration : $transitionDuration + 's'
			});
		});

	});

}