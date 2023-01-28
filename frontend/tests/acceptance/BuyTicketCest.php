<?php


namespace frontend\tests\Acceptance;

use frontend\tests\AcceptanceTester;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\WebDriverWait;
use Facebook\WebDriver\Remote\DesiredCapabilities;

class BuyTicketCest
{
    public function _before(AcceptanceTester $I)
    {

    }

    public function _after(AcceptanceTester $I)
    {
        $I->wait(5);
    }


    public function SelectFlight(AcceptanceTester $I)
    {
        $I->amOnPage('site/index');
        $I->wait(2);
        $I->amOnPage('site/login');
        $I->wait(1);
        $I->fillField('LoginForm[username]', 'abba');
        $I->wait(1);
        $I->fillField('LoginForm[password]', 'abba123');
        $I->wait(2);
        $I->click('login-button');
        $I->wait(2);

        $I->amOnPage('flight/select-airport');
        $I->wait(2);
        $I->appendField('SelectAirport[airportDeparture_id]', 'Novyy Karachay');
        $I->wait(2);
        $I->appendField('SelectAirport[airportArrival_id]', 'Komi');
        $I->wait(2);
        $I->fillField('SelectAirport[departureDate]', '2023/02/22');
        $I->click('#gtco-footer');
        $I->wait(2);
        $I->click('Search');

        $I->wait(5);
        $I->click('#normalPrice');

        $I->wait(3);
        $I->fillField('TicketBuilder[fName]', 'Abba');
        $I->wait(.5);
        $I->fillField('TicketBuilder[surname]', 'Test');
        $I->wait(.5);
        $I->appendField('TicketBuilder[gender]', 'F');
        $I->wait(.5);
        $I->fillField('TicketBuilder[age]', '18');
        $I->wait(2);
        $I->click('#config1_luggage2');
        $I->wait(1);
        $I->click('#F-6');
        $I->wait(2);
        $I->scrollTo('#gtco-footer');
        $I->wait(2);
        $I->click('Next');

        $I->wait(6);
        $I->click('Add +');

        $I->wait(2);
        $I->appendField('SelectAirport[airportDeparture_id]', 'Novyy Karachay');
        $I->wait(2);
        $I->appendField('SelectAirport[airportArrival_id]', 'Komi');
        $I->wait(2);
        $I->fillField('SelectAirport[departureDate]', '2023/02/22');
        $I->click('#gtco-footer');
        $I->wait(2);
        $I->click('Search');

        $I->wait(5);
        $I->click('#luxuryPrice');

        $I->wait(3);
        $I->fillField('TicketBuilder[fName]', 'Roberto');
        $I->wait(.5);
        $I->fillField('TicketBuilder[surname]', 'Silva');
        $I->wait(.5);
        $I->appendField('TicketBuilder[gender]', 'M');
        $I->wait(.5);
        $I->fillField('TicketBuilder[age]', '32');
        $I->wait(1);
        $I->click('#F-5');
        $I->wait(2);
        $I->scrollTo('#gtco-footer');
        $I->wait(2);
        $I->click('Next');

        $I->wait(5);
        $I->click('Pay');
    }
}
