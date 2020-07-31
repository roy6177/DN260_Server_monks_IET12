<?php

namespace UkrSolution\WpBarcodesGenerator;

class Validator
{
    private static $instance;

    private $data = array();
    private $validated = array();
    private $rules = array();
    private $errors = array();
    private $errorMessages = array();
    private $sendErrorResponse = false;
    private $shouldBail = false;

    /**
     * Creates validator instance.
     *
     * @param      $data
     * @param      $validationRules
     * @param bool $sendErrorResponse
     *
     * @return object
     */
    public static function create($data, $validationRules, $sendErrorResponse = false)
    {
        $config = require __DIR__.'/../config/config.php';
        self::$instance = new self($data, $validationRules, $config['listMessagesValidationRules'], $sendErrorResponse);

        return self::$instance;
    }

    /**
     * Validator constructor.
     *
     * @param      $data
     * @param      $validationRules
     * @param      $errorMessages
     * @param bool $sendErrorResponse
     */
    public function __construct($data, $validationRules, $errorMessages, $sendErrorResponse = false)
    {
        $this->data = $data;
        $this->rules = $validationRules;
        $this->sendErrorResponse = $sendErrorResponse;
        $this->errorMessages = $errorMessages;
    }

    /**
     * Validate provided to constructor data.
     *
     * @return array|bool
     */
    public function validate()
    {
        // rule loop and rule data retrieval
        foreach ($this->rules as $fieldName => $rules) {
            $rulesArray = ('' !== $rules) ? explode('|', $rules) : array();
            $rulesArrayFiltered = array();
            // getting field rules and other data
            foreach ($rulesArray as $key => $itemRule) {
                $rule = $this->getRule($itemRule);
                $attributes = $this->getAttributes($itemRule);
                $rulesArrayFiltered[$key] = array(
                    'rule' => $rule,
                );
                // if the field has attributes, then add data
                $rulesArrayFiltered[$key]['attributes'] = !empty($attributes) ? $attributes : array();
            }

            // start checking the field according to the rules
            $this->validateField($fieldName, $rulesArrayFiltered);

            // Check if we should stop validation.
            if ($this->shouldBail && !empty($this->errors)) {
                break;
            }
        }

        // If the request data is incorrect and if $sendErrorResponse is true - send error data.
        if ($this->isValid()) {
            // All validation rules passed, return data.
            return $this->validated;
        } else {
            // If should send error response.
            if ($this->sendErrorResponse) {
                // If AJAX return json with errors data
                if (defined('DOING_AJAX') && DOING_AJAX) {
                    a4bJsonResponse(array('error' => Validator::getErrors()));
                } else {
                    // Redirect back and show errors as notices
                    set_transient('wpbcu_old_post', $this->data, 10);
                    a4bRedirectBackWithErrorNotices(Validator::getErrors());
                }

                return false;
            } else {
                // Validation failed
                return false;
            }
        }
    }

    /**
     * Method of getting the name of the rule (cutting off extra data).
     *
     * @param $rule
     *
     * @return mixed
     */
    public function getRule($rule)
    {
        $tpmArr = explode(':', $rule);

        return $tpmArr[0];
    }

    /**
     * Attribute retrieval method for rule.
     *
     * @param $rule
     *
     * @return array
     */
    public function getAttributes($rule)
    {
        $tpmArr = explode(':', $rule);
        $attributes = array();
        // if attributes are specified for the field, break them into an array
        if (isset($tpmArr[1])) {
            $attributes = explode(',', $tpmArr[1]);
        }

        return $attributes;
    }

    /**
     * field data validity method.
     *
     * @return bool
     */
    public function isValid()
    {
        return (count($this->errors) > 0) ? false : true;
    }

    /**
     * the method returns field validation errors.
     *
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Start checking the field according to the rules.
     *
     * @param string $fieldName
     * @param array  $rulesArray
     *
     * @throws \Exception
     */
    private function validateField($fieldName, $rulesArray)
    {
        // No rules to check, field is valid.
        if (empty($rulesArray) && isset($this->data[$fieldName])) {
            $this->validated[$fieldName] = $this->data[$fieldName];
            return;
        }

        // iteration of the rules specified for the field
        foreach ($rulesArray as $itemRuleData) {
            $methodName = "rule{$itemRuleData['rule']}";
            // checking the existence of a method for validating by rule name
            if (method_exists($this, $methodName)) {
                // call validation method
                $fieldValid = call_user_func_array(array($this, $methodName), array($fieldName, $itemRuleData));
            } else {
                echo '<pre>';
                print_r($methodName);
                die();
                throw new \Exception("wp_barcodes: Validation rule doesn't exists.");
            }

            // Field is valid, add it to validated data.
            if ($fieldValid) {
                // If value is set, add it to validated data
                if (isset($this->data[$fieldName])) {
                    $this->validated[$fieldName] = $this->data[$fieldName];
                }
            }
        }
    }

    /**
     * validation method according to the 'required' rule.
     *
     * @param string $fieldName
     * @param array  $ruleData
     *
     * @return bool
     */
    private function ruleRequired($fieldName, $ruleData)
    {
        $attributes = $ruleData['attributes'];

        // If field required only if empty another field ($attributes[1])
        if (isset($attributes[0]) && 'if_empty' === $attributes[0] && isset($attributes[1])) {
            // If condition field is empty, write error. Else validation is passed.
            if (
                empty($this->data[$attributes[1]]) // empty conditioned field
                && (
                    !isset($this->data[$fieldName]) // empty required field
                    || '' === $this->data[$fieldName]
                )
            ) {
                $this->_error($this->errorMessages['required'], $fieldName);

                return false;
            }
        } elseif (!isset($this->data[$fieldName]) || ('' === $this->data[$fieldName] && $fieldName !== 'base_padding_uol')) {
            // if there is no field or the field is empty, write error
            $this->_error($this->errorMessages['required'], $fieldName);

            return false;
        }

        return true;
    }

    /**
     * Validation method according to the 'requiredItem' rule.
     *
     * @param string $fieldName
     * @param array  $ruleData
     *
     * @return bool
     */
    private function ruleRequiredItem($fieldName, $ruleData)
    {
        $attributes = $ruleData['attributes'];

        // If field required only if empty another field ($attributes[1])
        if (isset($attributes[0]) && 'if_empty' === $attributes[0] && isset($attributes[1])) {
            // If condition field is empty, write error. Else validation is passed.
            if (
                empty($this->data[$attributes[1]]) // empty conditioned field
                && (
                    !isset($this->data[$fieldName]) // empty required field
                    || '' === $this->data[$fieldName]
                )
            ) {
                $this->_error($this->errorMessages['required_item'], $fieldName);

                return false;
            }
        } elseif (
            !isset($this->data[$fieldName])
            || '' === $this->data[$fieldName]
        ) {
            // if there is no field or the field is empty, write error
            $this->_error($this->errorMessages['required_item'], $fieldName);

            return false;
        }

        return true;
    }

    private function ruleBail($fieldName, $ruleData)
    {
        $this->shouldBail = true;

        return false;
    }

    /**
     * Validation method according to the 'numeric' rule..
     *
     * @param $fieldName
     * @param $ruleData
     *
     * @return bool
     */
    private function ruleNumeric($fieldName, $ruleData)
    {
        // if the field exists, then the data type of the field
        if (isset($this->data[$fieldName])) {
            // if the data type is not a number, then write an error
            if (false == is_numeric($this->data[$fieldName])) {
                $this->_error($this->errorMessages['numeric'], $fieldName);

                return false;
            }
        }

        return true;
    }

    /**
     * Validation method according to the 'boolean' rule.
     *
     * @param $fieldName
     * @param $ruleData
     *
     * @return bool
     */
    private function ruleBoolean($fieldName, $ruleData)
    {
        // if the field exists, then the data type of the field
        if (true == isset($this->data[$fieldName])) {
            // if the data type is not a boolean, then write an error
            if (false == is_bool($this->data[$fieldName])) {
                $this->_error($this->errorMessages['boolean'], $fieldName);

                return false;
            }
        }

        return true;
    }

    /**
     * Validation method according to the 'string' rule.
     *
     * @param $fieldName
     * @param $ruleData
     *
     * @return bool
     */
    private function ruleString($fieldName, $ruleData)
    {
        // if the field exists, then the data type of the field
        if (true == isset($this->data[$fieldName])) {
            // if the data type is not a boolean, then write an error
            if (false == is_string($this->data[$fieldName])) {
                $this->_error($this->errorMessages['string'], $fieldName);

                return false;
            }
        }

        return true;
    }

    /**
     * Validation method according to the 'boolean' rule.
     *
     * @param $fieldName
     * @param $ruleData
     *
     * @return bool
     */
    private function ruleComplexCodeValue($fieldName, $ruleData)
    {
        // if the field exists, then the data type of the field
        if (true == isset($this->data[$fieldName])) {

            // This field should not be empty if 'match_code' field is checked
            if ($this->data['code_match'] && empty($this->data[$fieldName])) {
                $this->_error($this->errorMessages['not_empty'], $fieldName);

                return false;
            }

            $isValid = true;

            // Parse each args
            foreach (preg_split("/\\r\\n|\\r|\\n/", $this->data[$fieldName]) as $arg) {
                // String in double quotes - it is static value
                if (preg_match('/^(["]).*\1$/m', $arg)) {
                    continue;
                } elseif ('ID' === $arg || 'parent.ID' === $arg) {
                    continue;
                } else {
                    // Determine custom field key
                    if (0 === strpos($arg, 'parent.')) {
                        $customField = str_replace('parent.', '', $arg);
                    } else {
                        $customField = $arg;
                    }

                    $query = new \WP_Query(array('post_type' => array('product', 'product_variation'), 'meta_key' => $customField));

                    // Check if there is such custom field
                    if (!$query->have_posts()) {
                        $this->_error($this->errorMessages['custom_field'], $customField);

                        return false;
                    }
                }
            }
        }

        return true;
    }

    /**
     * Validation method according to the 'array' rule.
     *
     * @param string $fieldName
     * @param array  $ruleData
     *
     * @return bool
     */
    private function ruleArray($fieldName, $ruleData)
    {
        // if the field exists, then the data type of the field
        if (true == isset($this->data[$fieldName])) {
            // if the data type is not a array, then write an error
            if (false == is_array($this->data[$fieldName])) {
                $this->_error($this->errorMessages['array'], $fieldName);

                return false;
            }
        }

        return true;
    }

    /**
     * Validation method according to the 'in' rule.
     *
     * @param string $fieldName
     * @param array  $ruleData
     *
     * @return bool
     */
    private function ruleIn($fieldName, $ruleData)
    {
        // if the field exists and has attributes, then the data type of the field
        if (isset($this->data[$fieldName]) && isset($ruleData['attributes'])) {
            // if the value of the field is not among the value specified in the attributes, then write an error
            if (false == in_array($this->data[$fieldName], $ruleData['attributes'])) {
                $this->_error($this->errorMessages['in'], $fieldName);

                return false;
            }
        }

        return true;
    }

    /**
     * Check for valid xml.
     *
     * @param $fieldName
     * @param $ruleData
     *
     * @return bool
     */
    private function ruleXml($fieldName, $ruleData)
    {
        // if the field exists
        if (isset($this->data[$fieldName])) {
            // Save old setting value
            $useErrors = libxml_use_internal_errors(true);

            // Try to load value as XML
            $doc = new \DOMDocument();
            $success = $doc->loadXML('<?xml version="1.0" encoding="UTF-8"?><root>'.$this->data[$fieldName].'</root>');

            // Restore setting value
            libxml_use_internal_errors($useErrors);

            // Errors has occurred
            if (!$success) {
                $this->_error($this->errorMessages['xml'], $fieldName);

                return false;
            }
        }

        return true;
    }

    /**
     * method of filling in data validation errors.
     *
     * @param string $errorMsg
     * @param string $fieldName
     */
    private function _error($errorMsg, $fieldName)
    {
        $this->errors[] = sprintf($errorMsg, $fieldName);
    }
}
