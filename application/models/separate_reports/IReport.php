<?php

interface IReport {
    
    public function getReportDataQuery(Report_instance $pReportInstance);
    
    public function getReportColumnQuery(Report_instance $pReportInstance);
    
    public function transformQueryResultsIntoOutputArray(Report_cell_collection $pReportCellCollection, $columnLabelResultArray, $pReportColumnFields);
    
    public function formatOutputArrayForView($pResultOutputArray, $pLoadedColumnGroupings,
                                             $pReportDisplayOptions, $pColumnCountForHeadingCells);

    public function isFieldMatchingColumn($pColumnItem, $pColumnHeadingSet, $pReportColumnFields);

    public function pivotQueryArray($pResultArray, Report_display_options $pReportDisplayOptions);
    
}
