<?php


namespace frontend\tests\Functional;

use frontend\tests\FunctionalTester;

class BuyTicketCest
{
    protected $formId = '';

    public function _before(FunctionalTester $I)
    {
        $I->amLoggedInAs(120);
        $I->amOnRoute('flight/select-flight?airportDeparture_id=5&airportArrival_id=19&departureDate=2023%2F10%2F10');
    }

    // tests
    public function SelectEconomicFlight(FunctionalTester $I)
    {
        $I->click('#economicPrice');
        $I->see('Ticket');
    }

    public function SelectNormalFlight(FunctionalTester $I)
    {
        $I->click('#normalPrice');
        $I->see('Ticket');
    }

    public function SelectLuxuryFlight(FunctionalTester $I)
    {
        $I->click('#luxuryPrice');
        $I->see('Ticket');
    }

    public function DontFillSeats(FunctionalTester $I)
    {
        $this->SelectNormalFlight($I);
        $I->submitForm('#form-buyTicket', [
            'TicketBuilder[fName]' => 'john', 
            'TicketBuilder[surname]' => 'man', 
            'TicketBuilder[age]' => 12, 
            'TicketBuilder[gender]' => 'M', 
        ]);
        $I->dontSee('First Name cannot be blank.');
        $I->dontSee('Surname cannot be blank.');
        $I->dontSee('Age cannot be blank.');
        $I->see('Seat Col cannot be blank.');
        $I->see('Seat Linha cannot be blank.');
    }
    
    public function DontFillUserInfo(FunctionalTester $I)
    {
        $this->SelectNormalFlight($I);
        $I->submitForm('#form-buyTicket', [
            'TicketBuilder[seatCol]' => 4, 
            'TicketBuilder[seatLinha]' => 'A', 
        ]);
        $I->see('First Name cannot be blank.');
        $I->see('Surname cannot be blank.');
        $I->see('Age cannot be blank.');
        $I->dontSee('Seat Col cannot be blank.');
        $I->dontSee('Seat Linha cannot be blank.');
    }

    public function ChooseWrongSeat(FunctionalTester $I)
    {
        $this->SelectNormalFlight($I);
        $I->submitForm('#form-buyTicket', [
            'TicketBuilder[fName]' => 'john', 
            'TicketBuilder[surname]' => 'man', 
            'TicketBuilder[age]' => 12, 
            'TicketBuilder[gender]' => 'M', 
            'TicketBuilder[seatCol]' => 4, 
            'TicketBuilder[seatLinha]' => 'A', 
        ]);
        $I->see('You need to choose a ');
    }

    public function ValidInput(FunctionalTester $I)
    {
        $this->SelectNormalFlight($I);
        $I->submitForm('#form-buyTicket', [
            'TicketBuilder[fName]' => 'john', 
            'TicketBuilder[surname]' => 'man', 
            'TicketBuilder[age]' => 12, 
            'TicketBuilder[gender]' => 'M', 
            'TicketBuilder[seatCol]' => 4, 
            'TicketBuilder[seatLinha]' => 'F', 
            'TicketBuilder[luggage_2]' => 1, 
        ]);
        $I->see('Pay');
    }
    public function UseAccountDetails(FunctionalTester $I){
        $this->SelectNormalFlight($I);
        $I->submitForm('#form-buyTicket', [
            'TicketBuilder[useAccount]' => true,
            'TicketBuilder[seatCol]' => 4,
            'TicketBuilder[seatLinha]' => 'F',
            'TicketBuilder[luggage_2]' => 1,
        ]);
        $I->see('Pay');
    }

    public function BuySameSeatTwoTicketsSameReceipt(FunctionalTester $I)
    {
        $this->SelectNormalFlight($I);
        $I->submitForm('#form-buyTicket', [
            'TicketBuilder[fName]' => 'john',
            'TicketBuilder[surname]' => 'man',
            'TicketBuilder[age]' => 12,
            'TicketBuilder[gender]' => 'M',
            'TicketBuilder[seatCol]' => 4,
            'TicketBuilder[seatLinha]' => 'F',
            'TicketBuilder[luggage_2]' => 1,
        ]);
        $I->see('Pay');
        $I->click('Add +');
        $I->see('Search');
        $I->submitForm('#form-selectAirport', [
            'SelectAirport[airportDeparture_id]' => '7',
            'SelectAirport[airportArrival_id]' => '9',
            'SelectAirport[departureDate]' => '2023-02-11',
        ]);
        $I->see('Select one flight!');
        $this->SelectNormalFlight($I);
        $I->submitForm('#form-buyTicket', [
            'TicketBuilder[fName]' => 'john',
            'TicketBuilder[surname]' => 'man',
            'TicketBuilder[age]' => 12,
            'TicketBuilder[gender]' => 'M',
            'TicketBuilder[seatCol]' => 4,
            'TicketBuilder[seatLinha]' => 'F',
            'TicketBuilder[luggage_2]' => 1,
        ]);
        $I->see('You already chose F-4 on another ticket! ');
    }

    public function BuyTwoTicketsSameReceipt(FunctionalTester $I)
    {
        $this->SelectNormalFlight($I);
        $I->submitForm('#form-buyTicket', [
            'TicketBuilder[fName]' => 'john',
            'TicketBuilder[surname]' => 'man',
            'TicketBuilder[age]' => 12,
            'TicketBuilder[gender]' => 'M',
            'TicketBuilder[seatCol]' => 4,
            'TicketBuilder[seatLinha]' => 'F',
            'TicketBuilder[luggage_2]' => 1,
        ]);
        $I->see('Pay');
        $I->click('Add +');
        $I->see('Search');
        $I->submitForm('#form-selectAirport', [
            'SelectAirport[airportDeparture_id]' => '7',
            'SelectAirport[airportArrival_id]' => '9',
            'SelectAirport[departureDate]' => '2023-02-11',
        ]);
        $I->see('Select one flight!');
        $this->SelectNormalFlight($I);
        $I->submitForm('#form-buyTicket', [
            'TicketBuilder[useAccount]' => true,
            'TicketBuilder[seatCol]' => 5,
            'TicketBuilder[seatLinha]' => 'F',
            'TicketBuilder[luggage_2]' => 1,
        ]);
        $I->see('Pay');
    }

    public function NotEnoughBalanceToPay(FunctionalTester $I)
    {
        $I->amLoggedInAs(123);
        $this->ValidInput($I);
        $I->see('Pay');
        $I->click('Pay');
        $I->see("You");
    }

    public function NotEnoughBalanceAskForBalance(FunctionalTester $I)
    {
        $I->amLoggedInAs(123);
        $this->ValidInput($I);
        $I->see('Pay');
        $I->click('Ask for balance');
        $I->see("Successfully requested");
    }
    public function EnoughBalanceAskForBalance(FunctionalTester $I)
    {
        $I->amLoggedInAs(120);
        $this->ValidInput($I);
        $I->see('Pay');
        $I->click('Ask for balance');
        $I->see("You already have enought balance!");

    }
    public function Pay(FunctionalTester $I)
    {
        $I->amLoggedInAs(120);
        $this->ValidInput($I);
        $I->see('Pay');
        $I->click('Pay');
        $I->see("Receipt #");

    }
}
