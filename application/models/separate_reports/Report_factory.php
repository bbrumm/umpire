<?php
include 'Report1.php';
include 'Report2.php';
include 'Report3.php';
include 'Report4.php';
include 'Report5.php';
include 'Report6.php';
include 'Report7.php';
include 'Report8.php';

class Report_factory {
    
    public static function createReport($pReportNumber) {
        $report = null;
        
        switch ($pReportNumber) {
            case 1:
                $report = new Report1();
                break;
            case 2:
                $report = new Report2();
                break;
            case 3:
                $report = new Report3();
                break;
            case 4:
                $report = new Report4();
                break;
            case 5:
                $report = new Report5();
                break;
            case 6:
                $report = new Report6();
                break;
            case 7:
                $report = new Report7();
                break;
            case 8:
                $report = new Report8();
                break;
        }
        
        return $report;
        
    }
    
    
    
    
}