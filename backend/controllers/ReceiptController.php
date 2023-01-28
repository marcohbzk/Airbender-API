<?php

namespace backend\controllers;

use common\models\Receipt;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class ReceiptController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['admin', 'supervisor'],
                    ],


                    [
                        'actions' => ['index',  'view'],
                        'allow' => false,
                        'roles' => ['client', '?', 'ticketOperator'],
                    ],

                ],
            ],
        ];
    }

    public function actionIndex($client_id = null)
    {
        if (!\Yii::$app->user->can('listReceipt'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        $dataProvider = new ActiveDataProvider([
            'query' => Receipt::find()->where(['client_id' => $client_id]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        if (!\Yii::$app->user->can('readReceipt'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        return $this->render('view', [
            'receipt' => $this->findModel($id),
        ]);
    }


    protected function findModel($id)
    {
        if (($model = Receipt::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
