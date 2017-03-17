<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Debug_library {
    
    public function __construct() {
        $ci = "";
        $this->ci =& get_instance();
        $this->ci->load->config('config');
        //$this->ci->config->load('mylib');

    }

    public function debugOutput($beforeMessage, $pOutputValue) {
        if ($this->ci->config->item('debug_mode')) {
            if (is_array($pOutputValue)) {
                echo "$beforeMessage<pre>";
                print_r($pOutputValue);
                echo "</pre><BR />";
            } elseif (is_a($pOutputValue, 'Requested_report_model')) {
                //TODO: Write output if it is an object
                echo "This is an object. Output code to be written... <BR />";
            } else {
                echo $beforeMessage . " " . $pOutputValue . "<BR />";
            }
        }
    }

}

?>