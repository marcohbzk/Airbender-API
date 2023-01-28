<?php


namespace frontend\tests\Functional;

use frontend\tests\FunctionalTester;

class BalanceReqCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amLoggedInAs(120);
        $I->amOnRoute('balance-req/index');
    }

    // tests
    public function createBalanceRequestEmpty(FunctionalTester $I)
    {
        $I->see('My balance');
        $I->click('Create Balance Req');
        $I->fillField('Amount', '');
        $I->click('Save');
        $I->see('Amount cannot be blank.');
    }

    // tests
    public function createBalanceRequestNegative(FunctionalTester $I)
    {
        $I->see('My balance');
        $I->click('Create Balance Req');
        $I->fillField('Amount', '-100');
        $I->click('Save');
        $I->see('Amount must be no less than 10.');
    }

    public function createBalanceRequestTooMuch(FunctionalTester $I)
    {
        $I->see('My balance');
        $I->click('Create Balance Req');
        $I->fillField('Amount', '1000000');
        $I->click('Save');
        $I->see('Amount must be no greater than 10000.');
    }

    // tests
    public function createBalanceRequest(FunctionalTester $I)
    {
        $I->see('My balance');
        $I->click('Create Balance Req');
        $I->fillField('Amount', '200');
        $I->click('Save');
        $I->see('200');
    }

    public function viewBalanceRequest(FunctionalTester $I)
    {
        $I->see('My balance');
        $this->createBalanceRequest($I);
        $I->see('200');
        $I->click('View');
        $I->see('Decision Date');
    }

    public function deleteBalanceRequest(FunctionalTester $I)
    {
        $I->see('My balance');
        $this->createBalanceRequest($I);
        $I->see('200');
        $I->click('Delete');
        $I->dontSee('200');
    }
}
