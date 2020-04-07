<?php

interface IReport {
    
    public function getReportDataQuery(Report_instance $pReportInstance);
    
    public function getReportColumnQuery(Report_instance $pReportInstance);
    
    public function transformQueryResultsIntoOutputArray($pResultArray, $columnLabelResultArray, $pReportColumnFields);
    
    public function formatOutputArrayForView($pResultOutputArray, $pLoadedColumnGroupings,
                                             $pReportDisplayOptions, $pColumnCountForHeadingCells);

    public function isFieldMatchingColumn($pColumnItem, $pColumnHeadingSet, $pReportColumnFields);

    public function pivotQueryArray($pResultArray, Report_cell_collection $pRowLabelFields, Report_cell_collection $pColumnLabelFields);
    
}
