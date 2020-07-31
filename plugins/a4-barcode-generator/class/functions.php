<?php


//// DO NOT DELETE
//// On activation errors debug mode
//define('temp_file', ABSPATH.'/_temp_out.txt');
//add_action("activated_plugin", "activation_handler1");
//function activation_handler1(){
//    $cont = ob_get_contents();
//    if(!empty($cont)) file_put_contents(temp_file, $cont );
//}
//
//add_action( "pre_current_active_plugins", "pre_output1" );
//function pre_output1($action){
//    if(is_admin() && file_exists(temp_file))
//    {
//        $cont= file_get_contents(temp_file);
//        if(!empty($cont))
//        {
//            echo '<div class="error"> Error Message:' . $cont . '</div>';
//            @unlink(temp_file);
//        }
//    }
//}

/**
 * Send JSON response.
 *
 * @param $data
 */
function a4bJsonResponse($data)
{
    @header('Content-type: application/json; charset=utf-8');
    echo json_encode($data);
    wp_die();
}

/**
 * Get products by categories ids.
 *
 * @param        $categoriesIds
 * @param null   $postsIds
 * @param null   $notPostsIds
 * @param string $postType
 * @param string $postTaxonomy
 *
 * @return array
 */
function a4bGetPostsByCategories($categoriesIds = array(), $args = array())
{
    $defaultArgs = array(
        'post_type' => 'product',
        'post_status' => 'any',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'product_cat',
                'terms' => $categoriesIds,
                'field' => 'term_id',
                'operator' => 'IN',
            ),
        ),
    );

    $args = array_merge($defaultArgs, $args);
    $query = new \WP_Query($args);

    return $query->posts;
}

/**
 * Get products by ids.
 *
 * @param string $postType
 * @param null   $postsIds
 * @param null   $notPostsIds
 * @param null   $parentsIds
 *
 * @return array
 */
function a4bGetPosts($args)
{
    $defaultArgs = array(
        'post_type' => 'product',
        'post_status' => 'any',
        'posts_per_page' => -1,
    );

    $args = array_merge($defaultArgs, $args);
    $query = new \WP_Query($args);

    return $query->posts;
}

/**
 * Exclude posts from array with given IDs.
 *
 * @param $posts
 * @param $excludeIds
 *
 * @return array
 */
function a4bExcludePostsByIds($posts, $excludeIds)
{
    return array_filter(
        $posts,
        function ($post) use ($excludeIds) {
            return !in_array($post->ID, $excludeIds);
        }
    );
}

/**
 * Gather values of given field from each object into array.
 *
 * @param $objects
 * @param $field
 *
 * @return array
 */
function a4bObjectsFieldToArray($objects, $field)
{
    $fieldValues = [];
    // Get value of given field from each object.
    foreach ($objects as $object) {
        $fieldValues[] = $object->$field;
    }

    return $fieldValues;
}

/**
 * Add messages to cookies.
 *
 * @param $message
 * @param $type
 */
function a4bFlashMessage($message, $type)
{
    // Get wpbcu_validation_errors from wp transient
    $flashMessages = get_transient('wpbcu_validation_errors') ?: array();
    $flashMessages[] = compact('message', 'type');
    set_transient('wpbcu_validation_errors', $flashMessages, 10);
}

/**
 * Show errors in a4b_flash_messages cookie and clear it from cookie after.
 */
function a4bShowNotices()
{
    // Read error messages from wp transient and then clear it.
    $flashMessages = get_transient('wpbcu_validation_errors') ?: array();
    delete_transient('wpbcu_validation_errors');

    // Output notices
    foreach ($flashMessages as $flash) {
        printf('<div class="notice notice-%s is-dismissible"><p>%s</p></div>', esc_attr($flash['type']), esc_html($flash['message']));
    }
}

/**
 * Setup errors to flash and redirect back.
 *
 * @param $errors
 */
function a4bRedirectBackWithErrorNotices($errors)
{
    // Save error notices to show on page
    foreach ($errors as $error) {
        a4bFlashMessage($error, 'error');
    }

    wp_redirect(wp_get_referer());
    exit();
}

/**
 * Setup global wpbcu_old_post variable.
 */
function a4bOldPostInitialization()
{
    global $wpbcu_old_post;
    // Get old post data
    $wpbcu_old_post = get_transient('wpbcu_old_post') ?: array();
    // Delete old data
    if (!empty($wpbcu_old_post)) {
        delete_transient('wpbcu_old_post');
    }
}

/**
 * Get $field from $wpbcu_old_post global if exists.
 *
 * @param $field mixed
 *
 * @return mixed|null
 */
function a4bOld($field)
{
    global $wpbcu_old_post;

    return isset($wpbcu_old_post[$field]) ? $wpbcu_old_post[$field] : null;
}
