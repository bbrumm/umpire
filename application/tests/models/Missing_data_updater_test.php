<?php

class Missing_data_updater_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $this->CI->load->model('Missing_data_updater');
        $this->obj = $this->CI->Missing_data_updater;
    }


    public function test_GetPossibleLeaguesForComp() {
        $arrayStore = new Array_store_reference();
        $leagesForComp = $this->obj->loadPossibleLeaguesForComp($arrayStore);
        $expectedSize = 10;
        $actualSize = count($leagesForComp);
        $expectedFirstValue = "AFL Barwon Blood Toyota Geelong FNL";
        $actualFirstValue = $leagesForComp[0][1];
        $this->assertEquals($expectedSize, $actualSize);
        $this->assertEquals($expectedFirstValue, $actualFirstValue);
    }

    public function test_GetClubsForTeam() {
        $arrayStore = new Array_store_reference();
        $clubs = $this->obj->loadPossibleClubsForTeam($arrayStore);
        $expectedSize = 4;
        $actualSize = count($clubs);
        $expectedFirstValue = "East";
        $actualFirstValue = $clubs[2][1];
        $this->assertEquals($expectedSize, $actualSize);
        $this->assertEquals($expectedFirstValue, $actualFirstValue);
    }

    public function test_GetRegions() {
        $arrayStore = new Array_store_reference();
        $regions = $this->obj->loadPossibleRegions($arrayStore);
        $expectedSize = 3;
        $actualSize = count($regions);
        $expectedFirstValue = "Samba";
        $actualFirstValue = $regions[1][1];
        $this->assertEquals($expectedSize, $actualSize);
        $this->assertEquals($expectedFirstValue, $actualFirstValue);
    }

    public function test_GetAgeGroups() {
        $arrayStore = new Array_store_reference();
        $ages = $this->obj->loadPossibleAgeGroups($arrayStore);
        $expectedSize = 5;
        $actualSize = count($ages);
        $expectedFirstValue = "Under 18";
        $actualFirstValue = $ages[3][1];
        $this->assertEquals($expectedSize, $actualSize);
        $this->assertEquals($expectedFirstValue, $actualFirstValue);
    }

    public function test_GetShortLeagueNames() {
        $arrayStore = new Array_store_reference();
        $leagues = $this->obj->loadPossibleShortLeagueNames($arrayStore);
        $expectedSize = 4;
        $actualSize = count($leagues);
        $expectedFirstValue = "SANFL";
        $actualFirstValue = $leagues[1][1];
        $this->assertEquals($expectedSize, $actualSize);
        $this->assertEquals($expectedFirstValue, $actualFirstValue);
    }

    public function test_GetDivisions() {
        $arrayStore = new Array_store_reference();
        $divisions = $this->obj->loadPossibleDivisions($arrayStore);
        $expectedSize = 3;
        $actualSize = count($divisions);
        $expectedFirstValue = "Div 2";
        $actualFirstValue = $divisions[1][1];
        $this->assertEquals($expectedSize, $actualSize);
        $this->assertEquals($expectedFirstValue, $actualFirstValue);
    }

    public function test_UpdateSingleCompetition() {
        $arrayStore = new Array_store_missing_data();
        $inputArray = array(
            "competition_id" => 2
        );
        $expected = $this->obj->updateSingleCompetition($arrayStore, $inputArray);
        $actual = true;
        $this->assertEquals($expected, $actual);
    }

    public function test_InsertNewLeague() {
        $arrayStore = new Array_store_missing_data();
        $inputArray = array(
            "short_league_name" => "abc",
            "age_group" => "Under 15"
        );
        $expected = $this->obj->insertNewLeague($arrayStore, $inputArray);
        $actual = "abc";
        $this->assertEquals($expected, $actual);
    }


    public function test_updateTeamAndClubData_OneTeamInsert() {
        $arrayStore = new Array_store_missing_data();

        $postArray = array(
            'competition' => array('Some new competition'),
            'rdTeam' => array(
                'new'
            ),
            'txtTeam' => array(
                'Adelaide'
            ),
            'cboTeam' => array(
                'ABC'
            )
        );

        $expected = true;
        $actual = $this->obj->updateCompetitionAndTeamData($arrayStore, $postArray);

        $this->assertEquals($expected, $actual);

    }

    public function test_updateTeamAndClubData_TwoTeamsInsert() {
        $arrayStore = new Array_store_missing_data();
        //$arrayStoreMissingData = new Array_store_missing_data();

        $postArray = array(
            'competition' => array('Some new competition'),
            'rdTeam' => array(
                'new', 'new'
            ),
            'txtTeam' => array(
                'Adelaide', 'Brisbane'
            ),
            'cboTeam' => array(
            'AA', 'BB'
            )
        );

        $expected = true;
        $actual = $this->obj->updateCompetitionAndTeamData($arrayStore, $postArray);
        //$this->obj->runETLProcedure($arrayStoreMissingData);

        $this->assertEquals($expected, $actual);
    }


    public function test_updateTeamAndClubData_OneTeamUpdate() {
        $arrayStore = new Array_store_missing_data();

        $postArray = array(
            'competition' => array('Some new competition'),
            'rdTeam' => array(
                'existing'
            ),
            'txtTeam' => array(
                'Brisbane'
            ),
            'cboTeam' => array(
                'BB'
            )
        );

        $expected = true;
        $actual = $this->obj->updateCompetitionAndTeamData($arrayStore, $postArray);

        $this->assertEquals($expected, $actual);

    }

    public function test_updateTeamAndClubData_TwoTeamsUpdate() {
        $arrayStore = new Array_store_missing_data();

        $postArray = array(
            'competition' => array('Some new competition'),
            'rdTeam' => array(
                'existing', 'existing'
            ),
            'txtTeam' => array(
                'Adelaide', 'Brisbane'
            ),
            'cboTeam' => array(
                'AA', 'BB'
            )
        );

        $expected = true;
        $actual = $this->obj->updateCompetitionAndTeamData($arrayStore, $postArray);

        $this->assertEquals($expected, $actual);

    }

    public function test_updateTeamAndClubData_MissingValues() {
        $arrayStore = new Array_store_missing_data();

        $postArray = array(
            'competition' => array('Some new competition'),
        );

        $expected = true;
        $actual = $this->obj->updateCompetitionAndTeamData($arrayStore, $postArray);

        $this->assertEquals($expected, $actual);

    }


    public function test_updateTeamAndClubData_NoCompetition() {
        $arrayStore = new Array_store_missing_data();

        $postArray = array(
            'rdTeam' => array(
                'existing', 'existing'
            ),
            'txtTeam' => array(
                'Adelaide', 'Brisbane'
            ),
            'cboTeam' => array(
                'AA', 'BB'
            )

        );

        $expected = true;
        $actual = $this->obj->updateCompetitionAndTeamData($arrayStore, $postArray);

        $this->assertEquals($expected, $actual);

    }

}