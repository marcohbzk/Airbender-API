<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "airplanes".
 *
 * @property int $id
 * @property int $luggageCapacity
 * @property int $minLinha
 * @property string $minCol
 * @property int $maxLinha
 * @property string $maxCol
 * @property string $economicStart
 * @property string $economicStop
 * @property string $normalStart
 * @property string $normalStop
 * @property string $luxuryStart
 * @property string $luxuryStop
 * @property string|null $status
 *
 * @property Flights[] $flights
 */
class Airplane extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'airplanes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['luggageCapacity', 'minLinha', 'minCol', 'maxLinha', 'maxCol', 'economicStart', 'economicStop', 'normalStart', 'normalStop', 'luxuryStart', 'luxuryStop'], 'required'],
            [['luggageCapacity', 'minLinha', 'maxLinha'], 'integer'],
            [['status'], 'string'],
            ['status', 'in', 'range' => ['Active', 'Not working']],
            [['minCol', 'maxCol', 'economicStart', 'economicStop', 'normalStart', 'normalStop', 'luxuryStart', 'luxuryStop'], 'string', 'max' => 1],
            [['maxLinha'], 'integer', 'min' => 1, 'max' => 18, 'tooSmall' => 'The maximum line must be between 1 and 9', 'tooBig' => 'The maximum line must be between 1 and 9'],
            //Se a letra da minCol for "maior" que a letra da maxCol vai dar erro
            ['minCol', 'compare', 'compareAttribute' => 'maxCol', 'operator' => '<=', 'message' => 'The minimum column must be before the maximum column.'],
            ['maxCol', 'compare', 'compareValue' => 'L', 'operator' => '<=', 'message' => 'Columns range from A to L.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'luggageCapacity' => 'Luggage Capacity',
            'minLinha' => 'Min Row',
            'minCol' => 'Min Col',
            'maxLinha' => 'Max Row',
            'maxCol' => 'Max Col',
            'economicStart' => 'Economic Start',
            'economicStop' => 'Economic Stop',
            'normalStart' => 'Normal Start',
            'normalStop' => 'Normal Stop',
            'luxuryStart' => 'Luxury Start',
            'luxuryStop' => 'Luxury Stop',
            'status' => 'Status',
        ];
    }

    /**
     * Gets query for [[Flights]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFlights()
    {
        return $this->hasMany(Flight::class, ['airplane_id' => 'id']);
    }

    public function countTotalSeats()
    {
        $count = 0;
        for ($i = $this->minCol; $i <= $this->maxCol; $i++) {
            for ($j = $this->minLinha; $j <= $this->maxLinha; $j++) {
                $count++;
            }
        }
        return $count;
    }

    public function checkSeatClass($col)
    {
        // check luxury
        for ($i = $this->luxuryStart; $i <= $this->luxuryStop; $i++) {
            if ($col == $i) {
                return 'luxury';
            }
        }
        // check normal
        for ($i = $this->normalStart; $i <= $this->normalStop; $i++) {
            if ($col == $i) {
                return 'normal';
            }
        }
        // check economic
        for ($i = $this->economicStart; $i <= $this->economicStop; $i++) {
            if ($col == $i) {
                return 'economic';
            }
        }
    }
    public function getSeats()
    {
        // function to return an arrau with all the airplane seats  ex: A-1 A-2 A-3 etc..
        for ($i = $this->minCol; $i <= $this->maxCol; $i++) {
            for ($j = $this->minLinha; $j <= $this->maxLinha; $j++) {
                $seats[$i][$j] = ['status' => 1, 'type' => $this->checkSeatClass($i)];
            }
        }
        return $seats;
    }
}
