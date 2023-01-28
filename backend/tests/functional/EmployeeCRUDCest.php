<?php


namespace backend\tests\Functional;

use backend\tests\FunctionalTester;

class EmployeeCRUDCest
{
    public function _before(FunctionalTester $I)
    {
        $I->amLoggedInAs(8);
        $I->amOnRoute('/employee/index');
    }

    // tests
    public function createEmployee(FunctionalTester $I)
    {
        $I->see('Employees');
        $I->click('Create Employee');
        $I->fillField('First Name', 'João');
        $I->fillField('Surname', 'Silveira');
        $I->fillField('Email', 'joao@gmail.com');
        $I->selectOption('Gender', "Male");
        $I->fillField('Birthdate', '2000/05/11');
        $I->fillField('Phone', '928271823');
        $I->fillField('Nif', '182738128');
        $I->fillField('Salary', '1200');
        $I->selectOption('Roles', "supervisor");
        $I->selectOption('Airport', "Suya");
        $I->fillField('Username', 'joaozinho');
        $I->fillField('Password', 'joao1234');
        $I->click('Save');
        $I->see("Sucess!");
    }
    public function createEmployeeEmpty(FunctionalTester $I)
    {
        $I->see('Employees');
        $I->click('Create Employee');
        $I->fillField('First Name', '');
        $I->fillField('Surname', 'Silveira');
        $I->fillField('Email', 'joao@gmail.com');
        $I->selectOption('Gender', "Male");
        $I->fillField('Birthdate', '2000/05/11');
        $I->fillField('Phone', '928271823');
        $I->fillField('Nif', '182738128');
        $I->fillField('Salary', '1200');
        $I->selectOption('Roles', "supervisor");
        $I->selectOption('Airport', "Suya");
        $I->fillField('Username', 'joaozinho');
        $I->fillField('Password', 'joao1234');
        $I->click('Save');
        $I->see("F Name cannot be blank.");
    }
    public function createEmployeeEmailFormatoErrado(FunctionalTester $I)
    {
        $I->see('Employees');
        $I->click('Create Employee');
        $I->fillField('First Name', 'João');
        $I->fillField('Surname', 'Silveira');
        $I->fillField('Email', 'joaogmail.com');
        $I->selectOption('Gender', "Male");
        $I->fillField('Birthdate', '2000/05/11');
        $I->fillField('Phone', '928271823');
        $I->fillField('Nif', '182738128');
        $I->fillField('Salary', '1200');
        $I->selectOption('Roles', "supervisor");
        $I->selectOption('Airport', "Suya");
        $I->fillField('Username', 'joaozinho');
        $I->fillField('Password', 'joao1234');
        $I->click('Save');
        $I->see("Email is not a valid email address.");
    }
    public function createEmployeePhoneComOitoNumeros(FunctionalTester $I)
    {
        $I->see('Employees');
        $I->click('Create Employee');
        $I->fillField('First Name', 'João');
        $I->fillField('Surname', 'Silveira');
        $I->fillField('Email', 'joaogmail.com');
        $I->selectOption('Gender', "Male");
        $I->fillField('Birthdate', '2000/05/11');
        $I->fillField('Phone', '92827123');
        $I->fillField('Nif', '182738128');
        $I->fillField('Salary', '1200');
        $I->selectOption('Roles', "supervisor");
        $I->selectOption('Airport', "Suya");
        $I->fillField('Username', 'joaozinho');
        $I->fillField('Password', 'joao1234');
        $I->click('Save');
        $I->see("Phone should contain at least 9 characters.");
    }
    public function viewEmployee(FunctionalTester $I)
    {
        $I->see('Employees');
        $I->see('Diogo');
        $I->click('View');
        $I->see('932840128');
    }
    public function updateEmployee(FunctionalTester $I)
    {
        $I->see('Employees');
        $I->see('Diogo');
        $I->click('Update');
        $I->see('Update Employee: 8');
    }
    public function deleteEmployee(FunctionalTester $I)
    {
        $I->see('Employees');
        $I->see('Quintina');
        $I->click('Delete');
        $I->dontSee('Quintina');
    }
}
