<?php
require_once('ReportDisplayOptions.php');
class UserReport extends CI_Model {
	
	private $columnGroupForReport01 = array(
				'short_league_name',
				'club_name'
			);
	private $rowGroupForReport01 = array(
				'full_name'
			);
	
	private $columnGroupForReport02 = array(
	    'age_group',
	    'short_league_name'
	);
	private $rowGroupForReport02 = array(
	    'full_name'
	);
	
	private $columnGroupForReport03 = array(
	    'umpire_type_age_group',
	    'short_league_name'
	);
	private $rowGroupForReport03 = array(
	    'weekdate'
	);
	
	private $columnGroupForReport04 = array(
	    'umpire_type',
	    'age_group',
	    'short_league_name'
	);
	private $rowGroupForReport04 = array(
	    'club_name'
	);
	
	private $columnGroupForReport05 = array(
	    'short_league_name'
	);
	
	private $rowGroupForReport05 = array(
	    'umpire_type',
	    'age_group'
	);
	
	private $columnGroupForReport06 = array(
	    'umpire_name'
	);
	private $rowGroupForReport06 = array(
	    'umpire_name'
	);
    
		
	private $fieldForReport01 = 'match_count';
	private $fieldForReport02 = 'match_count';
	private $fieldForReport03 = 'match_count';
	private $fieldForReport04 = 'match_count';
	private $fieldForReport05 = 'match_count';
	private $fieldForReport06 = 'match_count';
	
	private $reportQuery;
	private $resultArray;
	private $columnGroupingArray;
	private $rowGroupingArray;
	private $reportTitle;
	private $reportTableName;
	private $reportColumnLabelQuery;
	private $reportRowLabelQuery;
	private $columnLabelResultArray;
	private $rowLabelResultArray;
	
	private $reportID;

	public $reportDisplayOptions;
	
	public function __construct() {
		$this->reportDisplayOptions = new ReportDisplayOptions();
		$this->load->database();
	}
	
	public function setReportType($reportParameters) {
		/*echo "<pre>";
		print_r($reportParameters);
		echo "</pre>";*/
	    //TODO: Clean up this switch statement to remove code repetition 
	    switch ($reportParameters['reportName']) {
	        case 1:
    			$this->reportDisplayOptions->setColumnGroup($this->columnGroupForReport01);
    			$this->reportDisplayOptions->setRowGroup($this->rowGroupForReport01);
    			$this->reportDisplayOptions->setFieldToDisplay($this->fieldForReport01);
    			$this->reportDisplayOptions->setNoDataValue("");
    			$this->reportDisplayOptions->setColumnHeadingLabel(array("Name"));
    			$this->reportDisplayOptions->setFirstColumnFormat("text");
    			$this->reportDisplayOptions->setMergeColumnGroup(array(TRUE, FALSE));
    			$this->reportDisplayOptions->setColourCells(TRUE);
    			$this->reportTitle = "01 - Umpires and Clubs - ". $reportParameters['age']." - ".$reportParameters['umpireType'];
    			$this->reportID = 1;
    			break;
	        case 2:
	            $this->reportDisplayOptions->setColumnGroup($this->columnGroupForReport02);
	            $this->reportDisplayOptions->setRowGroup($this->rowGroupForReport02);
	            $this->reportDisplayOptions->setFieldToDisplay($this->fieldForReport02);
	            $this->reportDisplayOptions->setNoDataValue("");
	            $this->reportDisplayOptions->setColumnHeadingLabel(array("Name"));
	            $this->reportDisplayOptions->setFirstColumnFormat("text");
	            $this->reportDisplayOptions->setMergeColumnGroup(array(TRUE, FALSE));
	            $this->reportDisplayOptions->setColourCells(FALSE);
	            $this->reportTitle = "02 - Umpire Names by League - ". $reportParameters['umpireType'];
	            $this->reportID = 2;
	            break;
            case 3:
                $this->reportDisplayOptions->setColumnGroup($this->columnGroupForReport03);
                $this->reportDisplayOptions->setRowGroup($this->rowGroupForReport03);
                $this->reportDisplayOptions->setFieldToDisplay($this->fieldForReport03);
                $this->reportDisplayOptions->setNoDataValue("");
                $this->reportDisplayOptions->setColumnHeadingLabel(array("Week (Sat)"));
                $this->reportDisplayOptions->setFirstColumnFormat("date");
                $this->reportDisplayOptions->setMergeColumnGroup(array(TRUE, FALSE));
                $this->reportDisplayOptions->setColourCells(FALSE);
                $this->reportTitle = "03 - Summary by Week";
                $this->reportID = 3;
                break;
            case 4:
                $this->reportDisplayOptions->setColumnGroup($this->columnGroupForReport04);
                $this->reportDisplayOptions->setRowGroup($this->rowGroupForReport04);
                $this->reportDisplayOptions->setFieldToDisplay($this->fieldForReport04);
                $this->reportDisplayOptions->setNoDataValue("");
                $this->reportDisplayOptions->setColumnHeadingLabel(array("Club"));
                $this->reportDisplayOptions->setFirstColumnFormat("text");
                $this->reportDisplayOptions->setMergeColumnGroup(array(TRUE, TRUE, FALSE));
                $this->reportDisplayOptions->setColourCells(FALSE);
                $this->reportTitle = "04 - Summary by Club";
                $this->reportID = 4;
                break;
            case 5:
                $this->reportDisplayOptions->setColumnGroup($this->columnGroupForReport05);
                $this->reportDisplayOptions->setRowGroup($this->rowGroupForReport05);
                $this->reportDisplayOptions->setFieldToDisplay($this->fieldForReport05);
                $this->reportDisplayOptions->setNoDataValue("0");
                $this->reportDisplayOptions->setColumnHeadingLabel(array("Discipline", "Age Group"));
                $this->reportDisplayOptions->setFirstColumnFormat("text");
                $this->reportDisplayOptions->setMergeColumnGroup(array(FALSE));
                $this->reportDisplayOptions->setColourCells(FALSE);
                $this->reportTitle = "05 - Games with Zero Umpires For Each League";
                $this->reportID = 5;
                break;
            case 6:
                $this->reportDisplayOptions->setColumnGroup($this->columnGroupForReport06);
                $this->reportDisplayOptions->setRowGroup($this->rowGroupForReport06);
                $this->reportDisplayOptions->setFieldToDisplay($this->fieldForReport06);
                $this->reportDisplayOptions->setNoDataValue("");
                $this->reportDisplayOptions->setColumnHeadingLabel(array("Name"));
                $this->reportDisplayOptions->setFirstColumnFormat("text");
                $this->reportDisplayOptions->setMergeColumnGroup(array(FALSE));
                $this->reportDisplayOptions->setColourCells(TRUE);
                $this->reportTitle = "06 - Umpire Pairing";
                $this->reportID = 6;
                break;

		}
		
	}
	
	
	
	public function getReportQuery() {
		return $this->reportQuery;
	}
	
	public function setResultArray($pResultArray) {
		$this->resultArray = $pResultArray;
	}
	
	public function getResultArray() {
		return $this->resultArray;
	}
	
	public function setColumnGroupingArray($pColumnGroupingArray) {
		$this->columnGroupingArray = $pColumnGroupingArray;
	}
	
	public function getColumnGroupingArray() {
		return $this->columnGroupingArray;
	}
	
	public function setRowGroupingArray($pRowGroupingArray) {
		$this->rowGroupingArray = $pRowGroupingArray;
	}
	
	public function getRowGroupingArray() {
		return $this->rowGroupingArray;
	}
	
	public function getDisplayOptions() {
		return $this->reportDisplayOptions;
	}
	
	public function getReportTitle() {
		return $this->reportTitle;
		
	}
	
	public function getReportColumnLabelQuery() {
		return $this->reportColumnLabelQuery;
	}
	
	public function getReportRowLabelQuery() {
		return $this->reportRowLabelQuery;
	}
	
	public function setColumnLabelResultArray($pResultArray) {
		$this->columnLabelResultArray = $pResultArray;
	}
	
	public function getColumnLabelResultArray() {
		return $this->columnLabelResultArray;
	}
	
	public function setRowLabelResultArray($pResultArray) {
		$this->rowLabelR = $pResultArray;
	}
	
	public function getRowLabelResultArray() {
		return $this->rowLabelResultArray;
	}
	
	public function setReportID($pValue) {
	    $this->reportID = $pValue;
	}
	
	public function getReportID() {
	    return $this->reportID;
	    
	}
	
	public function getColumnCountForHeadingCells() {
	    $columnLabelResults = $this->columnLabelResultArray;
	    $columnLabels = $this->getDisplayOptions()->getColumnGroup();
	    $columnCountLabels = [];

	    //Loop through the possible labels
	    for ($i=0; $i < count($columnLabels); $i++) {
	        if ($i == 0) {
	            $columnCountLabels[0] = [];
	        }
	        if ($i == 1) {
	            $columnCountLabels[1] = [];
	        }
	        if ($i == 2) {
	            $columnCountLabels[2] = [];
	        }
	        
	        $arrayKeyNumber = 0;
	        
	        
	        
	        //Loop through columnLabelResults
	        for ($j=0; $j < count($columnLabelResults); $j++) {
	           if ($i == 0) {
	               /*
	               echo "<BR />Find (" . $columnLabelResults[$j][$columnLabels[$i]] . ") in (";
	               echo $columnCountLabels[0] . ")<BR/>";
	               print_r($columnCountLabels[0]);
	               echo "<BR />";
	               */
	               //if (in_array($columnLabelResults[$j][$columnLabels[$i]], $columnCountLabels[0]) == TRUE) {
	               //if (array_key_exists($columnLabelResults[$j][$columnLabels[$i]], $columnCountLabels[0]) == TRUE) {
	               if (in_array_r($columnLabelResults[$j][$columnLabels[$i]], $columnCountLabels[$i]) == TRUE) {
	                   //Value found in array. Increment counter value
	                   //echo "- Value found: " . $columnLabelResults[$j][$columnLabels[$i]] . "<BR />";
	                   //Find the array that stores this value
	                   $currentArrayKey = $this->findKeyFromValue($columnCountLabels[$i], $columnLabelResults[$j][$columnLabels[$i]], "unique label");
	                   $columnCountLabels[$i][$currentArrayKey]["count"]++;
	                   
	                   //$columnCountLabels[0][$columnLabelResults[$j][$columnLabels[$i]]] = $columnCountLabels[0][$columnLabelResults[$j][$columnLabels[$i]]] + 1;
	               } else {
	                   //Value not found. Add to array.
	                   //$columnCountLabels[0][$columnLabelResults[$j][$columnLabels[$i]]] = 1;
	                   $columnCountLabels[$i][$arrayKeyNumber]["label"] = $columnLabelResults[$j][$columnLabels[$i]];
	                   $columnCountLabels[$i][$arrayKeyNumber]["unique label"] = $columnLabelResults[$j][$columnLabels[$i]];
	                   $columnCountLabels[$i][$arrayKeyNumber]["count"] = 1;
	                   $arrayKeyNumber++;
	               }
	           }
	           if ($i == 1) {
	               //echo "<BR />Find (" . $columnLabelResults[$j][$columnLabels[$i-1]] . "|" . $columnLabelResults[$j][$columnLabels[$i]] . ")<BR/>";
	               if (in_array_r($columnLabelResults[$j][$columnLabels[$i-1]] . "|" . $columnLabelResults[$j][$columnLabels[$i]], $columnCountLabels[$i]) == TRUE) {
	                   //Value found in array. Increment counter value
	                   //echo "- Value found: " . $columnLabelResults[$j][$columnLabels[$i]] . ". Check if previous fields match.<BR />";
	                   //Check if the value on the first row matches
	                   //echo "- Look for match (". $columnLabelResults[$j-1][$columnLabels[$i-1]] .") and (". $columnLabelResults[$j][$columnLabels[$i-1]] .")<BR />";
	                   if ($columnLabelResults[$j-1][$columnLabels[$i-1]] == $columnLabelResults[$j][$columnLabels[$i-1]]) {
	                       //echo "-- Match";
	                       
                           $currentArrayKey = $this->findKeyFromValue($columnCountLabels[$i], $columnLabelResults[$j][$columnLabels[$i-1]] . "|" . $columnLabelResults[$j][$columnLabels[$i]], "unique label");
                           //echo "-- Increment array key [". $i ."][". $currentArrayKey ."]<BR/>";
                           $columnCountLabels[$i][$currentArrayKey]["count"]++;
	                   } else {
	                       $columnCountLabels[$i][$arrayKeyNumber]["label"] = $columnLabelResults[$j][$columnLabels[$i]];
	                       $columnCountLabels[$i][$arrayKeyNumber]["unique label"] = $columnLabelResults[$j][$columnLabels[$i-1]] . "|" . $columnLabelResults[$j][$columnLabels[$i]];
	                       $columnCountLabels[$i][$arrayKeyNumber]["count"] = 1;
	                       $arrayKeyNumber++;
	                   }
	               } else {
	                   //Value not found. Add to array.
	                   //echo "- Value not found: " . $columnLabelResults[$j][$columnLabels[$i-1]] . "|" . $columnLabelResults[$j][$columnLabels[$i]] . ". Added to array.<BR />";
	                   $columnCountLabels[$i][$arrayKeyNumber]["label"] = $columnLabelResults[$j][$columnLabels[$i]];
	                   $columnCountLabels[$i][$arrayKeyNumber]["unique label"] = $columnLabelResults[$j][$columnLabels[$i-1]] . "|" . $columnLabelResults[$j][$columnLabels[$i]];
	                   $columnCountLabels[$i][$arrayKeyNumber]["count"] = 1;
	                   $arrayKeyNumber++;
	               }
	           }
	           if ($i == 2) {
	               //Set all count values to 1 for this level, as it is not likely that the third row will need to be merged/have a higher than 1 colspan.
	               $columnCountLabels[$i][$j]["label"] = $columnLabelResults[$j][$columnLabels[$i]];
	               $columnCountLabels[$i][$j]["unique label"] = $columnLabelResults[$j][$columnLabels[$i-2]] . "|" . $columnLabelResults[$j][$columnLabels[$i-1]] . "|" . $columnLabelResults[$j][$columnLabels[$i]];
	               $columnCountLabels[$i][$j]["count"] = 1;
	           }
	        }
	    }
	    
	    //echo "<BR />getColumnCountForHeadingCells:<BR /><pre>";
	    //print_r($columnCountLabels);
	    //echo "</pre><BR />";
	    
	    return $columnCountLabels;
	    
	}
	
	function in_array_r($needle, $haystack, $strict = false) {
	    foreach ($haystack as $item) {
	        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
	            return true;
	        }
	    }
	
	    return false;
	}
	
	function findKeyFromValue($pArray, $pValueToFind, $pKeyToLookAt) {
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