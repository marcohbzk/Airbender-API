<?php


namespace frontend\tests\Functional;

use frontend\tests\FunctionalTester;

class SelectAirportCest
{
    protected $formId = '';

    public function _before(FunctionalTester $I)
    {
        $I->amLoggedInAs(120);
        $I->amOnRoute('site/index');
        $I->click('Flights');
    }

    // tests
    public function SelectSameAirport(FunctionalTester $I)
    {
        $I->submitForm('#form-selectAirport', [
            'SelectAirport[airportDeparture_id]' => '8',
            'SelectAirport[airportArrival_id]' => '8',
            'SelectAirport[departureDate]' => '2023-02-11',
        ]);
        $I->see('Airport Departure Id must not be equal to "Airport Arrival Id".');
    }

    public function SelectAirportArrivalNull(FunctionalTester $I)
    {
        $I->submitForm('#form-selectAirport', [
            'SelectAirport[airportDeparture_id]' => '8',
            'SelectAirport[airportArrival_id]' => '',
            'SelectAirport[departureDate]' => '2023-02-11',
        ]);
        $I->see('Cannot be empty');
    }

    public function SelectAirportDepartureNull(FunctionalTester $I)
    {
        $I->submitForm('#form-selectAirport', [
            'SelectAirport[airportDeparture_id]' => '',
            'SelectAirport[airportArrival_id]' => '9',
            'SelectAirport[departureDate]' => '2023-02-11',
        ]);
        $I->see('Cannot be empty');
    }

    public function SelectDepartureDateNull(FunctionalTester $I)
    {
        $I->submitForm('#form-selectAirport', [
            'SelectAirport[airportDeparture_id]' => '1',
            'SelectAirport[airportArrival_id]' => '9',
            'SelectAirport[departureDate]' => '',
        ]);
        $I->dontSee('Select one flight!');
    }

    public function SelectOldDepartureDate(FunctionalTester $I)
    {
        $I->submitForm('#form-selectAirport', [
            'SelectAirport[airportDeparture_id]' => '8',
            'SelectAirport[airportArrival_id]' => '9',
            'SelectAirport[departureDate]' => '2022-02-11',
        ]);
        $I->dontSee('Select one flight!');
    }

    public function SelectAirportFlightDontExist(FunctionalTester $I)
    {
        $I->submitForm('#form-selectAirport', [
            'SelectAirport[airportDeparture_id]' => '5',
            'SelectAirport[airportArrival_id]' => '21',
            'SelectAirport[departureDate]' => '2023-02-11',
        ]);
        $I->see('There are no flights available from Novyy Karachay to Voznesenskaya');
    }

    public function SelectAirportsCorrect(FunctionalTester $I)
    {
        $I->submitForm('#form-selectAirport', [
            'SelectAirport[airportDeparture_id]' => '7',
            'SelectAirport[airportArrival_id]' => '9',
            'SelectAirport[departureDate]' => '2023-02-11',
        ]);
        $I->see('Select one flight!');
    }
}
