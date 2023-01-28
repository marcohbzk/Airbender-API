<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "balanceReq_employee".
 *
 * @property int $balanceReq_id
 * @property int $employee_id
 *
 * @property BalanceReq $balanceReq
 * @property User $employee
 */
class BalanceReqEmployee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    function __construct()
    {
    }
    public static function tableName()
    {
        return 'balanceReq_employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['balanceReq_id', 'employee_id'], 'required'],
            [['balanceReq_id', 'employee_id'], 'integer'],
            [['balanceReq_id'], 'unique'],
            [['balanceReq_id'], 'exist', 'skipOnError' => true, 'targetClass' => BalanceReq::class, 'targetAttribute' => ['balanceReq_id' => 'id']],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['employee_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'balanceReq_id' => 'Balance Req ID',
            'employee_id' => 'Employee ID',
        ];
    }
    /**
     * Gets query for [[BalanceReq]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBalanceReq()
    {
        return $this->hasOne(BalanceReq::class, ['id' => 'balanceReq_id']);
    }

    /**
     * Gets query for [[Employee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(User::class, ['id' => 'employee_id']);
    }
}