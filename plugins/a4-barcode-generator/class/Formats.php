<?php

namespace UkrSolution\WpBarcodesGenerator;

class Formats
{
    /**
     * Deletes the format by id.
     */
    public function deleteFormat()
    {
        global $wpdb;
        $validationOptions = array('id' => 'required|numeric');

        // Checking for the correct data from the request. Send error if not valid.
        $data = Validator::create(PostData::get(), $validationOptions, true)->validate();

        // Delete from db and send message success or fail.
        if ($wpdb->delete("{$wpdb->prefix}a4barcode_custom_formats", array('id' => $data['id']), array('%d'))) {
            $success = array(__('Data successfully deleted.', 'wpbcu-barcode-generator'));
            $error = array();
        } else {
            $success = array();
            $error = array(__('Data was not deleted.', 'wpbcu-barcode-generator').' '.$wpdb->last_error);
        }

        a4bJsonResponse(compact('success', 'error'));
    }

    /**
     * Creates / updates the format data.
     */
    public function saveFormat()
    {
        global $wpdb;

        $validationOptions = array(
            'id' => 'numeric',
            'name' => 'required',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'arround' => 'required|numeric',
            'across' => 'required|numeric',
            'marginLeft' => 'required|numeric',
            'marginRight' => 'required|numeric',
            'marginTop' => 'required|numeric',
            'marginBottom' => 'required|numeric',
            'arroundCount' => 'required|numeric',
            'acrossCount' => 'required|numeric',
            'paperId' => 'required|numeric',
        );

        // Checking for the correct data from the request. Return error if not valid.
        $data = Validator::create(PostData::get(), $validationOptions, true)->validate();
        $data['userId'] = get_current_user_id();

        // Update the data in the table
        if (isset($data['id'])) {
            $result = $wpdb->update("{$wpdb->prefix}a4barcode_custom_formats", $data, array('id' => $data['id'], 'default' => 0));
            $id = $data['id'];
        } else {
            // Create an entry in the table
            $result = $wpdb->insert("{$wpdb->prefix}a4barcode_custom_formats", $data);
            $id = $wpdb->insert_id;
        }

        // Send message success or fail.
        if (false !== $result) {
            $success = array(__('Data successfully saved.', 'wpbcu-barcode-generator'));
            $error = array();
        } else {
            $success = array();
            $error = array(__('Data was not saved.', 'wpbcu-barcode-generator').' '.$wpdb->last_error);
        }

        a4bJsonResponse(compact('success', 'error', 'id'));
    }

    /**
     * Returns a list of all formats.
     */
    public function getAllFormats()
    {
        global $wpdb;

        // Get formats data
        $listFormats = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}a4barcode_custom_formats ", ARRAY_A);

        a4bJsonResponse(array(
            'listFormats' => $listFormats,
            'error' => empty($listFormats) ? array(__('No data found on request.').' '.$wpdb->last_error) : array(),
            'success' => array(),
        ));
    }

    /**
     * Returns format data by id.
     */
    public function getFormat()
    {
        global $wpdb;

        $validationOptions = array('id' => 'required|numeric');

        // Checking for the correct data from the request. Return error if not valid.
        $data = Validator::create(PostData::get(), $validationOptions, true)->validate();

        // get format data
        $formatData = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}a4barcode_custom_formats where id='%d'", $data['id']), ARRAY_A);

        // Data is not empty
        if (!empty($formatData)) {
            $response = $formatData;
        } else {
            $response = array('error' => array(__('No data found on request').' '.$wpdb->last_error));
        }

        a4bJsonResponse($response);
    }

    /**
     * Returns a list of all paper formats.
     */
    public function getAllPaperFormats()
    {
        global $wpdb;

        // get paper formats data
        $listFormats = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}a4barcode_paper_formats WHERE `name` <> 'future-reserved-paper-format'", ARRAY_A);

        a4bJsonResponse(array(
            'listFormats' => $listFormats,
            'error' => empty($listFormats) ? array(__('No data found on request.').' '.$wpdb->last_error) : array(),
            'success' => array(),
        ));
    }

    /**
     * the method updates the paper format data.
     */
    public function savePaperFormat()
    {
        global $wpdb;

        $validationOptions = array(
            'id' => 'numeric',
            'name' => 'required',
            'width' => 'required|numeric',
            'height' => 'required|numeric',
            'uol' => 'required',
            'landscape' => 'required',
        );

        // Checking for the correct data from the request. Send error if not valid.
        $data = Validator::create(PostData::get(), $validationOptions, true)->validate();

        // Add default values
        $data['is_editable'] = 1;

        // If there is no id - insert new
        if (!isset($data['id'])) {
            $result = $wpdb->insert("{$wpdb->prefix}a4barcode_paper_formats", $data);
            $id = $wpdb->insert_id;
        } else {
            // Update data. You can update records in the table if the 'default' parameter is 0
            $result = $wpdb->update("{$wpdb->prefix}a4barcode_paper_formats", $data, array('id' => $data['id'], 'is_editable' => 1));
            $id = $data['id'];
        }

        // Send message success or fail.
        if (false !== $result) {
            $success = array(__('Data successfully saved.', 'wpbcu-barcode-generator'));
            $error = array();
        } else {
            $success = array();
            $error = array(__('Data was not saved.', 'wpbcu-barcode-generator').' '.$wpdb->last_error);
        }

        a4bJsonResponse(compact('success', 'error', 'id'));
    }

    /**
     * Returns formats data by paperId.
     */
    public function getFormatsByPaper()
    {
        global $wpdb;

        $validationOptions = array('paperId' => 'required|numeric');

        // checking for the correct data from the request. Send error if not valid.
        $data = Validator::create(PostData::get(), $validationOptions, true)->validate();

        $preparedSql = $wpdb->prepare("
            SELECT cf.*, pf.uol 
            FROM {$wpdb->prefix}a4barcode_custom_formats as cf, {$wpdb->prefix}a4barcode_paper_formats AS pf 
            WHERE pf.id = cf.paperId AND cf.paperId='%d'
        ", $data['paperId']);

        // Get formats data
        $listFormats = $wpdb->get_results($preparedSql, ARRAY_A);

        a4bJsonResponse(array(
            'listFormats' => $listFormats,
            'error' => array(),
            'success' => array(),
        ));
    }

    /**
     * Method for removing paper format and label formats which linked to it.
     */
    public function deletePaperFormat()
    {
        global $wpdb;

        $validationOptions = array('id' => 'required|numeric');

        // Checking for the correct data from the request. Return error if not valid.
        $data = Validator::create(PostData::get(), $validationOptions, true)->validate();

        // Delete from db and send message success or fail.
        if ($wpdb->delete("{$wpdb->prefix}a4barcode_paper_formats", array('id' => $data['id']), array('%d'))) {
            $success = array(__('Data successfully deleted.', 'wpbcu-barcode-generator'));
            $error = array();
        } else {
            $success = array();
            $error = array(__('Data was not deleted.', 'wpbcu-barcode-generator').' '.$wpdb->last_error);
        }

        a4bJsonResponse(compact('success', 'error'));
    }
}
