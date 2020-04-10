<?php
use Facebook\WebDriver\Chrome\ChromeDriver;
use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;


class Report1_UI_test extends TestCase
{

    const LEAGUE_BFL = "BFL";
    const UMP_TYPE_FIELD = "Field";
    const AGE_GROUP_SENIORS = "Seniors";
    const YEAR_2015 = 2015;

    public function setUp() {
        //TODO: Add class variable initialisation and check here, so we can run this code only once, to save time
        $host = 'http://localhost:4444/'; // this is the default
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
                $outputArray[$rowNumber][$columnNumber] = $cellsInRow[$columnNumber]->getText();
            }
        }
        return $outputArray;
    }

    public function test_DisplayReport1() {

        $this->login();

        //Make checkbox selections
        $this->clickElement($this::LEAGUE_BFL);
        $this->clickElement($this::UMP_TYPE_FIELD);
        $this->clickElement($this::AGE_GROUP_SENIORS);

        //Click "View Report"
        $this->clickElement("viewReport");

        /*
        $this->driver->wait()->until(
            WebDriverExpectedCondition::elementTextContains(WebDriverBy::id('mainHeading'), '01 - Umpires and Clubs')
        );
        */

        //Assert page
        $actualUmpireDiscipline = $this->getElement("reportInfoUmpireDiscipline")->getText();
        $this->assertEquals("Umpire Discipline: ". $this::UMP_TYPE_FIELD, $actualUmpireDiscipline);

        $actualLeague = $this->getElement("reportInfoLeague")->getText();
        $this->assertEquals("League: ". $this::LEAGUE_BFL, $actualLeague);

        $actualAgeGroup = $this->getElement("reportInfoAgeGroup")->getText();
        $this->assertEquals("Age Group: ". $this::AGE_GROUP_SENIORS, $actualAgeGroup);

        //Test search for row is shown
        $actualSearchRow = $this->getElement("searchForRow")->getText();
        $this->assertEquals("Search for Row", $actualSearchRow);

        $actualSearchTextbox = $this->getElement("search");

        $this->assertTrue($actualSearchTextbox->isDisplayed());

        //Test table:
        $reportTableFirstColHeader = $this->getElementByXpath("//table/thead[2]/tr/th")->getText();
        $this->assertEquals("Name\nUmpire_Name_First_Last", $reportTableFirstColHeader);

        //TODO: Now test rows in the table match the expected output
        $reportTableHeader = $this->getColumnHeaders(1);
        $this->assertEquals("Name\nUmpire_Name_First_Last", $reportTableHeader[0]->getText());
        $this->assertEquals($this::LEAGUE_BFL, $reportTableHeader[1]->getText());

        //Get second row headers
        $reportTableHeaderElements = $this->getColumnHeaders(2);

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

        $countHeaderCells = count($reportTableHeaderElements);
        for($i=0; $i<$countHeaderCells; $i++) {
            $this->assertEquals(
                $expectedSecondRowHeaders[$i],
                $reportTableHeaderElements[$i]->getText()
            );
        }

        //Test each row is correct
        $expectedTableData = array(
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

        $actualTableDataElements = $this->getTableData();
        $actualTableData = $this->convertTableDataElementsToTable($actualTableDataElements);

        $this->assertEquals($expectedTableData, $actualTableData);

    }



}