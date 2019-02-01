<?php
function getCellClassNameFromOutputValue($valueToCheck, $isTable) {
    $limitLevel1 = 3;
    $limitLevel2 = 4;
    $limitLevel3 = 5;

    if ($isTable) {
        switch ($valueToCheck) {
            case null:
                return "cellNumber cellNormal";
            case ($valueToCheck < $limitLevel1):
                return "cellNumber cellNormal";
            case $limitLevel1:
                return "cellNumber cellLevel1";
            case $limitLevel2:
                return "cellNumber cellLevel2";
            case $limitLevel3:
                return "cellNumber cellLevel3";
            case ($valueToCheck > $limitLevel3):
                return "cellNumber cellLevel4";
        }
	} else {
	    switch ($valueToCheck) {
    	    case null:
    	        return "divCell divCellNumber";
    	    case ($valueToCheck < $limitLevel1):
    	        return "divCell divCellNumber";
    	    case $limitLevel1:
    	        return "divCell divCellNumber cellLevel1";
    	    case $limitLevel2:
    	        return "divCell divCellNumber cellLevel2";
    	    case $limitLevel3:
    	        return "divCell divCellNumber cellLevel3";
    	    case ($valueToCheck > $limitLevel3):
    	        return "divCell divCellNumber cellLevel4";
    	}
	}
}