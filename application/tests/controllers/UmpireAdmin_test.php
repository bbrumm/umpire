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


}