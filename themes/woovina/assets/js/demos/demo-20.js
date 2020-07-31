jQuery(function ($) {
    // Move Compare Button & Wishlist Button & Countdown
    $('ul.products.woovina-row.grid li.product, div.wew-woo-slider ul.products li.product').each(function(index, item) {
        var wishlist   = $(item).find('div.tinv-wraper.tinv-wishlist');
        var compare    = $(item).find('a.compare.button');
        var addtocart  = $(item).find('li.btn-wrap a.button');
        var wrapper    = $(item).find('li.btn-wrap');
        var quickview  = $(item).find('li.image-wrap a.wvn-quick-view');
        var imgwrap    = $(item).find('li.image-wrap');
        var entryimage = $(item).find('div.woo-entry-image');

        var atctext = $(addtocart).html();
        var cptext  = $(compare).html();
        var qvtext  = $(quickview).text();

        // Add wrapper for the Add to cart & Compare
        $(addtocart).wrap('<div class="btn-inner btn-addtocart"></div>');
        $(compare).wrap('<div class="btn-inner btn-compare"></div>');
        $(quickview).wrap('<div class="btn-inner btn-quickview"></div>');
        addtocart = $(item).find('div.btn-inner.btn-addtocart');
        compare   = $(item).find('div.btn-inner.btn-compare');
        quickview = $(item).find('div.btn-inner.btn-quickview');

        var pach    = $('<div class="btn-positions"></div>');
        // Add tooltip for buttons
        $("<div class='tooltip for-compare'>" + cptext + "</div>").appendTo($(compare));
        $("<div class='tooltip for-quickview'>" + qvtext + "</div>").appendTo($(quickview));
        
        // Add class for button Wishlist
        $(wishlist).addClass('btn-inner btn-wishlist');
        $(wishlist).find('div.tinvwl-tooltip').attr('class', 'tooltip for-wishlist');
        
        $(wishlist).appendTo($(pach));
        $(compare).appendTo($(pach));
        $(quickview).appendTo($(pach));
        $(addtocart).appendTo($(wrapper));

        $(pach).appendTo($(entryimage));
        $(pach).clone().appendTo($(imgwrap));

        // Move Countdown
        var countdown = $(item).find('div.jquery-countdown');

        if ($(countdown).length) {
            $(countdown).removeClass('is-countdown');
            $(countdown).appendTo($(imgwrap));
            $(item).find('div.jquery-countdown').each(function (index) {
                $(this).countdown({until: new Date($(this).attr('data-timer')), format: 'DHMS', padZeroes: true});
            });
        }
    });

    // For single product page
    if($('body.single-product').length) {
        var wishlist = $('body.single-product div.entry-summary').find('div.tinv-wraper');
        var compare  = $('body.single-product div.entry-summary').find('a.compare.button');
        var formcart = $('body.single-product div.entry-summary').find('form.cart');
        var sale     = $('body.single-product div.product').find('.onsale');    
        var imagewrap= $('body.single-product div.product').find('.flex-viewport');
        var cptext   = $(compare).html();
        
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
        $(sale).appendTo($(imagewrap));
    }
    
    // After added to cart
    $(document.body).on('added_to_cart', function(){
        $('.btn-addtocart .added_to_cart').each(function(index, item) {
            var txtAdded = $(item).text();
            $(item).next().text(txtAdded);
        });
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

    $('.single-product .product .woocommerce-product-gallery .flex-control-thumbs').slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 4,
        slidesToScroll: 4,
        vertical: true,
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
