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

    const REPORT_CLASSNAMES = array (
        1=>'Report1',
        2=>'Report2',
        3=>'Report3',
        4=>'Report4',
        5=>'Report5',
        6=>'Report6',
        7=>'Report7',
        8=>'Report8',
    );
    
    public static function createReport($pReportNumber) {
        if (array_key_exists(intval($pReportNumber), self::REPORT_CLASSNAMES)) {
            $className = self::REPORT_CLASSNAMES[$pReportNumber];
            return new $className();
        } else {
            throw new InvalidArgumentException ("Specified report number does not match a required report. Value provided was " . $pReportNumber);
        }
    }
}
