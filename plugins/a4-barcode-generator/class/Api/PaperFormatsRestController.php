<?php

namespace UkrSolution\WpBarcodesGenerator\Api;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

class PaperFormatsRestController extends BaseRestController
{
    protected $base = 'papers';
    protected $table = 'a4barcode_paper_formats';

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes()
    {
        $this->requestQueryArgs = array(
            'id' => array(
                'required' => true,
                'type' => 'integer',
                'minimum' => 0,
            ),
        );
        $this->requestBodyArgs = array(
            'name' => array(
                'required' => true,
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ),
            'width' => array(
                'required' => true,
                'type' => 'number',
                'minimum' => 0,
                'default' => 210,
            ),
            'height' => array(
                'required' => true,
                'type' => 'number',
                'minimum' => 0,
                'default' => 297,
            ),
            'uol' => array(
                'required' => true,
                'type' => 'string',
                'enum' => array('mm', 'in'),
            ),
            'landscape' => array(
                'required' => true,
                'type' => 'boolean',
                'default' => 0,
            ),
        );

        parent::register_routes();
    }

    /**
     * Get table rows.
     *
     * @param WP_REST_Request $request full data about the request
     *
     * @return WP_Error|WP_REST_Response
     */
    public function index($request)
    {
        global $wpdb;

        $result = $this->queryRows("
            SELECT * 
            FROM {$wpdb->prefix}{$this->table}
            WHERE `name` <> 'future-reserved-paper-formats'
        ");

        return rest_ensure_response($result);
    }
}
