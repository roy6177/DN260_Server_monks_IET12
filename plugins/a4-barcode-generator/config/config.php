<?php

$result = array(
    'listAlgorithm' => array( // list of barcode algorithms
        array('key' => 'C128', 'label' => 'CODE-128'),
        array('key' => 'C39', 'label' => 'CODE-39'),
        array('key' => 'DATAMATRIX', 'label' => 'DataMatrix'),
        array('key' => 'EAN13', 'label' => 'EAN 13'),
        array('key' => 'EAN8', 'label' => 'EAN 8'),
        array('key' => 'QRCODE', 'label' => 'QRCODE'),
        array('key' => 'UPCA', 'label' => 'UPC-A'),
        array('key' => 'UPCE', 'label' => 'UPC-E'),
    ),
    'listMessagesValidationRules' => array(
        /* translators: %s: Name of a validated field */
        'required' => __('No %s parameter specified.', 'wpbcu-barcode-generator'),
        /* translators: %s: Name of a validated field */
        'required_item' => __('Please select at least one item and try again.', 'wpbcu-barcode-generator'),
        /* translators: %s: Name of a validated field */
        'numeric' => __('The %s parameter is not a number.', 'wpbcu-barcode-generator'),
        /* translators: %s: Name of a validated field */
        'boolean' => __('The %s parameter is not a boolean.', 'wpbcu-barcode-generator'),
        /* translators: %s: Name of a validated field */
        'string' => __('The %s parameter is not a string.', 'wpbcu-barcode-generator'),
        /* translators: %s: Name of a validated field */
        'array' => __('The %s parameter is not an array.', 'wpbcu-barcode-generator'),
        /* translators: %s: Name of a validated field */
        'in' => __('In the %s parameter, an invalid value was specified.', 'wpbcu-barcode-generator'),
        /* translators: %s: Name of a validated field */
        'xml' => __('Markup should be valid XML in the %s parameter.', 'wpbcu-barcode-generator'),
        /* translators: %s: Name of a validated field */
        'custom_field' => __('Posts not found with custom field: %s.', 'wpbcu-barcode-generator'),
        /* translators: %s: Name of a validated field */
        'not_empty' => __('Field "%s" should not be empty.', 'wpbcu-barcode-generator'),
    ),
    'folderBarcode' => 'a4_barcodes', // folder where barcode images are saved
    'testBarcodes' => array(
        'algorithms' => array(
            'C39' => array('short' => 'SKU-MGLASS-1234', 'long' => 'SKU-MGLASS-1234'),
            'C128' => array('short' => 'SKU-MGLASS-1234', 'long' => 'SKU-MGLASS-1234'),
            'QRCODE' => array('short' => 'SKU-MGLASS-1234', 'long' => 'http://wp4.julia-v.ukrsol.com/product/beautiful-murano-glass-vase-copy/'),
            'DATAMATRIX' => array('short' => 'SKU-MGLASS-1234', 'long' => 'http://wp4.julia-v.ukrsol.com/product/beautiful-murano-glass-vase-copy/'),
            'EAN8' => array('short' => '73127727', 'long' => '73127727'),
            'EAN13' => array('short' => '4006381333931', 'long' => '4006381333931'),
            'UPCA' => array('short' => '725272730706', 'long' => '725272730706'),
            'UPCE' => array('short' => '06141939', 'long' => '04252614'),
//            'UPCE' => array('short' => '614193', 'long' => '425261'),
        ),
        'names' => array(
            'short' => 'Murano Glass vase',
            'long' => 'Beautiful Murano Glass vase from Venice, Italy - M Size, Green Color, Authenticity Certificate',
        ),
        'texts1' => array(
            'short' => 'Shipped from Murano',
            'long' => 'Shipped directly from Murano island from murano artists - genuine glass with certificates of authenticity attached',
        ),
        'texts2' => array(
            'short' => 'Check out more',
            'long' => 'Check out more details about Murano Glass on our website, subscribe to our weekly updates & more',
        ),
    ),
);

return $result;
