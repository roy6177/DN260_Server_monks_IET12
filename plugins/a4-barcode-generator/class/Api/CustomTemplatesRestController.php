<?php

namespace UkrSolution\WpBarcodesGenerator\Api;

use DOMDocument;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class CustomTemplatesRestController extends BaseRestController
{
    protected $base = 'templates';
    protected $table = 'a4barcode_custom_templates';
    protected $defaultKey = 'is_default';

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
            'template' => array(
                'required' => true,
                'type' => 'string',
                'validate_callback' => array($this, 'validateXml'),
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
            'base_padding' => array(
                'required' => true,
                'type' => 'integer',
                'default' => 8,
                'minimum' => 0,
            ),
        );

        parent::register_routes();

        register_rest_route($this->namespace, '/'.$this->base.'/active', array(
            array(
                'methods' => WP_REST_Server::READABLE,
                'callback' => array($this, 'active'),
                'permission_callback' => array($this, 'permissionsCheck'),
            ),
        ));
    }

    /**
     * Validate template markup function.
     *
     * @param $param
     * @param $request
     * @param $key
     *
     * @return mixed
     */
    public function validateXml($param, $request, $key)
    {
        // Save old setting value
        $useErrors = libxml_use_internal_errors(true);

        // Try to load value as XML
        $doc = new DOMDocument();
        $success = $doc->loadXML('<?xml version="1.0" encoding="UTF-8"?><root>'.$param.'</root>');

        // Restore setting value
        libxml_use_internal_errors($useErrors);

        // Return result if xml parsed successfully
        return $success;
    }

    /**
     * Get active template.
     *
     * @param WP_REST_Request $request full data about the request
     *
     * @return WP_Error|WP_REST_Response
     */
    public function active($request)
    {
        global $wpdb;

        $result = $this->queryRow("
            SELECT * 
            FROM {$wpdb->prefix}{$this->table} 
            WHERE is_active = 1
        ");

        return rest_ensure_response($result);
    }
}
