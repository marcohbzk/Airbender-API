<?php


namespace backend\tests\Unit;

use backend\tests\UnitTester;
use common\models\BalanceReq;

class BalanceReqTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // tests
    public function testValidation()
    {
        $balanceReq = new BalanceReq();

        $balanceReq->amount = null;
        $this->assertFalse($balanceReq->validate(['amount']));

        $balanceReq->amount = -100;
        $this->assertFalse($balanceReq->validate(['amount']));

        $balanceReq->amount = 'asd';
        $this->assertFalse($balanceReq->validate(['amount']));

        $balanceReq->amount = 100000;
        $this->assertFalse($balanceReq->validate(['amount']));

        $balanceReq->amount = 1000;
        $this->assertTrue($balanceReq->validate(['amount']));

        $balanceReq->amount = 1000.3;
        $this->assertTrue($balanceReq->validate(['amount']));


        $balanceReq->status = 123;
        $this->assertFalse($balanceReq->validate(['status']));

        $balanceReq->status = 'not-enum';
        $this->assertFalse($balanceReq->validate(['status']));

        $balanceReq->status = 'Ongoing';
        $this->assertTrue($balanceReq->validate(['status']));


        $balanceReq->requestDate = null;
        $this->assertFalse($balanceReq->validate(['requestDate']));

        $balanceReq->requestDate = 123;
        $this->assertFalse($balanceReq->validate(['requestDate']));

        $balanceReq->requestDate = 'wrong-format';
        $this->assertFalse($balanceReq->validate(['requestDate']));

        $balanceReq->requestDate = '2023-01-09 14:00:00';
        $this->assertTrue($balanceReq->validate(['requestDate']));

        $balanceReq->decisionDate = null;
        $this->assertTrue($balanceReq->validate(['decisionDate']));

        $balanceReq->decisionDate = 123;
        $this->assertFalse($balanceReq->validate(['decisionDate']));

        $balanceReq->decisionDate = 'wrong format';
        $this->assertFalse($balanceReq->validate(['decisionDate']));

        $balanceReq->decisionDate = '2023-01-09 12:00:00';
        $this->assertFalse($balanceReq->validate(['decisionDate']));

        $balanceReq->decisionDate = '2023-01-09 18:00:00';
        $this->assertTrue($balanceReq->validate(['decisionDate']));

        $balanceReq->client_id = null;
        $this->assertFalse($balanceReq->validate(['client_id']));

        $balanceReq->client_id = 'asd';
        $this->assertFalse($balanceReq->validate(['client_id']));

        $balanceReq->client_id = 0;
        $this->assertFalse($balanceReq->validate(['client_id']));

        $balanceReq->client_id = 95;
        $this->assertTrue($balanceReq->validate(['client_id']));
    }

    public function testCRUD()
    {

        $balanceReq = new BalanceReq();
        $balanceReq->amount = 200;
        $balanceReq->requestDate = date('Y-m-d H:i:s');
        $balanceReq->decisionDate = null;
        $balanceReq->status = 'Ongoing';
        $balanceReq->client_id = 95;
        $balanceReq->save();

        // read test
        $this->tester->seeRecord('common\models\BalanceReq', ['id' => $balanceReq->id]);

        // update test
        $balanceReq = $this->tester->grabRecord('common\models\BalanceReq', ['id' => $balanceReq->id]);
        $balanceReq->amount = 250;
        $balanceReq->requestDate = date('Y-m-d H:i:s');
        $balanceReq->save();

        // update nao esta a funcionar
        $this->tester->dontSeeRecord('common\models\BalanceReq', ['amount' => 200, 'id' => $balanceReq->id]);
        $this->tester->seeRecord('common\models\BalanceReq', ['amount' => 250, 'id' => $balanceReq->id]);

        // delete test
        $balanceReq->delete();
        $this->tester->dontSeeRecord('common\models\BalanceReq', ['id' => $balanceReq->id]);
    }
}
