<?php
require_once('/system/libraries/MY_Model.php');


class MatchImport extends MY_Model 
{   
  /* Code .. */   
  
  function __construct()
    {
          parent::__construct();
    }
  
  public function fileImport() {
	$dataFfile = "application/import/2015-appointments-summary.xls";
	$objPHPExcel = PHPExcel_IOFactory::load($dataFfile);
	$sheet = $objPHPExcel->getActiveSheet();
	$lastRow = $sheet->getHighestRow();
	echo "Last row: $lastRow<BR/>";
	$data = $sheet->rangeToArray('A1:Q'.$lastRow);
	echo "Rows available: " . count($data) . "\n";
	/*foreach ($data as $row) {
		print_r($row);
	}*/
	/*
	echo "<pre>";
	print_r($data);
	echo "</pre>";
	*/
	$columns = array('season', 'round', 'date', 'competition_name', 'ground', 'time', 'home_team', 'away_team', 'field_umpire_1', 'field_umpire_2', 'field_umpire_3', 'boundary_umpire_1', 'boundary_umpire_2', 'boundary_umpire_3', 'boundary_umpire_4', 'goal_umpire_1', 'goal_umpire_2');
	$rows = $data;
	$this->insert_rows('match_import', $columns, $rows); 
  }
  
  function InsertTestData()   
  {     
    /* Prepare some fake data (10000 rows, 40,000 values total) */
    $rows = array_fill(0, 10000, array(34239, 102438, "Test Message!", '2009-12-12'));
    $columns = array('to_user_id', 'from_user_id', 'message', 'created');
    $this->insert_rows('messages', $columns, $rows); 
  }
}
?>