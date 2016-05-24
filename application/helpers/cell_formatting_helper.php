<?php

function getCellClassNameFromOutputValue($valueToCheck, $umpireTypeName) {
    $limitGenericLevel1 = 3;
    $limitGenericLevel2 = 4;
    $limitGenericLevel3 = 5;
    
    $limitGoalUmpireLevel1 = 2;
    $limitGoalUmpireLevel2 = 3;
    $limitGoalUmpireLevel3 = 3;
    
    $limitLevel1 = 1;
    $limitLevel2 = 1;
    $limitLevel3 = 1;
    
    
    if ($umpireTypeName == 'Goal') {
        $limitLevel1 = $limitGoalUmpireLevel1;
        $limitLevel2 = $limitGoalUmpireLevel2;
        $limitLevel3 = $limitGoalUmpireLevel3;
        
    } else {
        $limitLevel1 = $limitGenericLevel1;
        $limitLevel2 = $limitGenericLevel2;
        $limitLevel3 = $limitGenericLevel3;
        
    }
    
    
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
		default:
			return "cellNumber cellNormal";
			break;
	}
    
	
}


?>