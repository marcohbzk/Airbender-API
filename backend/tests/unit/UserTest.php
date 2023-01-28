<?php


namespace backend\tests\Unit;

use backend\tests\UnitTester;
use common\models\User;
use common\models\UserData;

class UserTest extends \Codeception\Test\Unit
{

    protected UnitTester $tester;

    protected function _before()
    {
    }

    // * * *TESTS* * *

    public function testValidation()
    {
        $user = new User();
        $userData = new UserData();

        // test User

            // Username

        $user->setUsername(null);
        $this->assertFalse($user->validate(['username']));

        $user->setUsername('toolooooonguseeernnaaaaaaameeee');
        $this->assertFalse($user->validate(['username']));

        $user->setUsername('a');
        $this->assertFalse($user->validate(['username']));

        $user->setUsername('pedrovski');
        $this->assertTrue($user->validate(['username']));

            // Email

        $user->setEmail(null);
        $this->assertFalse($user->validate(['email']));

        $user->setEmail('not-an-email');
        $this->assertFalse($user->validate(['email']));

        $user->setEmail('pedroestruturaspt@gmail.com');
        $this->assertTrue($user->validate(['email']));

        // test UserData

            // fName

        $userData->setFirstName(null);
        $this->assertFalse($userData->validate(['fName']));

        $userData->setFirstName('toolooooongnaaaaaaameeeeeeeee');
        $this->assertFalse($userData->validate(['fName']));

        $userData->setFirstName('davert');
        $this->assertTrue($userData->validate(['fName']));

            // test surname

        $userData->setSurname(null);
        $this->assertFalse($userData->validate(['surname']));

        $userData->setSurname('toolooooongsuuuurnaaaaaaameeee');
        $this->assertFalse($userData->validate(['surname']));

        $userData->setSurname('estrutrovski');
        $this->assertTrue($userData->validate(['surname']));

            // test birthdate
        
        $userData->setBirthdate(null);
        $this->assertFalse($userData->validate(['birthdate']));

        $userData->setBirthdate('not-a-date');
        $this->assertFalse($userData->validate(['birthdate']));

        $userData->setBirthdate('29/12/1999');
        $this->assertFalse($userData->validate(['birthdate']));

        $userData->setBirthdate('2024/12/31');
        $this->assertFalse($userData->validate(['birthdate']));

        $userData->setBirthdate('1999-12-31');
        $this->assertTrue($userData->validate(['birthdate']));

            // test phone
        
        $userData->setPhone(null);
        $this->assertFalse($userData->validate(['phone']));

        $userData->setPhone('not-a-phone');
        $this->assertFalse($userData->validate(['phone']));

        $userData->setPhone('91234567891412');
        $this->assertFalse($userData->validate(['phone']));

        $userData->setPhone('123');
        $this->assertFalse($userData->validate(['phone']));

        $userData->setPhone('abcdefghi');
        $this->assertFalse($userData->validate(['phone']));

        $userData->setPhone('912345678');
        $this->assertTrue($userData->validate(['phone']));

            // test nif
        
        $userData->setNif(null);
        $this->assertFalse($userData->validate(['nif']));

        $userData->setNif('not-a-nif');
        $this->assertFalse($userData->validate(['nif']));

        $userData->setNif('123456789123456789');
        $this->assertFalse($userData->validate(['nif']));

        $userData->setNif('123');
        $this->assertFalse($userData->validate(['nif']));

        $userData->setNif('abcdefghi');
        $this->assertFalse($userData->validate(['nif']));

        $userData->setNif('123456789');
        $this->assertTrue($userData->validate(['nif']));

            //test gender

        $userData->setGender(null);
        $this->assertFalse($userData->validate(['gender']));

        $userData->setGender('not-a-gender');
        $this->assertFalse($userData->validate(['gender']));

        $userData->setGender('O');
        $this->assertFalse($userData->validate(['gender']));

        $userData->setGender('2');
        $this->assertFalse($userData->validate(['gender']));

        $userData->setGender('M');
        $this->assertTrue($userData->validate(['gender']));
    }

    public function testCRUD()
    {
        //Create user entry on database
        $user = new User();
        $user->username = 'joaquim';
        $user->email = 'joaquim@gmail.com';
        $user->setPassword('joaquim123');
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->status = 10;
        $user->save();

        // save test
        $userData = new UserData();

        $userData->user_id = $user->getId();
        $userData->fName = 'joaquim';
        $userData->surname = 'estruturas';
        $userData->birthdate = '1999-12-31';
        $userData->phone = '961686162';
        $userData->nif = '274324662';
        $userData->gender = 'M';
        $userData->accCreationDate = date('Y-m-d H:i:s');
        $userData->save();

        $this->tester->seeRecord('common\models\User', ['id' => $user->id]);

        // update test
        $userData = $this->tester->grabRecord('common\models\UserData', ['fName' => $userData->fName]);

        $userData->fName = 'marco';
        $userData->save();

        $this->tester->dontSeeRecord('common\models\UserData', ['fName' => 'joaquim']);
        $this->tester->seeRecord('common\models\UserData', ['fName' => 'marco']);

        // delete test
        $userData->delete();
        $this->tester->dontSeeRecord('common\models\UserData', ['fName' => 'marco']);

    }
}
