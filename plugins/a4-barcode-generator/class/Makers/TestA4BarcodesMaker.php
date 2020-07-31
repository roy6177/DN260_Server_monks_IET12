<?php

namespace UkrSolution\WpBarcodesGenerator\Makers;

class TestA4BarcodesMaker extends A4BarcodesMaker
{
    protected function getItems()
    {
        $simpleTest = isset($_POST['simpleTest']) && 'true' === $_POST['simpleTest'];
        $config = require A4B_PLUGIN_BASE_PATH.'config/config.php';
        $testBarcodesSettings = $config['testBarcodes'];
        $items = array();

        // If it is extended test, make additional barcode for each show text option
        if (!$simpleTest) {
            // Extend test collection with different show options
            foreach ($testBarcodesSettings['algorithms'] as $algorithm => $codes) {
                $algorithmName = 'DATAMATRIX' !== $algorithm ? $algorithm : 'DMATRIX';

                // Test 1
                $items[] = array(
                    'algorithm' => $algorithm,
                    'code' => $codes['long'],
                    'name' => $algorithmName.' '.$testBarcodesSettings['names']['long'],
                    'text' => '',
                    'text2' => '',
                    'showCode' => true,
                );
                // Test 2
                $items[] = array(
                    'algorithm' => $algorithm,
                    'code' => $codes['long'],
                    'name' => $algorithmName.' '.$testBarcodesSettings['names']['long'],
                    'text' => $testBarcodesSettings['texts1']['long'],
                    'text2' => $testBarcodesSettings['texts2']['long'],
                    'showCode' => true,
                );
                // Test 3
                $items[] = array(
                    'algorithm' => $algorithm,
                    'code' => $codes['long'],
                    'name' => '',
                    'text' => '',
                    'text2' => '',
                    'showCode' => false,
                );
                // Test 4
                $items[] = array(
                    'algorithm' => $algorithm,
                    'code' => $codes['long'],
                    'name' => '',
                    'text' => $testBarcodesSettings['texts1']['long'],
                    'text2' => $testBarcodesSettings['texts2']['long'],
                    'showCode' => false,
                );
                // Test 5
                $items[] = array(
                    'algorithm' => $algorithm,
                    'code' => $codes['short'],
                    'name' => $algorithmName.' '.$testBarcodesSettings['names']['short'],
                    'text' => $testBarcodesSettings['texts1']['short'],
                    'text2' => '',
                    'showCode' => false,
                );
                // Test 6
                $items[] = array(
                    'algorithm' => $algorithm,
                    'code' => $codes['short'],
                    'name' => '',
                    'text' => '',
                    'text2' => $testBarcodesSettings['texts2']['short'],
                    'showCode' => true,
                );
            }
        }

        $this->items = $items;
    }

    protected function getFileOptions($itemData, $algorithm, $showCode)
    {
        return array(
            'quantity' => 1,
            'post_image' => null,
            'algorithm' => $itemData['algorithm'],
            'field_code' => $itemData['code'],
            'field_name' => $itemData['name'],
            'field_text' => $itemData['text'],
            'field_text_2' => $itemData['text2'],
            'showCode' => $itemData['showCode'],
            'replacements' => $this->getTemplateReplacements($itemData),
        );
    }

    protected function getTemplateReplacements($item)
    {
        $replacements = array();

        // Foreach found shortcodes, replace it with empty string
        foreach ($this->templateShortcodesArgs as $shortCode => $args) {
            $replacements[$shortCode] = '';
        }

        return $replacements;
    }
}
