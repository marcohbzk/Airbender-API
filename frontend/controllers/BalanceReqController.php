<?php

namespace frontend\controllers;

use yii;
use common\models\BalanceReq;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use common\models\Client;
use yii\filters\AccessControl;
use PhpMqtt\Client\MqttClient;
use Exception;

class BalanceReqController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'create', 'history', 'delete', 'view'],
                        'allow' => true,
                        'roles' => ['client'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->status == 10;
                        },
                    ],
                    [
                        'actions' => ['index', 'create', 'history', 'delete'],
                        'allow' => false,
                        'roles' => ['admin', 'supervisor', '?', 'ticketOperator'],
                    ],
                    [
                        'allow' => false,
                        'actions' => ['index', 'create', 'delete', 'history', 'view'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->status == 8;
                        },
                        'denyCallback' => function ($rule, $action) {
                            \Yii::$app->session->setFlash('error', 'You do not have sufficient permissions to perform this action');
                            \Yii::$app->response->redirect(['site/fill']);
                        },
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

    public function actionIndex()
    {
        if (!\Yii::$app->user->can('listBalanceReq')) {
            throw new \yii\web\ForbiddenHttpException('Access denied');
        }

        $client = Client::findOne([\Yii::$app->user->getId()]);

        $dataProvider = new ActiveDataProvider([
            'query' => BalanceReq::find()
                ->where(['client_id' => $client->user_id])
                ->andWhere(['status' => 'Ongoing']),
            'pagination' => [
                'pageSize' => 5
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ]
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'client' => $client,
        ]);
    }

    public function actionView($id)
    {
        if (!\Yii::$app->user->can('readBalanceReq')) {
            throw new \yii\web\ForbiddenHttpException('Access denied');
        }


        $balanceReq = $this->findModel($id);

        if ($balanceReq->client_id != \Yii::$app->user->identity->getId()) {
            \Yii::$app->session->setFlash('error', "Not your receipt!");
            return $this->redirect(['index']);
        }

        return $this->render('view', [
            'model' => $balanceReq,
        ]);
    }

    public function actionCreate()
    {
        if (!\Yii::$app->user->can('createBalanceReq')) {
            throw new \yii\web\ForbiddenHttpException('Access denied');
        }

        $model = new BalanceReq();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->client_id == \Yii::$app->user->identity->getId() && $model->save()) {
                return $this->redirect(['index']);
                try {
                    $client = new MqttClient('127.0.0.1', 1883, "test-publisher");
                    $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)->setUsername('android')->setPassword('a');
                    $client->connect($connectionSettings);
                    $client->publish($model->client_id, 'request', 1);
                    $client->loop(true, true);
                    $client->disconnect();
                } catch (Exception $ex) {
                    throw new \yii\web\ServerErrorHttpException('There was an error while sending the message');
                }
            }
        }

        $model->loadDefaultValues();
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        if (!\Yii::$app->user->can('deleteBalanceReq')) {
            throw new \yii\web\ForbiddenHttpException('Access denied');
        }

        $balanceReq = $this->findModel($id);

        if ($balanceReq->client_id != \Yii::$app->user->identity->getid()) {
            \Yii::$app->session->setflash('error', "cannot delete another's balance requests");
            return $this->redirect(['index']);
        }

        if ($balanceReq->deleteBalanceReq()) {
            \Yii::$app->session->setFlash('success', "Balance requests succesfully deleted");
            try {
                $client = new MqttClient('127.0.0.1', 1883, "test-publisher");
                $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)->setUsername('android')->setPassword('a');
                $client->connect($connectionSettings);
                $client->publish($balanceReq->client_id, 'request', 1);
                $client->loop(true, true);
                $client->disconnect();
            } catch (Exception $ex) {
                throw new \yii\web\ServerErrorHttpException('There was an error while sending the message');
            }
        } else
            \Yii::$app->session->setFlash('error', "There was an error while trying to delete the balance request");

        return $this->redirect(['index']);
    }

    public function actionHistory()
    {
        if (!\Yii::$app->user->can('listBalanceReq')) {
            throw new \yii\web\ForbiddenHttpException('Access denied');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => BalanceReq::find()->where('status="Accepted" OR status="Declined"')->andWhere(['client_id' => \Yii::$app->user->identity->getid()]),
            'pagination' => [
                'pageSize' => 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_ASC,
                ]
            ],

        ]);

        return $this->render('history', [
            'dataProvider' => $dataProvider,
        ]);
    }

    protected function findModel($id)
    {
        if (($model = BalanceReq::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
