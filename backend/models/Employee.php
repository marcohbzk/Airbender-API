<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use common\models\Airport;
use common\models\BalanceReqEmployee;
use common\models\Ticket;

/**
 * This is the model class for table "employees".
 *
 * @property int $user_id
 * @property float $salary
 * @property int|null $airport_id
 *
 * @property Airport $airport
 * @property Tickets[] $tickets
 * @property User $user
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'employees';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'salary'], 'required'],
            [['user_id', 'airport_id'], 'integer'],
            [['salary'], 'number'],
            [['user_id'], 'unique'],
            [['airport_id'], 'exist', 'skipOnError' => true, 'targetClass' => Airport::class, 'targetAttribute' => ['airport_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'salary' => 'Salary',
            'airport_id' => 'Airport ID',
        ];
    }

    public function createEmployee()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->status = 10;

        // the following three lines were added:
        $auth = \Yii::$app->authManager;
        $role = $auth->getRole('client');
        $auth->assign($role, $user->getId());

        return $user->save();
    }
    /**
     * Gets query for [[Airport]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAirport()
    {
        return $this->hasOne(Airport::class, ['id' => 'airport_id']);
    }


    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::class, ['checkedIn' => 'user_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
