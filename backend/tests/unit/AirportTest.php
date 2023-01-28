<?php


namespace backend\tests\Unit;

use backend\tests\UnitTester;
use common\models\Airport;

class AirportTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testValidation()
    {
        $airport = new Airport();

        $airport->country = null;
        $this->assertFalse($airport->validate(['country']));

        $airport->country = 123;
        $this->assertFalse($airport->validate(['country']));

        $airport->country = 'tooooooooooooooooooooooooooooooloooooooooooooooooooooong';
        $this->assertFalse($airport->validate(['country']));

        $airport->country = 'Portugal';
        $this->assertTrue($airport->validate(['country']));


        $airport->city = null;
        $this->assertFalse($airport->validate(['city']));

        $airport->city = 123;
        $this->assertFalse($airport->validate(['city']));

        $airport->city = 'tooooooooooooooooooooooooooooooloooooooooooooooooooooong';
        $this->assertFalse($airport->validate(['city']));

        $airport->city = 'Lisboa';
        $this->assertTrue($airport->validate(['city']));


        $airport->code = null;
        $this->assertFalse($airport->validate(['code']));

        $airport->code = 123;
        $this->assertFalse($airport->validate(['code']));

        $airport->code = 'PTL';
        $this->assertFalse($airport->validate(['code']));

        $airport->code = 'PT';
        $this->assertTrue($airport->validate(['code']));


        $airport->search = null;
        $this->assertFalse($airport->validate(['search']));

        $airport->search = '200';
        $this->assertFalse($airport->validate(['search']));

        $airport->search = 'not-a-int';
        $this->assertFalse($airport->validate(['search']));

        $airport->search = 76;
        $this->assertTrue($airport->validate(['search']));


        $airport->status = null;
        $this->assertFalse($airport->validate(['status']));

        $airport->status = 123;
        $this->assertFalse($airport->validate(['status']));

        $airport->status = 'not-a-enum-option';
        $this->assertFalse($airport->validate(['status']));

        $airport->status = 'Operational';
        $this->assertTrue($airport->validate(['status']));
    }

    public function testCRUD()
    {
        $airport = new Airport();
        $airport->country = 'Portugal';
        $airport->code = 'PT';
        $airport->city = 'Porto';
        $airport->search = 95;
        $airport->status = 'Operational';
        $airport->save();

        // read test
        $this->tester->seeRecord('common\models\Airport', ['id' => $airport->id]);

        // update test
        $flight = $this->tester->grabRecord('common\models\Airport', ['id' => $airport->id]);
        $flight->setAttribute('status', 'Not Operational');
        $flight->save();

        // update nao esta a funcionar
        $this->tester->dontSeeRecord('common\models\Airport', ['status' => 'Operational', 'id' => $airport->id]);
        $this->tester->seeRecord('common\models\Airport', ['status' => 'Not Operational', 'id' => $airport->id]);

        // delete test
        $flight->delete();
        $this->tester->dontSeeRecord('common\models\Airport', ['id' => $airport]);
    }
}
