<?php

namespace common\models;

use Yii;
use common\models\Airport;
use common\models\Airplane;
use yii\validators\DateValidator;

/**
 * This is the model class for table "flights".
 *
 * @property int $id
 * @property string $departureDate
 * @property string $duration
 * @property int $airplane_id
 * @property int $airportDeparture_id
 * @property int $airportArrival_id
 * @property string|null $status
 *
 * @property Airplanes $airplane
 * @property Airports $airportArrival
 * @property Airports $airportDeparture
 * @property Tariffs[] $tariffs
 * @property Tickets[] $tickets
 */
class Flight extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'flights';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['departureDate', 'duration', 'airplane_id', 'airportDeparture_id', 'airportArrival_id'], 'required'],
            [['departureDate'], 'safe'],
            [['departureDate'], DateValidator::class, 'format' => 'php:Y-m-d H:i:s'],
            [['duration'], DateValidator::class, 'format' => 'php:H:i:s'],
            [['airplane_id', 'airportDeparture_id', 'airportArrival_id'], 'integer'],
            [['status'], 'string'],
            ['status', 'in', 'range' => ['Available', 'Unavailable', 'Complete', 'Canceled']],
            [['airplane_id'], 'exist', 'skipOnError' => true, 'targetClass' => Airplane::class, 'targetAttribute' => ['airplane_id' => 'id']],
            [['airportArrival_id'], 'exist', 'skipOnError' => true, 'targetClass' => Airport::class, 'targetAttribute' => ['airportArrival_id' => 'id']],
            [['airportDeparture_id'], 'exist', 'skipOnError' => true, 'targetClass' => Airport::class, 'targetAttribute' => ['airportDeparture_id' => 'id']],
            ['airportDeparture_id', 'compare', 'compareValue' => 'airportArrival_id', 'operator' => '!=', 'message' => 'The destiny and arrival airports must not be the same.'],
        ];
    }

    public function fields()
    {
        $fields= parent::fields();
        $fields[]='tariff';
        $fields[]='airplane';
        $fields[]='airportDeparture';
        $fields[]='airportArrival';
        return $fields;
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Flight ID',
            'airplane.id' => 'Airplane ID',
            'airportDeparture.country' => 'Departure',
            'airportArrival.country' => 'Destination',
            'departureDate' => 'Departure Date',
            'Duration' => 'Duration',
            'status' => 'Status',
        ];
    }

    /**
     * Generates departureDate and sets it to the model
     *
     * @param string $departureDate
     */
    public function setDepartureDate($departureDate)
    {
        $this->departureDate = $departureDate;
    }

    /**
     * Generates duration and sets it to the model
     *
     * @param string $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * Generates airportDeparture and sets it to the model
     *
     * @param string $airportDeparture
     */
    public function setAirportDeparture($airportDeparture)
    {
        $this->airportDeparture_id = $airportDeparture;
    }

    /**
     * Generates airportArrival and sets it to the model
     *
     * @param string $airportArrival
     */
    public function setAirportArrival($airportArrival)
    {
        $this->airportArrival_id = $airportArrival;
    }

    /**
     * Generates airplane and sets it to the model
     *
     * @param string $airplane
     */
    public function setAirplane($airplane)
    {
        $this->airplane_id = $airplane;
    }

    /**
     * Gets query for [[Airplane]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAirplane()
    {
        return $this->hasOne(Airplane::class, ['id' => 'airplane_id']);
    }

    /**
     * Gets query for [[AirportArrival]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAirportArrival()
    {
        return $this->hasOne(Airport::class, ['id' => 'airportArrival_id']);
    }

    /**
     * Gets query for [[AirportDeparture]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAirportDeparture()
    {
        return $this->hasOne(Airport::class, ['id' => 'airportDeparture_id']);
    }

    /**
     * Gets query for [[Tariff]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTariff()
    {
        return $this->hasMany(Tariff::class, ['flight_id' => 'id']);
    }

    /**
     * Gets query for [[Ticket]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::class, ['flight_id' => 'id']);
    }

    public function activeTariff($tariffType = null)
    {
        if (is_null($this->tariff)) {
            return null;
        }

        foreach ($this->tariff as $tariff) {
            if ($tariff->active) {
                // devolve tarifa em si
                if (is_null($tariffType))
                    return $tariff;
                else {
                    switch ($tariffType) {
                        case 'economic':
                            return $tariff->economicPrice;
                            break;
                        case 'normal':
                            return $tariff->normalPrice;
                            break;
                        case 'luxury':
                            return $tariff->luxuryPrice;
                            break;
                    }
                }
            }
        }
    }

    public function getAvailableSeats()
    {
        $seats = $this->airplane->getSeats();

        foreach ($this->tickets as $ticket) {
            if ($ticket->receipt->status == 'Complete') {
                // set to false == not available
                $seats[$ticket->seatLinha][$ticket->seatCol]['status'] = 0;
            }
        }
        return $seats;
    }

    public function getAvailableLuggage()
    {
        foreach ($this->tickets as $ticket) {
            if ($ticket->receipt->status == 'Complete') {
                // set to false == not available
                if (isset($ticket->luggageOne->weight) && !is_null($ticket->luggageOne->weight))
                    $this->airplane->luggageCapacity -= $ticket->luggageOne->weight;
                if (isset($ticket->luggageTwo->weight) && !is_null($ticket->luggageTwo->weight))
                    $this->airplane->luggageCapacity -= $ticket->luggageOne->weight;
            }
        }
        return $this->airplane->luggageCapacity;
    }

    public function countBoughtTickets()
    {
        $count = 0;

        foreach ($this->tickets as $ticket) {
            if ($ticket->receipt->status == 'Complete') {
                // set to false == not available
                $count++;
            }
        }
        return $count;
    }

    public function countBoughtLuggage()
    {
        $count = 0;

        foreach ($this->tickets as $ticket) {
            if ($ticket->receipt->status == 'Complete') {
                // set to false == not available
                if (isset($ticket->luggageOne->weight) && !is_null($ticket->luggageOne->weight))
                    $count += $ticket->luggageOne->weight;
                if (isset($ticket->luggageTwo->weight) && !is_null($ticket->luggageTwo->weight))
                    $count += $ticket->luggageTwo->weight;
            }
        }

        return $count;
    }

    public function checkIfSeatAvailable($col, $linha)
    {
        foreach ($this->tickets as $ticket) {
            if ($ticket->receipt->status == 'Complete') {
                if ($ticket->seatCol == $col && $ticket->seatLinha == $linha)
                    return false;
            }
        }
        return true;
    }

    public function increasePrice($percentage){
        $oldActiveTariff = $this->activeTariff();
        $oldActiveTariff->active = false;

        $tariff = new Tariff();

        $tariff->flight_id = $this->id;
        $tariff->normalPrice = $oldActiveTariff->normalPrice + $oldActiveTariff->normalPrice * $percentage;
        $tariff->economicPrice = $tariff->normalPrice - ($tariff->normalPrice * 0.25);
        $tariff->luxuryPrice = $tariff->normalPrice + ($tariff->normalPrice * 0.25);

        return $tariff->save() && $oldActiveTariff->save();

    }
}

