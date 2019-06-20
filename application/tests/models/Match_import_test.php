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
     * Missing several columns
     * Extra column
     * No records
     * Not an Excel file
     * Another sheet selected
     *
     */

    public function test_ImportFile_ValidXLS() {
        $fileLoader = new File_loader_test();
        $dataStore = new Array_store_match_import();
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
        $actualLastRowSeason = $matchImportArray[3]['season'];
        $this->assertEquals($expectedLastRowSeason, $actualLastRowSeason);

        $expectedLastRowFieldUmp1 = "Christopher Jones";
        $actualLastRowFieldUmp1 = $matchImportArray[3]['field_umpire_1'];
        $this->assertEquals($expectedLastRowFieldUmp1, $actualLastRowFieldUmp1);
    }

    public function test_ImportFile_ValidXLSX() {
        $fileLoader = new File_loader_test();
        $dataStore = new Array_store_match_import();
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
        $actualLastRowSeason = $matchImportArray[$actualRowCount-1]['season'];
        $this->assertEquals($expectedLastRowSeason, $actualLastRowSeason);

        $expectedLastRowFieldUmp1 = "Aaron Riches";
        $actualLastRowFieldUmp1 = $matchImportArray[$actualRowCount-1]['field_umpire_1'];
        $this->assertEquals($expectedLastRowFieldUmp1, $actualLastRowFieldUmp1);
    }

    public function test_ImportFile_MissingColumnHeaderRow() {
        $this->expectException(Exception::class);
        $fileLoader = new File_loader_test();
        $dataStore = new Array_store_match_import();
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
        $dataStore = new Array_store_match_import();
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
        $dataStore = new Array_store_match_import();
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
        $dataStore = new Array_store_match_import();
        $data = array (
            "upload_data"=>array(
                "file_name"=>"test_missing_last_col.xlsx"
            )
        );

        $actualResult = $this->obj->fileImport($fileLoader, $dataStore, $data);
    }

    public function test_ImportFile_MissingSeveralColumns() {
        $this->expectException(Exception::class);
        $fileLoader = new File_loader_test();
        $dataStore = new Array_store_match_import();
        $data = array (
            "upload_data"=>array(
                "file_name"=>"test_missing_several_cols.xlsx"
            )
        );

        $actualResult = $this->obj->fileImport($fileLoader, $dataStore, $data);
    }

    public function test_ImportFile_ExtraColumn() {
        $fileLoader = new File_loader_test();
        $dataStore = new Array_store_match_import();
        $data = array (
            "upload_data"=>array(
                "file_name"=>"test_extra_column.xlsx"
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
        $actualLastRowSeason = $matchImportArray[$actualRowCount-1]['season'];
        $this->assertEquals($expectedLastRowSeason, $actualLastRowSeason);

        $expectedLastRowFieldUmp2 = "Daniel Dorling";
        $actualLastRowFieldUmp2 = $matchImportArray[$actualRowCount-1]['field_umpire_2'];
        $this->assertEquals($expectedLastRowFieldUmp2, $actualLastRowFieldUmp2);
    }

    public function test_ImportFile_NoData() {
        $this->expectException(Exception::class);
        $fileLoader = new File_loader_test();
        $dataStore = new Array_store_match_import();
        $data = array (
            "upload_data"=>array(
                "file_name"=>"test_no_data.xlsx"
            )
        );

        $actualResult = $this->obj->fileImport($fileLoader, $dataStore, $data);
    }

    public function test_ImportFile_NotExcel() {
        $this->expectException(Exception::class);
        $fileLoader = new File_loader_test();
        $dataStore = new Array_store_match_import();
        $data = array (
            "upload_data"=>array(
                "file_name"=>"license.txt"
            )
        );

        $actualResult = $this->obj->fileImport($fileLoader, $dataStore, $data);
    }

    public function test_ImportFile_AnotherSheetSelected() {
        $this->expectException(Exception::class);
        $fileLoader = new File_loader_test();
        $dataStore = new Array_store_match_import();
        $data = array (
            "upload_data"=>array(
                "file_name"=>"test_another_sheet.xlsx"
            )
        );

        $actualResult = $this->obj->fileImport($fileLoader, $dataStore, $data);
    }


}
