<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tariffs".
 *
 * @property int $id
 * @property string $startDate
 * @property float $economicPrice
 * @property float $normalPrice
 * @property float $luxuryPrice
 * @property int $flight_id
 * @property int $active
 *
 * @property Flights $flight
 */
class Tariff extends \yii\db\ActiveRecord
{
    public function __construct()
    {
        $this->startDate = date('Y-m-d H:i:s');
        $this->active = 1;
    }
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tariffs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['startDate', 'economicPrice', 'normalPrice', 'luxuryPrice', 'flight_id', 'active'], 'required'],
            [['startDate'], 'safe'],
            [['economicPrice', 'normalPrice', 'luxuryPrice'], 'number'],
            [['flight_id'], 'integer'],
            [['active'], 'boolean'],
            [['flight_id'], 'exist', 'skipOnError' => true, 'targetClass' => Flight::class, 'targetAttribute' => ['flight_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'startDate' => 'Start Date',
            'economicPrice' => 'Economic Price',
            'normalPrice' => 'Normal Price',
            'luxuryPrice' => 'Luxury Price',
            'flight_id' => 'Flight ID',
            'active' => 'Active',
        ];
    }

    /**
     * Gets query for [[Flight]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFlight()
    {
        return $this->hasOne(Flight::class, ['id' => 'flight_id']);
    }

    public function generateFirstTariff($flight_id, $defaultPrice, $airportDepartureSearch, $airportArrivalSearch, $airplaneSeats)
    {
        $this->flight_id = $flight_id;
        $this->normalPrice = $defaultPrice + (($defaultPrice * $airportDepartureSearch) / 100) + (($defaultPrice * $airportArrivalSearch) / 100) + ($defaultPrice / ($airplaneSeats / 10));
        $this->economicPrice = $this->normalPrice - ($this->normalPrice * 0.25);
        $this->luxuryPrice = $this->normalPrice + ($this->normalPrice * 0.25);
    }

    public function updateTariff()
    {
        // TODO: Deactivate last active one and create a new one with a new price
        
    }
}
