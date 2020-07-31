jQuery(function ($) {
    // Header -- MainMenu
    $('header#site-header div#site-header-inner div#site-navigation-wrap').each(function(index, item) {
        var stl = $(item).find('li.search-toggle-li');
        var wwl = $(item).find('li.woo-wishlist-link');
        var wmc = $(item).find('li.woo-menu-icon');
        var wmm = $('<ul class="wvn-main-menu"></ul>');

        $(stl).appendTo(wmm);
        $(wwl).appendTo(wmm);
        $(wmc).appendTo(wmm);

        var mmm = $(item).find('ul#menu-main-menu');
        $(wmm).appendTo(mmm);
    });

	// Move Compare Button & Wishlist Button & Countdown
	$('ul.products.grid li.product, div.wew-woo-slider ul.products li.product').each(function(index, item) {
        var categoryname = $(item).find('li.category');
        var titlename    = $(item).find('li.title');
        var innername    = $(item).find('li.inner');
        var woodesc      = $(item).find('li.woo-desc');

        // Add wrapper for the metawrap products
        var metawrap      = $('<li class="meta-wrap"></li>');
        
        // Add category, title, inner, woodesc to metawrap
        $(categoryname).appendTo($(metawrap));
        $(titlename).appendTo($(metawrap));
        $(innername).appendTo($(metawrap));
        $(woodesc).appendTo($(metawrap));
        
        // Add metawrap to wooentryinner
        var wooentryinner = $(item).find('ul.woo-entry-inner');
        $(metawrap).appendTo($(wooentryinner));
        
        // Move btnwrap to wooentryinner
        var btnwrap = $(item).find('li.btn-wrap');
        $(btnwrap).appendTo($(wooentryinner));   
        
        // Move spanonsale, outofstock, jquerycountdown, addtocart to wooentryimage
        var wooentryimage   = $(item).find('li.image-wrap div.woo-entry-image');
        var spanonsale      = $(item).find('li.image-wrap span.onsale');
        $(spanonsale).appendTo($(wooentryimage));
        var outofstock      = $(item).find('li.image-wrap div.outofstock-badge');
        $(outofstock).appendTo($(wooentryimage));
        var jquerycountdown = $(item).find('div.product-inner div.jquery-countdown');
        $(jquerycountdown).appendTo($(wooentryimage));
        var addtocart       = $(item).find('li.btn-wrap a.button');
        $(addtocart).wrap('<div class="btn-addtocart"></div>');
        addtocart           = $(item).find('div.btn-addtocart');
        $(addtocart).appendTo(wooentryimage);
    
        var quickview    = $(item).find('li.image-wrap a.wvn-quick-view');
        var wishlist     = $(item).find('a.tinvwl_add_to_wishlist_button');
        var compare      = $(item).find('a.compare');
        
        var qvtext  = $(quickview).text();
        var wltext	= $(wishlist).text();
        var cptext	= $(compare).text();
        
        // Add wrapper for the Add to cart & Compare
		$(quickview).wrap('<div class="btn-qwc btn-quickview"></div>');
		$(wishlist).wrap('<div class="btn-qwc btn-wishlist"></div>');
		$(compare).wrap('<div class="btn-qwc btn-compare"></div>');
        
        // Move quickview, wishlist, compare to btnwrap
        quickview = $(item).find('div.btn-qwc.btn-quickview');
        wishlist = $(item).find('div.btn-qwc.btn-wishlist');
        compare = $(item).find('div.btn-qwc.btn-compare');
        
        $(quickview).appendTo($(btnwrap));
        $(wishlist).appendTo($(btnwrap));
        $(compare).appendTo($(btnwrap));
        
        quickview = $(item).find('div.btn-qwc.btn-quickview');
        wishlist = $(item).find('div.btn-qwc.btn-wishlist');
        compare = $(item).find('div.btn-qwc.btn-compare');
        
        // Add tooltip for buttons
        $("<div class='tooltip quickview'>" + qvtext + "</div>").appendTo($(quickview));
        $("<div class='tooltip wishlist'>" + wltext + "</div>").appendTo($(wishlist));
        $("<div class='tooltip compare'>" + cptext + "</div>").appendTo($(compare));
        
        // Remove wishlistwrap
        var wishlistwrap = $(item).find('div.product-inner div.tinv-wraper');
        $(wishlistwrap).remove();
        
        // Rename btnwrap to btngroups
        $(btnwrap).toggleClass('btn-wrap btn-groups');
		
        // Move btngroups to wooentryimage
        var btngroups = $(item).find('li.btn-groups');
        $(btngroups).appendTo($(wooentryimage));
        // Clone to metawrap
        $(addtocart).clone().appendTo(metawrap);
        $(btngroups).clone().appendTo(metawrap);
	});

	// For single product page
	if($('body.single-product').length) {
		var wishlist = $('body.single-product div.entry-summary').find('div.tinv-wishlist');
		var compare  = $('body.single-product div.entry-summary').find('a.compare');
		var formcart = $('body.single-product div.entry-summary').find('form.cart');
		$("<i class='icon-refresh icons'></i>").appendTo($(compare));
       
        
		// Add wrapper for the Compare, Wishlist
		$(wishlist).wrap('<div class="btn-wc btn-wishlist"></div>');
		wishlist = $('body.single-product div.entry-summary').find('div.btn-wc.btn-wishlist');
        $(compare).wrap('<div class="btn-wc btn-compare"></div>');
		compare = $('body.single-product div.entry-summary').find('div.btn-wc.btn-compare');
        
        
		$('<div class="btn-groups"></div>').appendTo($(formcart));
		var btngroups = $('body.single-product div.entry-summary').find('.btn-groups');
		
		$(wishlist).appendTo($(btngroups));
		$(compare).appendTo($(btngroups));
	}
	
	// After added to cart
	$(document.body).on('added_to_cart', function(){
		$('.btn-addtocart .added_to_cart').each(function(index, item) {
			var txtAdded = $(item).text();
			$(item).next().text(txtAdded);
		});
	});

    // Close menu cart
    $(document).on("ready", function () {
    $('body').delegate('.elementor-menu-cart__close-button', 'click', function(e){
            e.preventDefault();
            $('body').removeClass('show-cart');
            $('.wvn-cart-overlay').hide();
        })
    });

    // Products tabs
    $(document).on("ready", function () {
        $(document).on('click', '.wew-tabs-wrap', function () {
            $(this).addClass('open-show');
        }).on('click', '.wew-tab-title', function (e) {
            e.stopPropagation();
            $(this).closest('.wew-tabs-wrap').removeClass('open-show');
        });
    });
    
    // Control single products
    $(document).ready(function() {
		$('.single-product .wvn-tabs-layout-horizontal .woocommerce-product-gallery .flex-control-thumbs').slick({
			dots: false,
			infinite: true,
			speed: 300,
			slidesToShow: 4,
			slidesToScroll: 4,
			responsive: [
				{
					breakpoint: 1024,
					settings: {
						slidesToShow: 4,
						slidesToScroll: 3,
						infinite: true,
					}
				},
				{
					breakpoint: 600,
					settings: {
						slidesToShow: 3,
						slidesToScroll: 2
					}
				},
				{
					breakpoint: 480,
					settings: {
						slidesToShow: 3,
						slidesToScroll: 1
					}
				}
			]
		});
	});
	
    // Blogs
    $('.wew-carousel.wew-carousel-blog').each(function(index, item) {
        var wecm     = $(item).find('a.wew-carousel-entry-img');

        // Add wrap overlay
        var imgoverlay      = $('<div class="image-overlay"></div>');
        $(imgoverlay).appendTo($(wecm));
    });
});















