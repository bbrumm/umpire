<?php
class Report_cell_collection_test extends TestCase {

    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('separate_reports/Report_cell_collection');
        $this->CI->load->model('separate_reports/Report_cell');
        $this->obj = $this->CI->Report_cell_collection;
    }

    //ReportCellArray
    public function test_ReportCellArray_ValidArray() {
        $inputValue = array('a', 'b', 'c');
        $this->obj->setReportCellArray($inputValue);

        $actualValue = $this->obj->getReportCellArray();
        $this->assertEquals($inputValue, $actualValue);
    }

    public function test_ReportCellArray_EmptyArray() {
        $inputValue = array();
        $this->obj->setReportCellArray($inputValue);

        $actualValue = $this->obj->getReportCellArray();
        $this->assertEquals($inputValue, $actualValue);
    }

    public function test_ReportCellArray_NonArray() {
        $inputValue = "something";
        $this->obj->setReportCellArray($inputValue);

        $actualValue = $this->obj->getReportCellArray();
        $this->assertEquals($inputValue, $actualValue);
    }

    //RowLabelFields
    public function test_RowLabelFields_ValidArray() {
        $inputValue = array('a', 'b', 'c');
        $this->obj->setRowLabelFields($inputValue);

        $actualValue = $this->obj->getRowLabelFields();
        $this->assertEquals($inputValue, $actualValue);
    }

    public function test_RowLabelFields_EmptyArray() {
        $inputValue = array();
        $this->obj->setRowLabelFields($inputValue);

        $actualValue = $this->obj->getRowLabelFields();
        $this->assertEquals($inputValue, $actualValue);
    }

    public function test_RowLabelFields_NonArray() {
        $inputValue = "something";
        $this->obj->setRowLabelFields($inputValue);

        $actualValue = $this->obj->getRowLabelFields();
        $this->assertEquals($inputValue, $actualValue);
    }

    //ColumnLabelFields
    public function test_ColumnLabelFields_ValidArray() {
        $inputValue = array('a', 'b', 'c');
        $this->obj->setColumnLabelFields($inputValue);

        $actualValue = $this->obj->getColumnLabelFields();
        $this->assertEquals($inputValue, $actualValue);
    }

    public function test_ColumnLabelFields_EmptyArray() {
        $inputValue = array();
        $this->obj->setColumnLabelFields($inputValue);

        $actualValue = $this->obj->getColumnLabelFields();
        $this->assertEquals($inputValue, $actualValue);
    }

    public function test_ColumnLabelFields_NonArray() {
        $inputValue = "something";
        $this->obj->setColumnLabelFields($inputValue);

        $actualValue = $this->obj->getColumnLabelFields();
        $this->assertEquals($inputValue, $actualValue);
    }

    //Current Row to Cell Collection
    public function test_AddCurrentRowToCellCollection_Valid() {
        $inputResultRow = array('GFL'=>5);
        $inputCounterForRow = 1;
        $inputFieldName = "GFL";

        //Add to collection
        $this->obj->addCurrentRowToCellCollection($inputResultRow, $inputCounterForRow, $inputFieldName);

        //Get collection
        $updatedCollection = $this->obj->getCollection();

        //Analyse collection
        $actualCountOfCollection = count($updatedCollection);
        $expectedCountOfCollection = 1;

        //Assert
        $this->assertEquals($expectedCountOfCollection, $actualCountOfCollection);

    }

    public function test_AddReportCellToCollection_Valid() {
        $newReportCell = new Report_cell();
        $newReportCell->setCellValue("sample value");
        $rowNumber = 2;

        $this->obj->addReportCellToCollection($rowNumber, $newReportCell);

        //Get collection
        $updatedCollection = $this->obj->getCollection();

        //Analyse collection
        $actualCountOfCollection = count($updatedCollection);
        $expectedCountOfCollection = 1;

        //Assert
        $this->assertEquals($expectedCountOfCollection, $actualCountOfCollection);
    }

    public function test_AddReportTotalCellToCollection() {
        $newReportCell = new Report_cell();
        $newReportCell->setCellValue("sample total");
        $rowNumber = 2;

        $this->obj->addReportTotalCellToCollection($rowNumber, $newReportCell);

        //Get collection
        $updatedCollection = $this->obj->getCollection();

        //Analyse collection
        $actualCountOfCollection = count($updatedCollection);
        $expectedCountOfCollection = 1;

        //Assert
        $this->assertEquals($expectedCountOfCollection, $actualCountOfCollection);

        //Assert total was added
        $expectedCellValue = $newReportCell;
        $actualCellValue = $updatedCollection[2]["rowTotal"];
        $this->assertEquals($expectedCellValue, $actualCellValue);

    }

    public function test_AddCurrentRowToCollection_Valid() {
        $inputResultRow = array(
            'GFL'=>5
        );
        $inputCounterForRow = 1;
        $inputFieldName = "GFL";
        $inputRowLabelFieldName = "GFL";

        //Add to collection
        $this->obj->addCurrentRowToCollection(
            $inputResultRow, $inputCounterForRow, $inputFieldName, $inputRowLabelFieldName);

        //Get array
        $actualArray = $this->obj->getPivotedArray();

        //Analyse collection
        $actualCountOfCollection = count($actualArray);
        $expectedCountOfCollection = 1;

        //Assert
        $this->assertEquals($expectedCountOfCollection, $actualCountOfCollection);



    }






}