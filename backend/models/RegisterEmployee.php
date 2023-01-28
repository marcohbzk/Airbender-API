<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\UserData;
use backend\models\Employee;

/**
 * Signup form
 */
class RegisterEmployee extends Model
{
    public $username;
    public $email;
    public $password;

    public $role;
    public $fName;
    public $surname;
    public $gender;
    public $phone;
    public $nif;
    public $birthdate;

    public $salary;
    public $user_id;
    public $airport_id;


    public function rules()
    {
        return [
            ['fName', 'trim'],
            ['fName', 'required'],
            ['fName', 'string', 'min' => 2, 'max' => 25],

            ['surname', 'trim'],
            ['surname', 'required'],
            ['surname', 'string', 'min' => 2, 'max' => 25],

            ['gender', 'required'],

            ['phone', 'trim'],
            ['phone', 'required'],
            ['phone', 'number'],
            ['phone', 'unique', 'targetClass' => '\common\models\UserData', 'message' => 'This phone has already been taken.'],
            ['phone', 'string', 'min' => 9, 'max' => 9],

            ['nif', 'trim'],
            ['nif', 'required'],
            ['nif', 'number'],
            ['nif', 'unique', 'targetClass' => '\common\models\UserData', 'message' => 'This nif has already been taken.'],
            ['nif', 'string', 'min' => 9, 'max' => 9],

            ['birthdate', 'trim'],
            ['birthdate', 'required'],
            ['birthdate', 'string', 'min' => 10, 'max' => 10],

            ['salary', 'trim'],
            ['salary', 'required'],
            ['salary', 'integer', 'min' => 700, 'max' => 5000],

            ['role', 'required'],
            ['role', 'string', 'min' => 2, 'max' => 255],

            ['airport_id', 'required'],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    public function update($id)
    {
        $user = User::findOne($id);
        $user->username = $this->username;
        $user->email = $this->email;
        if ($this->password != "")
            $user->setPassword($this->password);

        $state = $user->save();

        $userData = isset($user->userData) ? $user->userData : new UserData();
        $userData->user_id = isset($userData->user_id) ? $userData->user_id : $user->getId();
        $userData->fName = $this->fName;
        $userData->surname = $this->surname;
        $userData->birthdate = date('Y-m-d', strtotime($this->birthdate));
        $userData->phone = $this->phone;
        $userData->nif = $this->nif;
        $userData->gender = $this->gender;
        $userData->accCreationDate = date('Y-m-d H:i:s');
        $userData->accCreationDate = isset($userData->accCreationDate) ? $userData->accCreationDate : date('Y-m-d H:i:s');

        $state = $state && $userData->save();

        $employee = isset($user->userData) ? $user->employee : new Employee();
        $employee->user_id = $user->getId();
        $employee->salary = $this->salary;
        $employee->airport_id = $this->airport_id;

        // RBAC
        $auth = Yii::$app->authManager;
        $oldRole = $auth->getRole($user->authAssignment->item_name);
        $newRole = $auth->getRole($this->role);
        if ($newRole != $oldRole) {
            $auth->revoke($oldRole, $user->id);
            $auth->assign($newRole, $user->id);
        }

        return $state && $employee->save();
    }
    public function register()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $userData = new UserData();
        $employee = new Employee();

        // tabela USER
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->status = 10;

        $user->save();

        $this->user_id = $user->getId();
        // tabela USERDATA
        $userData->user_id = $user->getId();
        $userData->fName = $this->fName;
        $userData->surname = $this->surname;
        $userData->birthdate = date('Y-m-d', strtotime($this->birthdate));
        $userData->phone = $this->phone;
        $userData->nif = $this->nif;
        $userData->gender = $this->gender;
        $userData->accCreationDate = date('Y-m-d H:i:s');

        $userData->save();


        // tabela EMPLOYEE
        $employee->user_id = $user->getId();
        $employee->salary = $this->salary;
        $employee->airport_id = $this->airport_id;


        // RBAC
        $auth = \Yii::$app->authManager;
        $role = $auth->getRole($this->role);
        $auth->assign($role, $user->getId());


        return $employee->save();
    }
    public function setUser($user)
    {
        $this->username = isset($user->username) ? $user->username : null;
        $this->email = isset($user->email) ? $user->email : null;

        $this->role = $user->authAssignment->item_name;
        $this->fName = isset($user->userData->fName) ? $user->userData->fName : null;
        $this->surname = isset($user->userData->surname) ? $user->userData->surname : null;
        $this->gender = isset($user->userData->gender) ? $user->userData->gender : null;
        $this->phone = isset($user->userData->phone) ? $user->userData->phone : null;
        $this->nif = isset($user->userData->nif) ? $user->userData->nif : null;
        $this->birthdate = isset($user->userData->birthdate) ? $user->userData->birthdate : null;

        $this->salary = isset($user->employee->salary) ? $user->employee->salary : null;
        $this->user_id = isset($user->employee->user_id) ? $user->employee->user_id : null;
        $this->airport_id = isset($user->employee->airport_id) ? $user->employee->airport_id : null;
    }
}
