var $j = jQuery.noConflict();

$j(document).on('ready', function() {
	"use strict";
	// Custom select
	woovinaCustomSelects();
});

/* ==============================================
CUSTOM SELECT
============================================== */
function woovinaCustomSelects() {
	"use strict"

	$j(woovinaLocalize.customSelects).customSelect({
		customClass: 'theme-select'
	});

}