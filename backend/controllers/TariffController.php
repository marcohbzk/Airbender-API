<?php

namespace backend\controllers;

use common\models\Tariff;
use common\models\Flight;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;

class TariffController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['admin', 'supervisor', 'ticketOperator'],
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => false,
                        'roles' => ['client', '?'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex($flight_id)
    {
        if (!\Yii::$app->user->can('listTariff'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        $flight = Flight::findOne([$flight_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => Tariff::find()
                ->where('flight_id =' . $flight_id)
                ->orderBy(['startDate' => SORT_DESC]),
        ]);
        return $this->render('index', [
            'flight' => $flight,
            'dataProvider' => $dataProvider,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Tariff::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
