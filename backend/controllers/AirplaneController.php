<?php

namespace backend\controllers;

use common\models\Airplane;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class AirplaneController extends Controller
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

    public function actionIndex()
    {
        if (!\Yii::$app->user->can('listAirplane'))
            throw new \yii\web\ForbiddenHttpException('Access denied');

        $dataProvider = new ActiveDataProvider([
            'query' => Airplane::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        if (!\Yii::$app->user->can('readAirplane'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        if (!\Yii::$app->user->can('createAirplane'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        $model = new Airplane();


        // caso seja post
        if ($this->request->isPost && $model->load(\Yii::$app->request->post())) {
            if ($model->save())
                \Yii::$app->session->setFlash('success', "Airplane created successfully.");
            else
                \Yii::$app->session->setFlash('error', "Airplane not saved.");

            return $this->redirect(['index']);
        }

        $model->loadDefaultValues();
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        if (!\Yii::$app->user->can('updateAirplane'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        $model = $this->findModel($id);

        if ($model->load(\Yii::$app->request->post())) {
            if ($model->save())
                \Yii::$app->session->setFlash('success', "Airplane updated successfully.");
            else
                \Yii::$app->session->setFlash('error', "Airplane not updated sucessfully.");

            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        if (!\Yii::$app->user->can('deleteAirplane'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        $model = $this->findModel($id);
        if (count($model->flights) > 0) {
            \Yii::$app->session->setFlash('error', "Airplane already has flights associated with it!");
        } else {
            $model->status = $model->status == "Active" ? "Not working" : "Active";

            if ($model->save())
                \Yii::$app->session->setFlash('success', "Airplane deleted successfully.");
            else
                \Yii::$app->session->setFlash('error', "Airplane not deleted.");
        }

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Airplane::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
