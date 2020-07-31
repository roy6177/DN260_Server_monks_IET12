<?php

namespace UkrSolution\WpBarcodesGenerator\Makers;

use UkrSolution\WpBarcodesGenerator\Barcodes;
use UkrSolution\WpBarcodesGenerator\BarcodeTemplates\BarcodeTemplatesController;

/**
 * Class A4BarcodesMaker.
 *
 * Class for generating barcodes image files for items provided by getItems method, and sending result as array.
 */
abstract class A4BarcodesMaker
{
    protected $items = array();
    protected $barcodes = array();
    protected $success = array();
    protected $errors = array();
    protected $showName = true;
    protected $showText = true;
    protected $showText2 = true;
    protected $a4barcodes;
    protected $activeTemplate;
    protected $pattern;
    protected $templateShortcodesArgs = array();
//    protected $templateCodesArgs = array();
    protected $data;

    /**
     * A4BarcodesMaker constructor.
     *
     * @param array $data
     */
    public function __construct($data = array())
    {
        $this->data = $data;
        $this->a4barcodes = new Barcodes();

        // Using custom regex.
//        $this->pattern = '\[(([a-zA-Z0-9_]+)=(.+))\]';
        $this->pattern = '\[(([a-zA-Z0-9_]+)=([^]]+))\]';

        $customTemplatesController = new BarcodeTemplatesController();
        $this->activeTemplate = $customTemplatesController->getActiveTemplate();

        $this->templateShortcodesArgs = $this->getTemplateShortcodesArgs();
//        $this->templateCodesArgs = $this->getTemplateCodesArgs();
    }

    /**
     * Template method for generating resulting array and send.
     */
    public function make()
    {
        $this->getItems();
        $this->generateBarcodes();

        return $this->getResult();
    }

    /**
     * Array of items for which maker should generate barcodes.
     *
     * @return array
     */
    abstract protected function getItems();

    abstract protected function getFileOptions($itemData, $algorithm, $showCode);

    /**
     * Generate barcodes and form result.
     */
    protected function generateBarcodes()
    {
        $algorithm = $this->data['format'];
        $showCode = !$this->data['hideCode'];

        // Get code prefix option value
        $codePrefix = get_option('wpbcu_barcode_generator_barcode_prefix');

        // For each item generate barcode image
        foreach ($this->items as $itemData) {
            $fileOptions = $this->getFileOptions($itemData, $algorithm, $showCode);

            $fileOptionsForCodeGeneration = $fileOptions;

            // If prefix option not empty add it to code value
            if (!empty($codePrefix)) {
                $fileOptionsForCodeGeneration['field_code'] = $codePrefix.$fileOptionsForCodeGeneration['field_code'];
            }

            $validationResult = $this->a4barcodes->validateBarcode($fileOptions['field_code'], $fileOptions['algorithm']);

            // if the barcode is correct
            if ($validationResult['is_valid']) {
                // create barcode image by settings
//                $fileImage = $this->a4barcodes->generateFile($fileOptions);
                $fileImage = $this->a4barcodes->generateFile($fileOptionsForCodeGeneration);

                // Prepare barcode data
                $barcodeData = array(
                    'image' => $fileImage,
                    'post_image' => $fileOptions['post_image'],
                    'field_code' => $fileOptions['showCode'] ? $fileOptions['field_code'] : false,
                    'field_name' => $fileOptions['field_name'],
                    'field_text' => $fileOptions['field_text'],
                    'field_text_2' => $fileOptions['field_text_2'],
                    'format' => $fileOptions['algorithm'],
                    'replacements' => $fileOptions['replacements'],
                );

                // Add to results. Take into account quantity parameter.
                for ($i = $fileOptions['quantity']; $i > 0; --$i) {
                    $this->barcodes[] = $barcodeData;
                }
            } else { // if the barcode is not correct
                $this->errors[] = array(
                    'id' => $itemData->ID,
                    'name' => $fileOptions['field_name'],
                    'code' => $validationResult['message'] ? $validationResult['message'] : $fileOptions['field_code'],
                    'text' => $fileOptions['field_text'],
                    'text2' => $fileOptions['field_text_2'],
                    'format' => $fileOptions['algorithm'],
                );
            }
        }
    }

    /**
     * Send result.
     */
    protected function getResult()
    {
        $result = array(
            'listItems' => $this->barcodes,
            'success' => $this->success,
            'error' => $this->errors,
        );

        return $result;
    }

    /**
     * Get replacements for templates.
     *
     * @return \ArrayObject
     */
    protected function getTemplateReplacements($item)
    {
//        return array();
        return new \ArrayObject();
    }

    protected function getTemplateShortcodesArgs()
    {
        $shortcodesArgs = array();

        // If shortcodes found. Using custom regex.
        if (preg_match_all('/'. $this->pattern .'/s', $this->activeTemplate->template, $matches)) {

            // Match shortcode full text and it arguments array
            foreach ($matches[0] as $key => $shortCode) {
//                $shortcodesArgs[$shortCode] = shortcode_parse_atts($matches[1][$key]);
                $shortcodesArgs[$shortCode] = $this->templateParseAtts($matches[1][$key]);
            }
        }

        // Check for placeholder for categories
        if (false !== strpos($this->activeTemplate->template, '[category]')) {
//            $shortcodesArgs['[category]'] = array('category' => '');
            $shortcodesArgs['[category]'] = array('type' => 'category');
        }

        return $shortcodesArgs;
    }

//    protected function getTemplateCodesArgs()
//    {
//        // [code show=true type=variable parent.cf1=_sku static1=&amp; cf1=_sku]
//        $shortcodesArgs = array();
//        $pattern = get_shortcode_regex(array('code'));
//
//        // If shortcodes found. Using custom regex.
//        if (preg_match_all('/'. $pattern .'/s', $this->activeTemplate->template, $matches)) {
//            // Match shortcode full text and it arguments array
//            foreach ($matches[0] as $key => $shortCode) {
//                $args = shortcode_parse_atts($matches[3][$key]);
//
//                // Save code arguments
//                if (isset($args['type'])) {
//                    $shortcodesArgs[$args['type']] = $args;
//                } elseif (!empty($args)) {
//                    $shortcodesArgs['all'] = $args;
//                }
//            }
//        }
//
////        echo '<pre>';
////        print_r($shortcodesArgs);
////        die();
//
//        return $shortcodesArgs;
//    }

    protected function templateParseAtts($attsString)
    {
        $equalSignPos = strpos($attsString, '=');
        $attr = substr($attsString, 0, $equalSignPos);
        $value = trim(substr($attsString, ++$equalSignPos), "\"'");
        $term = null;

        // Check for term meta key
        $termPos = strpos($value, ' term=');
        // Check for term meta key
        if (false !== $termPos) {
            $term = trim(substr($value, ($termPos + strlen(' term='))), "\"'");
            $value = substr($value, 0, $termPos);
        }

//        return array($attr => $value, 'term_meta' => $term);
        return array('type' => $attr, 'value' => $value, 'term_meta' => $term);
    }
}
