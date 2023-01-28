<?php

namespace frontend\controllers;

use yii;
use common\models\Client;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;
use yii\filters\VerbFilter;
use common\models\UserData;
use yii\filters\AccessControl;


class ClientController extends Controller
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
                        'actions' => ['index', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['client'],
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->identity->status == 10;
                        },
                    ],
                    [
                        'allow' => false,
                        'actions' => ['index', 'update', 'delete'],
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
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        if (!\Yii::$app->user->can('readClient')) {
            throw new \yii\web\ForbiddenHttpException('Access denied');
        }

        $client = Client::findOne([\Yii::$app->user->getId()]);
        return $this->render('index', [
            'client' => $client,
        ]);
    }

    public function actionUpdate()
    {
        if (!\Yii::$app->user->can('updateClient')) {
            throw new \yii\web\ForbiddenHttpException('Access denied');
        }
        $model = UserData::findOne([\Yii::$app->user->identity->getId()]);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            \Yii::$app->session->setFlash('success', "Account successfully updated");
            return $this->redirect(['index']);
        }

        $model->loadDefaultValues();
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete()
    {
        if (!\Yii::$app->user->can('deleteClient')) {
            throw new \yii\web\ForbiddenHttpException('Access denied');
        }

        if ($this->findModel(\Yii::$app->user->identity->getId())->user->deleteUser()) {
            \Yii::$app->session->setFlash('success', "Account successfully deleted");
            return $this->redirect(['site/index']);
        } else {
            \Yii::$app->session->setFlash('error', "Not your receipt!");
            return $this->redirect(['index']);
        }
    }

    protected function findModel($user_id)
    {
        if (($model = Client::findOne(['user_id' => $user_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
