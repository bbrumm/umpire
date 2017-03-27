<?php

abstract class Report_data_query {
    
    abstract function getReportDataQuery(Report_instance $pReportInstance);
    
    abstract function getReportColumnQuery(Report_instance $pReportInstance);
    
}