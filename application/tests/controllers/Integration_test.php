<?php

class Integration_test extends TestCase
{

    public function setUp() {
        $this->resetInstance();
        $this->CI->load->library('Array_library');
        $this->arrayLibrary = new Array_library();
        //Connect to the ci_prod database in the "testing/database" file
        $this->db = $this->CI->load->database('ci_prod', TRUE);
        //This connects to the localhost database
        $this->dbLocal = $this->CI->load->database('default', TRUE);

    }

    public function test_Dummy() {
        $this->assertEquals(1, 1);
    }
/*
 * TOOD uncomment when other tests are written

    public function test_SeasonsExist() {
        $queryString = "SELECT season_year FROM season ORDER BY id;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array(2015, 2016, 2017, 2018);
        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->season_year);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
    }


    //TODO: Write more tests like this. It compares prod v local
    public function test_SeasonLocalVsProd() {
        $queryString = "SELECT season_year FROM season ORDER BY id;";
        $queryProd = $this->db->query($queryString);
        $queryLocal = $this->dbLocal->query($queryString);
        $resultArrayProd = $queryProd->result();
        $resultArrayLocal = $queryLocal->result();
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'season_year');
        $this->assertEmpty($arrayDifferences);
    }


    public function test_AgeGroupsExist() {
        $queryString = "SELECT age_group FROM age_group ORDER BY display_order;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array('Seniors', 'Reserves', 'Colts', 'Under 19',
            'Under 17.5', 'Under 17', 'Under 16', 'Under 15', 'Under 14.5', 'Under 14',
            'Under 13', 'Under 12', 'Under 19 Girls', 'Under 18 Girls', 'Under 15 Girls',
            'Under 12 Girls', 'Youth Girls', 'Junior Girls');
        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->age_group );
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
    }


    public function test_DivisionsExist() {
        $queryString = "SELECT division_name FROM division ORDER BY id;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array(
            'None', 'Grading', 'Practice', 'Div 1', 'Div 2', 'Div 3', 'Div 4', 'Div 5', 'Div 6', 'Div 7'
        );
        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->division_name );
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
    }
*/

/*
 * TODO refactor these tables to prevent duplication
    public function test_LeaguesExist() {
        $queryString = "SELECT league_name FROM league ORDER BY id;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result();

        $expectedArray = array();
        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->league_name );
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
    }

    public function test_LeaguesHaveCorrectShortName() {
        $queryString = "SELECT league_name, short_league_name FROM league ORDER BY id;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result();

        $expectedArray = array();
        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->league_name);
            $this->assertEquals($value, $resultArray[$key]->short_league_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
    }
*/

/*

    public function test_LeaguesHaveCorrectRegion() {
        $queryString = "SELECT DISTINCT l.short_league_name, r.region_name FROM league l INNER JOIN region r ON l.region_id = r.id ORDER BY l.short_league_name;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array(
            array('short_league_name'=>'BFL', 'region_name'=>'Geelong'),
            array('short_league_name'=>'CDFNL', 'region_name'=>'Colac'),
            array('short_league_name'=>'GDFL', 'region_name'=>'Geelong'),
            array('short_league_name'=>'GFL', 'region_name'=>'Geelong'),
            array('short_league_name'=>'GJFL', 'region_name'=>'Geelong'),
            array('short_league_name'=>'Women', 'region_name'=>'Geelong')

        );
        foreach ($expectedArray as $key=>$subArray) {
            $this->assertEquals($subArray['short_league_name'], $resultArray[$key]->short_league_name);
            $this->assertEquals($subArray['region_name'], $resultArray[$key]->region_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
    }

    public function test_PermissionsExist() {
        $queryString = "SELECT permission_name FROM permission ORDER BY id;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array('IMPORT_FILES', 'CREATE_PDF', 'VIEW_DATA_TEST', 'ADD_NEW_USERS', 'MODIFY_EXISTING_USERS', 'VIEW_REPORT', 'SELECT_REPORT_OPTION', 'VIEW_USER_ADMIN', 'VIEW_USER_ADMIN');
        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->permission_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
    }


    public function test_ShortLeagueNamesExist() {
        $queryString = "SELECT short_league_name FROM short_league_name ORDER BY id;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array('BFL', 'GFL', 'GDFL', 'GJFL', 'CDFNL', 'Women');
        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->short_league_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
    }


    public function test_FieldNamesExist() {
        $queryString = "SELECT field_name FROM t_field_list ORDER BY field_id;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array('match_count', 'short_league_name', 'club_name', 'full_name', 'age_group', 'umpire_type_age_group', 'weekend_date',  'umpire_type',  'umpire_name',  'subtotal',  'umpire_count',  'first_umpire',  'second_umpire', 'last_first_name');
        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->field_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
    }

    public function test_ReportNamesExist() {
        $queryString = "SELECT report_name FROM t_report ORDER BY report_id;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array('01 - Umpires and Clubs', '02 - Umpire Names by League', '03 - Summary', '04 - Summary by Club', '05 - Summary by League', '06 - Pairings', '07 - 2 and 3 Field Umpires',  '08 - Umpire Games Tally');
        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->report_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
    }

    public function test_ReportValuesAreCorrect() {
        $queryString = "SELECT r.report_name, r.report_title, r.no_value_display, 
r.first_column_format, r.colour_cells, r.region_enabled, 
r.league_enabled, r.age_group_enabled, r.umpire_type_enabled,
p.resolution, p.paper_size, p.orientation,
f.field_name
FROM t_report  r
INNER JOIN t_field_list f ON r.value_field_id = f.field_id
INNER JOIN t_pdf_settings p ON r.pdf_settings_id = p.pdf_settings_id
ORDER BY r.report_id;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array(
            array(
                'report_name'=>'01 - Umpires and Clubs',
                'report_title'=>'01 - Umpires and Clubs (%seasonYear)',
                'no_value_display'=>' ',
                'first_column_format'=>'text',
                'colour_cells'=>1,
                'region_enabled'=>1,
                'league_enabled'=>1,
                'age_group_enabled'=>1,
                'umpire_type_enabled'=>1,
                'resolution'=>200,
                'paper_size'=>'a3',
                'orientation'=>'Landscape',
                'field_name'=>'match_count'
            ),
            array(
                'report_name'=>'02 - Umpire Names by League',
                'report_title'=>'02 - Umpire Names by League (%seasonYear)',
                'no_value_display'=>' ',
                'first_column_format'=>'text',
                'colour_cells'=>0,
                'region_enabled'=>1,
                'league_enabled'=>1,
                'age_group_enabled'=>1,
                'umpire_type_enabled'=>1,
                'resolution'=>200,
                'paper_size'=>'a3',
                'orientation'=>'Portrait',
                'field_name'=>'match_count'
            ),
            array(
                'report_name'=>'03 - Summary',
                'report_title'=>'03 - Summary by Week (Matches Where No Umpires Are Recorded) (%seasonYear)',
                'no_value_display'=>' ',
                'first_column_format'=>'date',
                'colour_cells'=>0,
                'region_enabled'=>1,
                'league_enabled'=>0,
                'age_group_enabled'=>0,
                'umpire_type_enabled'=>0,
                'resolution'=>200,
                'paper_size'=>'a3',
                'orientation'=>'Landscape',
                'field_name'=>'match_count'
            ),
            array(
                'report_name'=>'04 - Summary by Club',
                'report_title'=>'04 - Summary by Club (Matches Where No Umpires Are Recorded) (%seasonYear)',
                'no_value_display'=>' ',
                'first_column_format'=>'text',
                'colour_cells'=>0,
                'region_enabled'=>1,
                'league_enabled'=>0,
                'age_group_enabled'=>0,
                'umpire_type_enabled'=>0,
                'resolution'=>200,
                'paper_size'=>'a3',
                'orientation'=>'Landscape',
                'field_name'=>'match_count'
            ),
            array(
                'report_name'=>'05 - Summary by League',
                'report_title'=>'05 - Games with Zero Umpires For Each League (%seasonYear)',
                'no_value_display'=>0,
                'first_column_format'=>'text',
                'colour_cells'=>0,
                'region_enabled'=>1,
                'league_enabled'=>0,
                'age_group_enabled'=>0,
                'umpire_type_enabled'=>0,
                'resolution'=>100,
                'paper_size'=>'a3',
                'orientation'=>'Landscape',
                'field_name'=>'match_count'
            ),
            array(
                'report_name'=>'06 - Pairings',
                'report_title'=>'06 - Umpire Pairing (%seasonYear)',
                'no_value_display'=>' ',
                'first_column_format'=>'text',
                'colour_cells'=>1,
                'region_enabled'=>1,
                'league_enabled'=>1,
                'age_group_enabled'=>1,
                'umpire_type_enabled'=>1,
                'resolution'=>200,
                'paper_size'=>'a3',
                'orientation'=>'Landscape',
                'field_name'=>'match_count'
            ),
            array(
                'report_name'=>'07 - 2 and 3 Field Umpires',
                'report_title'=>'07 - Games with 2 or 3 Field Umpires (%seasonYear)',
                'no_value_display'=>'',
                'first_column_format'=>'text',
                'colour_cells'=>0,
                'region_enabled'=>1,
                'league_enabled'=>1,
                'age_group_enabled'=>1,
                'umpire_type_enabled'=>0,
                'resolution'=>200,
                'paper_size'=>'a4',
                'orientation'=>'Landscape',
                'field_name'=>'match_count'
            ),
            array(
                'report_name'=>'08 - Umpire Games Tally',
                'report_title'=>'08 - Umpire Games Tally',
                'no_value_display'=>null,
                'first_column_format'=>'text',
                'colour_cells'=>0,
                'region_enabled'=>0,
                'league_enabled'=>0,
                'age_group_enabled'=>0,
                'umpire_type_enabled'=>0,
                'resolution'=>200,
                'paper_size'=>'a4',
                'orientation'=>'Landscape',
                'field_name'=>'match_count'
            )
        );
        foreach ($expectedArray as $key=>$subArray) {
            $this->assertEquals($subArray['report_title'], $resultArray[$key]->report_title);
            $this->assertEquals($subArray['no_value_display'], $resultArray[$key]->no_value_display);
            $this->assertEquals($subArray['first_column_format'], $resultArray[$key]->first_column_format);
            $this->assertEquals($subArray['colour_cells'], $resultArray[$key]->colour_cells);
            $this->assertEquals($subArray['region_enabled'], $resultArray[$key]->region_enabled);
            $this->assertEquals($subArray['league_enabled'], $resultArray[$key]->league_enabled);
            $this->assertEquals($subArray['age_group_enabled'], $resultArray[$key]->age_group_enabled);
            $this->assertEquals($subArray['umpire_type_enabled'], $resultArray[$key]->umpire_type_enabled);
            $this->assertEquals($subArray['resolution'], $resultArray[$key]->resolution);
            $this->assertEquals($subArray['paper_size'], $resultArray[$key]->paper_size);
            $this->assertEquals($subArray['orientation'], $resultArray[$key]->orientation);
            $this->assertEquals($subArray['field_name'], $resultArray[$key]->field_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
    }
*/

}