<?php

namespace UkrSolution\WpBarcodesGenerator\Api;

use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

class LabelsFormatsRestController extends BaseRestController
{
    protected $base = 'labels';
    protected $table = 'a4barcode_custom_formats';
    protected $paperFormatsTable = 'a4barcode_paper_formats';

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
             'userId' => array(
                 'required' => true,
                 'type' => 'integer',
                 'minimum' => 0,
                 'default' => get_current_user_id(),
             ),
             'width' => array(
                'required' => true,
                'type' => 'number',
                'minimum' => 0,
                'default' => 70,
             ),
             'height' => array(
                'required' => true,
                'type' => 'number',
                'minimum' => 0,
                'default' => 67.7,
             ),
             'arround' => array(
                'required' => true,
                'type' => 'number',
                'minimum' => 0,
                'default' => 0,
             ),
             'across' => array(
                'required' => true,
                'type' => 'number',
                'minimum' => 0,
                'default' => 0,
             ),
             'marginLeft' => array(
                'required' => true,
                'type' => 'number',
                'minimum' => 0,
                'default' => 0,
             ),
             'marginRight' => array(
                'required' => true,
                'type' => 'number',
                'minimum' => 0,
                'default' => 0,
             ),
             'marginTop' => array(
                'required' => true,
                'type' => 'number',
                'minimum' => 0,
                'default' => 13,
             ),
             'marginBottom' => array(
                'required' => true,
                'type' => 'number',
                'minimum' => 0,
                'default' => 13,
             ),
             'arroundCount' => array(
                'required' => true,
                'type' => 'integer',
                'minimum' => 1,
                'default' => 4,
             ),
             'acrossCount' => array(
                'required' => true,
                'type' => 'integer',
                'minimum' => 1,
                'default' => 3,
             ),
             'paperId' => array(
                'required' => true,
                'type' => 'integer',
                'minimum' => 1,
            ),
        );

        parent::register_routes();
    }

    /**
     * Get table row.
     *
     * @param WP_REST_Request $request full data about the request
     *
     * @return WP_Error|WP_REST_Response
     */
    public function show($request)
    {
        global $wpdb;
        $params = $request->get_params();

        $result = $this->queryRow($wpdb->prepare("
            SELECT cf.*, pf.uol
            FROM {$wpdb->prefix}{$this->table} AS cf
            LEFT JOIN {$wpdb->prefix}{$this->paperFormatsTable} AS pf
                ON pf.id = cf.paperId
            WHERE cf.{$this->primaryKey} = %d
        ", $params[$this->primaryKey]));

        return $this->prepareShowResults(rest_ensure_response($result));
    }

    /**
     * Add paper format resource links.
     *
     * @param WP_REST_Response|mixed $result
     *
     * @return mixed
     */
    protected function prepareShowResults($result)
    {
        // If result WP_REST_Response, we can add links to it.
        if (is_a($result, 'WP_REST_Response')) {
            $result->add_link(
                'paper',
                $this->prepareLinkUrl(array('papers', $result->data['paperId'])),
                array('embeddable' => true)
            );
        }

        return $result;
    }
}
