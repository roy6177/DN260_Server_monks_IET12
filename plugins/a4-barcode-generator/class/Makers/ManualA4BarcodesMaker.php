<?php

namespace UkrSolution\WpBarcodesGenerator\Makers;

class ManualA4BarcodesMaker extends A4BarcodesMaker
{
    /**
     * {@inheritdoc}.
     */
    protected function getItems()
    {
        $this->items = $this->data['fields'];
    }

    /**
     * {@inheritdoc}.
     */
    protected function getFileOptions($itemData, $algorithm, $showCode)
    {
        return array(
            'quantity' => 1,
            'post_image' => null,
            'field_code' => isset($itemData['code']['value']) ? $itemData['code']['value'] : '', // the field from which the barcode will be created
            'field_name' => isset($itemData['name']['value']) ? $itemData['name']['value'] : '', // additional field
            'field_text' => isset($itemData['text']['value']) ? $itemData['text']['value'] : '', // additional field
            'field_text_2' => isset($itemData['text2']['value']) ? $itemData['text2']['value'] : '', // additional field
            'algorithm' => $algorithm, // barcode algorithm
            'showCode' => $showCode, // show/hide the code value
            'showName' => $this->showName,
            'showText' => $this->showText,
            'showText2' => $this->showText2,
            'replacements' => $this->getTemplateReplacements($itemData),
        );
    }

    protected function getTemplateReplacements($item)
    {
//        $replacements = array();
        $replacements = new \ArrayObject();

        // Foreach found shortcodes, replace it with empty string
        foreach ($this->templateShortcodesArgs as $shortCode => $args) {
            $replacements[$shortCode] = '';
        }

        return $replacements;
    }
}
