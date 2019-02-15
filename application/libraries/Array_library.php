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
    		if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict))) {
    			return true;
    		}
    	}
    
    	return false;
    }

	public function findRecursiveArrayDiff($array1, $array2) {
	    //This function assumes that both arrays have the same keys, which works for the user permissions, but may not work elsewhere.
	    $arrayDifferences = [];
	    foreach ($array1 as $username=>$userPermissionArray) {
	        //Check if the username exists in the second array, otherwise we'll get an Undefined Index error.
	        if(array_key_exists($username, $array2)) {
	            $key1 = $array1[$username];
	            $key2 = $array2[$username];
	            $arrayDifferences[$username] = array_diff_key($key1, $key2);
	        }
	    }
	    return $arrayDifferences;
	}

	public function findMultiArrayDiff($array1, $array2) {
        $intersect = array_uintersect($array1, $array2, array($this, 'compareDeepValue'));
        return $intersect;
    }

    private function compareDeepValue($val1, $val2) {
        return strcmp(serialize($val1), serialize($val2));
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

    public function findArrayDBObjectDiff($array1, $array2, $pFieldName) {
	    $arrayDifferences = [];
        if (count($array1) < count($array2)) {
            $largerArray = $array2;
            $smallerArray = $array1;
        } else {
            $largerArray = $array1;
            $smallerArray = $array2;
        }

        foreach ($largerArray as $key => $subArray) {
            //Check if the second array has an element in this position.
            //There's a chance it may be smaller than array1
            if(isset($smallerArray[$key])) {
                if ($subArray->$pFieldName != $smallerArray[$key]->$pFieldName) {
                    $arrayDifferences[] = $subArray->$pFieldName;
                }
            } else {
                //Array2 does not have an element in this position. Mark it as an array difference
                $arrayDifferences[] = $subArray->$pFieldName;
            }
        }

        return $arrayDifferences;

    }

    public function sumArrayValues($pArray) {
        $arrayCount = count($pArray);
        $sumOfValues = 0;
        for ($i = 0; $i < $arrayCount; $i++) {
            $sumOfValues = $sumOfValues + count($pArray[$i]);
        }
        return $sumOfValues;
    }


}
?>