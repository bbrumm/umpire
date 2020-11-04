<?php

interface IReport {
    
    public function getReportDataQuery(Report_instance $pReportInstance);
    
    public function getReportColumnQuery(Report_instance $pReportInstance);
    
    public function formatOutputArrayForView($pResultOutputArray, $pLoadedColumnGroupings,
                                             $pReportDisplayOptions, $pColumnCountForHeadingCells);

    
}
