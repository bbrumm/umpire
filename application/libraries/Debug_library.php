<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*
* @property Object ci
* @property Object config
*/
class Debug_library {
    
    public function __construct() {
        $this->ci =& get_instance();
        $this->ci->load->config('config');
    }

    public function debugOutput($beforeMessage, $pOutputValue) {
        $outputText = "";
        if ($this->ci->config->item('debug_mode')) {
            if (is_array($pOutputValue)) {

                $outputText = "$beforeMessage<pre>";
                $outputText .= print_r($pOutputValue, true);
                $outputText .= "</pre><BR />";
            } else {
                $outputText = $beforeMessage . " " . $pOutputValue . "<BR />";
            }
        }
        return $outputText;
    }
}
