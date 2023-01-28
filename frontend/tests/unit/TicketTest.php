<?php


namespace frontend\tests\Unit;

use frontend\tests\UnitTester;
use common\models\Airplane;
use common\models\Airport;
use common\models\Ticket;
use common\models\Flight;
use common\models\Receipt;


class TicketTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testValidation()
    {
        $ticket = new Ticket();


        // fName

        $ticket->fName = null;
        $this->assertFalse($ticket->validate(['fName']));

        $ticket->fName = 123;
        $this->assertFalse($ticket->validate(['fName']));

        $ticket->fName = 'toooooooooooooolooooooooooooooooooooooooongggggggggggg';
        $this->assertFalse($ticket->validate(['fName']));

        $ticket->fName = 'normal';
        $this->assertTrue($ticket->validate(['fName']));

        // surname

        $ticket->surname = null;
        $this->assertFalse($ticket->validate(['surname']));

        $ticket->surname = 123;
        $this->assertFalse($ticket->validate(['surname']));

        $ticket->surname = 'toooooooooooooolooooooooooooooooooooooooongggggggggggg';
        $this->assertFalse($ticket->validate(['surname']));

        $ticket->surname = 'normal';
        $this->assertTrue($ticket->validate(['surname']));

        // gender

        $ticket->gender = null;
        $this->assertFalse($ticket->validate(['gender']));

        $ticket->gender = 123;
        $this->assertFalse($ticket->validate(['gender']));

        $ticket->gender = 'Not a gender';
        $this->assertFalse($ticket->validate(['gender']));

        $ticket->gender = 'N';
        $this->assertFalse($ticket->validate(['gender']));

        $ticket->gender = 'M';
        $this->assertTrue($ticket->validate(['gender']));

        $ticket->gender = 'F';
        $this->assertTrue($ticket->validate(['gender']));

        // age

        $ticket->age = null;
        $this->assertFalse($ticket->validate(['age']));

        $ticket->age = 123;
        $this->assertFalse($ticket->validate(['age']));

        $ticket->age = 'string';
        $this->assertFalse($ticket->validate(['age']));

        $ticket->age = -100;
        $this->assertFalse($ticket->validate(['age']));

        $ticket->age = 35;
        $this->assertTrue($ticket->validate(['age']));

        // checkedin

        $ticket->checkedIn = 123124;
        $this->assertFalse($ticket->validate(['checkedIn']));

        $ticket->checkedIn = 88;
        $this->assertTrue($ticket->validate(['checkedIn']));

        $ticket->checkedIn = null;
        $this->assertTrue($ticket->validate(['checkedIn']));

        // client_id

        $ticket->client_id = null;
        $this->assertFalse($ticket->validate(['client_id']));

        $ticket->client_id = 12;
        $this->assertFalse($ticket->validate(['client_id']));

        $ticket->client_id = 95;
        $this->assertTrue($ticket->validate(['client_id']));

        // flight_id

        $ticket->flight_id = null;
        $this->assertFalse($ticket->validate(['flight_id']));

        $ticket->flight_id = 41234;
        $this->assertFalse($ticket->validate(['flight_id']));

        $ticket->flight_id = 1120;
        $this->assertTrue($ticket->validate(['flight_id']));

        // seatLinha

        $ticket->seatLinha = null;
        $this->assertFalse($ticket->validate(['seatLinha']));

        $ticket->seatLinha = 12341;
        $this->assertFalse($ticket->validate(['seatLinha']));

        $ticket->seatLinha = 'Z';
        $this->assertFalse($ticket->validate(['seatLinha']));

        $ticket->seatLinha = 'A';
        $this->assertTrue($ticket->validate(['seatLinha']));

        // seatCol

        $ticket->seatCol = null;
        $this->assertFalse($ticket->validate(['seatCol']));

        $ticket->seatCol = 'A';
        $this->assertFalse($ticket->validate(['seatCol']));

        $ticket->seatCol = 18;
        $this->assertFalse($ticket->validate(['seatCol']));

        $ticket->seatCol = 8;
        $this->assertTrue($ticket->validate(['seatCol']));

        // luggage 1

        $ticket->luggage_1 = null;
        $this->assertTrue($ticket->validate(['luggage_1']));

        $ticket->luggage_1 = '20kg';
        $this->assertFalse($ticket->validate(['luggage_1']));

        $ticket->luggage_1 = 1234;
        $this->assertFalse($ticket->validate(['luggage_1']));

        $ticket->luggage_1 = 2;
        $this->assertTrue($ticket->validate(['luggage_1']));

        // luggage 1

        $ticket->luggage_2 = null;
        $this->assertTrue($ticket->validate(['luggage_2']));

        $ticket->luggage_2 = '10kg';
        $this->assertFalse($ticket->validate(['luggage_2']));

        $ticket->luggage_2 = 1234;
        $this->assertFalse($ticket->validate(['luggage_2']));

        $ticket->luggage_2 = 1;
        $this->assertTrue($ticket->validate(['luggage_2']));

        // receipt

        $ticket->receipt_id = null;
        $this->assertFalse($ticket->validate(['receipt_id']));

        $ticket->receipt_id = 'not a int';
        $this->assertFalse($ticket->validate(['receipt_id']));

        $ticket->receipt_id = 1234;
        $this->assertFalse($ticket->validate(['receipt_id']));

        // tariffType

        $ticket->tariffType = null;
        $this->assertFalse($ticket->validate(['tariffType']));

        $ticket->tariffType = 'not a tariff';
        $this->assertFalse($ticket->validate(['tariffType']));

        $ticket->tariffType = 1234;
        $this->assertFalse($ticket->validate(['tariffType']));

        $ticket->tariffType = 'economic';
        $this->assertTrue($ticket->validate(['tariffType']));

        $ticket->tariffType = 'luxury';
        $this->assertTrue($ticket->validate(['tariffType']));
    }

    public function testCRUD() {

        $receipt = new Receipt();
        $receipt->purchaseDate = date('Y-m-d H:i:s');
        $receipt->total = 0;
        $receipt->status = 'Pending';
        $receipt->client_id = 95;
        $receipt->save();

        $flight = $this->tester->grabRecord('common\models\Flight', ['id' => 1200]);

        //Create ticket entry on database
        $ticket = new Ticket();
        $ticket->fName = 'test';
        $ticket->surname = 'tester';
        $ticket->age = 25;
        $ticket->gender = 'M';
        $ticket->checkedIn = null;
        $ticket->flight_id = $flight->id;
        $ticket->client_id = $receipt->client_id;
        $ticket->seatLinha = 'A';
        $ticket->seatCol = 6;
        $ticket->luggage_1 = 2;
        $ticket->luggage_2 = null;
        $ticket->receipt_id = $receipt->id;
        $ticket->tariff_id = $flight->activeTariff()->id;
        $ticket->tariffType = 'economic';
        $ticket->save();

        // read test
        $this->tester->seeRecord('common\models\Ticket', ['id' => $ticket->id]);

        // update test
        $ticket = $this->tester->grabRecord('common\models\Ticket', ['id' => $ticket->id]);
        $ticket->setAttribute('seatCol', 7);
        $ticket->save();

        $this->tester->dontSeeRecord('common\models\Ticket', ['seatCol' => 6]);
        $this->tester->seeRecord('common\models\Ticket', ['seatCol' => 7]);

        // delete test
        $ticket->delete();
        $this->tester->dontSeeRecord('common\models\Ticket', ['id' => $ticket->id]);
    }
}
