<?php

class DebugLibrary {
    
    public function __construct() {
        $ci = "";
        $this->ci =& get_instance();
        $this->ci->load->config('config');
        //$this->ci->config->load('mylib');
        
        
    }

    public function debugOutput($pOutputValue) {
        
        
        if ($this->ci->config->item('debug_mode')) {
            if (is_array($pOutputValue)) {
                echo "<pre>";
                print_r($pOutputValue);
                echo "</pre><BR />";
            } else {
                echo $pOutputValue . "<BR />";
            }
        }
    }

}

?>