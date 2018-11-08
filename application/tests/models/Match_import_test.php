<?php

class Match_import_test extends TestCase
{

    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('Match_import');
        $this->obj = $this->CI->Match_import;
    }

    /*
     * Test cases:
     * Valid XLS file with all columns
     * Valid XLSX file with all columns
     * Missing column header row
     * Blank column headers
     * Incorrect column headers
     * Missing last column
     * //TODO write
     * Missing several columns
     * Extra column
     * No records
     * Not an Excel file
     * Another sheet selected
     *
     */

    public function test_ImportFile_ValidXLS() {
        $fileLoader = new File_loader_test();
        $dataStore = new Database_store_matches();
        $data = array (
            "upload_data"=>array(
                "file_name"=>"test_valid_xls.xls"
            )
        );
        $expectedResult = true;
        $actualResult = $this->obj->fileImport($fileLoader, $dataStore, $data);
        $this->assertEquals($expectedResult, $actualResult);

        $matchImportArray = $fileLoader->getMatchImportData();
        $expectedRowCount = 4;
        $actualRowCount = count($matchImportArray);
        $this->assertEquals($expectedRowCount, $actualRowCount);

        $expectedLastRowSeason = 2018;
        $actualLastRowSeason = $matchImportArray[3][0];
        $this->assertEquals($expectedLastRowSeason, $actualLastRowSeason);

        $expectedLastRowFieldUmp1 = "Christopher Jones";
        $actualLastRowFieldUmp1 = $matchImportArray[3][8];
        $this->assertEquals($expectedLastRowFieldUmp1, $actualLastRowFieldUmp1);
    }

    public function test_ImportFile_ValidXLSX() {
        $fileLoader = new File_loader_test();
        $dataStore = new Database_store_matches();
        $data = array (
            "upload_data"=>array(
                "file_name"=>"test_valid_xlsx.xlsx"
            )
        );
        $expectedResult = true;
        $actualResult = $this->obj->fileImport($fileLoader, $dataStore, $data);
        $this->assertEquals($expectedResult, $actualResult);

        $matchImportArray = $fileLoader->getMatchImportData();
        $expectedRowCount = 3;
        $actualRowCount = count($matchImportArray);
        $this->assertEquals($expectedRowCount, $actualRowCount);

        $expectedLastRowSeason = 2018;
        $actualLastRowSeason = $matchImportArray[$actualRowCount-1][0];
        $this->assertEquals($expectedLastRowSeason, $actualLastRowSeason);

        $expectedLastRowFieldUmp1 = "Aaron Riches";
        $actualLastRowFieldUmp1 = $matchImportArray[$actualRowCount-1][8];
        $this->assertEquals($expectedLastRowFieldUmp1, $actualLastRowFieldUmp1);
    }

    public function test_ImportFile_MissingColumnHeaderRow() {
        $this->expectException(Exception::class);
        $fileLoader = new File_loader_test();
        $dataStore = new Database_store_matches();
        $data = array (
            "upload_data"=>array(
                "file_name"=>"test_missing_header_row.xlsx"
            )
        );

        $actualResult = $this->obj->fileImport($fileLoader, $dataStore, $data);
    }

    public function test_ImportFile_BlankColumnHeaderRow() {
        $this->expectException(Exception::class);
        $fileLoader = new File_loader_test();
        $dataStore = new Database_store_matches();
        $data = array (
            "upload_data"=>array(
                "file_name"=>"test_blank_header_row.xlsx"
            )
        );

        $actualResult = $this->obj->fileImport($fileLoader, $dataStore, $data);
    }

    public function test_ImportFile_IncorrectColumnHeaderRow() {
        $this->expectException(Exception::class);
        $fileLoader = new File_loader_test();
        $dataStore = new Database_store_matches();
        $data = array (
            "upload_data"=>array(
                "file_name"=>"test_incorrect_header_row.xlsx"
            )
        );

        $actualResult = $this->obj->fileImport($fileLoader, $dataStore, $data);
    }

    public function test_ImportFile_MissingLastColumn() {
        $this->expectException(Exception::class);
        $fileLoader = new File_loader_test();
        $dataStore = new Database_store_matches();
        $data = array (
            "upload_data"=>array(
                "file_name"=>"test_missing_last_col.xlsx"
            )
        );

        $actualResult = $this->obj->fileImport($fileLoader, $dataStore, $data);
    }


}