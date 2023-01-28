<?php

namespace frontend\controllers;

use yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use common\models\Receipt;
use common\models\Client;
use yii\filters\AccessControl;
use common\models\BalanceReq;
use common\models\Ticket;

use PhpMqtt\Client\MqttClient;
use Exception;

class ReceiptController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'pay', 'update', 'delete', 'ask', 'delete-ticket'],
                        'allow' => true,
                        'roles' => ['client'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->status == 10;
                        }
                    ],
                    [
                        'allow' => false,
                        'actions' => ['index', 'view', 'pay', 'update', 'delete', 'ask', 'delete-ticket'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->status == 8;
                        },
                        'denyCallback' => function ($rule, $action) {
                            \Yii::$app->session->setFlash('error', 'You do not have sufficient permissions to perform this action');
                            \Yii::$app->response->redirect(['site/fill']);
                        },
                    ],
                    [
                        'actions' => ['index', 'view', 'pay', 'update', 'delete'],
                        'allow' => false,
                        'roles' => ['admin', 'supervisor', '?', 'ticketOperator'],
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
        if (!\Yii::$app->user->can('listReceipt')) {
            throw new \yii\web\ForbiddenHttpException('Access denied');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Receipt::find()->where(['client_id' => \Yii::$app->user->identity->getId()])
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        if (!\Yii::$app->user->can('readReceipt')) {
            throw new \yii\web\ForbiddenHttpException('Access denied');
        }

        $receipt = $this->findModel($id);

        if ($receipt->client_id != \Yii::$app->user->identity->getId()) {
            \Yii::$app->session->setFlash('error', "Not your receipt!");
            return $this->redirect(['index']);
        }

        if ($receipt->status == "Complete") {
            return $this->render('view', [
                'receipt' => $receipt,
                'client' => $receipt->client,
            ]);
        } else
            \Yii::$app->session->setFlash('error', "You need to pay first!");
        return $this->redirect(['pay', 'id' => $id]);
    }



    public function actionDelete($id)
    {
        if (!\Yii::$app->user->can('deleteReceipt')) {
            throw new \yii\web\ForbiddenHttpException('Access denied');
        }

        $receipt = $this->findModel($id);

        if ($receipt->client_id != \Yii::$app->user->identity->getId()) {
            \Yii::$app->session->setFlash('error', "Not your receipt!");
            return $this->redirect(['index']);
        }

        if ($receipt->status == 'Complete') {
            \Yii::$app->session->setFlash('error', "Cannot delete a complete receipt");
            return $this->redirect(['index']);
        }

        if ($receipt->shred()) {
            \Yii::$app->session->setFlash('success', "Receipt deleted successfully!");
            try {
                $client = new MqttClient('127.0.0.1', 1883);
                $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)->setUsername('android')->setPassword('a');
                $client->connect($connectionSettings);
                $client->publish($receipt->client_id, 'ticket', 1);
                $client->disconnect();
            } catch (Exception $ex) {
                throw new \yii\web\ServerErrorHttpException('There was an error while sending the message');
            }
        } else
            \Yii::$app->session->setFlash('error', "The receipt couldn't be deleted because it's already completed.");

        return $this->redirect(['index']);
    }

    public function actionDeleteTicket($id)
    {

        $ticket = Ticket::findOne([$id]);
        $receipt = $ticket->receipt;

        if ($receipt->status == 'Complete') {
            \Yii::$app->session->setFlash('error', "Cannot delete a ticket from a complete receipt");
            return $this->redirect(['index']);
        }

        if ($receipt->client_id != \Yii::$app->user->identity->getId()) {
            \Yii::$app->session->setFlash('error', "Not your receipt!");
            return $this->redirect(['index']);
        }

        if ($ticket->shred()) {
            \Yii::$app->session->setFlash('success', "Ticket deleted successfully!");
            try {
                $client = new MqttClient('127.0.0.1', 1883);
                $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)->setUsername('android')->setPassword('a');
                $client->connect($connectionSettings);
                $client->publish($receipt->client_id, 'ticket', 1);
                $client->disconnect();
            } catch (Exception $ex) {
                throw new \yii\web\ServerErrorHttpException('There was an error while sending the message');
            }
        } else
            \Yii::$app->session->setFlash('error', "The ticket couldn't be deleted.");

        if (count($receipt->tickets) > 0)
            return $this->redirect(['pay', 'id' => $receipt->id]);
        else {
            $receipt->shred();
            return $this->redirect(['index']);
        }
    }

    public function actionPay($id)
    {
        if (!\Yii::$app->user->can('updateReceipt')) {
            throw new \yii\web\ForbiddenHttpException('Access denied');
        }

        $receipt = $this->findModel($id);
        $client = Client::findOne([\Yii::$app->user->identity->getId()]);
        $receipt->refreshTotal();

        if ($receipt->client_id != \Yii::$app->user->identity->getId()) {
            \Yii::$app->session->setFlash('error', "Not your receipt!");
            return $this->redirect(['index']);
        }

        // verificar se a fatura ja nao foi paga
        if ($receipt->status == "Complete") {
            \Yii::$app->session->setFlash('error', "This receipt is already completed");
            return $this->redirect(['index']);
        }
        // caso seja post
        if ($this->request->isPost) {
            // verificar se o cliente tem saldo suficiente
            if (($client->application ? $receipt->total - $receipt->total * 0.05 : $receipt->total) <= $client->balance) {
                // descontar da conta do cliente dependendo se tem aplicacao ou nao
                $client->balance -= $client->application ? $receipt->total - $receipt->total * 0.05 : $receipt->total;

                // modificar o status da fatura
                $receipt->status = "Complete";
                $receipt->purchaseDate = date('Y-m-d H:i:s');

                $receipt->updateTicketPrices();

                // avisar o cliente se conseguiu guardar ou nao 
                if ($client->save() && $receipt->save()) {
                    try {
                        $client = new MqttClient('127.0.0.1', 1883);
                        $client->connect();
                        $client->publish($receipt->client_id, 'ticket', 1);
                        $client->disconnect();
                    } catch (Exception $ex) {
                        throw new \yii\web\ServerErrorHttpException('There was an error while sending the message');
                    }
                    \Yii::$app->session->setFlash('success', "Purchase completed successfully!");
                    return $this->redirect(['view', 'id' => $receipt->id]);
                } else
                    \Yii::$app->session->setFlash('error', "There was an error while completing the payment, please try again later.");
            } else
                \Yii::$app->session->setFlash('error', "You don't have enough balance");
        }

        return $this->render('pay', [
            'receipt' => $receipt,
            'client' => $client,
        ]);
    }
    public function actionAsk($id)
    {
        if (!\Yii::$app->user->can('createBalanceReq')) {
            throw new \yii\web\ForbiddenHttpException('Access denied');
        }

        $receipt = $this->findModel($id);

        $client = Client::findOne([$receipt->client_id]);

        if ($receipt->client_id != \Yii::$app->user->identity->getId()) {
            \Yii::$app->session->setFlash('error', "Not your receipt!");
            return $this->redirect(['index']);
        }

        if ($client->balance > ($client->application ? $receipt->total - $receipt->total * 0.05 : $receipt->total)) {
            \Yii::$app->session->setFlash('error', "You already have enought balance!");
            return $this->redirect(['pay', 'id' => $receipt->id]);
        }

        $req = new BalanceReq();
        $req->client_id = $receipt->client_id;
        $req->amount = ($client->application ? $receipt->total - $receipt->total * 0.05 : $receipt->total) - $client->balance;
        $req->requestDate = date('Y-m-d H:i:s');
        if ($req->save()) {
            \Yii::$app->session->setFlash('success', "Successfully requested " . $req->amount . "€");
            try {
                $client = new MqttClient('127.0.0.1', 1883);
                $client->connect();
                $client->publish($receipt->client_id, 'ticket', 1);
                $client->disconnect();
            } catch (Exception $ex) {
                throw new \yii\web\ServerErrorHttpException('There was an error while sending the message');
            }
        } else
            \Yii::$app->session->setFlash('error', "There was an error while completing the balance request, please try again later.");

        return $this->redirect(['pay', 'id' => $receipt->id]);
    }
    protected function findModel($id)
    {
        if (($model = Receipt::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
