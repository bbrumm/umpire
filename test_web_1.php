<?php
namespace Facebook\WebDriver;

// An example of using php-webdriver.
// Do not forget to run composer install before. You must also have Selenium server started and listening on port 4444.

use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;

require_once('vendor/autoload.php');

echo "hello world<br/>";

$host = 'http://localhost:4444/';

echo "Host: " . $host . "<br/>";

$capabilities = DesiredCapabilities::chrome();

$driver = RemoteWebDriver::create($host, $capabilities);

// navigate to Selenium page on Wikipedia
$driver->get('https://en.wikipedia.org/wiki/Selenium_(software)');

// write 'PHP' in the search box
$driver->findElement(WebDriverBy::id('searchInput')) // find search input element
    ->sendKeys('PHP') // fill the search box
    ->submit(); // submit the whole form

// wait until 'PHP' is shown in the page heading element
$driver->wait()->until(
    WebDriverExpectedCondition::elementTextContains(WebDriverBy::id('firstHeading'), 'PHP')
);

// print title of the current page to output
echo "The title is '" . $driver->getTitle() . "'\n";

// print URL of current page to output
echo "The current URL is '" . $driver->getCurrentURL() . "'\n";

// find element of 'History' item in menu
$historyButton = $driver->findElement(
    WebDriverBy::cssSelector('#ca-history a')
);

// read text of the element and print it to output
echo "About to click to button with text: '" . $historyButton->getText() . "'\n";

// click the element to navigate to revision history page
$historyButton->click();

// wait until the target page is loaded
$driver->wait()->until(
    WebDriverExpectedCondition::titleContains('Revision history')
);

// print the title of the current page
echo "The title is '" . $driver->getTitle() . "'\n";

// print the URI of the current page

echo "The current URI is '" . $driver->getCurrentURL() . "'\n";

// delete all cookies
$driver->manage()->deleteAllCookies();

// add new cookie
$cookie = new Cookie('cookie_set_by_selenium', 'cookie_value');
$driver->manage()->addCookie($cookie);

// dump current cookies to output
$cookies = $driver->manage()->getCookies();
print_r($cookies);

// close the browser
$driver->quit();