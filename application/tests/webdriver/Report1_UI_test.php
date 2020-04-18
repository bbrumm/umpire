<?php
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;


class Report1_UI_test extends TestCase
{

    const LEAGUE_BFL = "BFL";
    const LEAGUE_GFL = "GFL";
    const UMP_TYPE_FIELD = "Field";
    const UMP_TYPE_BOUNDARY = "Boundary";
    const AGE_GROUP_SENIORS = "Seniors";
    const AGE_GROUP_RESERVES = "Reserves";
    const YEAR_2015 = 2015;
    const YEAR_2018 = 2018;

    const EXPECTED_DATA_REPORT1_TEST1 = array(
        array('Abbott, Trevor', 1, '', '', '', '', '', '', '', '', 1),
        array('Abrehart, Jack', 1, '', '', 1, 1, '', 1, '', '', ''),
        array('Abrehart, Tom', '', 1, '', 1, '', '', '', '', '', 2),
        array('Arnott, Tim', '', '', '', 1, '', '', 1, '', '', ''),
        array('Baensch, Darren', 1, 2, '', 1, 2, '', '', '', 1, 1),
        array('Barton, Lawrie', 2, 4, 4, 2, 3, 1, 2, 1, '', 1),
        array('Bennett, Ross', 2, 2, 1, 2, 1, 1, '', '', '', 1),
        array('Beveridge, Brendan', '', '', 1, '', '', '', '', 1, '', ''),
        array('Binyon, Callum', '', '', 1, '', '', '', '', '', 1, ''),
        array('Boom, Marcus', 1, '', 2, '', 1, 1, 2, 1, 1, 1),
        array('Brown, Joshua', 1, 1, 1, 2, '', 1, 1, 1, 2, ''),
        array('Burke, Luke', '', 1, '', 1, '', 1, '', '', 1, 2),
        array('Bury, Nathan', 1, '', 1, '', '', '', 2, '', '', ''),
        array('Bushfield, Mark', '', 1, '', '', 1, '', '', '', '', ''),
        array('Callander, Timothy', 1, 1, 1, '', '', 1, 1, 1, '', ''),
        array('Carruthers, Christopher', '', 1, 3, 1, 1, '', '', 1, '', 1),
        array('Chaston, David', 3, 1, '', '', 1, '', 2, 1, 1, 3),
        array('Chaston, Samuel', '', 3, '', 1, '', 1, 2, 1, '', ''),
        array('Crucitti, Jess', '', '', 1, '', 1, 1, '', '', 1, 2),
        array('Danaher, Ryan', 1, '', 1, '', 2, 1, 1, 1, 1, ''),
        array('Davison, Mark', '', 1, '', 3, '', 1, 2, '', 1, ''),
        array('Delaney, Emma', 1, 1, 1, '', 1, 1, 2, 1, 1, 1),
        array('Denney, Joshua', 2, 1, 1, '', 1, 1, 1, 1, 1, 1),
        array('Dorling, Daniel', '', '', 2, 1, 2, 1, '', '', '', ''),
        array('Elliott, Peter', '', '', 1, 1, 1, 1, '', 1, 1, 2),
        array('Fanning, Murray', '', 1, '', '', 1, '', '', '', '', ''),
        array('Formosa, Jason', '', '', 1, 1, '', '', 1, 1, '', ''),
        array('Frazer, David', '', '', 1, 1, '', 1, 3, 1, '', 1),
        array('Gahan, Darren', 1, 1, '', 2, '', 1, '', 3, '', ''),
        array('Gray, Mitch', '', '', 1, '', '', '', 1, 1, 1, ''),
        array('Grossman, Graham', '', '', '', '', '', '', 1, '', 1, ''),
        array('Groves, Sam', 1, 1, 2, '', 1, 1, 1, '', 2, 1),
        array('Guarnaccia, Steve', '', 1, '', 1, '', '', '', '', '', ''),
        array('Harrison, William', '', 1, 3, '', '', '', 1, 1, '', ''),
        array('Jones, Adam', '', '', '', 1, 2, 1, '', '', 1, 1),
        array('Keating, Steve', 1, '', 1, '', 1, 1, '', 3, 1, ''),
        array('Kosmetschke, Luke', 2, '', 1, '', 1, '', '', 3, 1, 2),
        array('Kozina, Josip', '', '', '', 1, '', '', '', 1, '', ''),
        array('Lunt, Jordan', 3, 1, '', 2, 2, '', 2, 3, 4, 3),
        array('Maddock, David', 1, 1, '', 2, '', '', '', 1, 1, 2),
        array('Mccarthy, Luke', '', '', 1, 1, 3, '', '', 1, '', ''),
        array('Mcdonald, Stephen', '', 1, 1, 2, 1, '', 1, '', '', 4),
        array('Mcdowell, Paul', '', 1, 1, '', '', '', '', '', '', ''),
        array('Mcelhinney, Paul', '', 2, '', 3, 1, 1, 1, '', '', 4),
        array('Mckenzie, Rodney', 3, 1, 1, '', 2, 1, 1, '', 1, ''),
        array('Mcmaster, Damian', 3, 3, 1, '', 1, 1, 2, '', '', 1),
        array('Milligan, James', '', 1, '', '', 2, 1, 1, '', '', 1),
        array('Murnane, Nicholas', 1, '', '', '', 1, '', 1, '', '', 1),
        array('Noble, Reece', '', 1, '', 1, 1, 1, 1, 1, '', ''),
        array('Nolan, Mark', 2, 3, 2, 4, 2, 1, 1, 5, 3, 1),
        array('Oldfield, Craig', 1, '', '', '', '', 2, 1, 2, '', 2),
        array('Palmer, Anthony', 1, '', '', '', '', '', '', '', '', 1),
        array('Parrello, Dean', 1, 2, '', 2, '', 2, 2, 1, '', ''),
        array('Place, Cameron', 1, 3, 1, 1, 2, 3, 2, '', 4, 3),
        array('Reid, Davin', '', 2, '', 2, '', '', '', '', '', 2),
        array('Riches, Aaron', '', '', '', 1, '', '', '', '', '', 1),
        array('Robertson, Joshua', 1, '', 2, 1, '', 1, 2, 2, 1, ''),
        array('Rooke, Lachlan', 1, '', 1, 1, 1, 1, 1, 1, 1, 2),
        array('Ross-watson, Nicholas', 1, 2, '', '', '', 1, 2, 1, 3, ''),
        array('Smith, Bradley', 2, '', 2, 3, 2, 2, '', 1, 1, 1),
        array('Stephenson, Adrian', '', 3, '', 2, '', '', 2, '', 1, ''),
        array('Tatnell, John', 1, '', 1, 1, 1, '', 1, 1, 1, 3),
        array('Verdichizzi, Jess', 2, 3, '', 3, '', '', 1, '', 4, 1),
        array('Ververs, Jack', '', '', 1, '', '', '', '', '', 1, ''),
        array('Ververs, Trent', '', '', '', 1, '', 1, 1, '', '', 1),
        array('Waight, Jarrod', '', 1, 1, '', '', '', 1, 1, '', ''),
        array('Watson, Nick', 2, 1, 3, 2, 2, 1, '', 2, 2, 1),
        array('West, Peter', 1, '', '', '', 1, '', '', '', 2, '')
    );

    const EXPECTED_DATA_REPORT2_TEST1 = array(
        array('Abbott, Trevor', 7, 1, 1, '', '', 8),
        array('Abrehart, Jack', '', 2, '', '', '', 2),
        array('Allcorn, Mason', 3, 2, '', '', '', 5),
        array('Amisse, Samira', 7, 1, '', '', '', 8),
        array('Armstrong, Dean', 3, '', '', '', '', 3),
        array('Armstrong, Wayne', 2, 9, '', '', '', 11),
        array('Ash, Bailey', 2, '', '', '', 1, 3),
        array('Barnett, Charlie', 2, '', '', '', '', 2),
        array('Barrand, Michael', '', 3, '', '', '', 3),
        array('Bell, Jack', 1, '', '', '', '', 1),
        array('Beste, Flynn', 3, '', '', '', '', 3),
        array('Beveridge, Brendan', 2, '', '', '', '', 2),
        array('Bisinella, Alex', 2, 2, '', '', '', 4),
        array('Bisinella, Tiana', 4, 2, '', '', '', 6),
        array('Blyton, Ezekiel', 1, 8, '', '', '', 9),
        array('Blyton, Melody', '', '', '', 3, '', 3),
        array('Boom, Marcus', 5, 6, 1, '', '', 11),
        array('Boseley, Isaac', '', '', '', 1, 1, 2),
        array('Brayshaw, James', 3, 7, '', '', '', 10),
        array('Brew, Alana', 5, 4, '', '', '', 9),
        array('Brew, Kaylie', 6, 5, '', '', '', 11),
        array('Burns, Noah', 4, '', '', '', '', 4),
        array('Cain, Jack', '', 6, '', '', '', 6),
        array('Callander, Timothy', 4, 8, '', '', '', 12),
        array('Cannard, Patrick', 2, 4, '', '', '', 6),
        array('Carruthers, Chris', 6, 3, 1, 1, '', 10),
        array('Chaston, David', 3, 8, '', '', '', 11),
        array('Clissold, Kelvin', 1, '', '', '', '', 1),
        array('Coxon, Xavier', '', 3, '', '', '', 3),
        array('Crawford, Blake', 1, '', '', '', '', 1),
        array('Crocker, Oscar', 9, 4, '', '', '', 13),
        array('Crucitti, Jess', 3, 2, '', '', '', 5),
        array('Curtis, Harper', 1, '', '', 1, '', 2),
        array('Curtis, Tye', 1, '', '', 1, '', 2),
        array('Davison, Mark', 1, 9, '', '', '', 10),
        array('De Been, Rebecca', 3, '', '', '', '', 3),
        array('Dean, Darryl', 4, 1, '', '', '', 5),
        array('Deigan, Thomas', '', 1, '', '', '', 1),
        array('Dines, Jessica', 1, 2, '', '', '', 3),
        array('Dorling, Daniel', 9, '', 1, '', '', 9),
        array('Dougherty, Liam', 5, 1, '', '', '', 6),
        array('Dyer, Reuben', 6, 1, '', '', '', 7),
        array('Edwards, Callum', 2, 8, '', '', '', 10),
        array('Elliott, Peter', 4, 7, '', '', '', 11),
        array('Ellis, Hudson', 1, '', '', '', '', 1),
        array('Elvey, Brendan', 3, 2, 1, '', '', 5),
        array('Facey, Joshua', '', 2, '', '', '', 2),
        array('Ferguson, Christopher', 3, '', '', '', '', 3),
        array('Fox, Jake', '', '', '', 1, '', 1),
        array('Gahan, Darren', 2, '', '', '', '', 2),
        array('Geall, Cooper', 3, 5, '', '', '', 8),
        array('Gower, Harry', 2, 7, '', 1, '', 10),
        array('Graham, Peter', 2, '', '', '', '', 2),
        array('Grills, Ethan', 4, 1, '', '', '', 5),
        array('Grills, Jonathan', 4, '', '', '', '', 4),
        array('Grist, Lachlan', 4, 3, '', '', '', 7),
        array('Grossman, Graham', 2, '', '', '', '', 2),
        array('Guarnaccia, Steve', 3, 7, '', '', '', 10),
        array('Guy, Jaymee', 2, 10, '', '', '', 12),
        array('Hall, Corvan', '', 9, '', '', '', 9),
        array('Hamill-beach, Rhys', 3, 2, '', 1, '', 6),
        array('Hamilton, Brodey', '', 2, '', '', '', 2),
        array('Hamilton, Jake', 2, 9, '', '', '', 11),
        array('Harbison, David', 5, '', '', '', '', 5),
        array('Harrison, William', 2, '', '', '', '', 2),
        array('Hastie, Jack', 6, 7, '', '', '', 13),
        array('Hauser-teasdale, Christopher', 1, 6, '', '', '', 7),
        array('Helwig, Kieren', 5, 4, '', 1, '', 10),
        array('Hollis, Paul', 4, '', '', 1, 1, 6),
        array('Holmes-henley, Jackson', 6, '', '', '', '', 6),
        array('James, Joshua', 4, 5, 1, '', '', 9),
        array('Jenning, Jackson', 1, '', '', '', '', 1),
        array('Jones, Adam', 2, 7, 1, '', '', 9),
        array('Jones, Christopher', 4, 8, '', '', '', 12),
        array('Jones, Paul', 3, '', '', '', '', 3),
        array('Jose, Connor', 2, 4, '', '', '', 6),
        array('Keating, Steve', 1, '', '', '', '', 1),
        array('Kennedy, Lloyd', '', '', '', '', 13, 13),
        array('Kerr, Craig', '', 4, '', '', '', 4),
        array('Knight, Rob', 3, '', 1, '', '', 3),
        array('Laffy, Finn', 1, '', '', '', '', 1),
        array('Lawrence, Roy', 11, 3, '', 2, 1, 17),
        array('Lobbe, Dean', 14, '', '', '', '', 14),
        array('Lunt, Jordan', '', 1, '', '', '', 1),
        array('Lynch, Ashliegh', 2, '', '', '', '', 2),
        array('Lyon, James', '', 4, '', 1, '', 5),
        array('Martin, Angus', 2, '', '', '', '', 2),
        array('Martin, Harvey', 4, 10, '', '', '', 14),
        array('Mazaraki, Oscar', 2, 11, '', '', '', 13),
        array('Mccosh, Jason', '', 5, '', '', '', 5),
        array('Mcdonald, Charlie', 2, '', '', '', '', 2),
        array('Mcdonald, Stephen', 13, '', '', '', '', 13),
        array('Mcelhinney, Paul', 6, 7, '', '', '', 13),
        array('Mcglade, Aaron', 3, 9, 1, '', '', 12),
        array('Mcgrath, Caleb', 2, '', 1, 1, 1, 4),
        array('Mckenzie, Molly', 6, '', '', '', '', 6),
        array('Mckenzie, Rodney', 9, '', '', '', '', 9),
        array('Mcmaster, Damian', 7, 3, '', 1, '', 11),
        array('Menzies, Luke', 1, '', '', '', '', 1),
        array('Millard, Caleb', 1, '', '', '', '', 1),
        array('Milligan, James', 3, 9, '', '', '', 12),
        array('Moynahan, Ethan', 4, 4, '', '', '', 8),
        array('Nelson, Regan', '', 2, '', '', '', 2),
        array('Nisbet, William', 1, 6, '', '', '', 7),
        array('Nolan, Mark', 5, 5, '', '', '', 10),
        array('Nuessler, Peter', 7, 1, '', 1, '', 9),
        array('Nuessler, Thomas', '', '', '', 1, '', 1),
        array('O\'Dwyer, Bernard', 5, 8, 1, '', '', 13),
        array('O\'Neill, Jack', 3, 9, '', '', '', 12),
        array('O\'Neill, Ruby', 3, '', '', '', '', 3),
        array('Oldfield, Craig', 1, '', '', '', '', 1),
        array('Ozols, Peter', 1, '', '', '', '', 1),
        array('Palmer, Tony', 1, '', '', 1, '', 2),
        array('Peck, Jonathan', 5, 8, '', '', '', 13),
        array('Peeler, Benjamin', 5, 5, 2, '', '', 10),
        array('Phillips, Tarik', 7, '', '', '', '', 7),
        array('Plumridge, David', '', '', '', 1, '', 1),
        array('Pratt, Matthew', 7, 2, '', 1, '', 10),
        array('Previti, Frank', 5, '', '', 1, '', 6),
        array('Rakas-hoare, Brandon', 8, '', '', '', '', 8),
        array('Rankin, Bradley', 1, '', 1, '', 1, 2),
        array('Richardson, James', 1, 1, '', '', '', 2),
        array('Riches, Aaron', 5, 9, '', '', '', 14),
        array('Richmond-craig, Brandon', 2, 8, '', '', '', 10),
        array('Roberts, Trae', 2, 4, 1, '', '', 6),
        array('Robertson, Joshua', '', 1, '', '', '', 1),
        array('Robinson, Daniel', 3, 5, '', 2, '', 10),
        array('Rofe, William', 1, 7, '', '', '', 8),
        array('Ross, Will', 3, 10, '', '', '', 13),
        array('Scott, Leopold', '', 4, '', '', '', 4),
        array('Scott, Lionel', '', 3, '', '', '', 3),
        array('Shannon, Lykeira', 12, 2, '', '', '', 14),
        array('Sirolli, Christopher', 4, 1, '', '', '', 5),
        array('Smith, Bradley', 8, 2, 1, '', '', 10),
        array('Sykstus, Kyle', 2, 6, 1, '', '', 8),
        array('Tate, Benjamin', '', 5, '', '', '', 5),
        array('Tatnell, John', 7, 2, 1, 1, '', 10),
        array('Tattersall, Austin', 5, 7, 2, '', '', 12),
        array('Thornton, Jesse', 3, '', '', '', '', 3),
        array('Tingiri, Benjamin', 5, 8, '', '', '', 13),
        array('Tingiri, Timothy', 5, 8, 1, '', '', 13),
        array('Verdichizzi, Jess', 4, 8, 1, '', '', 12),
        array('Waight, Jarrod', 1, 10, '', '', '', 11),
        array('Wallis, Tomek', 3, 8, '', '', '', 11),
        array('Watson, Nick', 6, '', 1, '', '', 6),
        array('Weber, Joel', 1, 4, '', '', '', 5),
        array('Wekwerth, Karen', 3, '', '', '', '', 3),
        array('West, Peter', 1, 2, '', '', '', 3),
        array('Whelan, Gerard', 5, 8, '', '', '', 13),
        array('Williams, Daniel', '', 2, '', '', '', 2),
        array('Witham, Jye', 5, 3, 1, '', '', 8),
        array('Wood, Taleitha', 4, '', '', '', '', 4)
    );

    public function setUp() {
        //TODO: Add class variable initialisation and check here, so we can run this code only once, to save time
        $host = 'http://localhost:4444/wd/hub'; // this is the default. If I get a JSON decoding error, try adding or removing /wd/hub/
        $options = new ChromeOptions();
        //Set chrome as headless
        $options->addArguments(['--headless', 'window-size=1024,768']);

        $capabilities = DesiredCapabilities::chrome();
        $capabilities->setCapability(ChromeOptions::CAPABILITY, $options);


        $this->driver = RemoteWebDriver::create($host, $capabilities);
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
        $this->assertTableDataIsCorrect($this::EXPECTED_DATA_REPORT1_TEST1);
    }

    public function test_DisplayReport2() {
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
        $this->assertTableDataIsCorrect($this::EXPECTED_DATA_REPORT2_TEST1); //
    }



}