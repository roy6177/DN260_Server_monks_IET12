<?php

namespace UkrSolution\WpBarcodesGenerator\BarcodeTemplates;

class BarcodeTemplate
{
    protected $patterns = array(
        '/\[barcode_img_url]/',
        '/\[2dcode_img_url]/',
        '/\[product_image_url]/',
        '/\[code]/',
        '/\[name]/',
        '/\[text1]/',
        '/\[text2]/',
        '/\[field=ID]/',
        '/\[cf=_regular_price]/',
        '/\[cf=_price]/',
        '/\[attr=Size]/',
        '/\[attr=Color]/',
    );

    protected $replacements = array(
        A4B_PLUGIN_BASE_URL.'assets/img/example_barcode1d.svg',
        A4B_PLUGIN_BASE_URL.'assets/img/example_barcode2d.svg',
        A4B_PLUGIN_BASE_URL.'assets/img/product-img1.png',
        '190198457325',
        'Apple iPhone X 64Gb',
        '799.99 $',
        'Computers & Electronics',
        '123',
        '100.00 $',
        '89.99 $',
        'XL',
        'Green',
    );

    protected $firstTemplate = '<div class="barcode-print barcode-label-text text-1" style="max-height: 17.6px; overflow: hidden; font-size: 16px;">
    Apple iPhone X 64Gb Silver
</div>
<div class="barcode-print barcode-label-text no-wrap" style="font-size: 16px; max-height: 17.6px;">
    799.99 $
</div>
<div class="barcode-print barcode-label-image-wrapper">
    <img width="100%" src="'.A4B_PLUGIN_BASE_URL.'assets/img/example_barcode1d.svg">
</div>
<div class="barcode-print barcode-label-text no-wrap" style="font-size: 16px; max-height: 17.6px;">
    190198457325
</div>
<div class="barcode-print barcode-label-text no-wrap" style="font-size: 16px; max-height: 17.6px;">
    Computers & Electronics
</div>';

    /**
     * BarcodeTemplate constructor.
     *
     * @param $rowObject
     */
    public function __construct($object)
    {
        $this->mergeWith($object);
    }

    /**
     * Get preview.
     */
    public function getPreview()
    {
        // Static preview for template with id=1
        if (1 == $this->id) {
            return $this->firstTemplate;
        }

        $preview = preg_replace($this->patterns, $this->replacements, $this->template);

        return $preview;
    }

    /**
     * Merge given object/array fields to own fields.
     *
     * @param $object
     */
    protected function mergeWith($object)
    {
        // If given variable is object or is array
        if (is_array($object) || is_object($object)) {
            // Add each object field to own fields.
            foreach ($object as $key => $value) {
                $this->$key = $value;
            }
        }
    }
}
