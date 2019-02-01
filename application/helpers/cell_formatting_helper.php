<?php
function getCellClassNameFromOutputValue($valueToCheck, $isTable) {
    $limitGenericLevel1 = 3;
    $limitGenericLevel2 = 4;
    $limitGenericLevel3 = 5;

    $limitLevel1 = $limitGenericLevel1;
    $limitLevel2 = $limitGenericLevel2;
    $limitLevel3 = $limitGenericLevel3;

    if ($isTable) {
        switch ($valueToCheck) {
            case null:
                return "cellNumber cellNormal";
                break;
            case ($valueToCheck < $limitLevel1):
                return "cellNumber cellNormal";
                break;
            case $limitLevel1:
                return "cellNumber cellLevel1";
                break;
            case $limitLevel2:
                return "cellNumber cellLevel2";
                break;
            case $limitLevel3:
                return "cellNumber cellLevel3";
                break;
            case ($valueToCheck > $limitLevel3):
                return "cellNumber cellLevel4";
                break;
           }
	} else {
	    switch ($valueToCheck) {
    	    case null:
    	        return "divCell divCellNumber";
    	        break;
    	    case ($valueToCheck < $limitLevel1):
    	        return "divCell divCellNumber";
    	        break;
    	    case $limitLevel1:
    	        return "divCell divCellNumber cellLevel1";
    	        break;
    	    case $limitLevel2:
    	        return "divCell divCellNumber cellLevel2";
    	        break;
    	    case $limitLevel3:
    	        return "divCell divCellNumber cellLevel3";
    	        break;
    	    case ($valueToCheck > $limitLevel3):
    	        return "divCell divCellNumber cellLevel4";
    	        break;
    	}
	}
}