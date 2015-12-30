<?php
require_once('Reportdisplayoptions.php');
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
    
		
	private $fieldForReport01 = 'match_count';
	private $fieldForReport02 = 'match_count';
	private $fieldForReport03 = 'match_count';
	
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

	public $reportDisplayOptions;
	
	public function __construct() {
		$this->reportDisplayOptions = new ReportDisplayOptions();
		$this->load->database();
	}
	
	public function setReportType($reportParameters) {
		/*echo "<pre>";
		print_r($reportParameters);
		echo "</pre>";*/
	    
	    switch ($reportParameters['reportName']) {
	        case 1:
    			$this->reportDisplayOptions->setColumnGroup($this->columnGroupForReport01);
    			$this->reportDisplayOptions->setRowGroup($this->rowGroupForReport01);
    			$this->reportDisplayOptions->setFieldToDisplay($this->fieldForReport01);
    			$this->reportDisplayOptions->setNoDataValue("");
    			$this->reportDisplayOptions->setFirstColumnHeadingLabel("Name");
    			$this->reportDisplayOptions->setFirstColumnFormat("text");
    			$this->reportDisplayOptions->setMergeColumnGroup(array(TRUE, FALSE));
    			$this->reportDisplayOptions->setColourCells(TRUE);
    			$this->reportTitle = "01 - Umpires and Clubs - ". $reportParameters['age']." - ".$reportParameters['umpireType'];
    			break;
	        case 2:
	            $this->reportDisplayOptions->setColumnGroup($this->columnGroupForReport02);
	            $this->reportDisplayOptions->setRowGroup($this->rowGroupForReport02);
	            $this->reportDisplayOptions->setFieldToDisplay($this->fieldForReport02);
	            $this->reportDisplayOptions->setNoDataValue("");
	            $this->reportDisplayOptions->setFirstColumnHeadingLabel("Name");
	            $this->reportDisplayOptions->setFirstColumnFormat("text");
	            $this->reportDisplayOptions->setMergeColumnGroup(array(TRUE, FALSE));
	            $this->reportDisplayOptions->setColourCells(FALSE);
	            $this->reportTitle = "02 - Umpire Names by League - ". $reportParameters['umpireType'];
	            break;
            case 3:
                $this->reportDisplayOptions->setColumnGroup($this->columnGroupForReport03);
                $this->reportDisplayOptions->setRowGroup($this->rowGroupForReport03);
                $this->reportDisplayOptions->setFieldToDisplay($this->fieldForReport03);
                $this->reportDisplayOptions->setNoDataValue("");
                $this->reportDisplayOptions->setFirstColumnHeadingLabel("Week (Sat)");
                $this->reportDisplayOptions->setFirstColumnFormat("date");
                $this->reportDisplayOptions->setMergeColumnGroup(array(TRUE, FALSE));
                $this->reportDisplayOptions->setColourCells(FALSE);
                $this->reportTitle = "03 - Summary by Week";
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
	
	
	
	
	
}



?>