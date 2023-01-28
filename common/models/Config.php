<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "configs".
 *
 * @property int $id
 * @property string $description
 * @property int $weight
 * @property float $price
 * @property int $active
 *
 * @property Tickets[] $tickets
 * @property Tickets[] $tickets0
 */
class Config extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'configs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description', 'weight', 'price', 'active'], 'required'],
            [['description'], 'string'],
            [['weight', 'active'], 'integer'],
            [['price'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Description',
            'weight' => 'Weight (Kg)',
            'price' => 'Price (€)',
            'active' => 'Active',
        ];
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Tickets::class, ['luggage_1' => 'id']);
    }

    /**
     * Gets query for [[Tickets0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTickets0()
    {
        return $this->hasMany(Tickets::class, ['luggage_2' => 'id']);
    }

}
