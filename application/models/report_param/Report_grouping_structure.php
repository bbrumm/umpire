<?php
/*
ReportGroupingStructure
Defines how the reports are grouped and displayed, in terms of the columns and rows that are shown

*/
class Report_grouping_structure
{
    public function __construct()
    {
        //parent::__construct();
    }

    public static function createNewReportGroupingStructure($pReportGroupingStructureID, $pGroupingType, 
        $pFieldName, $pFieldGroupOrder, $pMergeField, 
        $pGroupHeading, $pGroupSizeText) {

        $obj = new Report_grouping_structure();

        $obj->setReportGroupingStructureID($pReportGroupingStructureID);
        $obj->setGroupingType($pGroupingType);
        $obj->setFieldName($pFieldName);
        $obj->setFieldGroupOrder($pFieldGroupOrder);
        $obj->setMergeField($pMergeField);
        $obj->setGroupHeading($pGroupHeading);
        $obj->setGroupSizeText($pGroupSizeText);

        return $obj;
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
    private function setReportGroupingStructureID($pValue) {
        $this->reportGroupingStructureID = $pValue;
    }
    
    private function setReportID($pValue) {
        $this->reportID = $pValue;
    }
    
    private function setGroupingType($pValue) {
        $this->groupingType = $pValue;
    }

    private function setFieldName($pValue) {
        $this->fieldName= $pValue;
    }

    private function setFieldGroupOrder($pValue) {
        $this->fieldGroupOrder = $pValue;
    }
    
    private function setMergeField($pValue) {
        $this->mergeField = $pValue;
    }
    
    private function setGroupHeading($pValue) {
        $this->groupHeading = $pValue;
    }
    private function setGroupSizeText($pValue) {
        $this->groupSizeText = $pValue;
    }
    
}
