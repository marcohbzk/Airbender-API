<?php

namespace common\models;

use Yii;
use yii\validators\DateValidator;
/**
 * This is the model class for table "balanceReq".
 *
 * @property int $id
 * @property float $amount
 * @property string $status
 * @property string $requestDate
 * @property string|null $decisionDate
 * @property int $client_id
 *
 * @property BalanceReqEmployee $balanceReqEmployee
 * @property User $client
 */
class BalanceReq extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'balanceReq';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['amount', 'requestDate', 'client_id'], 'required'],
            [['amount'], 'number', 'min' => 10, 'max' => 10000],
            [['status'], 'string'],
            ['status', 'in', 'range' => ['Accepted', 'Declined', 'Ongoing', 'Cancelled']],
            [['requestDate', 'decisionDate'], 'safe'],
            [['client_id'], 'integer'],
            [['client_id'], 'exist', 'skipOnError' => true, 'targetClass' => Client::class, 'targetAttribute' => ['client_id' => 'user_id']],
            ['decisionDate', 'compare', 'compareAttribute' => 'requestDate', 'operator' => '>'],
            ['requestDate', 'compare', 'compareValue' => date('Y-m-d H:i:s'), 'operator' => '<='],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'amount' => 'Amount',
            'status' => 'Status',
            'requestDate' => 'Request Date',
            'decisionDate' => 'Decision Date',
            'client_id' => 'Client ID',
        ];
    }

    /**
     * Gets query for [[BalanceReqEmployee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBalanceReqEmployee()
    {
        return $this->hasOne(BalanceReqEmployee::class, ['balanceReq_id' => 'id']);
    }

    /**
     * Gets query for [[Client]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getClient()
    {
        return $this->hasOne(Client::class, ['user_id' => 'client_id']);
    }



    public function setStatus($status)
    {
        $this->status = $status;
        $this->decisionDate = date('Y-m-d H:i:s');
        $this->save();
    }

    public function deleteBalanceReq()
    {
        if ($this->status = 'Ongoing')
            return $this->delete();
    }
}
