<?php

defined('BASEPATH') OR exit('No direct script access allowed');
class Array_library {

    public function __construct() {
        $ci = "";
        $this->ci =& get_instance();
        $this->ci->load->config('config');
        //$this->ci->config->load('mylib');
    
    }
    

    public function in_array_r($needle, $haystack, $strict = false) {
    	foreach ($haystack as $item) {
    		if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
    			return true;
    		}
    	}
    
    	return false;
    }
    
    
    public function sortByOrder($a, $b) {
    	return $a['1'] - $b['1'];
    }
    
    public function sortArray($a, $b) {
    	$sortResult = strcasecmp( $a[0], $b[0]);
    	if ($sortResult === 0 && array_key_exists(1, $a)) {
    		$sortResult = strcasecmp( $a[1], $b[1]);
    		if ($sortResult === 0 && array_key_exists(2, $a)) {
    			$sortResult = strcasecmp( $a[2], $b[2]);
    		}
    	}
    	
    	return $sortResult;
    	
    }
    
    public function compareStringValues($a, $b) {
	    return strcmp($a, $b);
	}

}
?>