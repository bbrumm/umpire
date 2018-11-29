<?php
class UmpireAdmin_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $_POST = array();
        $this->dbLocal = $this->CI->load->database('default', TRUE);
    }

    public function test_LoadPage() {
        $output = $this->request('POST', ['UmpireAdmin', 'loadPage']);
        $expected = "<h2>Umpire Administration</h2>";
        $this->assertContains($expected, $output);
    }

    public function test_UpdateUmpireGamesPlayed_Single() {
        //Original values are 166 and 0
        $newGeelongPrior = 167;
        $newOtherLeagues = 1;
        $oldGeelongPrior = 166;
        $oldOtherLeagues = 0;

        $postArray = array(
            22960=>array('geelong_prior'=>$newGeelongPrior,'other_leagues'=>$newOtherLeagues)
        );

        $output = $this->request('POST', ['UmpireAdmin', 'updateUmpireGamesPlayed'], $postArray);
        $expected = "<h2>Umpire Administration</h2>";
        $this->assertContains($expected, $output);
        $expectedMessage = "Data successfully updated.";
        $this->assertContains($expectedMessage, $output);

        //Check values are updated
        $queryString = "SELECT games_prior, games_other_leagues FROM umpire WHERE id = 22960;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result_array();
        $this->assertEquals($newGeelongPrior, $resultArray[0]['games_prior']);
        $this->assertEquals($newOtherLeagues, $resultArray[0]['games_other_leagues']);

        //Reset back to original values
        $queryString = "UPDATE umpire SET games_prior = 166, games_other_leagues = 0 WHERE id = 22960;";
        $query = $this->dbLocal->query($queryString);

        $queryString = "SELECT games_prior, games_other_leagues FROM umpire WHERE id = 22960;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result_array();
        $this->assertEquals($oldGeelongPrior, $resultArray[0]['games_prior']);
        $this->assertEquals($oldOtherLeagues, $resultArray[0]['games_other_leagues']);


    }

    public function test_UpdateUmpireGamesPlayed_SingleGamesPriorOnly() {
        //Original values are 166 and 0
        $newGeelongPrior = 167;
        $newOtherLeagues = 0;
        $oldGeelongPrior = 166;
        $oldOtherLeagues = 0;

        $postArray = array(
            22960=>array('geelong_prior'=>$newGeelongPrior,'other_leagues'=>$newOtherLeagues)
        );

        $output = $this->request('POST', ['UmpireAdmin', 'updateUmpireGamesPlayed'], $postArray);
        $expected = "<h2>Umpire Administration</h2>";
        $this->assertContains($expected, $output);
        $expectedMessage = "Data successfully updated.";
        $this->assertContains($expectedMessage, $output);

        //Check values are updated
        $queryString = "SELECT games_prior, games_other_leagues FROM umpire WHERE id = 22960;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result_array();
        $this->assertEquals($newGeelongPrior, $resultArray[0]['games_prior']);
        $this->assertEquals($newOtherLeagues, $resultArray[0]['games_other_leagues']);

        //Reset back to original values
        $queryString = "UPDATE umpire SET games_prior = 166, games_other_leagues = 0 WHERE id = 22960;";
        $query = $this->dbLocal->query($queryString);

        $queryString = "SELECT games_prior, games_other_leagues FROM umpire WHERE id = 22960;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result_array();
        $this->assertEquals($oldGeelongPrior, $resultArray[0]['games_prior']);
        $this->assertEquals($oldOtherLeagues, $resultArray[0]['games_other_leagues']);


    }

    public function test_UpdateUmpireGamesPlayed_SingleGamesOtherOnly() {
        //Original values are 166 and 0
        $newGeelongPrior = 166;
        $newOtherLeagues = 5;
        $oldGeelongPrior = 166;
        $oldOtherLeagues = 0;

        $postArray = array(
            22960=>array('geelong_prior'=>$newGeelongPrior,'other_leagues'=>$newOtherLeagues)
        );

        $output = $this->request('POST', ['UmpireAdmin', 'updateUmpireGamesPlayed'], $postArray);
        $expected = "<h2>Umpire Administration</h2>";
        $this->assertContains($expected, $output);
        $expectedMessage = "Data successfully updated.";
        $this->assertContains($expectedMessage, $output);

        //Check values are updated
        $queryString = "SELECT games_prior, games_other_leagues FROM umpire WHERE id = 22960;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result_array();
        $this->assertEquals($newGeelongPrior, $resultArray[0]['games_prior']);
        $this->assertEquals($newOtherLeagues, $resultArray[0]['games_other_leagues']);

        //Reset back to original values
        $queryString = "UPDATE umpire SET games_prior = 166, games_other_leagues = 0 WHERE id = 22960;";
        $query = $this->dbLocal->query($queryString);

        $queryString = "SELECT games_prior, games_other_leagues FROM umpire WHERE id = 22960;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result_array();
        $this->assertEquals($oldGeelongPrior, $resultArray[0]['games_prior']);
        $this->assertEquals($oldOtherLeagues, $resultArray[0]['games_other_leagues']);

    }

    public function test_UpdateUmpireGamesPlayed_TwoUpdates() {
        //Original values are 166 and 0
        $newGeelongPrior = 168;
        $newOtherLeagues = 2;
        $oldGeelongPrior = 166;
        $oldOtherLeagues = 0;

        $newGeelongPriorSecond = 9802;
        $newOtherLeaguesSecond = 531;
        $oldGeelongPriorSecond = 387;
        $oldOtherLeaguesSecond = 0;

        $postArray = array(
            22960=>array('geelong_prior'=>$newGeelongPrior,'other_leagues'=>$newOtherLeagues),
            89372=>array('geelong_prior'=>$newGeelongPriorSecond,'other_leagues'=>$newOtherLeaguesSecond)
        );

        $output = $this->request('POST', ['UmpireAdmin', 'updateUmpireGamesPlayed'], $postArray);
        $expected = "<h2>Umpire Administration</h2>";
        $this->assertContains($expected, $output);
        $expectedMessage = "Data successfully updated.";
        $this->assertContains($expectedMessage, $output);

        //Check values are updated
        $queryString = "SELECT games_prior, games_other_leagues FROM umpire WHERE id IN (22960, 89372) ORDER BY id;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result_array();
        $this->assertEquals($newGeelongPrior, $resultArray[0]['games_prior']);
        $this->assertEquals($newOtherLeagues, $resultArray[0]['games_other_leagues']);
        $this->assertEquals($newGeelongPriorSecond, $resultArray[1]['games_prior']);
        $this->assertEquals($newOtherLeaguesSecond, $resultArray[1]['games_other_leagues']);

        //Reset back to original values
        $queryString = "UPDATE umpire SET games_prior = 166, games_other_leagues = 0 WHERE id = 22960;";
        $query = $this->dbLocal->query($queryString);
        $queryString = "UPDATE umpire SET games_prior = 387, games_other_leagues = 0 WHERE id = 89372;";
        $query = $this->dbLocal->query($queryString);

        $queryString = "SELECT games_prior, games_other_leagues FROM umpire WHERE id IN (22960, 89372) ORDER BY id;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result_array();
        $this->assertEquals($oldGeelongPrior, $resultArray[0]['games_prior']);
        $this->assertEquals($oldOtherLeagues, $resultArray[0]['games_other_leagues']);
        $this->assertEquals($oldGeelongPriorSecond, $resultArray[1]['games_prior']);
        $this->assertEquals($oldOtherLeaguesSecond, $resultArray[1]['games_other_leagues']);

    }

    public function test_UpdateUmpireGamesPlayed_TwoUpdatesAlternate() {
        //Original values are 166 and 0
        $newGeelongPrior = 168;
        $newOtherLeagues = 0;
        $oldGeelongPrior = 166;
        $oldOtherLeagues = 0;

        $newGeelongPriorSecond = 387;
        $newOtherLeaguesSecond = 17;
        $oldGeelongPriorSecond = 387;
        $oldOtherLeaguesSecond = 0;

        $postArray = array(
            22960=>array('geelong_prior'=>$newGeelongPrior,'other_leagues'=>$newOtherLeagues),
            89372=>array('geelong_prior'=>$newGeelongPriorSecond,'other_leagues'=>$newOtherLeaguesSecond)
        );

        $output = $this->request('POST', ['UmpireAdmin', 'updateUmpireGamesPlayed'], $postArray);
        $expected = "<h2>Umpire Administration</h2>";
        $this->assertContains($expected, $output);
        $expectedMessage = "Data successfully updated.";
        $this->assertContains($expectedMessage, $output);

        //Check values are updated
        $queryString = "SELECT games_prior, games_other_leagues FROM umpire WHERE id IN (22960, 89372) ORDER BY id;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result_array();
        $this->assertEquals($newGeelongPrior, $resultArray[0]['games_prior']);
        $this->assertEquals($newOtherLeagues, $resultArray[0]['games_other_leagues']);
        $this->assertEquals($newGeelongPriorSecond, $resultArray[1]['games_prior']);
        $this->assertEquals($newOtherLeaguesSecond, $resultArray[1]['games_other_leagues']);

        //Reset back to original values
        $queryString = "UPDATE umpire SET games_prior = 166, games_other_leagues = 0 WHERE id = 22960;";
        $query = $this->dbLocal->query($queryString);
        $queryString = "UPDATE umpire SET games_prior = 387, games_other_leagues = 0 WHERE id = 89372;";
        $query = $this->dbLocal->query($queryString);

        $queryString = "SELECT games_prior, games_other_leagues FROM umpire WHERE id IN (22960, 89372) ORDER BY id;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result_array();
        $this->assertEquals($oldGeelongPrior, $resultArray[0]['games_prior']);
        $this->assertEquals($oldOtherLeagues, $resultArray[0]['games_other_leagues']);
        $this->assertEquals($oldGeelongPriorSecond, $resultArray[1]['games_prior']);
        $this->assertEquals($oldOtherLeaguesSecond, $resultArray[1]['games_other_leagues']);

    }

    public function test_UpdateUmpireGamesPlayed_TwoUmpireNoChange() {
        //Original values are 166 and 0
        $newGeelongPrior = 166;
        $newOtherLeagues = 0;
        $oldGeelongPrior = 166;
        $oldOtherLeagues = 0;

        $newGeelongPriorSecond = 387;
        $newOtherLeaguesSecond = 0;
        $oldGeelongPriorSecond = 387;
        $oldOtherLeaguesSecond = 0;

        $postArray = array(
            22960=>array('geelong_prior'=>$newGeelongPrior,'other_leagues'=>$newOtherLeagues),
            89372=>array('geelong_prior'=>$newGeelongPriorSecond,'other_leagues'=>$newOtherLeaguesSecond)
        );

        $this->assertEquals($newGeelongPrior, $oldGeelongPrior);
        $this->assertEquals($newOtherLeagues, $oldOtherLeagues);
        $this->assertEquals($newGeelongPriorSecond, $oldGeelongPriorSecond);
        $this->assertEquals($newOtherLeaguesSecond, $oldOtherLeaguesSecond);

        $output = $this->request('POST', ['UmpireAdmin', 'updateUmpireGamesPlayed'], $postArray);
        $expected = "<h2>Umpire Administration</h2>";
        $this->assertContains($expected, $output);
        $expectedMessage = "Data not updated.";
        $this->assertContains($expectedMessage, $output);

        //Check values are not updated
        $queryString = "SELECT games_prior, games_other_leagues FROM umpire WHERE id IN (22960, 89372) ORDER BY id;";
        $query = $this->dbLocal->query($queryString);
        $resultArray = $query->result_array();
        $this->assertEquals($newGeelongPrior, $resultArray[0]['games_prior']);
        $this->assertEquals($newOtherLeagues, $resultArray[0]['games_other_leagues']);
        $this->assertEquals($newGeelongPriorSecond, $resultArray[1]['games_prior']);
        $this->assertEquals($newOtherLeaguesSecond, $resultArray[1]['games_other_leagues']);

    }


}