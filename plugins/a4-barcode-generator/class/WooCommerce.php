<?php

namespace UkrSolution\WpBarcodesGenerator;

use UkrSolution\WpBarcodesGenerator\Makers\WoocommercePostsA4BarcodesMaker;

class WooCommerce
{
    /**
     * return list of categories in json format.
     */
    public function getCategories()
    {
        // Default
        $listCategories = array();

        $args = array(
            'taxonomy' => 'product_cat',
            'orderby' => 'name',
            'hide_empty' => false,
        );

        // Sorting out all categories and preparing data for sending to the front-line
        foreach (get_categories($args) as $category) {
            $listCategories[] = array(
                'id' => $category->term_id,
                'name' => $category->name,
                'countProds' => $category->category_count,
                'parent' => $category->category_parent,
            );
        }

        a4bJsonResponse(array(
            'list' => $listCategories,
            'error' => empty($listCategories) ? array(__('No data found on request')) : array(),
            'success' => array(),
        ));
    }

    /**
     * Return the result of creating barcodes.
     */
    public function getBarcodes()
    {
        $validationRules = array(
            'productsCategories' => 'requiredItem:if_empty,productsIds|array|bail', // required only if productsIds empty
            'productsIds' => 'requiredItem:if_empty,productsCategories|array|bail', // required only if productsCategories empty
            'fieldCode' => 'required|array',
            'fieldName' => 'array',
            'fieldText' => 'array',
            'fieldText2' => 'array',
            'format' => 'required',
            'hideCode' => 'boolean',
            'withVariations' => 'boolean',
        );

        $data = Validator::create(PostData::get(), $validationRules, true)->validate();

        $postsBarcodesGenerator = new WoocommercePostsA4BarcodesMaker($data);

        a4bJsonResponse($postsBarcodesGenerator->make());
    }

    /**
     * return count products with costom field.
     */
    public function countProductsByCustomField()
    {
        global $wpdb;

        $validationOptions = array('field' => 'required|string');

        // Checking for the correct data from the request. If not valid return error result.
        $data = Validator::create(PostData::get(), $validationOptions, true)->validate();

        // Get fields list
        $fields = array_map('trim', explode(',', $data['field']));

        $counters = array();
        // Get count for each field
        foreach ($fields as $field) {
            // Key for custom field count result array
            $fieldKey = $field;

            // Skip if empty value
            if (empty($field)) {
                continue;
            } elseif (0 === strpos($field, 'product.')) {
                $field = substr($field, 8); // strlen('variation.') === 10;
            } elseif (0 === strpos($field, 'variation.')) {
                $field = substr($field, 10); // strlen('variation.') === 10;
            }

            $response = $wpdb->get_row(
                $wpdb->prepare("
                SELECT COUNT(DISTINCT p.`ID`) as 'count'
                FROM `{$wpdb->prefix}postmeta` AS pm, `{$wpdb->prefix}posts` AS p 
                WHERE pm.`meta_key` = BINARY %s
                AND pm.`post_id` = p.`ID`
                AND p.`post_type` IN('product_variation', 'product')
                ", array($field)
                )
            );

            $counters[$fieldKey] = $response->count;
        }

        a4bJsonResponse(array('counters' => $counters));
    }

    /**
     * Get woocommerce attributes info.
     *
     * @return array
     */
    public function getAttributes()
    {
        // Default value.
        $wcAttributesInfo = array('wc_taxonomies' => array());

        // If woocommerce active get woocommerce taxonomies.
        if (is_plugin_active('woocommerce/woocommerce.php')) {
            $wcAttributesInfo['wc_taxonomies'] = wc_get_attribute_taxonomies();
        }

        return a4bJsonResponse(array('wc_attributes' => $wcAttributesInfo));
    }
}
