<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class myform_validation extends CI_Form_validation {

    function __construct() {
        parent::__construct();
    }

    function set_errors($fields) {
        if (is_array($fields) and count($fields)) {
            foreach ($fields as $key => $val) {
                if (array_key_exists($key, $this->_field_data) AND $this->_field_data[$key]['error'] != '' AND $this->_error_array[$key] != '') {
                    $this->_field_data[$key]['error'] = $val;
                    $this->_error_array[$key] = $val;
                }
            }
        }
    }

}
