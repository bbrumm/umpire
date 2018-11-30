<?php

class HomeController_test extends TestCase
{
    public function setUp() {
        $this->resetInstance();
        $_POST = array();
        $this->dbLocal = $this->CI->load->database('default', TRUE);

    }

    public function test_LoadPage() {
        $sessionArray['logged_in'] = array('username'=>'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);

        $output = $this->request('POST', ['Home', 'index']);
        $expectedHeader = "<h2>Select a Report</h2>";
        $this->assertContains($expectedHeader, $output);

        $expectedDateImported = "Data last imported on 19 Sep 2018, 05:37:15 PM";
        $this->assertContains($expectedDateImported, $output);

        /*
        Don't test these UI elements as they are fragile

        $expectedReportList = "<option value='1'>01 - Umpires and Clubs</option><option value='2'>02 - Umpire Names by League</option><option value='3'>03 - Summary</option><option value='4'>04 - Summary by Club</option><option value='5'>05 - Summary by League</option><option value='6'>06 - Pairings</option><option value='7'>07 - 2 and 3 Field Umpires</option><option value='8'>08 - Umpire Games Tally</option>";
        $this->assertContains($expectedReportList, $output);

        $expectedRegionList = "<div class='optionsLabelsSection'><div class='optionsSelectionRow'><input type='radio' id='Geelong' name='rdRegion' value='Geelong' onClick='updatePageFromCheckboxSelection()' checked><label class='reportControlLabel' for='Geelong'>Geelong</label></div><div class='optionsSelectionRow'><input type='radio' id='Colac' name='rdRegion' value='Colac' onClick='updatePageFromCheckboxSelection()'><label class='reportControlLabel' for='Colac'>Colac</label></div>";
        $this->assertContains($expectedRegionList, $output);

        $expectedLeagueList = "<div class='optionsLabelsSection'><div class='optionsSelectAllRow'><input type='checkbox' id='LeagueSelectAll' onClick='selectAll(this, \"chkLeague[]\")'></input><label class='reportControlLabel' for='LeagueSelectAll'>Select All</label></div><div class='optionsSelectionRow'><input type='checkbox' id='BFL' name='chkLeague[]' value='BFL' onClick='updatePageFromCheckboxSelection(this, LeagueSelectAll)'></input><label class='reportControlLabel' for='BFL'>BFL</label></div><div class='optionsSelectionRow'><input type='checkbox' id='GFL' name='chkLeague[]' value='GFL' onClick='updatePageFromCheckboxSelection(this, LeagueSelectAll)'></input><label class='reportControlLabel' for='GFL'>GFL</label></div><div class='optionsSelectionRow'><input type='checkbox' id='GDFL' name='chkLeague[]' value='GDFL' onClick='updatePageFromCheckboxSelection(this, LeagueSelectAll)'></input><label class='reportControlLabel' for='GDFL'>GDFL</label></div><div class='optionsSelectionRow'><input type='checkbox' id='GJFL' name='chkLeague[]' value='GJFL' onClick='updatePageFromCheckboxSelection(this, LeagueSelectAll)'></input><label class='reportControlLabel' for='GJFL'>GJFL</label></div><div class='optionsSelectionRow'><input type='checkbox' id='CDFNL' name='chkLeague[]' value='CDFNL' onClick='updatePageFromCheckboxSelection(this, LeagueSelectAll)'></input><label class='reportControlLabel' for='CDFNL'>CDFNL</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Women' name='chkLeague[]' value='Women' onClick='updatePageFromCheckboxSelection(this, LeagueSelectAll)'></input><label class='reportControlLabel' for='Women'>Women</label></div><input type='hidden' id='chkLeagueHidden' name='chkLeagueHidden' value='' /></div>";
        $this->assertContains($expectedLeagueList, $output);

        $expectedUmpireList = "<div class='optionsLabelsSection'><div class='optionsSelectAllRow'><input type='checkbox' id='UmpireDisciplineSelectAll' onClick='selectAll(this, \"chkUmpireDiscipline[]\")'></input><label class='reportControlLabel' for='UmpireDisciplineSelectAll'>Select All</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Field' name='chkUmpireDiscipline[]' value='Field' onClick='updatePageFromCheckboxSelection(this, UmpireDisciplineSelectAll)'></input><label class='reportControlLabel' for='Field'>Field</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Boundary' name='chkUmpireDiscipline[]' value='Boundary' onClick='updatePageFromCheckboxSelection(this, UmpireDisciplineSelectAll)'></input><label class='reportControlLabel' for='Boundary'>Boundary</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Goal' name='chkUmpireDiscipline[]' value='Goal' onClick='updatePageFromCheckboxSelection(this, UmpireDisciplineSelectAll)'></input><label class='reportControlLabel' for='Goal'>Goal</label></div><input type='hidden' id='chkUmpireDisciplineHidden' name='chkUmpireDisciplineHidden' value='' /></div>";
        $this->assertContains($expectedUmpireList, $output);

        $expectedAgeGroupList = "<div class='optionsLabelsSection'><div class='optionsSelectAllRow'><input type='checkbox' id='AgeGroupSelectAll' onClick='selectAll(this, \"chkAgeGroup[]\")'></input><label class='reportControlLabel' for='AgeGroupSelectAll'>Select All</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Seniors' name='chkAgeGroup[]' value='Seniors' onClick='updatePageFromCheckboxSelection(this, AgeGroupSelectAll)'></input><label class='reportControlLabel' for='Seniors'>Seniors</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Reserves' name='chkAgeGroup[]' value='Reserves' onClick='updatePageFromCheckboxSelection(this, AgeGroupSelectAll)'></input><label class='reportControlLabel' for='Reserves'>Reserves</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Colts' name='chkAgeGroup[]' value='Colts' onClick='updatePageFromCheckboxSelection(this, AgeGroupSelectAll)'></input><label class='reportControlLabel' for='Colts'>Colts</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Under 19' name='chkAgeGroup[]' value='Under 19' onClick='updatePageFromCheckboxSelection(this, AgeGroupSelectAll)'></input><label class='reportControlLabel' for='Under 19'>Under 19</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Under 17.5' name='chkAgeGroup[]' value='Under 17.5' onClick='updatePageFromCheckboxSelection(this, AgeGroupSelectAll)'></input><label class='reportControlLabel' for='Under 17.5'>Under 17.5</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Under 17' name='chkAgeGroup[]' value='Under 17' onClick='updatePageFromCheckboxSelection(this, AgeGroupSelectAll)'></input><label class='reportControlLabel' for='Under 17'>Under 17</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Under 16' name='chkAgeGroup[]' value='Under 16' onClick='updatePageFromCheckboxSelection(this, AgeGroupSelectAll)'></input><label class='reportControlLabel' for='Under 16'>Under 16</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Under 15' name='chkAgeGroup[]' value='Under 15' onClick='updatePageFromCheckboxSelection(this, AgeGroupSelectAll)'></input><label class='reportControlLabel' for='Under 15'>Under 15</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Under 14.5' name='chkAgeGroup[]' value='Under 14.5' onClick='updatePageFromCheckboxSelection(this, AgeGroupSelectAll)'></input><label class='reportControlLabel' for='Under 14.5'>Under 14.5</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Under 14' name='chkAgeGroup[]' value='Under 14' onClick='updatePageFromCheckboxSelection(this, AgeGroupSelectAll)'></input><label class='reportControlLabel' for='Under 14'>Under 14</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Under 13' name='chkAgeGroup[]' value='Under 13' onClick='updatePageFromCheckboxSelection(this, AgeGroupSelectAll)'></input><label class='reportControlLabel' for='Under 13'>Under 13</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Under 12' name='chkAgeGroup[]' value='Under 12' onClick='updatePageFromCheckboxSelection(this, AgeGroupSelectAll)'></input><label class='reportControlLabel' for='Under 12'>Under 12</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Under 19 Girls' name='chkAgeGroup[]' value='Under 19 Girls' onClick='updatePageFromCheckboxSelection(this, AgeGroupSelectAll)'></input><label class='reportControlLabel' for='Under 19 Girls'>Under 19 Girls</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Under 18 Girls' name='chkAgeGroup[]' value='Under 18 Girls' onClick='updatePageFromCheckboxSelection(this, AgeGroupSelectAll)'></input><label class='reportControlLabel' for='Under 18 Girls'>Under 18 Girls</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Under 15 Girls' name='chkAgeGroup[]' value='Under 15 Girls' onClick='updatePageFromCheckboxSelection(this, AgeGroupSelectAll)'></input><label class='reportControlLabel' for='Under 15 Girls'>Under 15 Girls</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Under 12 Girls' name='chkAgeGroup[]' value='Under 12 Girls' onClick='updatePageFromCheckboxSelection(this, AgeGroupSelectAll)'></input><label class='reportControlLabel' for='Under 12 Girls'>Under 12 Girls</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Youth Girls' name='chkAgeGroup[]' value='Youth Girls' onClick='updatePageFromCheckboxSelection(this, AgeGroupSelectAll)'></input><label class='reportControlLabel' for='Youth Girls'>Youth Girls</label></div><div class='optionsSelectionRow'><input type='checkbox' id='Junior Girls' name='chkAgeGroup[]' value='Junior Girls' onClick='updatePageFromCheckboxSelection(this, AgeGroupSelectAll)'></input><label class='reportControlLabel' for='Junior Girls'>Junior Girls</label></div><input type='hidden' id='chkAgeGroupHidden' name='chkAgeGroupHidden' value='' /></div>";
        $this->assertContains($expectedAgeGroupList, $output);
        */


    }

    public function test_LoadPage_NotLoggedIn() {
        $sessionArray['logged_in'] = null;
        $this->CI->session->set_userdata($sessionArray);
        $output = $this->request('POST', ['Home', 'index']);

        $this->assertRedirect('login');

    }

    public function test_LoadPage_Logout() {
        $sessionArray['logged_in'] = array('username'=>'bbrummtest');
        $this->CI->session->set_userdata($sessionArray);

        $output = $this->request('POST', ['Home', 'index']);
        $expectedHeader = "<h2>Select a Report</h2>";
        $this->assertContains($expectedHeader, $output);

        //Logout
        $output = $this->request('POST', ['Home', 'logout']);
        $this->assertRedirect('home');

    }







}