<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "refund_employee".
 *
 * @property int $refund_id
 * @property int $employee_id
 *
 * @property User $employee
 * @property Refund $refund
 */
class RefundEmployee extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'refund_employee';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['refund_id', 'employee_id'], 'required'],
            [['refund_id', 'employee_id'], 'integer'],
            [['refund_id'], 'unique'],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['employee_id' => 'id']],
            [['refund_id'], 'exist', 'skipOnError' => true, 'targetClass' => Refund::class, 'targetAttribute' => ['refund_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'refund_id' => 'Refund ID',
            'employee_id' => 'Employee ID',
        ];
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

    /**
     * Gets query for [[Refund]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefund()
    {
        return $this->hasOne(Refund::class, ['id' => 'refund_id']);
    }
}
