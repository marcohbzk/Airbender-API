<?php

namespace frontend\controllers;

use frontend\models\TicketBuilder;
use common\models\Ticket;
use yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use common\models\Flight;
use common\models\Config;
use common\models\Receipt;
use yii\filters\AccessControl;
use PhpMqtt\Client\MqttClient;
use Exception;

class TicketController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'view', 'history'],
                        'allow' => true,
                        'roles' => ['client'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->status == 10;
                        },
                    ],
                    [
                        'actions' => ['index', 'create', 'delete', 'view', 'history'],
                        'allow' => false,
                        'roles' => ['admin', 'supervisor', '?', 'ticketOperator'],
                    ],
                    [
                        'allow' => false,
                        'actions' => ['index', 'create', 'delete', 'view', 'history'],
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

    public function getReceipt($receipt_id)
    {

        // caso ja exista a fatura vai buscar a existente
        if (!is_null($receipt_id)) {
            $receipt = Receipt::findOne([$receipt_id]);
            if ($receipt->client_id != \Yii::$app->user->identity->getId()) {
                \Yii::$app->session->setFlash('error', "Not your receipt!");
                $this->redirect(['index']);
            }
            return $receipt;
        }

        $receipt = new Receipt();
        $receipt->purchaseDate = date('Y-m-d H:i:s');
        $receipt->total = 0;
        $receipt->status = 'Pending';
        return $receipt;
    }

    public function actionIndex()
    {
        if (!\Yii::$app->user->can('listTicket')) {
            throw new \yii\web\ForbiddenHttpException('Access denied');
        }
        $dataProvider = new ActiveDataProvider(['query' => Ticket::find()->where(['client_id' => \Yii::$app->user->identity->getId()])->andWhere('checkedIn IS NULL ')]);


        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionHistory()
    {
        if (!\Yii::$app->user->can('listTicket')) {
            throw new \yii\web\ForbiddenHttpException('Access denied');
        }
        $dataProvider = new ActiveDataProvider(['query' => Ticket::find()->where(['client_id' => \Yii::$app->user->identity->getId()])->andWhere('checkedIn > 0')]);


        return $this->render('history', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        if (!\Yii::$app->user->can('readTicket')) {
            throw new \yii\web\ForbiddenHttpException('Access denied');
        }

        $ticket = Ticket::findOne([$id]);
        if ($ticket->client_id != \Yii::$app->user->identity->getId()) {
            \Yii::$app->session->setFlash('error', "Not your ticket!");
        }

        return $this->render('view', ['model' => $ticket]);
    }

    public function actionCreate($flight_id, $tariffType, $receipt_id = null)
    {
        if (!\Yii::$app->user->can('createTicket')) {
            throw new \yii\web\ForbiddenHttpException('Access denied');
        }

        $flight = Flight::findOne([$flight_id]);

        $ticket = new TicketBuilder;

        if ($this->request->isPost) {
            $receipt = $this->getReceipt($receipt_id);
            // caso consiga criar o bilhete incrementa o current passanger
            if ($ticket->load($this->request->post()) && $ticket->generateTicket($receipt, $flight, $tariffType)) {
                return $this->redirect(['receipt/pay', 'id' => $receipt->id]);
                try {
                    $client = new MqttClient('127.0.0.1', 1883, "test-publisher");
                    $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)->setUsername('android')->setPassword('a');
                    $client->connect($connectionSettings);
                    $client->publish($ticket->client_id, 'ticket', 1);
                    $client->disconnect();
                } catch (Exception $ex) {
                    throw new \yii\web\ServerErrorHttpException('There was an error while sending the message');
                }
            }

            // caso nao crie o bilhete apaga a fatura se nao tiver mais nenhum bilhete associado
            if ($receipt->tickets == null)
                $receipt->delete();
        }

        $config = Config::find()->orderBy('price')->where('active = true')->all();

        return $this->render('create', [
            'ticket' => $ticket,
            'config' => $config,
            'flight' => $flight,
            'tariffType' => $tariffType,
        ]);
    }

    public function actionDelete($id)
    {
        if (!\Yii::$app->user->can('deleteTicket')) {
            throw new \yii\web\ForbiddenHttpException('Access denied');
        }

        $ticket = Ticket::findOne([$id]);

        if ($ticket->client_id != \Yii::$app->user->identity->getId()) {
            \Yii::$app->session->setFlash('error', "Not your ticket!");
            $this->redirect(['ticket/index']);
        }

        if ($ticket->shred()) {
            \Yii::$app->session->setFlash('success', "Successfuly deleted ticket");
            try {
                $client = new MqttClient('127.0.0.1', 1883, "test-publisher");
                $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)->setUsername('android')->setPassword('a');
                $client->connect($connectionSettings);
                $client->publish($ticket->client_id, 'ticket', 1);
                $client->disconnect();
            } catch (Exception $ex) {
                throw new \yii\web\ServerErrorHttpException('There was an error while sending the message');
            }
        } else
            \Yii::$app->session->setFlash('error', "There was an error while trying to delete the ticket");

        $this->redirect(['ticket/index']);
    }

    protected function findModel($id)
    {
        if (($model = Ticket::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
