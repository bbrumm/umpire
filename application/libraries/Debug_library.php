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

?>