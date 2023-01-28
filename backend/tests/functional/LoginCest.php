<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\fixtures\UserFixture;

/**
 * Class LoginCest
 */
class LoginCest
{
    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */

    /**
     * @param FunctionalTester $I
     */

    public function loginEmptyUsername(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->submitForm('#login-form',
            [
                'LoginForm[username]' => '',
                'LoginForm[password]' => 'password_0',
            ]);

        $I->see('Username cannot be blank.');
    }

    public function loginEmptyPassword(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->submitForm('#login-form',
            [
                'LoginForm[username]' => 'username',
                'LoginForm[password]' => '',
            ]);

        $I->see('Password cannot be blank.');
    }

    public function loginWrongUser(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->submitForm('#login-form',
            [
                'LoginForm[username]' => 'username',
                'LoginForm[password]' => 'password_0',
            ]);

        $I->see('Incorrect username or password.');
    }
    
    public function loginUser(FunctionalTester $I)
    {
        $I->amOnRoute('/site/login');
        $I->submitForm('#login-form',
            [
                'LoginForm[username]' => 'diogo',
                'LoginForm[password]' => 'diogo123',
            ]);

        $I->dontSeeLink('Login');
        $I->dontSeeLink('Signup');
    }
}
