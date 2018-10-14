<?php
//This interface is used to allow testing of the validation separate from database testing
//More info: https://stackoverflow.com/questions/19937667/how-can-i-unit-test-a-method-with-database-access
interface IData_store {
    public function loadAllReportParameters($pReportNumber);
    
    public function loadAllGroupingStructures($pReportNumber);
}