<?php

namespace backend\controllers;

use common\models\Config;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use Exception;

use PhpMqtt\Client\MqttClient;

class ConfigController extends Controller
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
        if (!\Yii::$app->user->can('listConfig'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        $dataProvider = new ActiveDataProvider([
            'query' => Config::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        if (!\Yii::$app->user->can('readConfig'))
            throw new \yii\web\ForbiddenHttpException('Access denied');

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        if (!\Yii::$app->user->can('createConfig'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        $model = new Config();

        if ($this->request->isPost && $model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', "Config created successfully.");
                try {
                    $client = new MqttClient('127.0.0.1', 1883);
                    $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)->setUsername('android')->setPassword('a');
                    $client->connect($connectionSettings);
                    $client->publish('config', 'update', 1);
                    $client->disconnect();
                } catch (Exception $ex) {
                    throw new \yii\web\ServerErrorHttpException('There was an error while sending the message');
                }
            } else
                \Yii::$app->session->setFlash('error', "Config not saved.");
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
        if (!\Yii::$app->user->can('updateConfig'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        $model = $this->findModel($id);

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
                \Yii::$app->session->setFlash('success', "Config updated successfully.");
                try {
                    $client = new MqttClient('127.0.0.1', 1883);
                    $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)->setUsername('android')->setPassword('a');
                    $client->connect($connectionSettings);
                    $client->publish('config', 'update', 1);
                    $client->disconnect();
                } catch (Exception $ex) {
                    throw new \yii\web\ServerErrorHttpException('There was an error while sending the message');
                }
            } else
                \Yii::$app->session->setFlash('error', "Config not updated successfully.");
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        if (!\Yii::$app->user->can('deleteConfig'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        $model = $this->findModel($id);

        // desisto, isto nao faz sentido nenhum
        // '$model->active = !$model->active'
        // nao funciona por algum motivo
        $model->active = $model->active ? 0 : 1;

        if ($model->save()) {
            \Yii::$app->session->setFlash('success', "Config deleted successfully.");
            try {
                $client = new MqttClient('127.0.0.1', 1883);
                $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)->setUsername('android')->setPassword('a');
                $client->connect($connectionSettings);
                $client->publish('config', 'update', 1);
                $client->disconnect();
            } catch (Exception $ex) {
                throw new \yii\web\ServerErrorHttpException('There was an error while sending the message');
            }
        } else
            \Yii::$app->session->setFlash('error', "Config not deleted successfully.");
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Config::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
