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
        switch ($pReportNumber) {
            case 1:
                return new Report1();
            case 2:
                return new Report2();
            case 3:
                return new Report3();
            case 4:
                return new Report4();
            case 5:
                return new Report5();
            case 6:
                return new Report6();
            case 7:
                return new Report7();
            case 8:
                return new Report8();
            default:
                throw new InvalidArgumentException ("Specified report number does not match a required report. Value provided was " . $pReportNumber);
        }
    }
}
