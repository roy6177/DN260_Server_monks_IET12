window.loadScript = function(el){
    // Vendor path is not empty
    if (a4bjs.vendorJsPath !== '') {
        console.log('start loading barcodes scripts');
        jQuery.when(
            jQuery.getScript(a4bjs.appJsPath),
            jQuery.getScript(a4bjs.vendorJsPath),
        ).done(function(){
            console.log('end loading barcodes scripts');
            el.click();
        });
    } else {
        jQuery.getScript(a4bjs.appJsPath, function(){
            console.log('vendor js path is empty');
            el.click();
        })
    }
}

jQuery(document).ready(function () {
    // var menu = jQuery('#toplevel_page_wpbcu-barcode-generator a')
    var menu = jQuery('a[href*="page=wpbcu-barcode-generator"]');
    console.log('menu items: ', menu.length);
    menu.off('click');

    menu.click(function(e){
        e.preventDefault();
        e.stopPropagation();
        console.log('click on menu: ', menu.attr('href'));
        menu.off('click');
        loadScript(jQuery(this));
        return false;
    })
})
