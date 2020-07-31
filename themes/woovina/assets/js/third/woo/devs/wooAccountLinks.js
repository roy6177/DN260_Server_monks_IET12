var $j = jQuery.noConflict();

$j(document).on('ready', function() {
	"use strict";
    // Woo account Login/Register links
    woovinaWooAccountLinks();
});

/* ==============================================
WOOCOMMERCE ACCOUNT LOGIN/REGISTER LINKS
============================================== */
function woovinaWooAccountLinks() {
	"use strict"

	// Return if registration disabled
	if($j('.wvn-account-links').hasClass('registration-disabled')) {
		return;
	}
	
	// Vars
	var $login 		= $j('.wvn-account-links .login a'),
		$register 	= $j('.wvn-account-links .register a'),
		$col_1 		= $j('#customer_login .col-1'),
		$col_2 		= $j('#customer_login .col-2');

	// Display login form
	$login.on('click', function() {
		
		$j(this).addClass('current');
		$register.removeClass('current');

		$col_1.siblings().stop().fadeOut(function() {
			$col_1.fadeIn();
		});

		return false;
	});

	// Display register form
	$register.on('click', function() {
		
		$j(this).addClass('current');
		$login.removeClass('current');

		$col_2.siblings().stop().fadeOut(function() {
			$col_2.fadeIn();
		});

		return false;
	});

}