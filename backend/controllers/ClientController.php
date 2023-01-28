<?php

namespace backend\controllers;

use PhpMqtt\Client\MqttClient;
use Exception;
use common\models\User;
use common\models\Client;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class ClientController extends Controller
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
                        'roles' => ['admin', 'supervisor'],
                    ],
                    [
                        'actions' => ['view', 'index'],
                        'allow' => true,
                        'roles' => ['ticketOperator'],
                    ],
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view'],
                        'allow' => false,
                        'roles' => ['client', '?'],
                    ],
                    [
                        'actions' => ['create', 'delete', 'update'],
                        'allow' => false,
                        'roles' => ['ticketOperator'],
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
        if (!\Yii::$app->user->can('listClient'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        $dataProvider = new ActiveDataProvider([
            'query' => User::find()
                ->innerJoin('auth_assignment', 'auth_assignment.user_id = user.id')
                ->andWhere('auth_assignment.item_name = "client"')
                ->orderBy(['status' => SORT_DESC])
                ->orderBy(['id' => SORT_ASC]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($user_id)
    {
        if (!\Yii::$app->user->can('readClient'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        return $this->render('view', [
            'model' => User::findOne([$user_id]),
        ]);
    }

    public function actionUpdate($user_id)
    {
        if (!\Yii::$app->user->can('updateClient'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        $model = $this->findModel($user_id);

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', "Client updated successfully.");
                try {
                    $client = new MqttClient('127.0.0.1', 1883, 'balance-req');
                    $client->connect();
                    $client->publish($model->user_id, 'user', 1);
                    $client->disconnect();
                } catch (Exception $ex) {
                    throw new \yii\web\ServerErrorHttpException('There was an error while sending the message');
                }
            } else
                \Yii::$app->session->setFlash('error', "Client not updated sucessfully.");
            return $this->redirect(['index']);
        }


        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($user_id)
    {
        if (!\Yii::$app->user->can('deleteClient'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        if ($this->findModel($user_id)->user->deleteUser())
            \Yii::$app->session->setFlash('success', "Client deleted successfully.");
        else
            \Yii::$app->session->setFlash('error', "Client not deleted sucessfully.");

        return $this->redirect(['index']);
    }

    protected function findModel($user_id)
    {
        if (($model = Client::findOne(['user_id' => $user_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
