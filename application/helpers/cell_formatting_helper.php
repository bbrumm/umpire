<?php

function getCellClassNameFromOutputValue($valueToCheck) {
	switch ($valueToCheck) {
		case null:
			return "cellNumber cellNormal";
			break;
		case ($valueToCheck < 3):
			return "cellNumber cellNormal";
			break;
		case 3:	
			return "cellNumber cellLevel1";
			break;	
		case 4:
			return "cellNumber cellLevel2";
			break;	
		case 5:
			return "cellNumber cellLevel3";
			break;	
		case ($valueToCheck > 5):
			return "cellNumber cellLevel4";
			break;
		default:
			return "cellNumber cellNormal";
			break;
	}
	
}


?>