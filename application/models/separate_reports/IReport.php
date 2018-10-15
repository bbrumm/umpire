<?php

interface IReport {
    
    public function getReportDataQuery(Report_instance $pReportInstance);
    
    public function getReportColumnQuery(Report_instance $pReportInstance);
    
    public function transformQueryResultsIntoOutputArray($pResultArray, $columnLabelResultArray, $pReportColumnFields);
    
    public function formatOutputArrayForView();

    public function isFieldMatchingColumn($pColumnItem, $pColumnHeadingSet, $pReportColumnFields);
    
}
