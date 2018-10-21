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
	
	public function findRecursiveArrayDiff($array1, $array2) {
	    //This function assumes that both arrays have the same keys, which works for the user permissions, but may not work elsewhere.
	    $arrayDifferences = "";
	    foreach ($array1 as $username=>$userPermissionArray) {
	        //Check if the username exists in the second array, otherwise we'll get an Undefined Index error.
	        if(array_key_exists($username, $array2)) {
	            $arrayDifferences[$username] = array_diff_key($array1[$username], $array2[$username]);
	        }
	    }
	    return $arrayDifferences;
	}

    public function findKeyFromValue($pArray, $pValueToFind, $pKeyToLookAt) {
        $arrayKeyFound = 0;
        for ($i=0; $i < count($pArray); $i++) {
            if ($pArray[$i][$pKeyToLookAt] == $pValueToFind) {
                $arrayKeyFound = $i;
            }
        }
        return $arrayKeyFound;

    }

}
?>