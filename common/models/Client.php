<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "clients".
 *
 * @property int $user_id
 * @property float $balance
 * @property int $application
 *
 * @property Tickets[] $tickets
 * @property User $user
 */
class Client extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'clients';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'balance', 'application'], 'required'],
            [['user_id'], 'integer'],
            [['application'], 'boolean'],
            [['balance'], 'number'],
            [['user_id'], 'unique'],
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
            'balance' => 'Balance',
            'application' => 'Application',
        ];
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::class, ['client_id' => 'user_id']);
    }

    public function getRequests()
    {
        return $this->hasMany(BalanceReq::class, ['client_id' => 'user_id']);
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

    public function addBalance($amount)
    {
        if ($amount > 0) {
            $this->balance += $amount;
        }
    }
}
