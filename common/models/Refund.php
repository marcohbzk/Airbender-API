<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "refund".
 *
 * @property int $id
 * @property string $status
 * @property string $requestDate
 * @property string|null $decisionDate
 * @property int $receipt_id
 *
 * @property RefundEmployee $refundEmployee
 */
class Refund extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'refund';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'string'],
            [['requestDate', 'receipt_id'], 'required'],
            [['requestDate', 'decisionDate'], 'safe'],
            [['receipt_id'], 'integer'],
            [['receipt_id'], 'unique'],
            [['receipt_id'], 'exist', 'skipOnError' => true, 'targetClass' => Receipt::class, 'targetAttribute' => ['receipt_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'requestDate' => 'Request Date',
            'decisionDate' => 'Decision Date',
            'receipt_id' => 'Receipt ID',
        ];
    }

    /**
     * Gets query for [[RefundEmployee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRefundEmployee()
    {
        return $this->hasOne(RefundEmployee::class, ['refund_id' => 'id']);
    }
}
