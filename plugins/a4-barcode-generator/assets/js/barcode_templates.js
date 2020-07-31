jQuery(document).ready(function (e) {
    jQuery('#js-templates-select').on('change', function (e) {
        var templateId = jQuery(this).val();
        window.location = a4bjs.adminUrl+'admin.php?page=wpbcu-barcode-templates-edit&id=' + templateId;
    });

    // Editable template textarea
    var myTextarea = jQuery('#js-template-tpl').not('[disabled]')[0];
    var templateWrapper;
    // If found editable textarea init codemirror
    if (myTextarea) {
        var editor = CodeMirror.fromTextArea(myTextarea, {
            mode: "htmlmixed",
            lineNumbers: true,
        });
        // editor.setSize(null, 390);

        editor.on('change', function (cm, change) {
            templateWrapper.html(doReplacements(cm.getValue()));
        });
    }

    // Set template preview iframe content
    var templateIframe = jQuery('#js-template-preview-iframe');
    var template = templateIframe.attr('data-template');
    var templateWrapHtml = templateIframe.attr('data-template-wrapper');
    var templateBody = templateIframe.contents().find('body');

    templateBody.html(templateWrapHtml);
    templateWrapper = templateIframe.contents().find('.template-container');
    templateWrapper.html(template);

    /**
     * Replace placeholders to values
     *
     * @param template
     * @returns {*}
     */
    function doReplacements(template) {
        return template.replace(/\[barcode_img_url]/g, a4bjs.pluginUrl + 'assets/img/example_barcode1d.svg')
            .replace(/\[2dcode_img_url]/g, a4bjs.pluginUrl + 'assets/img/example_barcode2d.svg')
            .replace(/\[product_image_url]/g, a4bjs.pluginUrl + 'assets/img/product-img1.png')
            .replace(/\[code]/g, '190198457325')
            .replace(/\[name]/g, 'Apple iPhone X 64Gb')
            .replace(/\[text1]/g, '799.99 USD')
            .replace(/\[text2]/g, 'Computers & Electronics');
    }

    // Change preview iframe size --------------------
    var $tsWidth = jQuery('input[name="width"]');
    var $tsHeight = jQuery('input[name="height"]');
    var $tsUol = jQuery('select[name="uol"]');
    var $tsBasePadding = jQuery('input[name="base_padding"]');
    var $tsBasePaddingUol = jQuery('input[name="base_padding_uol"]');
    var $iframe = jQuery('#js-template-preview-iframe');
    jQuery('.js-template-size').on('change', function (e) {
        changeIframeSizes($tsWidth.val(), $tsHeight.val(), $tsUol.val(), $tsBasePadding.val(), $tsBasePaddingUol.val());
    });

    /**
     * Change iframe and template size dynamically.
     *
     * @param width
     * @param height
     * @param uol
     * @param basePadding
     * @param basePaddingUol
     */
    function changeIframeSizes(width, height, uol, basePadding, basePaddingUol) {
        $iframe
            .css('width', width + uol)
            .css('height', height + uol);

        // $iframe.contents().find('body').attr('style', 'padding: '+basePadding+'px');
        $iframe.contents().find('body').attr('style', 'padding: '+basePaddingUol+uol);
        // console.log($iframe.contents().find('body'));
    }

    changeIframeSizes($tsWidth.val(), $tsHeight.val(), $tsUol.val(), $tsBasePadding.val(), $tsBasePaddingUol.val());
    $iframe.css({outline: '1px dashed #333333'});
    // Change preview iframe size END-----------------

    // Change notice message -------------------------
    jQuery('select[name="uol"]').on('change', function (e) {
        console.log('change:', jQuery(this).val());
        jQuery('.js-uol-value').text(jQuery(this).val());
    });
    // Change notice message END----------------------

    // Woocommerce match -----------------------------
    jQuery('input[name="code_match"]').on('change', function (e) {
        // If code match feature checked
        if (jQuery(this).prop('checked')) {
            jQuery('.js-woocommerce-match').removeAttr('disabled');
            jQuery('#code_match_shadow').attr('disabled', true);
        } else {
            jQuery('.js-woocommerce-match').attr('disabled', true);
            jQuery('#code_match_shadow').removeAttr('disabled');
        }
    });
    // Woocommerce match END--------------------------

    jQuery('.js-nav-tab').on('click', function (e) {
        e.preventDefault();
        var tab = jQuery(this);

        // Reset styles
        jQuery('.js-nav-tab').removeClass('nav-tab-active');
        jQuery('.js-nav-tab-content').hide();

        // Show chosen tab
        jQuery(tab.attr('data-target')).show();
        tab.addClass('nav-tab-active')
    });
});
