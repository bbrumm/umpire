<?php
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;


class Report_UI_test extends TestCase
{

    const LEAGUE_BFL = "BFL";
    const LEAGUE_GFL = "GFL";
    const LEAGUE_GDFL = "GDFL";
    const LEAGUE_GJFL = "GJFL";
    const LEAGUE_CDFNL = "CDFNL";
    const LEAGUE_WOMEN = "Women";

    const UMP_TYPE_FIELD = "Field";
    const UMP_TYPE_BOUNDARY = "Boundary";
    const UMP_TYPE_GOAL = "Goal";

    const AGE_GROUP_SENIORS = "Seniors";
    const AGE_GROUP_RESERVES = "Reserves";
    const AGE_GROUP_COLTS = "Colts";
    const AGE_GROUP_U19 = "Under 19";
    const AGE_GROUP_U18 = "Under 18";
    const AGE_GROUP_U175 = "Under 17.5";
    const AGE_GROUP_U17 = "Under 17";
    const AGE_GROUP_U16 = "Under 16";
    const AGE_GROUP_U15 = "Under 15";
    const AGE_GROUP_U145 = "Under 14.5";
    const AGE_GROUP_U14 = "Under 14";
    const AGE_GROUP_U13 = "Under 13";
    const AGE_GROUP_U12 = "Under 12";
    const AGE_GROUP_U19G = "Under 19 Girls";
    const AGE_GROUP_U18G = "Under 18 Girls";
    const AGE_GROUP_U15G = "Under 15 Girls";
    const AGE_GROUP_U12G = "Under 12 Girls";
    const AGE_GROUP_YG = "Youth Girls";
    const AGE_GROUP_JG = "Junior Girls";


    const REGION_GEELONG = "Geelong";
    const REGION_COLAC = "Colac";
    const YEAR_2015 = 2015;
    const YEAR_2017 = 2017;
    const YEAR_2018 = 2018;


    public function setUp() {
        $this->resetInstance();
        //TODO: Add class variable initialisation and check here, so we can run this code only once, to save time
        $host = 'http://localhost:4444/wd/hub'; // this is the default. If I get a JSON decoding error, try adding or removing /wd/hub/
        $options = new ChromeOptions();
        //Set chrome as headless
        $options->addArguments(['--headless', 'window-size=1024,768']);

        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);

        $this->driver = RemoteWebDriver::create($host, $capabilities);

        $this->CI->load->library('UI_test_expected_results');
    }

    public function tearDown() {
        //TODO check for initialised variable from before.
        $this->driver->close();
    }

    private function login() {

        $this->driver->get("http://localhost:8888/umpire/");
        $this->driver->findElement(WebDriverBy::id("username"))->sendKeys("bbrumm");
        $this->driver->findElement(WebDriverBy::id("password"))->sendKeys("bbrumm2017");

        $this->clickElement('loginBtn');

    }

    private function getElement($pElementID) {
        return $this->driver->findElement(WebDriverBy::id($pElementID));
    }

    private function getElementByXpath($pXpath) {
        return $this->driver->findElement(WebDriverBy::xpath($pXpath));
    }

    private function getMultipleElementsBYXpath($pXpath) {
        return $this->driver->findElements(WebDriverBy::xpath($pXpath));
    }

    private function clickElement($pElementID) {
        $this->driver->findElement(WebDriverBy::id($pElementID))->click();
    }


    private function getColumnHeaders($pHeaderRowNumber) {
        return $this->getMultipleElementsBYXpath("//table/thead[2]/tr[".$pHeaderRowNumber."]/th");
    }

    private function getTableData() {
        return $this->getMultipleElementsBYXpath("//*[@id='reportTable']/tbody");
    }

    private function convertTableDataElementsToTable($pElements) {
        $outputArray = array();

        $rowsInTable = $pElements[0]->findElements(WebDriverBy::tagName("tr"));
        $rowCount = count($rowsInTable);
        for($rowNumber = 0; $rowNumber < $rowCount; $rowNumber++) {
            $cellsInRow = $rowsInTable[$rowNumber]->findElements(WebDriverBy::tagName("td"));
            $columnCount = count($cellsInRow);

            for($columnNumber = 0; $columnNumber < $columnCount; $columnNumber++) {
                $outputArray[$rowNumber][$columnNumber] = $cellsInRow[$columnNumber]->getText(); //TODO: this is a slow step, or the fact it loops makes it slow
            }
        }
        return $outputArray;
    }

    private function assertUmpireDisciplineIsCorrect($pUmpireDiscipline) {
        $actualUmpireDiscipline = $this->getElement("reportInfoUmpireDiscipline")->getText();
        $this->assertEquals("Umpire Discipline: ". $pUmpireDiscipline, $actualUmpireDiscipline);
    }

    private function assertLeagueIsCorrect($pLeague) {
        $actualLeague = $this->getElement("reportInfoLeague")->getText();
        $this->assertEquals("League: ". $pLeague, $actualLeague);
    }

    private function assertAgeGroupIsCorrect($pAgeGroup) {
        $actualAgeGroup = $this->getElement("reportInfoAgeGroup")->getText();
        $this->assertEquals("Age Group: ". $pAgeGroup, $actualAgeGroup);
    }

    private function assertSearchRowIsShown() {
        $actualSearchRow = $this->getElement("searchForRow")->getText();
        $this->assertEquals("Search for Row", $actualSearchRow);
        $actualSearchTextbox = $this->getElement("search");
        $this->assertTrue($actualSearchTextbox->isDisplayed());
    }


    private function assertColumnHeadersAreCorrect($pExpectedColumnHeadersFirstRow, $pExpectedColumnHeadersSecondRow) {
        $this->assertSingleRowOfHeaderColumns(1, $pExpectedColumnHeadersFirstRow);
        $this->assertSingleRowOfHeaderColumns(2, $pExpectedColumnHeadersSecondRow);
        /*
        $reportTableHeader = $this->getColumnHeaders(1);
        $this->assertEquals($pExpectedColumnHeadersFirstRow[0], $reportTableHeader[0]->getText());
        $this->assertEquals($pExpectedColumnHeadersFirstRow[1], $reportTableHeader[1]->getText());

        //Get second row headers
        $reportTableHeaderElements = $this->getColumnHeaders(2);

        $countHeaderCells = count($reportTableHeaderElements);
        for($i=0; $i<$countHeaderCells; $i++) {
            $this->assertEquals(
                $pExpectedColumnHeadersSecondRow[$i],
                $reportTableHeaderElements[$i]->getText()
            );
        }
        */
    }

    private function assertSingleRowOfHeaderColumns($pHeaderRowNumber, $pHeaderColumnValues) {
        $reportTableHeaderElements = $this->getColumnHeaders($pHeaderRowNumber);

        $countHeaderCells = count($reportTableHeaderElements);
        for($i=0; $i<$countHeaderCells; $i++) {
            $this->assertEquals(
                $pHeaderColumnValues[$i],
                $reportTableHeaderElements[$i]->getText()
            );
        }
    }

    private function assertTableDataIsCorrect($pExpectedTableData) {
        //Test each row is correct
        $actualTableDataElements = $this->getTableData();
        $actualTableData = $this->convertTableDataElementsToTable($actualTableDataElements);

        $this->assertEquals($pExpectedTableData, $actualTableData);
    }

    private function selectReportFromSelectionBox($pReportNumber) {
        $selectElement = $this->driver->findElement(WebDriverBy::id('reportName'));
        $select = new \Facebook\WebDriver\WebDriverSelect($selectElement);
        $select->selectByValue($pReportNumber);
    }

    private function selectReportYear($pYear) {
        $selectElement = $this->driver->findElement(WebDriverBy::name('season'));
        $select = new \Facebook\WebDriver\WebDriverSelect($selectElement);
        $select->selectByValue($pYear);
    }

    public function test_DisplayReport1() {
        $uiExpectedResults = new UI_test_expected_results();
        $this->login();

        //Make checkbox selections
        $this->clickElement($this::LEAGUE_BFL);
        $this->clickElement($this::UMP_TYPE_FIELD);
        $this->clickElement($this::AGE_GROUP_SENIORS);

        //Click "View Report"
        $this->clickElement("viewReport");

        //Assert page
        $this->assertUmpireDisciplineIsCorrect($this::UMP_TYPE_FIELD);
        $this->assertLeagueIsCorrect($this::LEAGUE_BFL);
        $this->assertAgeGroupIsCorrect($this::AGE_GROUP_SENIORS);
        $this->assertSearchRowIsShown();

        //Assert table
        $expectedSecondRowHeaders = array(
            '',
            'Anglesea',
            'Barwon Heads',
            'Drysdale',
            'Geelong Amateur',
            'Modewarre',
            'Newcomb Power',
            'Ocean Grove',
            'Portarlington',
            'Queenscliff',
            'Torquay'
        );

        $this->assertColumnHeadersAreCorrect(array("Name\nUmpire_Name_First_Last", $this::LEAGUE_BFL), $expectedSecondRowHeaders);
        $this->assertTableDataIsCorrect($uiExpectedResults::EXPECTED_DATA_REPORT1_TEST1);
    }

    public function test_DisplayReport2() {
        $uiExpectedResults = new UI_test_expected_results();
        $this->login();

        //Make checkbox selections
        $this->clickElement($this::LEAGUE_GFL);
        $this->clickElement($this::LEAGUE_BFL);
        $this->clickElement($this::UMP_TYPE_BOUNDARY);
        $this->clickElement($this::UMP_TYPE_FIELD);
        $this->clickElement($this::AGE_GROUP_SENIORS);
        $this->clickElement($this::AGE_GROUP_RESERVES);

        //Change report
        $this->selectReportFromSelectionBox(2);

        //Change year
        $this->selectReportYear($this::YEAR_2018);

        //Click "View Report"
        $this->clickElement("viewReport");

        //Assert page
        $this->assertUmpireDisciplineIsCorrect($this::UMP_TYPE_FIELD . ", " . $this::UMP_TYPE_BOUNDARY);
        $this->assertLeagueIsCorrect($this::LEAGUE_BFL . ", ". $this::LEAGUE_GFL);
        $this->assertAgeGroupIsCorrect($this::AGE_GROUP_SENIORS . ", ". $this::AGE_GROUP_RESERVES);
        $this->assertSearchRowIsShown();

        //Assert table
        $this->assertColumnHeadersAreCorrect(
            array("Name", $this::AGE_GROUP_SENIORS, $this::AGE_GROUP_RESERVES, 'Total'),
            array('', $this::LEAGUE_BFL, $this::LEAGUE_GFL, '2 Umpires', $this::LEAGUE_BFL, $this::LEAGUE_GFL, '')
        );
        $this->assertTableDataIsCorrect($uiExpectedResults::EXPECTED_DATA_REPORT2_TEST1); //
    }

    public function test_DisplayReport3_Geelong() {
        $uiExpectedResults = new UI_test_expected_results();
        $this->login();

        //Make checkbox selections
        $this->clickElement($this::REGION_GEELONG);

        //Change report
        $this->selectReportFromSelectionBox(3);

        //Change year
        $this->selectReportYear($this::YEAR_2017);

        //Click "View Report"
        $this->clickElement("viewReport");

        //Assert page
        //TODO: Fix this defect that adds an extra comma to the end when it shouldn't
        $this->assertUmpireDisciplineIsCorrect($this::UMP_TYPE_FIELD . ", " . $this::UMP_TYPE_BOUNDARY . ", " . $this::UMP_TYPE_GOAL. ",");
        $this->assertLeagueIsCorrect($this::LEAGUE_BFL . ", ". $this::LEAGUE_GFL . ", ". $this::LEAGUE_GDFL . ", ". $this::LEAGUE_GJFL . ", ". $this::LEAGUE_CDFNL . ", ". $this::LEAGUE_WOMEN . ",");
        $this->assertAgeGroupIsCorrect($this::AGE_GROUP_SENIORS . ", ". $this::AGE_GROUP_RESERVES . ", ".
            $this::AGE_GROUP_COLTS . ", ". $this::AGE_GROUP_U19 . ", ". $this::AGE_GROUP_U18 . ", ".
            $this::AGE_GROUP_U175 . ", ". $this::AGE_GROUP_U17 . ", ". $this::AGE_GROUP_U16 . ", ".
            $this::AGE_GROUP_U15 . ", ". $this::AGE_GROUP_U145 . ", ". $this::AGE_GROUP_U14 . ", ".
            $this::AGE_GROUP_U13 . ", ". $this::AGE_GROUP_U12 . ", ". $this::AGE_GROUP_U19G . ", ".
            $this::AGE_GROUP_U18G . ", ". $this::AGE_GROUP_U15G . ", ". $this::AGE_GROUP_U12G . ", ".
            $this::AGE_GROUP_YG . ", ". $this::AGE_GROUP_JG . ",");
        $this->assertSearchRowIsShown();

        //Assert table
        $this->assertColumnHeadersAreCorrect(
            array("Week (Sat)", "No Seniors Boundary", "No Seniors Goal", "No Reserves Goal", "No Colts Field", "No Under 16 Field", "No Under 14 Field", "No Under 12 Field"),
            array('', $this::LEAGUE_BFL, $this::LEAGUE_GDFL, $this::LEAGUE_GFL, "Total", $this::LEAGUE_WOMEN,
                $this::LEAGUE_BFL, $this::LEAGUE_GDFL, $this::LEAGUE_GFL, "Total", $this::LEAGUE_WOMEN,
                $this::LEAGUE_BFL, $this::LEAGUE_GDFL, $this::LEAGUE_GFL, "Total",
                $this::LEAGUE_GJFL, "Total", $this::LEAGUE_GJFL, "Total", $this::LEAGUE_GJFL, "Total", $this::LEAGUE_GJFL, "Total")
        );

        $this->assertTableDataIsCorrect($uiExpectedResults::EXPECTED_DATA_REPORT3_TEST1);
    }

    public function test_DisplayReport3_Colac() {
        $uiExpectedResults = new UI_test_expected_results();
        $this->login();

        //Make checkbox selections
        $this->clickElement($this::REGION_COLAC);

        //Change report
        $this->selectReportFromSelectionBox(3);

        //Change year
        $this->selectReportYear($this::YEAR_2017);

        //Click "View Report"
        $this->clickElement("viewReport");

        //Assert page
        //TODO: Fix this defect that adds an extra comma to the end when it shouldn't
        $this->assertUmpireDisciplineIsCorrect($this::UMP_TYPE_FIELD . ", " . $this::UMP_TYPE_BOUNDARY . ", " . $this::UMP_TYPE_GOAL. ",");
        $this->assertLeagueIsCorrect($this::LEAGUE_BFL . ", ". $this::LEAGUE_GFL . ", ". $this::LEAGUE_GDFL . ", ". $this::LEAGUE_GJFL . ", ". $this::LEAGUE_CDFNL . ", ". $this::LEAGUE_WOMEN . ",");
        $this->assertAgeGroupIsCorrect($this::AGE_GROUP_SENIORS . ", ". $this::AGE_GROUP_RESERVES . ", ".
            $this::AGE_GROUP_COLTS . ", ". $this::AGE_GROUP_U19 . ", ". $this::AGE_GROUP_U18 . ", ".
            $this::AGE_GROUP_U175 . ", ". $this::AGE_GROUP_U17 . ", ". $this::AGE_GROUP_U16 . ", ".
            $this::AGE_GROUP_U15 . ", ". $this::AGE_GROUP_U145 . ", ". $this::AGE_GROUP_U14 . ", ".
            $this::AGE_GROUP_U13 . ", ". $this::AGE_GROUP_U12 . ", ". $this::AGE_GROUP_U19G . ", ".
            $this::AGE_GROUP_U18G . ", ". $this::AGE_GROUP_U15G . ", ". $this::AGE_GROUP_U12G . ", ".
            $this::AGE_GROUP_YG . ", ". $this::AGE_GROUP_JG . ",");
        $this->assertSearchRowIsShown();

        //Assert table
        $this->assertColumnHeadersAreCorrect(
            array("Week (Sat)", "No Seniors Boundary", "No Seniors Goal", "No Reserves Goal", "No Colts Field", "No Under 16 Field", "No Under 14 Field", "No Under 12 Field"),
            array('', $this::LEAGUE_CDFNL, "Total", $this::LEAGUE_CDFNL, "Total", $this::LEAGUE_CDFNL, "Total",
                "Total", "Total", "Total", "Total")
        );

        $this->assertTableDataIsCorrect($uiExpectedResults::EXPECTED_DATA_REPORT3_TEST2);
    }

    public function test_DisplayReport4_Geelong() {
        $uiExpectedResults = new UI_test_expected_results();
        $this->login();

        //Make checkbox selections
        $this->clickElement($this::REGION_GEELONG);

        //Change report
        $this->selectReportFromSelectionBox(4);

        //Change year
        $this->selectReportYear($this::YEAR_2017);

        //Click "View Report"
        $this->clickElement("viewReport");

        //Assert page
        //TODO: Fix this defect that adds an extra comma to the end when it shouldn't
        $this->assertUmpireDisciplineIsCorrect($this::UMP_TYPE_FIELD . ", " . $this::UMP_TYPE_BOUNDARY . ", " . $this::UMP_TYPE_GOAL. ",");
        $this->assertLeagueIsCorrect($this::LEAGUE_BFL . ", ". $this::LEAGUE_GFL . ", ". $this::LEAGUE_GDFL . ", ". $this::LEAGUE_GJFL . ", ". $this::LEAGUE_CDFNL . ", ". $this::LEAGUE_WOMEN . ",");
        $this->assertAgeGroupIsCorrect($this::AGE_GROUP_SENIORS . ", ". $this::AGE_GROUP_RESERVES . ", ".
            $this::AGE_GROUP_COLTS . ", ". $this::AGE_GROUP_U19 . ", ". $this::AGE_GROUP_U18 . ", ".
            $this::AGE_GROUP_U175 . ", ". $this::AGE_GROUP_U17 . ", ". $this::AGE_GROUP_U16 . ", ".
            $this::AGE_GROUP_U15 . ", ". $this::AGE_GROUP_U145 . ", ". $this::AGE_GROUP_U14 . ", ".
            $this::AGE_GROUP_U13 . ", ". $this::AGE_GROUP_U12 . ", ". $this::AGE_GROUP_U19G . ", ".
            $this::AGE_GROUP_U18G . ", ". $this::AGE_GROUP_U15G . ", ". $this::AGE_GROUP_U12G . ", ".
            $this::AGE_GROUP_YG . ", ". $this::AGE_GROUP_JG . ",");
        $this->assertSearchRowIsShown();

        //Assert table
        $this->assertColumnHeadersAreCorrect(
            array("Club", $this::UMP_TYPE_BOUNDARY, $this::UMP_TYPE_FIELD, $this::UMP_TYPE_GOAL),
            array('', $this::AGE_GROUP_SENIORS, $this::AGE_GROUP_RESERVES, $this::AGE_GROUP_COLTS,
                $this::AGE_GROUP_U19, $this::AGE_GROUP_U16, $this::AGE_GROUP_U14,
                $this::AGE_GROUP_U12, $this::AGE_GROUP_U19G, $this::AGE_GROUP_U15G, $this::AGE_GROUP_U12G,
                $this::AGE_GROUP_SENIORS, $this::AGE_GROUP_RESERVES, $this::AGE_GROUP_COLTS,
                $this::AGE_GROUP_U16, $this::AGE_GROUP_U12, $this::AGE_GROUP_U19G, $this::AGE_GROUP_U12G,
                $this::AGE_GROUP_SENIORS, $this::AGE_GROUP_RESERVES, $this::AGE_GROUP_COLTS,
                $this::AGE_GROUP_U16, $this::AGE_GROUP_U14,
                $this::AGE_GROUP_U12, $this::AGE_GROUP_U19G, $this::AGE_GROUP_U15G, $this::AGE_GROUP_U12G),
            array('', $this::LEAGUE_BFL, $this::LEAGUE_GDFL, $this::LEAGUE_WOMEN,
                $this::LEAGUE_BFL, $this::LEAGUE_GFL, $this::LEAGUE_GDFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL,
                $this::LEAGUE_GJFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL,
                $this::LEAGUE_GJFL, $this::LEAGUE_WOMEN,
                $this::LEAGUE_BFL, $this::LEAGUE_GFL, $this::LEAGUE_GDFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL,
                $this::LEAGUE_GJFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL,
                $this::LEAGUE_BFL, $this::LEAGUE_WOMEN,
                $this::LEAGUE_BFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL,
                $this::LEAGUE_GJFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL, $this::LEAGUE_GJFL)
        );

        $this->assertTableDataIsCorrect($uiExpectedResults::EXPECTED_DATA_REPORT4_TEST1);
    }

    public function test_DisplayReport4_Colac() {
        $uiExpectedResults = new UI_test_expected_results();
        $this->login();

        //Make checkbox selections
        $this->clickElement($this::REGION_COLAC);

        //Change report
        $this->selectReportFromSelectionBox(4);

        //Change year
        $this->selectReportYear($this::YEAR_2017);

        //Click "View Report"
        $this->clickElement("viewReport");

        //Assert page
        //TODO: Fix this defect that adds an extra comma to the end when it shouldn't
        $this->assertUmpireDisciplineIsCorrect($this::UMP_TYPE_FIELD . ", " . $this::UMP_TYPE_BOUNDARY . ", " . $this::UMP_TYPE_GOAL. ",");
        $this->assertLeagueIsCorrect($this::LEAGUE_BFL . ", ". $this::LEAGUE_GFL . ", ". $this::LEAGUE_GDFL . ", ". $this::LEAGUE_GJFL . ", ". $this::LEAGUE_CDFNL . ", ". $this::LEAGUE_WOMEN . ",");
        $this->assertAgeGroupIsCorrect($this::AGE_GROUP_SENIORS . ", ". $this::AGE_GROUP_RESERVES . ", ".
            $this::AGE_GROUP_COLTS . ", ". $this::AGE_GROUP_U19 . ", ". $this::AGE_GROUP_U18 . ", ".
            $this::AGE_GROUP_U175 . ", ". $this::AGE_GROUP_U17 . ", ". $this::AGE_GROUP_U16 . ", ".
            $this::AGE_GROUP_U15 . ", ". $this::AGE_GROUP_U145 . ", ". $this::AGE_GROUP_U14 . ", ".
            $this::AGE_GROUP_U13 . ", ". $this::AGE_GROUP_U12 . ", ". $this::AGE_GROUP_U19G . ", ".
            $this::AGE_GROUP_U18G . ", ". $this::AGE_GROUP_U15G . ", ". $this::AGE_GROUP_U12G . ", ".
            $this::AGE_GROUP_YG . ", ". $this::AGE_GROUP_JG . ",");
        $this->assertSearchRowIsShown();

        //Assert table
        $this->assertColumnHeadersAreCorrect(
            array("Club", $this::UMP_TYPE_BOUNDARY, $this::UMP_TYPE_FIELD, $this::UMP_TYPE_GOAL),
            array('', $this::AGE_GROUP_RESERVES, $this::AGE_GROUP_U175, $this::AGE_GROUP_U145,
                $this::AGE_GROUP_RESERVES, $this::AGE_GROUP_U175, $this::AGE_GROUP_U145,
                $this::AGE_GROUP_SENIORS, $this::AGE_GROUP_RESERVES, $this::AGE_GROUP_U175, $this::AGE_GROUP_U145),
            array('', $this::LEAGUE_CDFNL, $this::LEAGUE_CDFNL, $this::LEAGUE_CDFNL, $this::LEAGUE_CDFNL,
                $this::LEAGUE_CDFNL, $this::LEAGUE_CDFNL, $this::LEAGUE_CDFNL, $this::LEAGUE_CDFNL,
                $this::LEAGUE_CDFNL, $this::LEAGUE_CDFNL)
        );

        $this->assertTableDataIsCorrect($uiExpectedResults::EXPECTED_DATA_REPORT4_TEST2);
    }

    public function test_DisplayReport5_Geelong() {
        $uiExpectedResults = new UI_test_expected_results();
        $this->login();

        //Make checkbox selections
        $this->clickElement($this::REGION_GEELONG);

        //Change report
        $this->selectReportFromSelectionBox(5);

        //Change year
        $this->selectReportYear($this::YEAR_2017);

        //Click "View Report"
        $this->clickElement("viewReport");

        //Assert page
        //TODO: Fix this defect that adds an extra comma to the end when it shouldn't
        $this->assertUmpireDisciplineIsCorrect($this::UMP_TYPE_FIELD . ", " . $this::UMP_TYPE_BOUNDARY . ", " . $this::UMP_TYPE_GOAL. ",");
        $this->assertLeagueIsCorrect($this::LEAGUE_BFL . ", ". $this::LEAGUE_GFL . ", ". $this::LEAGUE_GDFL . ", ". $this::LEAGUE_GJFL . ", ". $this::LEAGUE_CDFNL . ", ". $this::LEAGUE_WOMEN . ",");
        $this->assertAgeGroupIsCorrect($this::AGE_GROUP_SENIORS . ", ". $this::AGE_GROUP_RESERVES . ", ".
            $this::AGE_GROUP_COLTS . ", ". $this::AGE_GROUP_U19 . ", ". $this::AGE_GROUP_U18 . ", ".
            $this::AGE_GROUP_U175 . ", ". $this::AGE_GROUP_U17 . ", ". $this::AGE_GROUP_U16 . ", ".
            $this::AGE_GROUP_U15 . ", ". $this::AGE_GROUP_U145 . ", ". $this::AGE_GROUP_U14 . ", ".
            $this::AGE_GROUP_U13 . ", ". $this::AGE_GROUP_U12 . ", ". $this::AGE_GROUP_U19G . ", ".
            $this::AGE_GROUP_U18G . ", ". $this::AGE_GROUP_U15G . ", ". $this::AGE_GROUP_U12G . ", ".
            $this::AGE_GROUP_YG . ", ". $this::AGE_GROUP_JG . ",");
        $this->assertSearchRowIsShown();

        //Assert table
        $this->assertColumnHeadersAreCorrect(
            array("Discipline", "Age Group", $this::LEAGUE_BFL, $this::LEAGUE_GDFL, $this::LEAGUE_GFL,
                $this::LEAGUE_GJFL, $this::LEAGUE_WOMEN, "All"),
            array("", "", "Games", "Total", "Pct", "Games", "Total", "Pct", "Games", "Total", "Pct",
                "Games", "Total", "Pct", "Games", "Total", "Pct", "Total")
        );

        $this->assertTableDataIsCorrect($uiExpectedResults::EXPECTED_DATA_REPORT5_TEST1);
    }

    public function test_DisplayReport5_Colac() {
        $uiExpectedResults = new UI_test_expected_results();
        $this->login();

        //Make checkbox selections
        $this->clickElement($this::REGION_COLAC);

        //Change report
        $this->selectReportFromSelectionBox(5);

        //Change year
        $this->selectReportYear($this::YEAR_2017);

        //Click "View Report"
        $this->clickElement("viewReport");

        //Assert page
        //TODO: Fix this defect that adds an extra comma to the end when it shouldn't
        $this->assertUmpireDisciplineIsCorrect($this::UMP_TYPE_FIELD . ", " . $this::UMP_TYPE_BOUNDARY . ", " . $this::UMP_TYPE_GOAL. ",");
        $this->assertLeagueIsCorrect($this::LEAGUE_BFL . ", ". $this::LEAGUE_GFL . ", ". $this::LEAGUE_GDFL . ", ". $this::LEAGUE_GJFL . ", ". $this::LEAGUE_CDFNL . ", ". $this::LEAGUE_WOMEN . ",");
        $this->assertAgeGroupIsCorrect($this::AGE_GROUP_SENIORS . ", ". $this::AGE_GROUP_RESERVES . ", ".
            $this::AGE_GROUP_COLTS . ", ". $this::AGE_GROUP_U19 . ", ". $this::AGE_GROUP_U18 . ", ".
            $this::AGE_GROUP_U175 . ", ". $this::AGE_GROUP_U17 . ", ". $this::AGE_GROUP_U16 . ", ".
            $this::AGE_GROUP_U15 . ", ". $this::AGE_GROUP_U145 . ", ". $this::AGE_GROUP_U14 . ", ".
            $this::AGE_GROUP_U13 . ", ". $this::AGE_GROUP_U12 . ", ". $this::AGE_GROUP_U19G . ", ".
            $this::AGE_GROUP_U18G . ", ". $this::AGE_GROUP_U15G . ", ". $this::AGE_GROUP_U12G . ", ".
            $this::AGE_GROUP_YG . ", ". $this::AGE_GROUP_JG . ",");
        $this->assertSearchRowIsShown();

        //Assert table
        $this->assertColumnHeadersAreCorrect(
            array("Discipline", "Age Group", $this::LEAGUE_CDFNL, "All"),
            array("", "", "Games", "Total", "Pct", "Total")
        );

        $this->assertTableDataIsCorrect($uiExpectedResults::EXPECTED_DATA_REPORT5_TEST2);
    }

    public function test_DisplayReport6_Geelong() {
        $uiExpectedResults = new UI_test_expected_results();
        $this->login();

        //Make checkbox selections
        $this->clickElement($this::REGION_GEELONG);

        //Make checkbox selections
        $this->clickElement($this::LEAGUE_BFL);
        $this->clickElement($this::UMP_TYPE_FIELD);
        $this->clickElement($this::AGE_GROUP_RESERVES);

        //Change report
        $this->selectReportFromSelectionBox(6);

        //Change year
        $this->selectReportYear($this::YEAR_2017);

        //Click "View Report"
        $this->clickElement("viewReport");

        //Assert page
        //TODO: Fix this defect that adds an extra comma to the end when it shouldn't
        $this->assertUmpireDisciplineIsCorrect($this::UMP_TYPE_FIELD );
        $this->assertLeagueIsCorrect($this::LEAGUE_BFL);
        $this->assertAgeGroupIsCorrect($this::AGE_GROUP_RESERVES);
        $this->assertSearchRowIsShown();

        //Assert table
        $this->assertSingleRowOfHeaderColumns(
            1, $uiExpectedResults::EXPECTED_DATA_REPORT6_COLHEADERS
        );

        $this->assertTableDataIsCorrect($uiExpectedResults::EXPECTED_DATA_REPORT6_TEST1);
    }

}