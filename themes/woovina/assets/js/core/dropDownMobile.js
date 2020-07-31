var $j 		= jQuery.noConflict(),
	$window = $j(window);

$j(document).on('ready', function() {
	"use strict";
	// Drop down mobile menu
	woovinaDropDownMobile();
});

/* ==============================================
DROPDOWN MOBILE SCRIPT
============================================== */
function woovinaDropDownMobile() {
	"use strict"

	if($j('body').hasClass('dropdown-mobile')) {

		// Open drop down menu
		$j('.mobile-menu').on('click', function() {
			$j('#mobile-dropdown').slideToggle(500);
			$j(this).toggleClass('opened');
			$j('.mobile-menu > .hamburger').toggleClass('is-active');
			return false;
		});

		// Close menu function
		var woovinaDropDownMobileClose = function(e) {
			$j('#mobile-dropdown').slideUp(200);
			$j('.mobile-menu').removeClass('opened');
			$j('.mobile-menu > .hamburger').removeClass('is-active');
        }

		// Declare useful vars
		var $hasChildren = $j('#mobile-dropdown .menu-item-has-children');

		// Add dropdown toggle (plus)
		$hasChildren.children('a').append('<span class="dropdown-toggle"></span>');

		// Toggle dropdowns
		var $dropdownTarget = $j('.dropdown-toggle');

		// Check localization
		if(woovinaLocalize.sidrDropdownTarget == 'link') {
			$dropdownTarget = $j('#mobile-dropdown li.menu-item-has-children > a');
		}

		// Add toggle click event
		$dropdownTarget.on('tap click', function() {

			// Define toggle vars
			if(woovinaLocalize.sidrDropdownTarget == 'link') {
				var $toggleParentLi = $j(this).parent('li');
			} else {
				var $toggleParentLink = $j(this).parent('a'),
					$toggleParentLi   = $toggleParentLink.parent('li');
			}

			// Get parent items and dropdown
			var $allParentLis = $toggleParentLi.parents('li'),
				$dropdown     = $toggleParentLi.children('ul');

			// Toogle items
			if(! $toggleParentLi.hasClass('active')) {
				$hasChildren.not($allParentLis).removeClass('active').children('ul').slideUp('fast');
				$toggleParentLi.addClass('active').children('ul').slideDown('fast');
			} else {
				$toggleParentLi.removeClass('active').children('ul').slideUp('fast');
			}

			// Return false
			return false;

		});

		// Close menu
		$j(document).on('click', function() {
			woovinaDropDownMobileClose();
		}).on('click', '#mobile-dropdown', function(e) {
		    e.stopPropagation();
		});

		// Close on resize
		$window.resize(function() {
			if($window.width() >= 960) {
				woovinaDropDownMobileClose();
			}
		});

		// Close menu if anchor link
        $j('#mobile-dropdown li a[href*="#"]:not([href="#"])').on('click', function() {
        	woovinaDropDownMobileClose();
	    });

	}

}