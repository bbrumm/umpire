<?php

class Report_populator_model_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('Report_populator_model');
    }

    public function test_dummy() {
        $this->assertEquals(1, 1);
    }


    /*
     * These tests actually look up data from the database.
     * I should run these once I have test data set up.
    public function test_CreateReport1() {
        $requestedReport = Requested_report_model::createRequestedReportFromValues(
            1, '2018', 'North', array('Mid'),
            array('Field'), array('AFL'), false);
        $this->obj = new Report_populator_model();

        $generatedReport = $this->obj->getReport($requestedReport);
        $this->assertInstanceOf('Report1', $generatedReport);
    }

    public function test_CreateReport2() {
        $requestedReport = Requested_report_model::createRequestedReportFromValues(
            2, '2018', 'North', array('Mid'),
            array('Field'), array('AFL'), false);
        $this->obj = new Report_populator_model();

        $generatedReport = $this->obj->getReport($requestedReport);
        $this->assertInstanceOf('Report2', $generatedReport);
    }


    public function test_CreateReport3() {
        $requestedReport = Requested_report_model::createRequestedReportFromValues(
            3, '2018', 'North', array('Mid'),
            array('Field'), array('AFL'), false);
        $this->obj = new Report_populator_model();

        $generatedReport = $this->obj->getReport($requestedReport);
        $this->assertInstanceOf('Report3', $generatedReport);
    }


    public function test_CreateReport4() {
        $requestedReport = Requested_report_model::createRequestedReportFromValues(
            4, '2018', 'North', array('Mid'),
            array('Field'), array('AFL'), false);
        $this->obj = new Report_populator_model();

        $generatedReport = $this->obj->getReport($requestedReport);
        $this->assertInstanceOf('Report4', $generatedReport);
    }


    public function test_CreateReport5() {
        $requestedReport = Requested_report_model::createRequestedReportFromValues(
            5, '2018', 'North', array('Mid'),
            array('Field'), array('AFL'), false);
        $this->obj = new Report_populator_model();

        $generatedReport = $this->obj->getReport($requestedReport);
        $this->assertInstanceOf('Report5', $generatedReport);
    }


    public function test_CreateReport6() {
        $requestedReport = Requested_report_model::createRequestedReportFromValues(
            6, '2018', 'North', array('Mid'),
            array('Field'), array('AFL'), false);
        $this->obj = new Report_populator_model();

        $generatedReport = $this->obj->getReport($requestedReport);
        $this->assertInstanceOf('Report6', $generatedReport);
    }


    public function test_CreateReport7() {
        $requestedReport = Requested_report_model::createRequestedReportFromValues(
            7, '2018', 'North', array('Mid'),
            array('Field'), array('AFL'), false);
        $this->obj = new Report_populator_model();

        $generatedReport = $this->obj->getReport($requestedReport);
        $this->assertInstanceOf('Report7', $generatedReport);
    }


    public function test_CreateReport8() {
        $requestedReport = Requested_report_model::createRequestedReportFromValues(
            8, '2018', 'North', array('Mid'),
            array('Field'), array('AFL'), false);
        $this->obj = new Report_populator_model();

        $generatedReport = $this->obj->getReport($requestedReport);
        $this->assertInstanceOf('Report8', $generatedReport);
    }


    public function test_CreateReport9() {
        $this->expectException(Exception::class);
        $requestedReport = Requested_report_model::createRequestedReportFromValues(
            9, '2018', 'North', array('Mid'),
            array('Field'), array('AFL'), false);
        $this->obj = new Report_populator_model();

        $generatedReport = $this->obj->getReport($requestedReport);
    }
    */
}