<?php

namespace UkrSolution\WpBarcodesGenerator\Makers;

use UkrSolution\WpBarcodesGenerator\Enums\CustomFieldPriority;

class WoocommercePostsA4BarcodesMaker extends A4BarcodesMaker
{
    protected $currency = '';

    protected $currencyPosition = 'left';

    protected $getItemsMethods = array(
        true => array(
            true => 'getItemsForCategoriesWithVariations',
            false => 'getItemsForCategories',
        ),
        false => array(
            true => 'getItemsForProductsWithVariations',
            false => 'getItemsForProducts',
        ),
    );

    /**
     * WoocommercePostsA4BarcodesMaker constructor.
     *
     * @param $data
     */
    public function __construct($data)
    {
        parent::__construct($data);
        // Check if woocommerce plugin active
        if (is_plugin_active('woocommerce/woocommerce.php')) {
            $this->currency = html_entity_decode(get_woocommerce_currency_symbol());
            $this->currencyPosition = get_option( 'woocommerce_currency_pos' );
        }
    }

    /**
     * {@inheritdoc}.
     */
    protected function getItems()
    {
        // Get params to determine which get items method should be used
        $forCategories = !empty($this->data['productsCategories']);
        $withVariations = !empty($this->data['withVariations']);

        $getItemsMethod = $this->getItemsMethods[$forCategories][$withVariations];
        $this->items = $this->$getItemsMethod();
    }

    /**
     * Get items for categories ids with variations.
     *
     * @return array
     */
    protected function getItemsForCategoriesWithVariations()
    {
        // Get data from request
        $productsCategories = isset($this->data['productsCategories']) ? $this->data['productsCategories'] : null;

        // Get all variations and products without variations in given categories
        $products = a4bGetPostsByCategories($productsCategories);
        $variations = a4bGetPosts(array('post_type' => 'product_variation', 'post_parent__in' => a4bObjectsFieldToArray($products, 'ID')));
        $productsWithoutVariations = a4bExcludePostsByIds($products, a4bObjectsFieldToArray($variations, 'post_parent'));

        return array_merge($productsWithoutVariations, $variations);
    }

    /**
     * Get items for categories ids.
     *
     * @return array
     */
    protected function getItemsForCategories()
    {
        // Get data from request
        $productsCategories = isset($this->data['productsCategories']) ? $this->data['productsCategories'] : null;

        // Get only products in given categories
        return a4bGetPostsByCategories($productsCategories);
    }

    /**
     * Get items for products ids with variations.
     *
     * @return array
     */
    protected function getItemsForProductsWithVariations()
    {
        // Get data from request
        $productsIds = isset($this->data['productsIds']) ? $this->data['productsIds'] : null;

        $products = a4bGetPosts(array('post__in' => $productsIds));
        $variations = a4bGetPosts(array('post_type' => 'product_variation', 'post_parent__in' => $productsIds));
        $productsWithoutVariations = a4bExcludePostsByIds($products, a4bObjectsFieldToArray($variations, 'post_parent'));
        $productsWithVariations = array_values(array_merge($productsWithoutVariations, $variations));

        return $this->sortByIds($productsWithVariations, $productsIds);
    }

    /**
     * Get items for products.
     *
     * @return array
     */
    protected function getItemsForProducts()
    {
        // Get data from request
        $productsIds = isset($this->data['productsIds']) ? $this->data['productsIds'] : null;
        $products = a4bGetPosts(array('post__in' => $productsIds));

        return $this->sortByIds($products, $productsIds);
    }

    /**
     * {@inheritdoc}.
     */
    protected function getFileOptions($post, $algorithm, $showCode)
    {
        // Check if we need generate barcodes for each item in stock.
        if (
            isset($_POST['useStockQuantity'])
            && 'true' === $_POST['useStockQuantity']
            && 'yes' === get_post_meta($post->ID, '_manage_stock', true) // Woocommerce 'Manage stock' option checkeds
        ) {
            $quantity = get_post_meta($post->ID, '_stock', true);
            // If quantity is not set(null or ''), then use 1.
            $quantity = (empty($quantity) || '0' === $quantity) ? 0 : (int) $quantity;
        } elseif (
            'outofstock' === get_post_meta($post->ID, '_stock_status', true)
            && 'true' === $_POST['useStockQuantity']
        ) {
            // set quantity for out of stock
            $quantity = 0;
        } else {
            // Default, generate one barcode.
            $quantity = 1;
        }

        // get thumbnail_url
        $thumbnailUrl = get_the_post_thumbnail_url($post);

        // get thumbnail_url from parent product if variation image is empty
        if (!$thumbnailUrl && $post->post_parent) {
            $thumbnailUrl = get_the_post_thumbnail_url($post->post_parent);
        }

        return array(
            'quantity' => $quantity,
            'post_image' => $thumbnailUrl,
            'field_code' => $this->getCodeField($post), // the field from which the barcode will be created
            'field_name' => isset($this->data['fieldName']) ? $this->getField($post, $this->data['fieldName']) : '', // additional field
            'field_text' => isset($this->data['fieldText']) ? $this->getField($post, $this->data['fieldText']) : '', // additional field
            'field_text_2' => isset($this->data['fieldText2']) ? $this->getField($post, $this->data['fieldText2']) : '', // additional field
            'algorithm' => $algorithm, // barcode algorithm
            'showCode' => $showCode, // show/hide the code value on the image
            'showName' => $this->showName, // show/hide the name value on the image
            'showText' => $this->showText, // show/hide the text value on the image
            'showText2' => $this->showText2,
            'replacements' => $this->getTemplateReplacements($post),
        );
    }

    /**
     * The field from which the barcode will be created  (shortcodes).
     *
     * @param $post
     *
     * @return string
     */
    protected function getCodeField($post)
    {
        // There is complex code value specified
        if ($this->activeTemplate->code_match) {
            // Use corresponding code settings for each product type
            if ('product_variation' === $post->post_type) {
                return $this->getCodeValue($post, preg_split("/\\r\\n|\\r|\\n/", $this->activeTemplate->variable_product_code));
            } elseif ('product' === $post->post_type) {
                return $this->getCodeValue($post, preg_split("/\\r\\n|\\r|\\n/", $this->activeTemplate->single_product_code));
            } else {
                return '';
            }
        } else {
            return isset($this->data['fieldCode']) ? $this->getField($post, $this->data['fieldCode']) : '';
        }
    }

    /**
     * Get code value (shortcodes).
     *
     * @param $post
     * @param $args
     *
     * @return string
     */
    protected function getCodeValue($post, $args)
    {
        $texts = array();

        // Parse each args
        foreach ($args as $arg) {
            // String in double quotes - it is static value
            if (preg_match('/^(["]).*\1$/m', $arg)) {
                $text = trim($arg, '"');
            } elseif ('ID' === $arg) {
                $text = $post->ID;
            } elseif ('parent.ID' === $arg) {
                $text = $post->post_parent;
            } elseif (0 === strpos($arg, 'parent.')) {
                // Take value from parent post
                $text = $this->getField(
                    get_post($post->post_parent),
                    array('type' => 'custom', 'value' => str_replace('parent.', '', $arg))
                );
            } else {
                // Custom field
                $text = $this->getField($post, array('type' => 'custom', 'value' => $arg));
            }

            $texts[] = $text;
        }

        return implode('', $texts);
    }

    /**
     * Get filed value from array field.
     *
     * @param array $post
     * @param array $field
     *
     * @return string
     */
    protected function getField($post, $field)
    {
        // Check and return 'value' by 'type'
        switch ($field['type']) {
            case 'standart':
                $value = $this->getStandardPostField($post, $field['value']);
                break;
            case 'static':
                $value = $field['value'];
                break;
            case 'permalink':
                $value = get_post_permalink($post->ID);
                break;
            case 'permalink_admin':
                $value = get_edit_post_link($post->ID, '');
                break;
            case 'price_with_tax':
                $value = $this->getProductPriceWithTax($post);
                break;
            case 'wc_category':
                $value = $this->getProductCategories($post);
                break;
            case 'wc_taxonomy':
                $value = $this->getWoocommerceProductTerms($post, $field['value']);
                // Global attribute not set for product, try to find in local product attributes
                if (null === $value) {
                    $value = $this->getWoocommerceProductTermsExtended($post, $field['value']);
                }
                break;
            case 'wc_taxonomy_name':
                $value = $this->getWoocommerceProductAttributeValueByName($post, $field['value'], ($field['term_meta'] ?: null));
                break;
            case 'custom':
//                $value = ($this->shouldAddCurrency($field) && '' !== get_post_meta($post->ID, $field['value'], true))
                $value = $this->getCustomFieldsValues($post, $field);
                break;
            default:
                $value = '';
        }

        return (string) $value;
    }

    protected function getCustomFieldsValues($post, $field) {
        $customFields = array_map('trim', explode(',', $field['value']));
        $values = array();

        // Get value for each custom field
        foreach ($customFields as $customField) {
            // If empty string, add empty string as value.
            if (empty($customField)) {
                continue;
            }

            $values[] = ($this->shouldAddCurrency(array('value' => $customField)))
                ? $this->getValueWithCurrency($this->getProductMeta($post, $customField, true))
                : $this->getProductMeta($post, $customField, true);

            // Remove empty values
            $values = array_filter($values);
        }

        return implode(',', $values);

//        $result = ($this->shouldAddCurrency($field))
//            ? $this->getValueWithCurrency($this->getProductMeta($post, $field['value'], true))
//            : $this->getProductMeta($post, $field['value'], true);

//        return $result;
    }

    /**
     * Check if currency should be added.
     *
     * @param $field
     *
     * @return bool
     */
    protected function shouldAddCurrency($field)
    {
        return in_array($field['value'], array('_price', '_regular_price', '_sale_price'));
    }

    /**
     * Get value with currency position according to woocommerce settings.
     *
     * @param $value
     *
     * @return string
     */
    protected function getValueWithCurrency($value)
    {
        // If empty price value, return empty string
        if (empty($value)) {
            return '';
        }

        // Add currency symbol according to currency position settings.
        switch ($this->currencyPosition) {
            case 'left':
                $result = $this->currency.$value;
                break;
            case 'left_space':
                $result = $this->currency.' '.$value;
                break;
            case 'right':
                $result = $value.$this->currency;
                break;
            case 'right_space':
                $result = $value.' '.$this->currency;
                break;
            default:
                $result = $this->currency.$value;
        }
        return $result;
    }

    protected function getProductMeta($post, $param, $single = true)
    {
        // Check if priority determined explicitly (parent.* or variation.*)
        if (0 === strpos($param, 'product.')) {
            $priority = CustomFieldPriority::PRODUCT;
            $param = substr($param, 8); // strlen('product.') === 8;
        } elseif (0 === strpos($param, 'variation.')) {
            $priority = CustomFieldPriority::VARIATION;
            $param = substr($param, 10); // strlen('variation.') === 10;
        } else {
            // Get priority from options
            $priority = get_option('wpbcu_barcode_generator_custom_fields_priority', 'variation');
        }

        // Product value in priority
        // And if variation and product have custom field - take from product
        if (
            'product' === $priority
            && 'product_variation' === $post->post_type
            && in_array($param, get_post_custom_keys($post->ID))
            && in_array($param, get_post_custom_keys($post->post_parent))
        ) {
            return get_post_meta($post->post_parent, $param, $single);
        } else {
            // If param exists return it value
            if (in_array($param, get_post_custom_keys($post->ID))) {
                return get_post_meta($post->ID, $param, $single);
            } elseif ('product_variation' === $post->post_type) {
                // Param not found, try to find it in parent product (if post is variation)
                return get_post_meta($post->post_parent, $param, $single);
            } else {
                // Nothing found
                return '';
            }
        }
    }

    protected function getProductPriceWithTax($post)
    {
        $productFactory = new \WC_Product_Factory();
        $product = $productFactory->get_product($post->ID);

//        $price = 'incl' === get_option('woocommerce_tax_display_shop') ? wc_get_price_including_tax($product) : wc_get_price_excluding_tax($product);
        $price = wc_get_price_including_tax($product);
        $price = number_format($price, wc_get_price_decimals(), wc_get_price_decimal_separator(), wc_get_price_thousand_separator());

        return $this->getValueWithCurrency($price);
    }

    /**
     * Get woocommerce product terms by taxonomy.
     *
     * @param $post
     * @param $taxonomy
     *
     * @return mixed|string
     */
    protected function getWoocommerceProductTerms($post, $taxonomy, $termMeta = null)
    {
        // If it is 'product', return product taxonomy terms.
        if ('product' === $post->post_type) {
            return $this->termsObjectsToString(get_the_terms($post, 'pa_'.$taxonomy), $termMeta);
        }

        // If it is 'product_variation' return taxonomy term if not empty, otherwise list of parent product taxonomy terms.
        if ('product_variation' === $post->post_type) {
            $term = get_term_by(
                'slug',
                get_post_meta($post->ID, 'attribute_pa_'.$taxonomy, true),
                'pa_'.$taxonomy
            );

//            echo '<pre>';
//            print_r($term);
//            die();

            return $term
                ? (empty($termMeta) ? $term->name : get_term_meta($term->term_id, $termMeta, true))
                : $this->termsObjectsToString(get_the_terms($post->post_parent, 'pa_'.$taxonomy), $termMeta); // All terms: Any value of attribute.
        }
    }

    /**
     * Get attribute name by slug and find product attribute value by product name.
     *
     * @param $post
     * @param $taxonomy
     *
     * @return mixed|string
     */
    protected function getWoocommerceProductTermsExtended($post, $taxonomy)
    {
        // Get global attribute taxonomy name
        $taxonomy = get_taxonomy('pa_' . $taxonomy);
        $taxonomyName = $taxonomy->labels->singular_name;

        // Use function to get attribute value by taxonomy name
       return $this->getWoocommerceProductAttributeValueByName($post, $taxonomyName);
    }

    /**
     * Get woocommerce product terms by taxonomy name.
     *
     * @param $post
     * @param $taxonomyName
     *
     * @return mixed|string
     */
    protected function getWoocommerceProductAttributeValueByName($post, $taxonomyName, $termMeta = null)
    {
        global $wpdb;

        // Find taxonomy slug by name
        $wc_attribute_taxonomy = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}woocommerce_attribute_taxonomies WHERE `attribute_label` = %s", $taxonomyName
        ));

        // Taxonomy is global attribute
        if (null !== $wc_attribute_taxonomy) {
            $taxonomy = $wc_attribute_taxonomy->attribute_name;
            $value = $this->getWoocommerceProductTerms($post, $taxonomy, $termMeta);
        } else  {
            $value = null;
        }

        // Value found - return it
        if (null !== $value) {
            return $value;
        } else {
            // If value not found for global attribute try to get value from local attribute

            // Taxonomy is individual product attribute
            if ('product' === $post->post_type) {
                $attributes = get_post_meta($post->ID, '_product_attributes', true);
            }

            // Product variation, get attributes for parent
            if ('product_variation' === $post->post_type) {
                $attributes = get_post_meta($post->post_parent, '_product_attributes', true);
            }

            // Information about attributes not found
            if (empty($attributes)) {
                return '';
            }

            $taxonomy = null;
            // Try to find taxonomy info by name
            foreach ($attributes as $slug => $attribute) {
                // Taxonomy info found
                if ($taxonomyName === $attribute['name']) {
                    $taxonomy = $slug;
                    break;
                }
            }

            // Taxonomy found
            if (null !== $taxonomy) {
                // Simple product
                if ('product' === $post->post_type) {
                    return $this->getWcAttributesComaSeparatedString($attributes[$taxonomy]['value']);
                }

                // Product variation
                if ('product_variation' === $post->post_type) {
                    return get_post_meta($post->ID, "attribute_{$taxonomy}", true)
                        ?: $this->getWcAttributesComaSeparatedString($attributes[$taxonomy]['value']);
                }
            } else {
                return '';
            }
        }
    }

    /**
     * Creates string of terms names from get terms result.
     *
     * @param $terms
     *
     * @return string
     */
    protected function termsObjectsToString($terms, $termMeta = null)
    {
        // If terms not empty and not error.
        if ($terms && !is_wp_error($terms)) {
            // Get terms names array.
            $terms = array_map(function ($term) use ($termMeta) {
                return empty($termMeta) ? $term->name : get_term_meta($term->term_id, $termMeta, true);
            }, $terms);

            // Join terms names with ','.
            return implode(', ', $terms);
        }

        // Default value.
        return null;
    }

    protected function getWcAttributesComaSeparatedString($attributeValuesString)
    {
        $values = explode('|', $attributeValuesString);
        $values = array_map('trim', $values);

        return implode(', ', $values);
    }

    /**
     * Sort items by given array of ID.
     *
     * @param $targetArray
     * @param $orderArray
     *
     * @return mixed
     */
    protected function sortByIds($targetArray, $orderArray)
    {
        $foundProductsIds = array();
        // for each found item take it id
        foreach ($targetArray as $product) {
            // if it is variation get parent id
            if ('product_variation' === $product->post_type) {
                $foundProductsIds[] = $product->post_parent;
            } else {
                $foundProductsIds[] = $product->ID;
            }
        }

        $orderArray = array_values(array_intersect($orderArray, $foundProductsIds));
        uksort($targetArray, function ($key1, $key2) use ($orderArray, $foundProductsIds) {
            $product1Id = $foundProductsIds[$key1];
            $product2Id = $foundProductsIds[$key2];

            return array_search($product1Id, $orderArray) > array_search($product2Id, $orderArray);
        });

        return $targetArray;
    }

    /**
     * Get standard post field.
     *
     * @param $post
     * @param $field
     *
     * @return string
     */
    protected function getStandardPostField($post, $field)
    {
        // If it is variation title - get parent product title instead
        if ('post_title' === $field && !empty($post->post_parent)) {
            $parent = get_post($post->post_parent);
            $value = $parent->post_title;
        } else {
            $value = isset($post->{$field}) ? $post->{$field} : '';
        }

        return $value;
    }

    /**
     * Get associative array of placeholders and it replacements.
     *
     * @param $post
     *
     * @return \ArrayObject
     */
    protected function getTemplateReplacements($post)
    {
        $replacements = new \ArrayObject();

        // Foreach found shortcodes, create replacement string
        foreach ($this->templateShortcodesArgs as $shortCode => $args) {
            $texts = array();

//            // Foreach parameter in shortcode, get replacement string
//            foreach ($args as $argType => $argValue) {

                // Use proper function for each type of parameter
                switch ($args['type']) {
                    case 'attr':
                        $text = $this->getField($post, array('type' => 'wc_taxonomy_name', 'value' => $args['value'], 'term_meta' => $args['term_meta']));
                        break;
                    case 'cf':
                        $text = $this->getField($post, array('type' => 'custom', 'value' => $args['value']));
                        break;
                    case 'field':
                        $text = $this->getField($post, array('type' => 'standart', 'value' => $args['value']));
                        break;
                    case 'static':
                        $text = $args['value'];
                        break;
                    case 'category':
                        $text = $this->getField($post, array('type' => 'wc_category', 'value' => 'wc_category'));
                        break;
                    default:
                        $text = '';
                        break;
                }

                // Add text only if not empty
                if (!empty($text)) {
                    $texts[] = $text;
                }
//            }

            $replacements[$shortCode] = implode(' ', $texts);
        }

        return $replacements;
    }

    /**
     * Get product categories.
     *
     * @param $post
     *
     * @return string
     */
    protected function getProductCategories($post)
    {
        // If it is 'product', return product taxonomy terms.
        if ('product' === $post->post_type) {
            return $this->termsObjectsToString(get_the_terms($post, 'product_cat'));
        }

        // If it is 'product_variation' return taxonomy term if not empty, otherwise list of parent product taxonomy terms.
        if ('product_variation' === $post->post_type) {
            return $this->termsObjectsToString(get_the_terms($post->post_parent, 'product_cat')); // All terms: Any value of attribute.
        }
    }
}
