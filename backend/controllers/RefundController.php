<?php

namespace backend\controllers;

use common\models\Refund;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class RefundController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['admin','supervisor'],
                    ],
                   
                    [
                        'actions' => [],
                        'allow' => false,
                        'roles' => ['client', '?','ticketOperator'],
                    ],
                    
                ],
            ],
        ];
    }


    protected function findModel($id)
    {
        if (($model = Refund::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}

