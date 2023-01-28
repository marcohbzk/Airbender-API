<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use common\models\Flight;
use backend\modules\api\components\CustomAuth;

class FlightController extends ActiveController
{
    public $modelClass = 'common\models\Flight';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::class,
            'auth' => [$this, 'authCustom'],
        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        // dar disable de todas as actions desnecessarias
        unset($actions['index'], $actions['view'], $actions['delete'], $actions['create'], $actions['update'], $actions['options']);


        return $actions;
    }

    public function actionView($id)
    {
        $flight =  Flight::find()
            ->with('tariff')
            ->with('airplane')
            ->where(['id' => $id])
            ->one();

        $error = ['name' => 'Success', 'message' => 'No tickets were found', 'code' => 204, 'status' => 204];
        return $flight ? $flight : json_encode($error);
    }

    public function actionFind($airportDeparture, $airportArrival, $departureDate)
    {
        $flights = Flight::find()
            ->with('tariff')
            ->with('airplane')
            ->where('airportDeparture_id = ' . $airportDeparture)
            ->andWhere('airportArrival_id = ' . $airportArrival)
            ->all();

            if (count($flights) > 0) {
                foreach ($flights as $flight) {
                    $interval[$flight->id] = abs(strtotime($flight->departureDate) - strtotime($departureDate));
                }
                asort($interval);
                $selectedFlight = Flight::findOne([key($interval)]);

                // se esta action nao for chamada por post
                $flights = Flight::find()
                    ->where('airportDeparture_id = ' . $airportDeparture)
                    ->andWhere('airportArrival_id = ' . $airportArrival)
                    ->andWhere(['status' => 'Available'])
                    ->orderBy('departureDate')
                    ->one();
            }

        $error = ['name' => 'Success', 'message' => 'No tickets were found', 'code' => 204, 'status' => 204];
        return $flights ? $flights : json_encode($error);
    }
}
