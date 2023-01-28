<?php

namespace backend\controllers;

use common\models\Airport;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

use PhpMqtt\Client\MqttClient;
use Exception;

class AirportController extends Controller
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

    public function actionIndex($filter = null)
    {
        if (!\Yii::$app->user->can('listAirport'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        if ($filter == 'active')
            $dataProvider = new ActiveDataProvider(['query' => Airport::find()->where(['status' => 'Operational'])]);
        elseif ($filter == 'inactive')
            $dataProvider = new ActiveDataProvider(['query' => Airport::find()->where(['status' => 'Not Operational'])]);
        else
            $dataProvider = new ActiveDataProvider(['query' => Airport::find()]);


        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        if (!\Yii::$app->user->can('readAirport'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        if (!\Yii::$app->user->can('createAirport'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        $model = new Airport();

        if ($this->request->isPost && $model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', "Ariport created successfully.");
                try {
                    $client = new MqttClient('127.0.0.1', 1883);
                    $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)->setUsername('android')->setPassword('a');
                    $client->connect($connectionSettings);
                    $client->publish('airport', 'update', 0);
                    $client->disconnect();
                } catch (Exception $ex) {
                    throw new \yii\web\ServerErrorHttpException('There was an error while sending the message');
                }
            } else
                \Yii::$app->session->setFlash('error', "Airport not saved.");
            return $this->redirect(['index']);
        }

        // caso nao seja post redireciona para o formulario
        $model->loadDefaultValues();
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        if (!\Yii::$app->user->can('updateAirport'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        $model = $this->findModel($id);

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', "Airport updated successfully.");
                try {
                    $client = new MqttClient('127.0.0.1', 1883);
                    $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)->setUsername('android')->setPassword('a');
                    $client->connect($connectionSettings);
                    $client->publish('airport', 'update', 0);
                    $client->disconnect();
                } catch (Exception $ex) {
                    throw new \yii\web\ServerErrorHttpException('There was an error while sending the message');
                }
            } else
                \Yii::$app->session->setFlash('error', "Airport not updated sucessfully.");
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        if (!\Yii::$app->user->can('deleteAirport'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        $model = $this->findModel($id);

        $model = $this->findModel($id);
        if (count($model->flightsArrival) > 0 || count($model->flightsDeparture) > 0) {
            \Yii::$app->session->setFlash('error', "Airport already has flights associated with it!");
        } else {
            $model->status = $model->status == "Operational" ? "Not Operational" : "Operational";

            if ($model->save()) {
                \Yii::$app->session->setFlash('success', "Airport deleted successfully.");
                try {
                    $client = new MqttClient('127.0.0.1', 1883);
                    $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)->setUsername('android')->setPassword('a');
                    $client->connect($connectionSettings);
                    $client->publish('airport', 'update', 0);
                    $client->disconnect();
                } catch (Exception $ex) {
                    throw new \yii\web\ServerErrorHttpException('There was an error while sending the message');
                }
            } else
                \Yii::$app->session->setFlash('error', "Airport not deleted sucessfully.");
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Airport::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
