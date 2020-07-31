<?php

namespace UkrSolution\WpBarcodesGenerator\BarcodeTemplates;

use UkrSolution\WpBarcodesGenerator\PostData;
use UkrSolution\WpBarcodesGenerator\Validator;

class BarcodeTemplatesController
{
    protected $wpdb;
    protected $tbl;
    protected $templateValidationRules = array(
        'id' => 'numeric',
        'name' => 'required',
        'template' => 'xml',
        'height' => 'required|numeric',
        'width' => 'required|numeric',
        'uol' => 'required',
//        'base_padding' => 'required',
        'base_padding' => '',
        'base_padding_uol' => 'required',
        'code_match' => 'numeric',
        'single_product_code' => 'complexCodeValue',
        'variable_product_code' => 'complexCodeValue',
    );
    protected $defaultTemplateValidationRules = array(
        'id' => 'numeric',
        'code_match' => 'numeric',
        'single_product_code' => 'complexCodeValue',
        'variable_product_code' => 'complexCodeValue',
    );

    /**
     * CustomBarcodeTemplates constructor.
     */
    public function __construct()
    {
        global $wpdb;

        $this->wpdb = $wpdb;
        $this->tbl = $wpdb->prefix.'a4barcode_custom_templates';
    }

    /**
     * Create page.
     */
    public function create()
    {
        // Get data from db
        $templates = $this->wpdb->get_results("SELECT * FROM `{$this->tbl}`");

        // Show template
        include_once A4B_PLUGIN_BASE_PATH.'templates/barcode-templates/create.php';
    }

    /**
     * Store and redirect ot edit page.
     */
    public function store()
    {
        // Get validated data from request
        $data = Validator::create(PostData::get(), $this->templateValidationRules, true)->validate();

        // If insert ok, redirect to edit page adn show success message.
        if ($this->wpdb->insert($this->tbl, $data)) {
            $templateId = $this->wpdb->insert_id;
            a4bFlashMessage(__('Template saved successfully!', 'wpbcu-barcode-generator'), 'success');
            $this->redirectToEditPage($templateId);
        } else {
            // Error on saving to db. Show error message.
            a4bFlashMessage($this->wpdb->last_error, 'error');
            $this->redirectToCreatePage();
        }
    }

    /**
     * Edit page.
     */
    public function edit()
    {
        // Get id from request
        $id = isset($_GET['id']) ? intval($_GET['id']) : null;

        // Get template by id
        if ($id) {
            $chosenTemplateRow = $this->wpdb->get_row(
                $this->wpdb->prepare("SELECT * FROM `{$this->tbl}` WHERE `id` = %s", $id), ARRAY_A
            );
        } else {
            // Get active template
            $chosenTemplateRow = $this->wpdb->get_row("SELECT * FROM `{$this->tbl}` WHERE `is_active` = 1", ARRAY_A);
        }

        // Get data from db
        $chosenTemplate = new BarcodeTemplate($chosenTemplateRow);
        $templates = $this->wpdb->get_results("SELECT * FROM `{$this->tbl}`");

        // Show template
        include_once A4B_PLUGIN_BASE_PATH.'templates/barcode-templates/edit.php';
    }

    /**
     * Update and redirect to edit page.
     */
    public function update()
    {
        // Get id from request
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;
        $chosenTemplateRow = $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM `{$this->tbl}` WHERE `id` = %s", $id), ARRAY_A);

        // Get validated data from request
        if ($chosenTemplateRow['is_default']) {
            $data = Validator::create(PostData::get(), $this->defaultTemplateValidationRules, true)->validate();
        } else {
            $data = Validator::create(PostData::get(), $this->templateValidationRules, true)->validate();
        }

//        $res = $this->wpdb->update($this->tbl, $data, array('id' => $data['id'], 'is_default' => 0));
        $res = $this->wpdb->update($this->tbl, $data, array('id' => $data['id']));

        // If update ok, setup success message.
        if ($res !== false) {
            a4bFlashMessage(__('Template updated successfully!', 'wpbcu-barcode-generator'), 'success');
        } else {
            a4bFlashMessage($this->wpdb->last_error, 'error');
        }

        // Redirect to edit page
        $this->redirectToEditPage($data['id']);
    }

    /**
     * Delete and redirect to edit page.
     */
    public function delete()
    {
        // Get data from request
        $data = Validator::create(PostData::get(), array('id' => 'required'), true)->validate();

        // Delete from db
        if ($this->wpdb->delete($this->tbl, array('id' => $data['id'], 'is_default' => 0))) {
            a4bFlashMessage(__('Template deleted successfully!'), 'success');
        } else {
            a4bFlashMessage($this->wpdb->last_error ?: __('Template not found.'), 'error');
        }

        // Redirect to edit page
        $this->redirectToEditPage();
    }

    /**
     * Set template as active and show it edit page.
     */
    public function setactive()
    {
        // Get template id from request
        $id = isset($_POST['id']) ? intval($_POST['id']) : null;

        // Get template id from request
        if ($id) {
            // Set chosen template as active
            $this->setActiveTemplate($id);
        }

        // Redirect to edit page
        $this->redirectToEditPage($id);
    }

    /**
     * Set active template by id.
     *
     * @param $id
     */
    protected function setActiveTemplate($id)
    {
        // Unset old active template
        $this->wpdb->update($this->tbl, array('is_active' => 0), array('is_active' => 1));

        // Set template with given id as active
        $this->wpdb->update($this->tbl, array('is_active' => 1), array('id' => $id));
    }

    /**
     * Redirect ot edit page.
     *
     * @param null $id
     */
    protected function redirectToEditPage($id = null)
    {
        wp_redirect(admin_url('/admin.php?page=wpbcu-barcode-templates-edit&id='.$id));
        exit;
    }

    /**
     * Redirect to create page.
     */
    protected function redirectToCreatePage()
    {
        wp_redirect(admin_url('/admin.php?page=wpbcu-barcode-templates-create'));
        exit;
    }

    /**
     * Get active template.
     *
     * @return mixed
     */
    public function getActiveTemplate()
    {
        // Get active template
        $chosenTemplateRow = $this->wpdb->get_row("SELECT * FROM `{$this->tbl}` WHERE `is_active` = 1");

        return $chosenTemplateRow;
    }
}
