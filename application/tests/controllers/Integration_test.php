<?php

class Integration_test extends TestCase
{
    const TEST_LOCAL_V_PROD = FALSE;

    public function setUp() {
        $this->resetInstance();
        $this->CI->load->library('Array_library');
        $this->arrayLibrary = new Array_library();
        //Connect to the ci_prod database in the "testing/database" file
        $this->db = $this->CI->load->database('ci_prod', TRUE);
        //This connects to the localhost database, and should be used when unit testing
        //$this->dbLocal = $this->CI->load->database('default', TRUE);

        //This connects to the local copy of the dbunittest database, which should be a copy of the DB created on Travis CI
        //It's helpful when debugging why something works on localhost but not on Travis
        $this->dbLocal = $this->CI->load->database('local_dbunittest', TRUE);
    }

    public function tearDown() {
        $this->dbLocal->close();
        $this->db->close();
    }



    public function test_SeasonsExist() {
        $queryString = "SELECT season_year FROM season ORDER BY id;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array(2015, 2016, 2017, 2018, 2019);
        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->season_year);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->dbLocal->close();
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
        $this->dbLocal->close();
    }


    public function test_AgeGroupsExist() {
        $queryString = "SELECT age_group FROM age_group ORDER BY display_order;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array('Seniors', 'Reserves', 'Colts', 'Under 19', 'Under 18',
            'Under 17.5', 'Under 17', 'Under 16', 'Under 15', 'Under 14.5', 'Under 14',
            'Under 13', 'Under 12', 'Under 19 Girls', 'Under 18 Girls', 'Under 15 Girls',
            'Under 12 Girls', 'Youth Girls', 'Junior Girls');
        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->age_group );
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->dbLocal->close();
    }

    public function test_AgeGroupLocalVsProd() {
        if ($this::TEST_LOCAL_V_PROD) {
            $queryString = "SELECT age_group FROM age_group ORDER BY display_order;";
            $queryProd = $this->db->query($queryString);
            $queryLocal = $this->dbLocal->query($queryString);
            $resultArrayProd = $queryProd->result();
            $resultArrayLocal = $queryLocal->result();
            $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'age_group');
            $this->assertEmpty($arrayDifferences);
            $this->db->close();
            $this->dbLocal->close();
        } else {
            $this->assertEquals(1, 1);
        }
    }


    public function test_AgeGroupsMatchDivisions() {
        $queryString = "SELECT ag.age_group, d.division_name
FROM age_group ag
INNER JOIN age_group_division agd ON ag.id = agd.age_group_id
INNER JOIN division d ON d.id = agd.division_id
ORDER BY ag.display_order ASC, d.division_name ASC;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array(
            array('age_group'=>'Seniors', 'division_name'=>'Div 1'),
            array('age_group'=>'Seniors', 'division_name'=>'Div 2'),
            array('age_group'=>'Seniors', 'division_name'=>'Grading'),
            array('age_group'=>'Seniors', 'division_name'=>'None'),
            array('age_group'=>'Reserves', 'division_name'=>'None'),
            array('age_group'=>'Colts', 'division_name'=>'Div 1'),
            array('age_group'=>'Colts', 'division_name'=>'Div 2'),
            array('age_group'=>'Colts', 'division_name'=>'Div 3'),
            array('age_group'=>'Colts', 'division_name'=>'Div 4'),
            array('age_group'=>'Colts', 'division_name'=>'Grading'),
            array('age_group'=>'Colts', 'division_name'=>'Practice'),
            array('age_group'=>'Under 19', 'division_name'=>'Div 1'),
            array('age_group'=>'Under 19', 'division_name'=>'Div 2'),
            array('age_group'=>'Under 19', 'division_name'=>'Div 3'),
            array('age_group'=>'Under 19', 'division_name'=>'Grading'),
            array('age_group'=>'Under 19', 'division_name'=>'None'),
            array('age_group'=>'Under 18', 'division_name'=>'None'),
            array('age_group'=>'Under 17.5', 'division_name'=>'None'),
            array('age_group'=>'Under 17', 'division_name'=>'Div 1'),
            array('age_group'=>'Under 17', 'division_name'=>'Div 2'),
            array('age_group'=>'Under 17', 'division_name'=>'Div 3'),
            array('age_group'=>'Under 17', 'division_name'=>'Div 4'),
            array('age_group'=>'Under 17', 'division_name'=>'Grading'),
            array('age_group'=>'Under 17', 'division_name'=>'None'),
            array('age_group'=>'Under 16', 'division_name'=>'Div 1'),
            array('age_group'=>'Under 16', 'division_name'=>'Div 2'),
            array('age_group'=>'Under 16', 'division_name'=>'Div 3'),
            array('age_group'=>'Under 16', 'division_name'=>'Div 4'),
            array('age_group'=>'Under 16', 'division_name'=>'Div 5'),
            array('age_group'=>'Under 16', 'division_name'=>'Grading'),
            array('age_group'=>'Under 15', 'division_name'=>'Div 1'),
            array('age_group'=>'Under 15', 'division_name'=>'Div 2'),
            array('age_group'=>'Under 15', 'division_name'=>'Div 3'),
            array('age_group'=>'Under 15', 'division_name'=>'Div 4'),
            array('age_group'=>'Under 15', 'division_name'=>'Div 5'),
            array('age_group'=>'Under 15', 'division_name'=>'Grading'),
            array('age_group'=>'Under 15', 'division_name'=>'None'),
            array('age_group'=>'Under 14.5', 'division_name'=>'None'),
            array('age_group'=>'Under 14', 'division_name'=>'Div 1'),
            array('age_group'=>'Under 14', 'division_name'=>'Div 2'),
            array('age_group'=>'Under 14', 'division_name'=>'Div 3'),
            array('age_group'=>'Under 14', 'division_name'=>'Div 4'),
            array('age_group'=>'Under 14', 'division_name'=>'Div 5'),
            array('age_group'=>'Under 14', 'division_name'=>'Div 6'),
            array('age_group'=>'Under 14', 'division_name'=>'Div 7'),
            array('age_group'=>'Under 14', 'division_name'=>'Grading'),
            array('age_group'=>'Under 13', 'division_name'=>'Div 1'),
            array('age_group'=>'Under 13', 'division_name'=>'Div 2'),
            array('age_group'=>'Under 13', 'division_name'=>'Div 3'),
            array('age_group'=>'Under 13', 'division_name'=>'Div 4'),
            array('age_group'=>'Under 13', 'division_name'=>'Div 5'),
            array('age_group'=>'Under 13', 'division_name'=>'Div 6'),
            array('age_group'=>'Under 13', 'division_name'=>'Div 7'),
            array('age_group'=>'Under 13', 'division_name'=>'Grading'),
            array('age_group'=>'Under 13', 'division_name'=>'None'),
            array('age_group'=>'Under 12', 'division_name'=>'None'),
            array('age_group'=>'Under 19 Girls', 'division_name'=>'Div 1'),
            array('age_group'=>'Under 19 Girls', 'division_name'=>'Div 2'),
            array('age_group'=>'Under 19 Girls', 'division_name'=>'Grading'),
            array('age_group'=>'Under 19 Girls', 'division_name'=>'None'),
            array('age_group'=>'Under 18 Girls', 'division_name'=>'Div 1'),
            array('age_group'=>'Under 18 Girls', 'division_name'=>'Div 2'),
            array('age_group'=>'Under 18 Girls', 'division_name'=>'Grading'),
            array('age_group'=>'Under 18 Girls', 'division_name'=>'None'),
            array('age_group'=>'Under 15 Girls', 'division_name'=>'Div 1'),
            array('age_group'=>'Under 15 Girls', 'division_name'=>'Div 2'),
            array('age_group'=>'Under 15 Girls', 'division_name'=>'Grading'),
            array('age_group'=>'Under 15 Girls', 'division_name'=>'None'),
            array('age_group'=>'Under 12 Girls', 'division_name'=>'None'),
            array('age_group'=>'Youth Girls', 'division_name'=>'None'),
            array('age_group'=>'Junior Girls', 'division_name'=>'None')
        );

        foreach ($expectedArray as $key=>$subArray) {
            $this->assertEquals($subArray['age_group'], $resultArray[$key]->age_group);
            $this->assertEquals($subArray['division_name'], $resultArray[$key]->division_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->dbLocal->close();
    }

    public function test_AgeGroupDivisionLocalVsProd() {
        if ($this::TEST_LOCAL_V_PROD) {
            $queryString = "SELECT ag.age_group, d.division_name
    FROM age_group ag
    INNER JOIN age_group_division agd ON ag.id = agd.age_group_id
    INNER JOIN division d ON d.id = agd.division_id
    ORDER BY ag.display_order ASC, d.division_name ASC;";
            $queryProd = $this->db->query($queryString);
            $queryLocal = $this->dbLocal->query($queryString);
            $resultArrayProd = $queryProd->result();
            $resultArrayLocal = $queryLocal->result();
            $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'age_group');
            $this->assertEmpty($arrayDifferences);
            $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'division_name');
            $this->assertEmpty($arrayDifferences);
            $this->db->close();
            $this->dbLocal->close();
        } else {
            $this->assertEquals(1, 1);
        }
    }

    public function test_DivisionsExist() {
        $queryString = "SELECT division_name FROM division ORDER BY id;";
        $query = $this->dbLocal->query($queryString);
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
        $this->dbLocal->close();
    }

    public function test_DivisionLocalVsProd() {
        if ($this::TEST_LOCAL_V_PROD) {
            $queryString = "SELECT division_name FROM division ORDER BY id;";
            $queryProd = $this->db->query($queryString);
            $queryLocal = $this->dbLocal->query($queryString);
            $resultArrayProd = $queryProd->result();
            $resultArrayLocal = $queryLocal->result();
            $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'division_name');
            $this->assertEmpty($arrayDifferences);
            $this->db->close();
            $this->dbLocal->close();
        } else {
            $this->assertEquals(1, 1);
        }
    }

    public function test_ClubNamesExist() {
        $queryString = "SELECT club_name FROM club ORDER BY club_name";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array(
            'Aireys Inlet',
            'Alvie',
            'Ammos',
            'Ammos Abfalter',
            'Ammos Clarke',
            'Ammos Kershaw',
            'Ammos King',
            'Ammos Tenace',
            'Ammos Westwood',
            'Ammos Westy',
            'Anakie',
            'Anakie/North Shore',
            'Anglesea',
            'Apollo Bay',
            'Bannockburn',
            'Bannockburn / South Barwon',
            'Bannockburn Arklay',
            'Bannockburn Giles',
            'Bannockburn Hickleton',
            'Bannockburn Taylor',
            'Bannockburn Turnley',
            'Barwon Heads',
            'Barwon Heads Blue',
            'Barwon Heads White',
            'Bell Park',
            'Bell Park Burke',
            'Bell Park Jarvis',
            'Bell Park Lynch',
            'Bell Park Rizun',
            'Bell Post Hill',
            'Bell Post Hill / Bannockburn',
            'Belmont',
            'Belmont Lions',
            'Belmont Lions / Newcomb',
            'Birregurra',
            'Colac',
            'Colac Imperials',
            'Corio',
            'Dragons',
            'Drysdale',
            'Drysdale Bennett',
            'Drysdale Byrne',
            'Drysdale Eddy',
            'Drysdale Hall',
            'Drysdale Hector',
            'Drysdale Hoyer',
            'Eagles',
            'East Geelong',
            'East Geelong / Geelong West',
            'East Geelong Thomson',
            'East Newcomb',
            'East Tigers',
            'ewcomb Lions',
            'Flying Joeys',
            'Gdfl Raiders',
            'Geelong Amateur',
            'Geelong West',
            'Geelong West Giants Grey',
            'Geelong West Giants Orange',
            'Geelong West St Peters',
            'Giants',
            'Grovedale',
            'Grovedale / South Barwon',
            'Grovedale / St Albans',
            'Grovedale Fisher',
            'Grovedale Hood',
            'Grovedale Jen',
            'Grovedale Kelly',
            'Grovedale Miers',
            'Grovedale Shiell',
            'Gwsp',
            'Gwsp / Bannockburn',
            'Inverleigh',
            'Irrewarra-beeac',
            'Lara',
            'Leaping Joeys',
            'Leopold',
            'Leopold A',
            'Leopold B',
            'Leopold Butteriss',
            'Leopold Pitt',
            'Lethbridge',
            'Little River',
            'Little River/Anakie',
            'Lorne',
            'Modewarre',
            'Modewarre / Grovedale',
            'Modewarre / Winchelsea',
            'Ncfnc Alford',
            'Ncfnc Hall',
            'Ncfnc Harrington',
            'Ncfnc Harris',
            'Ncfnc Hyland',
            'Ncfnc Morrissy',
            'Ncfnc Orr',
            'Ncfnc Taylor',
            'Newcomb',
            'Newcomb Power',
            'Newtown & Chilwell',
            'North Geelong',
            'North Shore',
            'North Shore / Geelong West',
            'Ocean Grove',
            'Ocean Grove Blue',
            'Ogcc',
            'Ogcc Dean',
            'Ogcc Every',
            'Ogcc Pearson',
            'Ogcc Walter',
            'Otway Districts',
            'Portarlington',
            'Queenscliff',
            'Roosters',
            'Saints',
            'Seagulls',
            'Simpson',
            'South Barwon',
            'South Barwon / Geelong Amateur',
            'South Barwon Brebner',
            'South Barwon Corrigan',
            'South Barwon Garvey',
            'South Barwon Holz',
            'South Barwon Mccann',
            'South Barwon Stewart',
            'South Barwon Thompson',
            'South Barwon Tomkins',
            'South Barwon Walerys',
            'South Colac',
            'Spotswood',
            'St Albans',
            'St Albans Allthorpe',
            'St Albans King',
            'St Albans Reid',
            'St Albans/Newtown & Chilwell',
            'St Joeys',
            'St Joeys 1',
            'St Joeys 2',
            'St Joseph\'s',
            'St Joseph\'s Hill',
            'St Joseph\'s Jackman',
            'St Joseph\'s Podbury',
            'St Mary\'s',
            'Surf Coast',
            'Swans',
            'Teesdale',
            'Thomson',
            'Tigers',
            'Tigers Black',
            'Tigers Gold',
            'Torquay',
            'Torquay Bumpstead',
            'Torquay Coles',
            'Torquay Dunstan',
            'Torquay Jones',
            'Torquay Miliken',
            'Torquay Nairn',
            'Torquay Papworth',
            'Torquay Pyers',
            'Torquay Scott',
            'Werribee Centrals',
            'Western Eagles',
            'Winchelsea',
            'Winchelsea / Grovedale',
            'Winchelsea / Inverleigh',
            'Winchelsea Blues'
        );

        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->club_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->dbLocal->close();
    }

    public function test_ClubNamesAreNotDuplicated() {
        $queryString = "SELECT club_name
FROM club
GROUP BY club_name
HAVING COUNT(*) > 1;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();

        $expectedArray = array();

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->dbLocal->close();
    }

    public function test_ClubsLocalVsProd() {
        if ($this::TEST_LOCAL_V_PROD) {
            $queryString = "SELECT club_name FROM club ORDER BY id;";
            $queryProd = $this->db->query($queryString);
            $queryLocal = $this->dbLocal->query($queryString);
            $resultArrayProd = $queryProd->result();
            $resultArrayLocal = $queryLocal->result();
            $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'club_name');
            $this->assertEmpty($arrayDifferences);
            $this->db->close();
            $this->dbLocal->close();
        } else {
            $this->assertEquals(1, 1);
        }
    }

    public function test_TeamsLocalVsProd() {
        if ($this::TEST_LOCAL_V_PROD) {
            $queryString = "SELECT team_name FROM team 
                WHERE team_name NOT IN ('Lorne NEW TEAM', 'Some new club') 
                ORDER BY id;";
            $queryProd = $this->db->query($queryString);
            $queryLocal = $this->dbLocal->query($queryString);
            $resultArrayProd = $queryProd->result();
            $resultArrayLocal = $queryLocal->result();
            $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'team_name');
            $this->assertEmpty($arrayDifferences);
            $this->db->close();
            $this->dbLocal->close();
        } else {
            $this->assertEquals(1, 1);
        }
    }


/*
 * TODO refactor these tables to prevent duplication, then write the test for this

    public function test_LeaguesExist() {
        $queryString = "SELECT league_name FROM league ORDER BY id;";
        $query = $this->db->query($queryString);
        $resultArray = $query->result();

        $expectedArray = array();
        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->league_name);
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

*/

    public function test_LeaguesHaveCorrectRegion() {
        $queryString = "SELECT DISTINCT l.short_league_name, r.region_name FROM league l INNER JOIN region r ON l.region_id = r.id ORDER BY l.short_league_name;";
        $query = $this->dbLocal->query($queryString);
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
        $this->dbLocal->close();
    }

    public function test_PermissionsExist() {
        $queryString = "SELECT permission_name FROM permission ORDER BY id;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array('IMPORT_FILES', 'CREATE_PDF', 'VIEW_DATA_TEST', 'ADD_NEW_USERS', 'MODIFY_EXISTING_USERS', 'VIEW_REPORT', 'SELECT_REPORT_OPTION', 'VIEW_USER_ADMIN', 'VIEW_USER_ADMIN');
        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->permission_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->dbLocal->close();
    }

    public function test_PermissionsLocalVsProd() {
        $queryString = "SELECT permission_name FROM permission ORDER BY id;";
        $queryProd = $this->db->query($queryString);
        $queryLocal = $this->dbLocal->query($queryString);
        $resultArrayProd = $queryProd->result();
        $resultArrayLocal = $queryLocal->result();
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'permission_name');
        $this->assertEmpty($arrayDifferences);
        $this->db->close();
        $this->dbLocal->close();
    }


    public function test_PermissionCategorySelectionOrder() {
        $queryString = "SELECT p.permission_name, ps.category, ps.selection_name
FROM permission p
INNER JOIN permission_selection ps ON p.id = ps.permission_id
ORDER BY p.permission_name, ps.category, ps.display_order;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array(
            array('permission_name'=>'ADD_NEW_USERS', 'category'=>'General', 'selection_name'=>'All'),
            array('permission_name'=>'CREATE_PDF', 'category'=>'General', 'selection_name'=>'All'),
            array('permission_name'=>'IMPORT_FILES', 'category'=>'General', 'selection_name'=>'All'),
            array('permission_name'=>'MODIFY_EXISTING_USERS', 'category'=>'General', 'selection_name'=>'All'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Seniors'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Reserves'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Colts'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 17.5'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 16'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 14.5'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 12'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Youth Girls'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Junior Girls'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 14'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'BFL'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'GFL'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'GDFL'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'GJFL'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'CDFNL'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Region', 'selection_name'=>'Geelong'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Region', 'selection_name'=>'Colac'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Umpire Type', 'selection_name'=>'Boundary'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Umpire Type', 'selection_name'=>'Field'),
            array('permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Umpire Type', 'selection_name'=>'Goal'),
            array('permission_name'=>'VIEW_DATA_TEST', 'category'=>'General', 'selection_name'=>'All'),
            array('permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 1'),
            array('permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 2'),
            array('permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 3'),
            array('permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 4'),
            array('permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 5'),
            array('permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 6'),
            array('permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 7'),
            array('permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 8'),
            array('permission_name'=>'VIEW_USER_ADMIN', 'category'=>'General', 'selection_name'=>'All'),
        );

        foreach ($expectedArray as $key=>$subArray) {
            $this->assertEquals($subArray['permission_name'], $resultArray[$key]->permission_name);
            $this->assertEquals($subArray['category'], $resultArray[$key]->category);
            $this->assertEquals($subArray['selection_name'], $resultArray[$key]->selection_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->dbLocal->close();
    }


    public function test_ShortLeagueNamesExist() {
        $queryString = "SELECT short_league_name FROM short_league_name ORDER BY id;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array('BFL', 'GFL', 'GDFL', 'GJFL', 'CDFNL', 'Women');
        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->short_league_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->dbLocal->close();
    }

    public function test_ShortLeagueNameLocalVsProd() {
        $queryString = "SELECT short_league_name FROM short_league_name ORDER BY id;";
        $queryProd = $this->db->query($queryString);
        $queryLocal = $this->dbLocal->query($queryString);
        $resultArrayProd = $queryProd->result();
        $resultArrayLocal = $queryLocal->result();
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'short_league_name');
        $this->assertEmpty($arrayDifferences);
        $this->db->close();
        $this->dbLocal->close();
    }

/*
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
*/

    public function test_ReportNamesExist() {
        $queryString = "SELECT report_name FROM report ORDER BY report_id;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array('01 - Umpires and Clubs', '02 - Umpire Names by League', '03 - Summary', '04 - Summary by Club', '05 - Summary by League', '06 - Pairings', '07 - 2 and 3 Field Umpires',  '08 - Umpire Games Tally');
        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->report_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->dbLocal->close();
    }

    public function test_ReportNameLocalVsProd() {
        $queryString = "SELECT report_name FROM report ORDER BY report_id;";
        $queryProd = $this->db->query($queryString);
        $queryLocal = $this->dbLocal->query($queryString);
        $resultArrayProd = $queryProd->result();
        $resultArrayLocal = $queryLocal->result();
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'report_name');
        $this->assertEmpty($arrayDifferences);
        $this->db->close();
        $this->dbLocal->close();
        
    }

    public function test_ReportValuesAreCorrect() {
        $queryString = "SELECT r.report_name, r.report_title, r.no_value_display, 
r.first_column_format, r.colour_cells, r.region_enabled, 
r.league_enabled, r.age_group_enabled, r.umpire_type_enabled,
p.resolution, p.paper_size, p.orientation,
f.field_name
FROM report  r
INNER JOIN t_field_list f ON r.value_field_id = f.field_id
INNER JOIN t_pdf_settings p ON r.pdf_settings_id = p.pdf_settings_id
ORDER BY r.report_id;";
        $query = $this->dbLocal->query($queryString);
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
        $this->dbLocal->close();
    }

    public function test_ReportValuesLocalVsProd() {
        $queryString = "SELECT r.report_name, r.report_title, r.no_value_display, 
r.first_column_format, r.colour_cells, r.region_enabled, 
r.league_enabled, r.age_group_enabled, r.umpire_type_enabled,
p.resolution, p.paper_size, p.orientation,
f.field_name
FROM report  r
INNER JOIN t_field_list f ON r.value_field_id = f.field_id
INNER JOIN t_pdf_settings p ON r.pdf_settings_id = p.pdf_settings_id
ORDER BY r.report_id;";
        $queryProd = $this->db->query($queryString);
        $queryLocal = $this->dbLocal->query($queryString);
        $resultArrayProd = $queryProd->result();
        $resultArrayLocal = $queryLocal->result();
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'report_title');
        $this->assertEmpty($arrayDifferences);
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'no_value_display');
        $this->assertEmpty($arrayDifferences);
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'first_column_format');
        $this->assertEmpty($arrayDifferences);
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'colour_cells');
        $this->assertEmpty($arrayDifferences);
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'region_enabled');
        $this->assertEmpty($arrayDifferences);
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'league_enabled');
        $this->assertEmpty($arrayDifferences);
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'age_group_enabled');
        $this->assertEmpty($arrayDifferences);
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'umpire_type_enabled');
        $this->assertEmpty($arrayDifferences);
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'resolution');
        $this->assertEmpty($arrayDifferences);
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'paper_size');
        $this->assertEmpty($arrayDifferences);
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'orientation');
        $this->assertEmpty($arrayDifferences);
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'field_name');
        $this->assertEmpty($arrayDifferences);
        $this->db->close();
        $this->dbLocal->close();
    }

    public function test_UmpireTypesExist() {
        $queryString = "SELECT umpire_type_name FROM umpire_type ORDER BY id;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array('Field', 'Boundary', 'Goal');
        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->umpire_type_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->dbLocal->close();
    }

    public function test_UmpireTypeLocalVsProd() {
        if ($this::TEST_LOCAL_V_PROD) {
            $queryString = "SELECT umpire_type_name FROM umpire_type ORDER BY id;";
            $queryProd = $this->db->query($queryString);
            $queryLocal = $this->dbLocal->query($queryString);
            $resultArrayProd = $queryProd->result();
            $resultArrayLocal = $queryLocal->result();
            $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'umpire_type_name');
            $this->assertEmpty($arrayDifferences);
            $this->db->close();
            $this->dbLocal->close();
        } else {
            $this->assertEquals(1, 1);
        }
    }

    //TODO: refactor this test so it's not so dependent on live data
    public function test_UserPermissionsAreCorrect() {
        $queryString = "SELECT
u.user_name, u.role_id, ps.category, ps.selection_name, ps.display_order
FROM umpire_users u
INNER JOIN user_permission_selection us ON u.id = us.user_id
INNER JOIN permission_selection ps ON us.permission_selection_id = ps.id
WHERE u.active = 1
AND u.role_id != 4
AND u.user_name NOT IN ('bbrummtest')
ORDER BY u.id, ps.category, ps.display_order;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array(
            array('user_name'=>'bbrumm', 'role_id'=>1, 'category'=>'General', 'selection_name'=>'All'),
            array('user_name'=>'bbeveridge', 'role_id'=>2, 'category'=>'General', 'selection_name'=>'All'),
            array('user_name'=>'jhillgrove', 'role_id'=>2, 'category'=>'General', 'selection_name'=>'All'),
            array('user_name'=>'gmanager', 'role_id'=>2, 'category'=>'General', 'selection_name'=>'All'),
            array('user_name'=>'skeating', 'role_id'=>3, 'category'=>'General', 'selection_name'=>'All'),
            array('user_name'=>'chood', 'role_id'=>3, 'category'=>'General', 'selection_name'=>'All'),
            array('user_name'=>'dsantospirito', 'role_id'=>3, 'category'=>'General', 'selection_name'=>'All')
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
        $this->dbLocal->close();
    }

    public function test_AllRegularUsersHaveRequiredPermissions() {
        $queryStringForDistinctUsers = "SELECT DISTINCT u.user_name
FROM umpire_users u
INNER JOIN user_permission_selection us ON u.id = us.user_id
INNER JOIN permission_selection ps ON us.permission_selection_id = ps.id
WHERE u.active = 1
AND u.role_id = 4
AND u.user_name NOT IN ('bbrummtest')
ORDER BY u.user_name";

        $queryForDistinctUsers = $this->dbLocal->query($queryStringForDistinctUsers);
        $resultArrayForDistinctUsers = $queryForDistinctUsers->result();

        $queryString = "SELECT
u.user_name, ps.category, ps.selection_name
FROM umpire_users u
INNER JOIN user_permission_selection us ON u.id = us.user_id
INNER JOIN permission_selection ps ON us.permission_selection_id = ps.id
WHERE u.active = 1
AND role_id = 4
AND u.user_name NOT IN ('bbrummtest')
ORDER BY u.user_name, ps.category;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();

        $expectedPermissions = array(
            array('category'=>'Age Group', 'selection_name'=>'Seniors'),
            array('category'=>'Age Group', 'selection_name'=>'Reserves'),
            array('category'=>'Age Group', 'selection_name'=>'Colts'),
            array('category'=>'Age Group', 'selection_name'=>'Under 17.5'),
            array('category'=>'Age Group', 'selection_name'=>'Under 16'),
            array('category'=>'Age Group', 'selection_name'=>'Under 14.5'),
            array('category'=>'Age Group', 'selection_name'=>'Under 12'),
            array('category'=>'Age Group', 'selection_name'=>'Youth Girls'),
            array('category'=>'Age Group', 'selection_name'=>'Junior Girls'),
            array('category'=>'Age Group', 'selection_name'=>'Under 14'),
            array('category'=>'General', 'selection_name'=>'All'),
            array('category'=>'League', 'selection_name'=>'BFL'),
            array('category'=>'League', 'selection_name'=>'GFL'),
            array('category'=>'League', 'selection_name'=>'GDFL'),
            array('category'=>'League', 'selection_name'=>'GJFL'),
            array('category'=>'League', 'selection_name'=>'CDFNL'),
            array('category'=>'Region', 'selection_name'=>'Geelong'),
            array('category'=>'Region', 'selection_name'=>'Colac'),
            array('category'=>'Report', 'selection_name'=>'Report 1'),
            array('category'=>'Report', 'selection_name'=>'Report 2'),
            array('category'=>'Report', 'selection_name'=>'Report 3'),
            array('category'=>'Report', 'selection_name'=>'Report 4'),
            array('category'=>'Report', 'selection_name'=>'Report 5'),
            array('category'=>'Report', 'selection_name'=>'Report 6'),
            array('category'=>'Report', 'selection_name'=>'Report 7'),
            array('category'=>'Report', 'selection_name'=>'Report 8'),
            array('category'=>'Umpire Type', 'selection_name'=>'Boundary'),
            array('category'=>'Umpire Type', 'selection_name'=>'Field'),
            array('category'=>'Umpire Type', 'selection_name'=>'Goal')
        );

        //Convert to array
        foreach ($resultArray as $resultKey=>$resultRecord) {
            $resultArrayTransformed[$resultKey]['user_name'] = $resultRecord->user_name;
            $resultArrayTransformed[$resultKey]['category'] = $resultRecord->category;
            $resultArrayTransformed[$resultKey]['selection_name'] = $resultRecord->selection_name;
        }

        foreach ($resultArrayForDistinctUsers as $userKey=>$usernameRecord) {
            //foreach ($resultArray as $resultKey=>$resultRecord) {
                foreach ($expectedPermissions as $permissionKey => $userPermissionArrayRecord) {
                    $valueFound = $this->arrayLibrary->searchMultiArrayForThreeValues(
                        $resultArrayTransformed,
                        $usernameRecord->user_name,
                        $userPermissionArrayRecord['category'],
                        $userPermissionArrayRecord['selection_name']);
                    if (!$valueFound) {
                        echo "Value not found. Username (" . $usernameRecord->user_name . "), category (" . $userPermissionArrayRecord['category'] . "), selection_name (" . $userPermissionArrayRecord['selection_name'] . ")";
                    }
                    $this->assertEquals(true, $valueFound);
                }
            //}
        }

    }

    /*public function test_UserPermissionsLocalVsProd() {
        $queryString = "SELECT
u.user_name, u.role_id, ps.category, ps.selection_name, ps.display_order
FROM umpire_users u
INNER JOIN user_permission_selection us ON u.id = us.user_id
INNER JOIN permission_selection ps ON us.permission_selection_id = ps.id
WHERE u.active = 1
AND user_name NOT IN ('bbrummtest')
ORDER BY u.id, ps.category, ps.display_order;";
        $queryProd = $this->db->query($queryString);
        $queryLocal = $this->dbLocal->query($queryString);
        $resultArrayProd = $queryProd->result();
        $resultArrayLocal = $queryLocal->result();
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'user_name');
        $this->assertEmpty($arrayDifferences);
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'role_id');
        $this->assertEmpty($arrayDifferences);
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'category');
        $this->assertEmpty($arrayDifferences);
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'selection_name');
        $this->assertEmpty($arrayDifferences);
        $this->db->close();
    }*/



    public function test_UserPermissionsArePopulated() {
        $queryString = "SELECT COUNT(*) AS usercount
FROM umpire_users u
WHERE u.active = 1
AND u.user_name NOT IN ('bbtest2')
AND u.id NOT IN (
  SELECT user_id
  FROM user_permission_selection
);";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $expectedCount = 0;
        $actualCount = $resultArray[0]->usercount;
        $this->assertEquals($expectedCount, $actualCount);
        $this->dbLocal->close();
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
        $query = $this->dbLocal->query($queryString);
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
            array('region'=>'Geelong', 'league'=>'GJFL', 'age_group'=>'Under 18 Girls'),
            array('region'=>'Colac', 'league'=>'CDFNL', 'age_group'=>'Under 18'),
            array('region'=>'Colac', 'league'=>'CDFNL', 'age_group'=>'Under 15'),
            array('region'=>'Colac', 'league'=>'CDFNL', 'age_group'=>'Under 13')
        );
        foreach ($expectedArray as $key=>$subArray) {
            $this->assertEquals($subArray['region'], $resultArray[$key]->region);
            $this->assertEquals($subArray['league'], $resultArray[$key]->league);
            $this->assertEquals($subArray['age_group'], $resultArray[$key]->age_group);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->dbLocal->close();
    }

    public function test_UsersAreCorrect() {
        $queryString = "SELECT 
user_name, first_name, last_name, role_id, active
FROM umpire_users
WHERE user_name NOT IN ('bbrummtest', 'bbtest2', 'bbrummtest_newvalid')
ORDER BY id;";
        $query = $this->dbLocal->query($queryString);
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
            array('user_name'=>'aedwick', 'first_name'=>'Adam', 'last_name'=>'Edwick', 'role_id'=>4, 'active'=>1),
            array('user_name'=>'kmcmaster', 'first_name'=>'Kevin', 'last_name'=>'McMaster', 'role_id'=>3, 'active'=>0),
            array('user_name'=>'ldonohue', 'first_name'=>'Larry', 'last_name'=>'Donohue', 'role_id'=>3, 'active'=>0),
            array('user_name'=>'dreid', 'first_name'=>'Davin', 'last_name'=>'Reid', 'role_id'=>4, 'active'=>0),
            array('user_name'=>'kclissold', 'first_name'=>'Kel', 'last_name'=>'Clissold', 'role_id'=>4, 'active'=>1),
            array('user_name'=>'rsteel', 'first_name'=>'Robert', 'last_name'=>'Steel', 'role_id'=>4, 'active'=>1),
            array('user_name'=>'bsmith', 'first_name'=>'Brad', 'last_name'=>'Smith', 'role_id'=>4, 'active'=>1),
            array('user_name'=>'adame', 'first_name'=>'Adam', 'last_name'=>'Edwick', 'role_id'=>4, 'active'=>1),
            array('user_name'=>'mdavison', 'first_name'=>'Mark', 'last_name'=>'Davison', 'role_id'=>4, 'active'=>1)
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
        $this->dbLocal->close();
    }

    public function test_UsersLocalVsProd() {
        if ($this::TEST_LOCAL_V_PROD) {
        
            //Query excludes test users that are used to test user login scenarios
            $queryString = "SELECT 
    user_name, first_name, last_name, role_id, active
    FROM umpire_users
    WHERE user_name NOT IN ('bbrummtest', 'bbtest2', 'bbrummtest_newvalid')
    ORDER BY id;";
            $queryProd = $this->db->query($queryString);
            $queryLocal = $this->dbLocal->query($queryString);
            $resultArrayProd = $queryProd->result();
            $resultArrayLocal = $queryLocal->result();
            $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'user_name');
            $this->assertEmpty($arrayDifferences);
            $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'first_name');
            $this->assertEmpty($arrayDifferences);
            $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'last_name');
            $this->assertEmpty($arrayDifferences);
            $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'role_id');
            $this->assertEmpty($arrayDifferences);
            $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'active');
            $this->assertEmpty($arrayDifferences);
            $this->db->close();
            $this->dbLocal->close();
        } else {
            $this->assertEquals(1, 1);
        }
    }


    public function test_CheckForDuplicateUsers() {
        $queryString = "SELECT UPPER(user_name)
FROM umpire_users
GROUP BY user_name
HAVING COUNT(*) > 1;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();

        $expectedCount = 0;
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->dbLocal->close();
    }

    public function test_RegionsExist() {
        $queryString = "SELECT region_name FROM region ORDER BY id;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array('Geelong', 'Colac');
        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->region_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->dbLocal->close();
    }

    public function test_RegionLocalVsProd() {
        if ($this::TEST_LOCAL_V_PROD) {
            $queryString = "SELECT region_name FROM region ORDER BY id;";
            $queryProd = $this->db->query($queryString);
            $queryLocal = $this->dbLocal->query($queryString);
            $resultArrayProd = $queryProd->result();
            $resultArrayLocal = $queryLocal->result();
            $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'region_name');
            $this->assertEmpty($arrayDifferences);
            $this->db->close();
            $this->dbLocal->close();
        } else {
            $this->assertEquals(1, 1);
        }
    }

    public function test_ReportSelectionParametersExist() {
        $queryString = "SELECT p.parameter_name, p.allow_multiple_selections
            FROM report_selection_parameters p
            ORDER BY p.parameter_display_order;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array(
            array('parameter_name'=>'Region', 'allow_multiple_selections'=>'0'),
            array('parameter_name'=>'League', 'allow_multiple_selections'=>'1'),
            array('parameter_name'=>'Umpire Discipline', 'allow_multiple_selections'=>'1'),
            array('parameter_name'=>'Age Group', 'allow_multiple_selections'=>'1')
        );
        foreach ($expectedArray as $key=>$subArray) {
            $this->assertEquals($subArray['parameter_name'], $resultArray[$key]->parameter_name);
            $this->assertEquals($subArray['allow_multiple_selections'], $resultArray[$key]->allow_multiple_selections);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->dbLocal->close();
    }

    public function test_ReportSelectionParameterValues() {
        $queryString = "SELECT p.parameter_name, v.parameter_value_name
            FROM report_selection_parameter_values v
            INNER JOIN report_selection_parameters p ON v.parameter_id = p.parameter_id
            ORDER BY p.parameter_display_order, v.parameter_display_order;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array(
            array('parameter_name'=>'Region', 'parameter_value_name'=>'Geelong'),
            array('parameter_name'=>'Region', 'parameter_value_name'=>'Colac'),
            array('parameter_name'=>'League', 'parameter_value_name'=>'BFL'),
            array('parameter_name'=>'League', 'parameter_value_name'=>'GFL'),
            array('parameter_name'=>'League', 'parameter_value_name'=>'GDFL'),
            array('parameter_name'=>'League', 'parameter_value_name'=>'GJFL'),
            array('parameter_name'=>'League', 'parameter_value_name'=>'CDFNL'),
            array('parameter_name'=>'League', 'parameter_value_name'=>'Women'),
            array('parameter_name'=>'Umpire Discipline', 'parameter_value_name'=>'Field'),
            array('parameter_name'=>'Umpire Discipline', 'parameter_value_name'=>'Boundary'),
            array('parameter_name'=>'Umpire Discipline', 'parameter_value_name'=>'Goal'),
            array('parameter_name'=>'Age Group', 'parameter_value_name'=>'Seniors'),
            array('parameter_name'=>'Age Group', 'parameter_value_name'=>'Reserves'),
            array('parameter_name'=>'Age Group', 'parameter_value_name'=>'Colts'),
            array('parameter_name'=>'Age Group', 'parameter_value_name'=>'Under 19'),
            array('parameter_name'=>'Age Group', 'parameter_value_name'=>'Under 18'),
            array('parameter_name'=>'Age Group', 'parameter_value_name'=>'Under 17.5'),
            array('parameter_name'=>'Age Group', 'parameter_value_name'=>'Under 17'),
            array('parameter_name'=>'Age Group', 'parameter_value_name'=>'Under 16'),
            array('parameter_name'=>'Age Group', 'parameter_value_name'=>'Under 15'),
            array('parameter_name'=>'Age Group', 'parameter_value_name'=>'Under 14.5'),
            array('parameter_name'=>'Age Group', 'parameter_value_name'=>'Under 14'),
            array('parameter_name'=>'Age Group', 'parameter_value_name'=>'Under 13'),
            array('parameter_name'=>'Age Group', 'parameter_value_name'=>'Under 12'),
            array('parameter_name'=>'Age Group', 'parameter_value_name'=>'Under 19 Girls'),
            array('parameter_name'=>'Age Group', 'parameter_value_name'=>'Under 18 Girls'),
            array('parameter_name'=>'Age Group', 'parameter_value_name'=>'Under 15 Girls'),
            array('parameter_name'=>'Age Group', 'parameter_value_name'=>'Under 12 Girls'),
            array('parameter_name'=>'Age Group', 'parameter_value_name'=>'Youth Girls'),
            array('parameter_name'=>'Age Group', 'parameter_value_name'=>'Junior Girls')
        );
        foreach ($expectedArray as $key=>$subArray) {
            $this->assertEquals($subArray['parameter_name'], $resultArray[$key]->parameter_name);
            $this->assertEquals($subArray['parameter_value_name'], $resultArray[$key]->parameter_value_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->dbLocal->close();
    }

    public function test_RoleValues() {
        $queryString = "SELECT role_name FROM role ORDER BY display_order;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array('Owner', 'Administrator', 'Super User (Geelong)', 'Regular User', 'Super User (Colac)');
        foreach ($expectedArray as $key=>$value) {
            $this->assertEquals($value, $resultArray[$key]->role_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->dbLocal->close();
    }

    public function test_RoleLocalVsProd() {
        $queryString = "SELECT role_name FROM role ORDER BY display_order;";
        $queryProd = $this->db->query($queryString);
        $queryLocal = $this->dbLocal->query($queryString);
        $resultArrayProd = $queryProd->result();
        $resultArrayLocal = $queryLocal->result();
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'role_name');
        $this->assertEmpty($arrayDifferences);
        $this->db->close();
        $this->dbLocal->close();
    }

    public function test_RolePermissionSelection() {
        $queryString = "SELECT r.role_name, p.permission_name, ps.category, ps.selection_name
FROM role_permission_selection rp
INNER JOIN permission_selection ps ON rp.permission_selection_id = ps.id
INNER JOIN role r ON rp.role_id = r.id
INNER JOIN permission p ON ps.permission_id = p.id
ORDER BY r.display_order, p.permission_name, ps.category, ps.display_order;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $expectedArray = array(
            array('role_name'=>'Owner', 'permission_name'=>'ADD_NEW_USERS', 'category'=>'General', 'selection_name'=>'All'),
            array('role_name'=>'Owner', 'permission_name'=>'CREATE_PDF', 'category'=>'General', 'selection_name'=>'All'),
            array('role_name'=>'Owner', 'permission_name'=>'IMPORT_FILES', 'category'=>'General', 'selection_name'=>'All'),
            array('role_name'=>'Owner', 'permission_name'=>'MODIFY_EXISTING_USERS', 'category'=>'General', 'selection_name'=>'All'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Seniors'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Reserves'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Colts'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 17.5'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 16'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 14.5'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 12'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Youth Girls'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Junior Girls'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 14'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'BFL'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'GFL'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'GDFL'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'GJFL'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'CDFNL'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Region', 'selection_name'=>'Geelong'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Region', 'selection_name'=>'Colac'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Umpire Type', 'selection_name'=>'Boundary'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Umpire Type', 'selection_name'=>'Field'),
            array('role_name'=>'Owner', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Umpire Type', 'selection_name'=>'Goal'),
            array('role_name'=>'Owner', 'permission_name'=>'VIEW_DATA_TEST', 'category'=>'General', 'selection_name'=>'All'),
            array('role_name'=>'Owner', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 1'),
            array('role_name'=>'Owner', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 2'),
            array('role_name'=>'Owner', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 3'),
            array('role_name'=>'Owner', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 4'),
            array('role_name'=>'Owner', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 5'),
            array('role_name'=>'Owner', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 6'),
            array('role_name'=>'Administrator', 'permission_name'=>'ADD_NEW_USERS', 'category'=>'General', 'selection_name'=>'All'),
            array('role_name'=>'Administrator', 'permission_name'=>'CREATE_PDF', 'category'=>'General', 'selection_name'=>'All'),
            array('role_name'=>'Administrator', 'permission_name'=>'IMPORT_FILES', 'category'=>'General', 'selection_name'=>'All'),
            array('role_name'=>'Administrator', 'permission_name'=>'MODIFY_EXISTING_USERS', 'category'=>'General', 'selection_name'=>'All'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Seniors'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Reserves'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Colts'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 17.5'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 16'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 14.5'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 12'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Youth Girls'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Junior Girls'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 14'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'BFL'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'GFL'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'GDFL'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'GJFL'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'CDFNL'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Region', 'selection_name'=>'Geelong'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Region', 'selection_name'=>'Colac'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Umpire Type', 'selection_name'=>'Boundary'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Umpire Type', 'selection_name'=>'Field'),
            array('role_name'=>'Administrator', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Umpire Type', 'selection_name'=>'Goal'),
            array('role_name'=>'Administrator', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 1'),
            array('role_name'=>'Administrator', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 2'),
            array('role_name'=>'Administrator', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 3'),
            array('role_name'=>'Administrator', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 4'),
            array('role_name'=>'Administrator', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 5'),
            array('role_name'=>'Administrator', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 6'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'CREATE_PDF', 'category'=>'General', 'selection_name'=>'All'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Seniors'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Reserves'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Colts'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 17.5'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 16'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 14.5'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 12'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Youth Girls'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Junior Girls'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 14'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'BFL'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'GFL'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'GDFL'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'GJFL'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'CDFNL'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Region', 'selection_name'=>'Geelong'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Region', 'selection_name'=>'Colac'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Umpire Type', 'selection_name'=>'Boundary'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Umpire Type', 'selection_name'=>'Field'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Umpire Type', 'selection_name'=>'Goal'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 1'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 2'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 3'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 4'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 5'),
            array('role_name'=>'Super User (Geelong)', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 6'),
            array('role_name'=>'Regular User', 'permission_name'=>'CREATE_PDF', 'category'=>'General', 'selection_name'=>'All'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Seniors'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Reserves'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Colts'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 17.5'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 16'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 14.5'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 12'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Youth Girls'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Junior Girls'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Age Group', 'selection_name'=>'Under 14'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'BFL'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'GFL'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'GDFL'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'GJFL'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'League', 'selection_name'=>'CDFNL'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Region', 'selection_name'=>'Geelong'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Region', 'selection_name'=>'Colac'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Umpire Type', 'selection_name'=>'Boundary'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Umpire Type', 'selection_name'=>'Field'),
            array('role_name'=>'Regular User', 'permission_name'=>'SELECT_REPORT_OPTION', 'category'=>'Umpire Type', 'selection_name'=>'Goal'),
            array('role_name'=>'Regular User', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 1'),
            array('role_name'=>'Regular User', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 2'),
            array('role_name'=>'Regular User', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 3'),
            array('role_name'=>'Regular User', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 4'),
            array('role_name'=>'Regular User', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 5'),
            array('role_name'=>'Regular User', 'permission_name'=>'VIEW_REPORT', 'category'=>'Report', 'selection_name'=>'Report 6'),
        );
        foreach ($expectedArray as $key=>$subArray) {
            $this->assertEquals($subArray['role_name'], $resultArray[$key]->role_name);
            $this->assertEquals($subArray['permission_name'], $resultArray[$key]->permission_name);
            $this->assertEquals($subArray['category'], $resultArray[$key]->category);
            $this->assertEquals($subArray['selection_name'], $resultArray[$key]->selection_name);
        }

        $expectedCount = count($expectedArray);
        $actualCount = count($resultArray);
        $this->assertEquals($expectedCount, $actualCount);
        $this->dbLocal->close();
    }

    public function test_RolePermissionSelectionLocalVsProd() {
        $queryString = "SELECT r.role_name, p.permission_name, ps.category, ps.selection_name
FROM role_permission_selection rp
INNER JOIN permission_selection ps ON rp.permission_selection_id = ps.id
INNER JOIN role r ON rp.role_id = r.id
INNER JOIN permission p ON ps.permission_id = p.id
ORDER BY r.display_order, p.permission_name, ps.category, ps.display_order;";
        $queryProd = $this->db->query($queryString);
        $queryLocal = $this->dbLocal->query($queryString);
        $resultArrayProd = $queryProd->result();
        $resultArrayLocal = $queryLocal->result();
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'role_name');
        $this->assertEmpty($arrayDifferences);
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'permission_name');
        $this->assertEmpty($arrayDifferences);
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'category');
        $this->assertEmpty($arrayDifferences);
        $arrayDifferences = $this->arrayLibrary->findArrayDBObjectDiff($resultArrayProd, $resultArrayLocal, 'selection_name');
        $this->assertEmpty($arrayDifferences);
        $this->db->close();
        $this->dbLocal->close();
    }


}
