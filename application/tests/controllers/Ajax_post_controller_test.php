<?php

class Ajax_post_controller_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $_POST = array();
        $this->dbLocal = $this->CI->load->database('default', TRUE);

    }

    public function test_UserDataSubmit() {
        $sessionArray['logged_in'] = array('username' => 'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);
        $postDataArray = array (
            'name'=>'bbrummtest',
            'pwd'=>'123',
        );

        $output = $this->request('POST', ['Ajax_post_controller', 'user_data_submit'], $postDataArray);
        $expectedOutput = '{"username":"bbrummtest","pwd":"123"}';
        $this->assertEquals($expectedOutput, $output);
    }

    public function test_UpdateSingleCompetition() {
        //Add new competition
        $queryString = "DELETE FROM competition_lookup WHERE id = 1000;";
        $query = $this->dbLocal->query($queryString);
        $queryString = "INSERT INTO competition_lookup(id, competition_name, season_id, league_id) VALUES (1000, 'Test Comp', 4, NULL);";
        $query = $this->dbLocal->query($queryString);
        
        $sessionArray['logged_in'] = array('username' => 'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);
        $postDataArray = array (
            'selectedRegion'=>'1',
            'selectedAgeGroup'=>'1',
            'selectedDivision'=>'4',
            'selectedLeague'=>'GFL',
            'competitionID'=>'1000'
        );

        $output = $this->request('POST', ['Ajax_post_controller', 'updateCompetition'], $postDataArray);

        //Find league ID of the test competition
        $queryString = "SELECT league_id FROM competition_lookup WHERE id = 1000;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $updatedLeagueID = $resultArray[0]->league_id;
        $expectedLeagueID = 3;
        $this->assertEquals($expectedLeagueID, $updatedLeagueID);
        $queryString = "DELETE FROM competition_lookup WHERE id = 1000;";
        $query = $this->dbLocal->query($queryString);

    }

    public function test_UpdateSingleCompetition_LeagueDoesNotExist() {
        //Add new competition
        $queryString = "DELETE FROM competition_lookup WHERE id = 1000;";
        $query = $this->dbLocal->query($queryString);
        $queryString = "INSERT INTO competition_lookup(id, competition_name, season_id, league_id) VALUES (1000, 'Test Comp', 4, NULL);";
        $query = $this->dbLocal->query($queryString);

        $sessionArray['logged_in'] = array('username' => 'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);
        $postDataArray = array (
            'selectedRegion'=>'1',
            'selectedAgeGroup'=>'1',
            'selectedDivision'=>'4',
            'selectedLeague'=>'ABC',
            'competitionID'=>'1000'
        );

        $output = $this->request('POST', ['Ajax_post_controller', 'updateCompetition'], $postDataArray);

        //Check league was inserted
        $queryString = "SELECT MAX(id) AS id FROM league WHERE short_league_name = 'ABC';";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $actualNewLeagueID = $resultArray[0]->id;
        $actualCount = count($resultArray);
        $expectedCount = 1;
        $this->assertEquals($expectedCount, $actualCount);

        //Find league ID of the test competition
        $queryString = "SELECT league_id FROM competition_lookup WHERE id = 1000;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $updatedLeagueID = $resultArray[0]->league_id;
        $this->assertEquals($actualNewLeagueID, $updatedLeagueID);

        //Clean up data
        $queryString = "DELETE FROM competition_lookup WHERE id = 1000;";
        $query = $this->dbLocal->query($queryString);
        $queryString = "DELETE FROM league WHERE short_league_name = 'ABC';";
        $query = $this->dbLocal->query($queryString);

    }

    public function test_UpdateUmpireGames() {
        $queryString = "DELETE FROM umpire WHERE id = 1000 AND first_name = 'bbrumm';";
        $query = $this->dbLocal->query($queryString);

        //Set up test data
        $queryString = "INSERT INTO umpire (id, first_name, last_name, games_prior, games_other_leagues)
        VALUES (1000, 'bbrumm', 'test', 13, 21);";
        $query = $this->dbLocal->query($queryString);


        $sessionArray['logged_in'] = array('username' => 'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);
        $newGamesPrior = 41;
        $newGamesOtherLeagues = 106;

        $postDataArray['data'] = "1000[first_name]=bbrumm&1000[last_name]=test&1000[geelong_prior]=$newGamesPrior&1000[other_leagues]=$newGamesOtherLeagues";


        $output = $this->request('POST', ['Ajax_post_controller', 'updateUmpireGames'], $postDataArray);

        //Check updated data
        $queryString = "SELECT games_prior, games_other_leagues FROM umpire WHERE id = 1000 AND first_name = 'bbrumm';";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result();
        $updatedGamesPrior = $resultArray[0]->games_prior;
        $updatedGamesOtherLeagues = $resultArray[0]->games_other_leagues;
        $this->assertEquals($newGamesPrior, $updatedGamesPrior);
        $this->assertEquals($newGamesOtherLeagues, $updatedGamesOtherLeagues);

        //Clean up test data
        $queryString = "DELETE FROM umpire WHERE id = 1000 AND first_name = 'bbrumm';";
        $query = $this->dbLocal->query($queryString);

    }

}