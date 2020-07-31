jQuery(function ($) {
    // Move Compare Button & Wishlist Button & Countdown
    $('ul.products.woovina-row.grid li.product, div.wew-woo-slider ul.products li.product').each(function(index, item) {
        var wishlist        = $(item).find('div.tinv-wraper.tinv-wishlist');
        var compare         = $(item).find('a.compare.button');
        var addtocart       = $(item).find('li.btn-wrap a.button');
        var wrapper         = $(item).find('li.btn-wrap');
        var quickview       = $(item).find('li.image-wrap a.wvn-quick-view');
        var wrapperBottom   = $(item).find('ul.woo-entry-inner');
        var atctext	        = $(addtocart).html();
        var cptext	        = $(compare).html();
        var qvtext          = $(quickview).text();

        var titlename    = $(item).find('li.title');
        var innername    = $(item).find('li.inner');
        var woodesc      = $(item).find('li.woo-desc');
        var categoryname = $(item).find('li.category');

        var wrapMeta 	= $('<div class="wrap-meta"></div>');

        $(wrapMeta).appendTo($(wrapperBottom));
        $(titlename).appendTo($(wrapMeta));
        $(categoryname).appendTo($(wrapMeta));
        $(innername).appendTo($(wrapMeta));
        $(woodesc).appendTo($(wrapMeta));

        // Add wrapper for the Add to cart & Compare
        $(addtocart).wrap('<div class="btn-inner btn-addtocart"></div>');
        $(compare).wrap('<div class="btn-inner btn-compare"></div>');
        $(quickview).wrap('<div class="btn-inner btn-quickview"></div>');
        addtocart = $(item).find('div.btn-inner.btn-addtocart');
        compare   = $(item).find('div.btn-inner.btn-compare');
        quickview = $(item).find('div.btn-inner.btn-quickview');

        var pach 	= $('<div class="btn-positions"></div>');
        var btnBottom 	= $('<div class="btn-bottom"></div>');

        // Add tooltip for buttons
        $("<div class='tooltip for-addtocart'>" + atctext + "</div>").appendTo($(addtocart));
        $("<div class='tooltip for-compare'>" + cptext + "</div>").appendTo($(compare));
        $("<div class='tooltip for-quickview'>" + qvtext + "</div>").appendTo($(quickview));

        // Add class for button Wishlist
        $(wishlist).addClass('btn-inner btn-wishlist');
        $(wishlist).find('div.tinvwl-tooltip').attr('class', 'tooltip for-wishlist');

        $(quickview).appendTo($(pach));
        $(btnBottom).appendTo($(wrapMeta));
        $(wishlist).appendTo($(pach));
        $(pach).appendTo($(wrapper));
        $(compare).appendTo($(pach));
        $(addtocart).appendTo($(pach));

        // Move Countdown
        var countdown  = $(item).find('div.jquery-countdown');
        var imgwrapper = $(item).find('div.woo-entry-image');

        $(wrapper).appendTo(imgwrapper);
        $(addtocart).clone().appendTo(btnBottom);
        $(quickview).clone().appendTo(btnBottom);
        $(wishlist).clone().appendTo(btnBottom);
        $(compare).clone().appendTo(btnBottom);

        if($(countdown).length) {
            $(countdown).removeClass('is-countdown');
            $(countdown).appendTo($(imgwrapper));
            $(item).find('div.jquery-countdown').each(function(index) {
                $(this).countdown({until: new Date($(this).attr('data-timer')), format: 'DHMS', padZeroes: true});
            });
        }
    });

    // For single product page
    if($('body.single-product').length) {
        var wishlist = $('body.single-product div.entry-summary').find('div.tinv-wraper');
        var compare  = $('body.single-product div.entry-summary').find('a.compare.button');
        var formcart = $('body.single-product div.entry-summary').find('form.cart');
        var cptext	 = $(compare).html();

        // Add wrapper for the Compare
        $(compare).wrap('<div class="btn-inner btn-compare"></div>');
        compare = $('body.single-product div.entry-summary').find('div.btn-inner.btn-compare');
        $("<div class='tooltip for-compare'>" + cptext + "</div>").appendTo($(compare));

        $('<div class="btn-single-page"></div>').appendTo($(formcart));
        var wrapper = $('body.single-product div.entry-summary').find('.btn-single-page');

        // Add class for button Wishlist
        $(wishlist).addClass('btn-inner btn-wishlist');
        $(wishlist).find('div.tinvwl-tooltip').attr('class', 'tooltip for-wishlist');

        $(wishlist).appendTo($(wrapper));
        $(compare).appendTo($(wrapper));
    }
    // After added to cart
    $(document.body).on('added_to_cart', function(){
        $('.btn-addtocart .added_to_cart').each(function(index, item) {
            var txtAdded = $(item).text();
            $(item).next().text(txtAdded);
        });
    });

    $('.single-product .product .woocommerce-product-gallery .flex-control-thumbs').slick({
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
    $(document).on("ready", function () {
        "use strict";
        $j('.select-categories h5').on('click', function (event) {
            event.preventDefault();
            $j('.select-categories .product-categories').slideToggle("fast");
        });
        $(document).on('click', '.wew-tabs-wrap', function () {
            $(this).addClass('open-show');
        }).on('click', '.wew-tab-title', function (e) {
            e.stopPropagation();
            $(this).closest('.wew-tabs-wrap').removeClass('open-show');
        });
        $('body').delegate('.elementor-menu-cart__close-button', 'click', function(e){
            e.preventDefault();
            $('body').removeClass('show-cart');
            $('.wvn-cart-overlay').hide();
        });
    });
});

