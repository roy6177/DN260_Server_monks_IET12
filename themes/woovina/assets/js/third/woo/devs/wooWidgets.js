var $j = jQuery.noConflict();

$j(document).on('ready', function() {
	"use strict";
    // Woo remove brackets from categories and filter widgets
    woovinaWooRemoveBrackets();
});

/* ==============================================
WOOCOMMERCE REMOVE BRACKETS
============================================== */
function woovinaWooRemoveBrackets() {
	"use strict"

	$j('.widget_layered_nav span.count, .widget_product_categories span.count').each(function() {
		var count = $j(this).html();
		count = count.substring(1, count.length-1);
		$j(this).html(count);
	});

}