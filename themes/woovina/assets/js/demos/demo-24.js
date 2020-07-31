jQuery(function ($) {

	// Move Compare Button & Wishlist Button & Countdown

	$('ul.products.woovina-row.grid li.product, div.wew-woo-slider ul.products li.product').each(function(index, item) {

		var wishlist   = $(item).find('div.tinv-wraper.tinv-wishlist');

		var compare    = $(item).find('a.compare.button');

		var addtocart  = $(item).find('li.btn-wrap a.button');

		var quickview  = $(item).find('li.image-wrap a.wvn-quick-view');

		var wrapper    = $(item).find('li.btn-wrap');



		var cptext	= $(compare).text();

		var qvtext  = $(quickview).text();



		// Addtocart

        $(addtocart).attr("id", "wvn-btn-addtocart");

        $(addtocart).attr("data-hover", $(addtocart).text());

        var loadingatc = $('<div class="loading-atc"></div>');

        var dotloading = $('<div class="dot-loading-atc"></div>').appendTo(loadingatc);

        $(loadingatc).appendTo(addtocart);

		$(addtocart).wrap('<div class="btn-addtocart"></div>');

        

        

        // Quickview

        $(quickview).wrap('<div class="btn-quickview"></div>');

        var spanloadingquickview = $('<span class="loading-quickview"></span>').appendTo(quickview);

        

        

        //Wishlist Compare

		$(compare).wrap('<div class="btn-action btn-compare"></div>');

		compare   = $(item).find('div.btn-action.btn-compare');

        

		

        // Add class for button Wishlist

		$(wishlist).addClass('btn-action btn-wishlist');

		$(wishlist).find('div.tinvwl-tooltip').attr('class', 'tooltip for-wishlist'); 

        $(wishlist).find('div.tinv-wishlist-clear').remove();



		var btngroup 	= $('<div class="btn-group"></div>');

		// Add tooltip for buttons

		$("<div class='tooltip for-compare'>" + cptext + "</div>").appendTo($(compare));

		$("<div class='tooltip for-quickview'>" + qvtext + "</div>").appendTo($(quickview));

		

		

		

		$(wishlist).appendTo($(btngroup));

		$(compare).appendTo($(btngroup));

		$(btngroup).appendTo($(wrapper));

        

        

        

        // Rename btnwrap to btngroups

        $(wrapper).toggleClass('btn-wrap btn-action-wrap');

        

        var wooentryimage = $(item).find('li.image-wrap div.woo-entry-image');

        var btngroup = $(item).find('li.btn-action-wrap div.btn-group').clone().appendTo(wooentryimage);

        

        

        

		// Move Countdown 

		var countdown  = $(item).find('div.jquery-countdown');

		var imgwrapper = $(item).find('div.woo-entry-image');

        

		if($(countdown).length) {

			$(countdown).removeClass('is-countdown');

			$(countdown).appendTo($(imgwrapper));

			$(item).find('div.jquery-countdown').each(function(index) {

				$(this).countdown({until: new Date($(this).attr('data-timer')), format: 'DHMS', padZeroes: true}); 		

			});

		}

        

        //span onslae

        var onsale = $(item).find('li.image-wrap span.onsale');

        $(onsale).clone().prependTo(imgwrapper);

        

        

        //Out of stock

        var outofstockbadge = $(item).find('li.image-wrap div.outofstock-badge');

        var uostext = $(outofstockbadge).text();

        var iconoutofstock = $('<i class="icon-outofstock"></i>').prependTo(outofstockbadge);

        var textoutofstock = $("<span class='text-outofstock'>" + uostext + "</span>").appendTo(outofstockbadge);

        $(outofstockbadge).clone().appendTo(imgwrapper);

        

        

        // Variable Swatches

        var wooentryimage = $(item).find('div.woo-entry-image');

        var variableswatches = $(item).find('div.shopswatchinput');

        $(variableswatches).prependTo(wooentryimage);

        

	});

	



    



    

    

    

	// For single product page

	if($('body.single-product').length) {

		var wishlist = $('body.single-product div.entry-summary').find('div.tinv-wraper');

		var compare  = $('body.single-product div.entry-summary').find('a.compare.button');

		var formcart = $('body.single-product div.entry-summary').find('form.cart');

		var cptext	 = $(compare).html();

		

		// Add wrapper for the Compare

		$(compare).wrap('<div class="btn-action btn-compare"></div>');

		compare = $('body.single-product div.entry-summary').find('div.btn-action.btn-compare');

		

		$('<div class="btn-single-page"></div>').appendTo($(formcart));

		var wrapper = $('body.single-product div.entry-summary').find('.btn-single-page');

		

		// Add class for button Wishlist

		$(wishlist).addClass('btn-action btn-wishlist');

		

		$(wishlist).appendTo($(wrapper));

		$(compare).appendTo($(wrapper));

        

        

        

        // Show More Description

        var desc = $('body.single-product div.woocommerce-tabs div#tab-description').addClass('fold');

        var ManageFold = $('<div id="manageFold"></button>');

        $('<button class="show more">Show More</button>').prependTo($(ManageFold));

        $('<button class="show less">Show Less</button>').appendTo($(ManageFold));

        $(desc).after($(ManageFold));

        

        

        $("#manageFold button.show.less").on('click', function (event){

           $(desc).addClass('fold');

        });



        $("#manageFold button.show.more").on('click', function (event){

          $(desc).removeClass('fold');

        });

        

	}

	

	// After added to cart

	$(document.body).on('added_to_cart', function(){

		$('.btn-addtocart .added_to_cart').each(function(index, item) {

			var txtAdded = $(item).text();

			$(item).next().text(txtAdded);

		});

	});

	

	// Add carousel for Thumbnail images

	$('.single-product .product.wvn-tabs-layout-horizontal .woocommerce-product-gallery .flex-control-thumbs').slick({

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









