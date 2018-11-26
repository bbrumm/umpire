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
 * Uncomment later to improve speed
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
        $this->db->close();
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
        $this->db->close();
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
        $this->db->close();
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
        $this->db->close();
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
        $this->db->close();
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
        $this->db->close();
    }
*/

/*
 * Uncomment later to improve speed


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
        $this->db->close();
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
        $this->db->close();
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
        $this->db->close();
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
        $this->db->close();
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
        $this->db->close();
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
        $this->db->close();
    }

    public function test_UmpireTypesExist() {
        $queryString = "SELECT umpire_type_name FROM umpire_type ORDER BY id;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array('Field', 'Boundary', 'Goal');
        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->umpire_type_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->db->close();
    }

    public function test_UserPermissionsAreCorrect() {
        $queryString = "SELECT
u.user_name, u.role_id, ps.category, ps.selection_name, ps.display_order
FROM umpire_users u
INNER JOIN user_permission_selection us ON u.id = us.user_id
INNER JOIN permission_selection ps ON us.permission_selection_id = ps.id
WHERE u.active = 1
ORDER BY u.id, ps.category, ps.display_order;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array(
            array('user_name'=>'bbrumm', 'role_id'=>1, 'category'=>'General', 'selection_name'=>'All'),
            array('user_name'=>'bbeveridge', 'role_id'=>2, 'category'=>'General', 'selection_name'=>'All'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Seniors'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Reserves'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Colts'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Under 17.5'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Under 16'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Under 14.5'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Under 12'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Youth Girls'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Junior Girls'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Under 14'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'General', 'selection_name'=>'All'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'League', 'selection_name'=>'BFL'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'League', 'selection_name'=>'GFL'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'League', 'selection_name'=>'GDFL'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'League', 'selection_name'=>'GJFL'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'League', 'selection_name'=>'CDFNL'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Region', 'selection_name'=>'Geelong'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Region', 'selection_name'=>'Colac'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 1'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 2'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 3'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 4'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 5'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 6'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 7'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 8'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Umpire Type', 'selection_name'=>'Boundary'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Umpire Type', 'selection_name'=>'Field'),
            array('user_name'=>'dreid', 'role_id'=>4, 'category'=>'Umpire Type', 'selection_name'=>'Goal'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Seniors'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Reserves'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Colts'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Under 17.5'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Under 16'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Under 14.5'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Under 12'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Youth Girls'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Junior Girls'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Under 14'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'General', 'selection_name'=>'All'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'League', 'selection_name'=>'BFL'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'League', 'selection_name'=>'GFL'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'League', 'selection_name'=>'GDFL'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'League', 'selection_name'=>'GJFL'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'League', 'selection_name'=>'CDFNL'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Region', 'selection_name'=>'Geelong'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 1'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 2'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 3'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 4'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 5'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 6'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 7'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 8'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Umpire Type', 'selection_name'=>'Boundary'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Umpire Type', 'selection_name'=>'Field'),
            array('user_name'=>'kclissold', 'role_id'=>4, 'category'=>'Umpire Type', 'selection_name'=>'Goal'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Seniors'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Reserves'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Colts'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Under 17.5'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Under 16'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Under 14.5'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Under 12'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Youth Girls'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Junior Girls'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Age Group', 'selection_name'=>'Under 14'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'General', 'selection_name'=>'All'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'League', 'selection_name'=>'BFL'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'League', 'selection_name'=>'GFL'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'League', 'selection_name'=>'GDFL'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'League', 'selection_name'=>'GJFL'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'League', 'selection_name'=>'CDFNL'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Region', 'selection_name'=>'Geelong'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Region', 'selection_name'=>'Colac'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 1'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 2'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 3'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 4'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 5'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 6'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 7'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Report', 'selection_name'=>'Report 8'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Umpire Type', 'selection_name'=>'Boundary'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Umpire Type', 'selection_name'=>'Field'),
            array('user_name'=>'rsteel', 'role_id'=>4, 'category'=>'Umpire Type', 'selection_name'=>'Goal')
        );
        foreach ($expectedArray as $key=>$subArray) {
            $this->assertEquals($subArray['user_name'], $resultArray[$key]->user_name);
            $this->assertEquals($subArray['role_id'], $resultArray[$key]->role_id);
            $this->assertEquals($subArray['category'], $resultArray[$key]->category);
            $this->assertEquals($subArray['selection_name'], $resultArray[$key]->selection_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->db->close();
    }


    public function test_ValidSelectionCombinationsAreCorrect() {
        $queryString = "SELECT 
v.id,
pvr.parameter_value_name AS region,
pvl.parameter_value_name AS league,
pva.parameter_value_name AS age_group
FROM valid_selection_combinations v
INNER JOIN report_selection_parameter_values pvr ON pvr.parameter_value_id = v.pv_region_id
INNER JOIN report_selection_parameter_values pvl ON pvl.parameter_value_id = v.pv_league_id
INNER JOIN report_selection_parameter_values pva ON pva.parameter_value_id = v.pv_age_group_id
ORDER BY v.id;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array(
            array('region'=>'Geelong', 'league'=>'BFL', 'age_group'=>'Seniors'),
            array('region'=>'Geelong', 'league'=>'BFL', 'age_group'=>'Reserves'),
            array('region'=>'Geelong', 'league'=>'GFL', 'age_group'=>'Seniors'),
            array('region'=>'Geelong', 'league'=>'GFL', 'age_group'=>'Reserves'),
            array('region'=>'Geelong', 'league'=>'GDFL', 'age_group'=>'Seniors'),
            array('region'=>'Geelong', 'league'=>'GDFL', 'age_group'=>'Reserves'),
            array('region'=>'Geelong', 'league'=>'Women', 'age_group'=>'Seniors'),
            array('region'=>'Geelong', 'league'=>'Women', 'age_group'=>'Reserves'),
            array('region'=>'Geelong', 'league'=>'GJFL', 'age_group'=>'Colts'),
            array('region'=>'Geelong', 'league'=>'GJFL', 'age_group'=>'Under 16'),
            array('region'=>'Geelong', 'league'=>'GJFL', 'age_group'=>'Under 14'),
            array('region'=>'Geelong', 'league'=>'GJFL', 'age_group'=>'Under 12'),
            array('region'=>'Geelong', 'league'=>'GJFL', 'age_group'=>'Junior Girls'),
            array('region'=>'Geelong', 'league'=>'GJFL', 'age_group'=>'Youth Girls'),
            array('region'=>'Geelong', 'league'=>'GJFL', 'age_group'=>'Under 19 Girls'),
            array('region'=>'Geelong', 'league'=>'GJFL', 'age_group'=>'Under 15 Girls'),
            array('region'=>'Geelong', 'league'=>'GJFL', 'age_group'=>'Under 12 Girls'),
            array('region'=>'Colac', 'league'=>'CDFNL', 'age_group'=>'Seniors'),
            array('region'=>'Colac', 'league'=>'CDFNL', 'age_group'=>'Reserves'),
            array('region'=>'Colac', 'league'=>'CDFNL', 'age_group'=>'Under 17.5'),
            array('region'=>'Colac', 'league'=>'CDFNL', 'age_group'=>'Under 14.5'),
            array('region'=>'Geelong', 'league'=>'GJFL', 'age_group'=>'Under 19'),
            array('region'=>'Geelong', 'league'=>'GJFL', 'age_group'=>'Under 17'),
            array('region'=>'Geelong', 'league'=>'GJFL', 'age_group'=>'Under 15'),
            array('region'=>'Geelong', 'league'=>'GJFL', 'age_group'=>'Under 13'),
            array('region'=>'Geelong', 'league'=>'GJFL', 'age_group'=>'Under 18 Girls')
        );
        foreach ($expectedArray as $key=>$subArray) {
            $this->assertEquals($subArray['region'], $resultArray[$key]->region);
            $this->assertEquals($subArray['league'], $resultArray[$key]->league);
            $this->assertEquals($subArray['age_group'], $resultArray[$key]->age_group);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->db->close();
    }

    public function test_UsersAreCorrect() {
        $queryString = "SELECT 
user_name, first_name, last_name, role_id, active
FROM umpire_users
ORDER BY id;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array(
            array('user_name'=>'bbrumm', 'first_name'=>'Ben', 'last_name'=>'Brumm', 'role_id'=>1, 'active'=>1),
            array('user_name'=>'bbeveridge', 'first_name'=>'Brendan', 'last_name'=>'Beveridge', 'role_id'=>2, 'active'=>1),
            array('user_name'=>'jhillgrove', 'first_name'=>'Jason', 'last_name'=>'Hillgrove', 'role_id'=>2, 'active'=>1),
            array('user_name'=>'gmanager', 'first_name'=>'General', 'last_name'=>'Manager', 'role_id'=>2, 'active'=>1),
            array('user_name'=>'dbaensch', 'first_name'=>'Darren', 'last_name'=>'Baensch', 'role_id'=>3, 'active'=>0),
            array('user_name'=>'skeating', 'first_name'=>'Steve', 'last_name'=>'Keating', 'role_id'=>3, 'active'=>1),
            array('user_name'=>'hphilpott', 'first_name'=>'Howard', 'last_name'=>'Philpott', 'role_id'=>3, 'active'=>0),
            array('user_name'=>'rtrotter', 'first_name'=>'Rohan', 'last_name'=>'Trotter', 'role_id'=>3, 'active'=>0),
            array('user_name'=>'agrant', 'first_name'=>'Alan', 'last_name'=>'Grant', 'role_id'=>3, 'active'=>0),
            array('user_name'=>'chood', 'first_name'=>'Colin', 'last_name'=>'Hood', 'role_id'=>3, 'active'=>1),
            array('user_name'=>'dsantospirito', 'first_name'=>'Darren', 'last_name'=>'Santospirito', 'role_id'=>3, 'active'=>1),
            array('user_name'=>'tbrooks', 'first_name'=>'Tony', 'last_name'=>'Brooks', 'role_id'=>4, 'active'=>0),
            array('user_name'=>'aedwick', 'first_name'=>'Adam', 'last_name'=>'Edwick', 'role_id'=>4, 'active'=>0),
            array('user_name'=>'kmcmaster', 'first_name'=>'Kevin', 'last_name'=>'McMaster', 'role_id'=>3, 'active'=>0),
            array('user_name'=>'ldonohue', 'first_name'=>'Larry', 'last_name'=>'Donohue', 'role_id'=>3, 'active'=>0),
            array('user_name'=>'dreid', 'first_name'=>'Davin', 'last_name'=>'Reid', 'role_id'=>4, 'active'=>1),
            array('user_name'=>'kclissold', 'first_name'=>'Kel', 'last_name'=>'Clissold', 'role_id'=>4, 'active'=>1),
            array('user_name'=>'rsteel', 'first_name'=>'Robert', 'last_name'=>'Steel', 'role_id'=>4, 'active'=>1),
            array('user_name'=>'bsmith', 'first_name'=>'Brad', 'last_name'=>'Smith', 'role_id'=>6, 'active'=>1)
        );
        foreach ($expectedArray as $key=>$subArray) {
            $this->assertEquals($subArray['user_name'], $resultArray[$key]->user_name);
            $this->assertEquals($subArray['first_name'], $resultArray[$key]->first_name);
            $this->assertEquals($subArray['last_name'], $resultArray[$key]->last_name);
            $this->assertEquals($subArray['role_id'], $resultArray[$key]->role_id);
            $this->assertEquals($subArray['active'], $resultArray[$key]->active);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->db->close();
    }


    public function test_CheckForDuplicateUsers() {
        $queryString = "SELECT UPPER(user_name)
FROM umpire_users
GROUP BY user_name
HAVING COUNT(*) > 1;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result();

        $expectedCount = 0;
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->db->close();
    }
*/

}