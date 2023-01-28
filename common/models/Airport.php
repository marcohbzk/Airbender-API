<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "airports".
 *
 * @property int $id
 * @property string $country
 * @property string $code
 * @property string $city
 * @property int $search
 * @property string $status
 *
 * @property Employees[] $employees
 * @property Flights[] $flights
 * @property Flights[] $flights0
 */
class Airport extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'airports';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['country', 'code', 'city', 'search', 'status'], 'required'],
            [['search'], 'integer'],
            [['status'], 'string'],
            ['status', 'in', 'range' => ['Operational', 'Not Operational']],
            [['country', 'city'], 'string', 'max' => 50],
            [['code'], 'string', 'max' => 2],
            ['search', 'integer', 'min' => 0, 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country' => 'Country',
            'code' => 'Code',
            'city' => 'City',
            'search' => 'Search (%)',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Employees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::class, ['id' => 'airport_id']);
    }

    /**
     * Gets query for [[Flights]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFlightsArrival()
    {
        return $this->hasMany(Flight::class, ['airportArrival_id' => 'id']);
    }

    /**
     * Gets query for [[Flights0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFlightsDeparture()
    {
        return $this->hasMany(Flight::class, ['airportDeparture_id' => 'id']);
    }
}
