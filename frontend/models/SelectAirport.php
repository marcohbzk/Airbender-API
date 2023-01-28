<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

class SelectAirport extends Model
{
    public $airportDeparture_id;
    public $airportArrival_id;
    public $departureDate = null;
    public $arrivalDate = null;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['airportDeparture_id', 'compare', 'compareValue' => 'airportArrival_id', 'operator' => '!=', 'message' => 'The destiny and arrival airports must not be the same.'],
            ['airportDeparture_id', 'required', 'message' => 'Cannot be empty'],
            ['airportArrival_id', 'required', 'message' => 'Cannot be empty'],
            ['departureDate', 'required', 'message' => 'Cannot be empty'],
            [['departureDate'], 'date', 'format' => 'yyyy-mm-dd'],
            ['departureDate', 'compare', 'compareValue' => date('Y-m-d'), 'operator' => '>='],
            ['airportDeparture_id', 'compare', 'compareAttribute' => 'airportArrival_id', 'operator' => '!='],
            ['departureDate', 'compare', 'compareValue' => date('Y-m-d'), 'operator' => '>='],
        ];
    }
}
