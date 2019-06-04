<?php
class FileImport_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $_POST = array();
        $this->dbLocal = $this->CI->load->database('default', TRUE);
    }


    public function tearDown() {
        $this->dbLocal->close();
    }

    public function test_ImportFile() {

        $filename = "2018_Appointments_Master 2018_08_08.xls";
        $fileNameFull = "application/tests/import/". $filename;
        $postArray = array(
            'userfile'=>$fileNameFull
        );

        $_FILES['userfile'] = array(
            //'name'      =>  $fileNameFull,
            'name'      =>  $filename,
            'tmp_name'  =>  APPPATH . 'tests/import/' . $filename,
            //'tmp_name'  =>  $filename,
            'type'      =>  'xlsx',
            'size'      =>  10141,
            'error'     =>  0
        );


        //$_FILES['userfile'] = "application/tests/import/test_valid_xlsx.xlsx";

        $output = $this->request('POST', ['FileImport', 'do_upload'], $postArray);
        $expected = "Upload completed!";
        $this->assertContains($expected, $output);
    }

    public function test_ImportFile_Invalid() {
        $filename = "some_file_that_doesnt_exist.xlsx";


        $_FILES['userfile'] = array(
            //'name'      =>  $fileNameFull,
            'name'      =>  $filename,
            'tmp_name'  =>  APPPATH . 'tests/import/' . $filename,
            //'tmp_name'  =>  $filename,
            'type'      =>  'xlsx',
            'size'      =>  10141,
            'error'     =>  0
        );


        $output = $this->request('POST', ['FileImport', 'do_upload']);
        $expected = "You did not select a file to upload.";
        $this->assertContains($expected, $output);
    }

/*
 * TODO: This test takes >10 minutes to run on Travis. Look into why it takes so long.
 
    public function test_RunETLProcess() {
        $output = $this->request('POST', ['FileImport', 'runETLProcess']);
        $expected = "Upload completed!";
        $this->assertContains($expected, $output);

    }
*/

    public function test_ImportFile_NewData() {

        $filename = "test_new_data.xlsx";
        $fileNameFull = "application/tests/import/". $filename;
        $postArray = array(
            'userfile'=>$fileNameFull
        );

        $_FILES['userfile'] = array(
            //'name'      =>  $fileNameFull,
            'name'      =>  $filename,
            'tmp_name'  =>  APPPATH . 'tests/import/' . $filename,
            //'tmp_name'  =>  $filename,
            'type'      =>  'xlsx',
            'size'      =>  10141,
            'error'     =>  0
        );


        $output = $this->request('POST', ['FileImport', 'do_upload'], $postArray);
        $expected = "Upload completed!";
        $this->assertContains($expected, $output);
        $expected = "Missing Data";
        $this->assertContains($expected, $output);

        $expected = "<div class='centerText'>Competitions</div>";
        $this->assertContains($expected, $output);
        $expected = "<div class='centerText'>Teams</div>";
        $this->assertContains($expected, $output);


    }



    //Test that all import steps are performed

    public function test_ImportSteps() {
        $filename = "2018_Appointments_Master 2018_08_08.xls";
        $fileNameFull = "application/tests/import/". $filename;
        $postArray = array(
            'userfile'=>$fileNameFull
        );

        $_FILES['userfile'] = array(
            //'name'      =>  $fileNameFull,
            'name'      =>  $filename,
            'tmp_name'  =>  APPPATH . 'tests/import/' . $filename,
            //'tmp_name'  =>  $filename,
            'type'      =>  'xlsx',
            'size'      =>  10141,
            'error'     =>  0
        );
        $output = $this->request('POST', ['FileImport', 'do_upload'], $postArray);

        $expectedArray = array(
            array('table_name'=>'umpire_name_type_match', 'operation_name'=>'DELETE'),
            array('table_name'=>'match_played', 'operation_name'=>'DELETE'),
            array('table_name'=>'round', 'operation_name'=>'DELETE'),
            array('table_name'=>'match_staging', 'operation_name'=>'DELETE'),
            array('table_name'=>'dw_fact_match', 'operation_name'=>'DELETE'),
            array('table_name'=>'round', 'operation_name'=>'INSERT'),
            array('table_name'=>'umpire', 'operation_name'=>'INSERT'),
            array('table_name'=>'umpire_name_type', 'operation_name'=>'INSERT'),
            array('table_name'=>'match_staging', 'operation_name'=>'INSERT'),
            array('table_name'=>'match_staging', 'operation_name'=>'DELETE'),
            array('table_name'=>'match_played', 'operation_name'=>'INSERT'),
            array('table_name'=>'umpire_name_type_match', 'operation_name'=>'INSERT'),
            array('table_name'=>'dw_dim_umpire', 'operation_name'=>'INSERT'),
            array('table_name'=>'dw_dim_age_group', 'operation_name'=>'INSERT'),
            array('table_name'=>'dw_dim_league', 'operation_name'=>'INSERT'),
            array('table_name'=>'dw_dim_team', 'operation_name'=>'INSERT'),
            array('table_name'=>'dw_dim_time', 'operation_name'=>'INSERT'),
            array('table_name'=>'staging_match', 'operation_name'=>'INSERT'),
            array('table_name'=>'staging_all_ump_age_league', 'operation_name'=>'INSERT'),
            array('table_name'=>'dw_fact_match', 'operation_name'=>'INSERT'),
            array('table_name'=>'staging_no_umpires', 'operation_name'=>'INSERT'),
            array('table_name'=>'competition_lookup', 'operation_name'=>'DELETE'),
            array('table_name'=>'competition_lookup', 'operation_name'=>'INSERT'),
            array('table_name'=>'team', 'operation_name'=>'INSERT'),
            array('table_name'=>'ground', 'operation_name'=>'INSERT'),
            array('table_name'=>'dw_mv_report_01', 'operation_name'=>'DELETE'),
            array('table_name'=>'dw_mv_report_01', 'operation_name'=>'INSERT'),
            array('table_name'=>'dw_mv_report_02', 'operation_name'=>'DELETE'),
            array('table_name'=>'dw_mv_report_02', 'operation_name'=>'INSERT'),
            array('table_name'=>'dw_mv_report_04', 'operation_name'=>'DELETE'),
            array('table_name'=>'dw_mv_report_04', 'operation_name'=>'INSERT'),
            array('table_name'=>'dw_mv_report_05', 'operation_name'=>'DELETE'),
            array('table_name'=>'dw_mv_report_05', 'operation_name'=>'INSERT'),
            array('table_name'=>'dw_mv_report_06', 'operation_name'=>'INSERT'),
            array('table_name'=>'dw_rpt06_stg2', 'operation_name'=>'INSERT'),
            array('table_name'=>'dw_rpt06_stg2', 'operation_name'=>'INSERT'),
            array('table_name'=>'dw_rpt06_stg2', 'operation_name'=>'INSERT'),
            array('table_name'=>'dw_rpt06_stg2', 'operation_name'=>'INSERT'),
            array('table_name'=>'dw_mv_report_06', 'operation_name'=>'DELETE'),
            array('table_name'=>'dw_mv_report_06', 'operation_name'=>'INSERT'),
            array('table_name'=>'mv_report_07_stg1', 'operation_name'=>'INSERT'),
            array('table_name'=>'dw_mv_report_07', 'operation_name'=>'DELETE'),
            array('table_name'=>'dw_mv_report_07', 'operation_name'=>'INSERT'),
            array('table_name'=>'dw_mv_report_08', 'operation_name'=>'DELETE'),
            array('table_name'=>'dw_mv_report_08', 'operation_name'=>'INSERT'),
            array('table_name'=>'dw_mv_report_08', 'operation_name'=>'UPDATE')

        );

        $queryString = "SELECT
            t.table_name,
            r.operation_name,
            op.rowcount
            FROM table_operations op
            INNER JOIN processed_table t ON op.processed_table_id = t.id
            INNER JOIN operation_ref r ON op.operation_id = r.id
            WHERE 1=1
            AND op.imported_file_id = (
                SELECT MAX(imported_file_id)
                FROM table_operations
            )
            ORDER BY op.id;";
        $query = $this->dbLocal->query($queryString);
        $actualArray = $query->result();

        foreach ($expectedArray as $key=>$arrayItem) {
            $this->assertEquals($arrayItem['table_name'], $actualArray[$key]->table_name);
            $this->assertEquals($arrayItem['operation_name'], $actualArray[$key]->operation_name);
        }

    }
    

    //Test that importing the same file twice will show the same import steps and counts
    /*
     * TODO: uncomment this once Travis timeout issue is resolved
    public function test_ImportFileTwice_CheckMatchingCount() {
        $filename = "2018_Appointments_Master 2018_08_08.xls";
        $fileNameFull = "application/tests/import/". $filename;
        $postArray = array(
            'userfile'=>$fileNameFull
        );

        $_FILES['userfile'] = array(
            //'name'      =>  $fileNameFull,
            'name'      =>  $filename,
            'tmp_name'  =>  APPPATH . 'tests/import/' . $filename,
            //'tmp_name'  =>  $filename,
            'type'      =>  'xlsx',
            'size'      =>  10141,
            'error'     =>  0
        );

        //Import file once
        $output = $this->request('POST', ['FileImport', 'do_upload'], $postArray);

        $queryString = "SELECT
            t.table_name,
            r.operation_name,
            op.rowcount
            FROM table_operations op
            INNER JOIN processed_table t ON op.processed_table_id = t.id
            INNER JOIN operation_ref r ON op.operation_id = r.id
            WHERE 1=1
            AND op.imported_file_id = (
                SELECT MAX(imported_file_id)
                FROM table_operations
            )
            ORDER BY op.id;";
        $query = $this->dbLocal->query($queryString);
        $firstArray = $query->result();

        //Import file for the second time
        $output = $this->request('POST', ['FileImport', 'do_upload'], $postArray);
        $query = $this->dbLocal->query($queryString);
        $secondArray = $query->result();

        //Compare arrays
        $firstArrayRowCount = count($firstArray);
        $secondArrayRowCount = count($secondArray);

        $this->assertEquals($firstArrayRowCount, $secondArrayRowCount);

        for ($i=0; $i<$firstArrayRowCount; $i++) {
            $this->assertEquals($firstArray[$i]->table_name, $secondArray[$i]->table_name);
            $this->assertEquals($firstArray[$i]->operation_name, $secondArray[$i]->operation_name);
            $this->assertEquals($firstArray[$i]->rowcount, $secondArray[$i]->rowcount);
        }

    }
    */

}
