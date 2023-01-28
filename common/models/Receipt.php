<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "receipts".
 *
 * @property int $id
 * @property string $purchaseDate
 * @property float $total
 * @property string|null $status
 *
 * @property Tickets[] $tickets
 */
class Receipt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'receipts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['purchaseDate', 'total'], 'required'],
            [['purchaseDate'], 'safe'],
            [['total'], 'number'],
            [['status'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'purchaseDate' => 'Purchase Date',
            'total' => 'Total',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::class, ['receipt_id' => 'id']);
    }

    public function getClient()
    {
        return $this->hasOne(Client::class, ['user_id' => 'client_id']);
    }

    public function shred()
    {
        if ($this->status != "Pending")
            return false;

        foreach ($this->tickets as $ticket) {
            $ticket->delete();
        }

        return $this->delete();
    }

    public function refreshTotal()
    {
        $this->total = 0;
        foreach ($this->tickets as $ticket) {
            $this->total += $ticket->getTicketPrice();
        }
        return $this->save();
    }

    public function updateTicketPrices()
    {
        foreach($this->tickets as $ticket)
        {
            if(!$ticket->flight->increasePrice(0.03))
                return false;
        }
        return true;
    }
}
