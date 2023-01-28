<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\Flight;
use common\models\Airport;
use common\models\Airplane;
use common\models\Tariff;
use yii\validators\DateValidator;

/**
 * Signup form
 */
class CreateFlight extends Model
{
    public $departureDate;
    public $departureTime;
    public $duration;
    public $airportDeparture_id;
    public $airportArrival_id;
    public $airplane_id;
    public $status;
    public $normalPrice = 30;

    public function rules()
    {
        return [
            [['departureDate', 'duration', 'airplane_id', 'airportDeparture_id', 'airportArrival_id'], 'required'],
            [['departureDate'], 'safe'],
            [['departureDate'], DateValidator::class, 'format' => 'php:Y-m-d'],
            [['departureTime'], DateValidator::class, 'format' => 'php:H:i:s'],
            [['duration'], DateValidator::class, 'format' => 'php:H:i:s'],
            [['airplane_id', 'airportDeparture_id', 'airportArrival_id'], 'integer'],
            [['status'], 'string'],
            [['airplane_id'], 'exist', 'skipOnError' => true, 'targetClass' => Airplane::class, 'targetAttribute' => ['airplane_id' => 'id']],
            [['airportArrival_id'], 'exist', 'skipOnError' => true, 'targetClass' => Airport::class, 'targetAttribute' => ['airportArrival_id' => 'id']],
            [['airportDeparture_id'], 'exist', 'skipOnError' => true, 'targetClass' => Airport::class, 'targetAttribute' => ['airportDeparture_id' => 'id']],
            ['airportDeparture_id', 'compare', 'compareValue' => 'airportArrival_id', 'operator' => '!=', 'message' => 'The destiny and arrival airports must not be the same.'],
        ];
    }

    public function save()
    {
        $flight = new Flight();
        $flight->departureDate = $this->departureDate . ' ' . $this->departureTime;
        $flight->duration = $this->duration;
        $flight->airportDeparture_id = $this->airportDeparture_id;
        $flight->airportArrival_id = $this->airportArrival_id;
        $flight->airplane_id = $this->airplane_id;
        $flight->status = $this->status;

        $flight->save();

        $tariff = new Tariff();

        $airportDeparureSearch = Airport::findOne($this->airportDeparture_id)->search;
        $airportArrivalSearch = Airport::findOne($this->airportArrival_id)->search;
        $airplaneSeats = Airplane::findOne($this->airplane_id)->countTotalSeats();

        $tariff->generateFirstTariff($flight->id, $this->normalPrice, $airportDeparureSearch, $airportArrivalSearch, $airplaneSeats);

        return $tariff->save();
    }
}
