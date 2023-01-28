<?php

namespace backend\controllers;

use common\models\Flight;
use common\models\Tariff;
use backend\models\CreateFlight;
use common\models\Airport;
use common\models\Airplane;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use yii\helpers\ArrayHelper;

class FlightController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['view', 'index'],
                        'allow' => true,
                        'roles' => ['supervisor', 'ticketOperator'],
                    ],
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view'],
                        'allow' => false,
                        'roles' => ['client', '?'],
                    ],
                    [
                        'actions' => ['create', 'delete', 'update'],
                        'allow' => false,
                        'roles' => ['supervisor', 'ticketOperator'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex($airport_id = null)
    {
        if (!\Yii::$app->user->can('listFlight'))
            throw new \yii\web\ForbiddenHttpException('Access denied');

        $dataProvider = new ActiveDataProvider([
            'query' => Flight::find(),
            'sort' => [
                'defaultOrder' => [
                    'departureDate' => SORT_ASC,
                ]
            ],
        ]);

        if (!is_null($airport_id)) {
            $dataProvider = new ActiveDataProvider([
                'query' => Flight::find()->where('airportDeparture_id=' . $airport_id . ' OR airportArrival_id=' . $airport_id),
                'sort' => [
                    'defaultOrder' => [
                        'departureDate' => SORT_ASC,
                    ]
                ],
            ]);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        if (!\Yii::$app->user->can('readFlight'))
            throw new \yii\web\ForbiddenHttpException('Access denied');

        $flight = $this->findModel($id);


        return $this->render('view', [
            'model' => $flight,
        ]);
    }

    public function actionCreate()
    {
        if (!\Yii::$app->user->can('createFlight'))
            throw new \yii\web\ForbiddenHttpException('Access denied');

        $model = new CreateFlight();


        // caso seja post
        if ($this->request->isPost && $model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', "Flight created successfully.");
            } else {
                \Yii::$app->session->setFlash('error', "Flight not saved.");
            }
            return $this->redirect(['index']);
        }

        $airports = ArrayHelper::map(Airport::find()->where('status = "Operational"')->asArray()->all(), 'id', 'city', 'country');
        $airplanes = ArrayHelper::map(Airplane::find()->where('status = "Active"')->asArray()->all(), 'id', 'id');

        return $this->render('create', [
            'model' => $model,
            'airports' => $airports,
            'airplanes' => $airplanes,
        ]);
    }

    public function actionUpdate($id)
    {
        if (!\Yii::$app->user->can('updateFlight'))
            throw new \yii\web\ForbiddenHttpException('Access denied');

        $model = $this->findModel($id);

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', "Flight updated successfully.");
            } else {
                \Yii::$app->session->setFlash('error', "Flight not updated sucessfully.");
            }
            return $this->redirect(['index']);
        }

        $airports = ArrayHelper::map(Airport::find()->asArray()->all(), 'id', 'city', 'country');
        $airplanes = ArrayHelper::map(Airplane::find()->asArray()->all(), 'id', 'id');

        return $this->render('update', [
            'model' => $model,
            'airports' => $airports,
            'airplanes' => $airplanes,
        ]);
        \Yii::$app->session->setFlash('error', 'You are a fucking nigger');
    }

    public function actionDelete($id)
    {
        if (!\Yii::$app->user->can('deleteFlight'))
            throw new \yii\web\ForbiddenHttpException('Access denied');

        $model = $this->findModel($id);

        if ($model->countBoughtTickets() > 0) {
            \Yii::$app->session->setFlash('error', "Flight already has tickets bought to it, to it cant be deleted.");
        } else {
            $model->status = 'Canceled';
            if ($model->save())
                \Yii::$app->session->setFlash('success', "Flight deleted successfully.");
            else
                \Yii::$app->session->setFlash('error', "Flight not deleted.");
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Flight::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
