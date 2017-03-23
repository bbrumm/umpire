<?php
/*
ReportGroupingStructure
Defines how the reports are grouped and displayed, in terms of the columns and rows that are shown

*/
class Report_grouping_structure
{
    function __construct()
    {
        //parent::__construct();
    }

    private $reportGroupingStructureID;
    private $reportID;
    private $groupingType;
    private $fieldName;
    private $fieldGroupOrder;
    private $mergeField;
    private $groupHeading;
    private $groupSizeText;

    //Get Functions
    public function getReportGroupingStructureID() {
        return $this->reportGroupingStructureID;
    }
    
    public function getReportID() {
        return $this->reportID;
    }

    public function getGroupingType() {
        return $this->groupingType;
    }

    public function getFieldName() {
        return $this->fieldName;
    }
    
    public function getFieldGroupOrder() {
        return $this->fieldGroupOrder;
    }
    
    public function getMergeField() {
        return $this->mergeField;
    }
    public function getGroupHeading() {
        return $this->groupHeading;
    }
    public function getGroupSizeText() {
        return $this->groupSizeText;
    }
        

    //Set Functions
    public function setReportGroupingStructureID($pValue) {
        $this->reportGroupingStructureID = $pValue;
    }
    
    public function setReportID($pValue) {
        $this->reportID = $pValue;
    }
    
    public function setGroupingType($pValue) {
        $this->groupingType = $pValue;
    }

    public function setFieldName($pValue) {
        $this->fieldName= $pValue;
    }

    public function setFieldGroupOrder($pValue) {
        $this->fieldGroupOrder = $pValue;
    }
    
    public function setMergeField($pValue) {
        $this->mergeField = $pValue;
    }
    
    public function setGroupHeading($pValue) {
        $this->groupHeading = $pValue;
    }
    public function setGroupSizeText($pValue) {
        $this->groupSizeText = $pValue;
    }
    
}

?>