<?php

namespace backend\controllers;

use backend\models\RegisterEmployee;
use backend\models\Employee;
use common\models\User;
use common\models\Airport;
use common\models\UserData;
use common\models\Client;
use common\models\Airplane;
use common\models\Flight;
use common\models\Tariff;
use yii\helpers\ArrayHelper;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class EmployeeController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view', 'activate'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['supervisor', 'ticketOperator'],
                    ],
                    [
                        'actions' => ['index', 'create', 'delete', 'update', 'view'],
                        'allow' => false,
                        'roles' => ['client', '?'],
                    ],
                    [
                        'actions' => ['index', 'create', 'delete', 'activate', 'update'],
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
        if (!\Yii::$app->user->can('listEmployee'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        $dataProvider = new ActiveDataProvider([
            'query' => User::find()->where('auth_assignment.item_name != "client"')
                ->innerJoin('auth_assignment', 'auth_assignment.user_id = user.id')
                ->orderBy(['status' => SORT_DESC])
                ->orderBy(['id' => SORT_ASC]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($user_id)
    {
        if (!\Yii::$app->user->can('readEmployee'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        if (User::findOne([\Yii::$app->user->identity->getId()])->authAssignment->item_name == 'admin')
            $user = User::findOne([$user_id]);
        else
            $user = User::findOne([\Yii::$app->user->identity->getId()]);

        return $this->render('view', [
            'model' => $user
        ]);
    }

    public function actionCreate()
    {
        if (!\Yii::$app->user->can('createEmployee'))
            throw new \yii\web\ForbiddenHttpException('Access denied');

        $model = new RegisterEmployee();

        if ($this->request->isPost && $model->load(\Yii::$app->request->post()) && $model->register()) {
            \Yii::$app->session->setFlash('success', "Employee created successfully.");
            $this->redirect(['index']);
        }

        $airports = ArrayHelper::map(Airport::find()->asArray()->all(), 'id', 'city', 'country');
        $roles = $this->filtrarRoles(\Yii::$app->authManager->getRoles());

        return $this->render('create', [
            'model' => $model,
            'airports' => $airports,
            'roles' => $roles
        ]);
    }

    public function actionUpdate($user_id)
    {
        if (!\Yii::$app->user->can('updateEmployee'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        $user = User::findOne([$user_id]);

        $model = new RegisterEmployee();

        $model->setUser($user);



        if ($this->request->isPost && $model->load(\Yii::$app->request->post()) && $model->update($user_id)) {
            \Yii::$app->session->setFlash('success', "Employee updated successfully.");
            return $this->redirect(['index']);
        }

        $airports = ArrayHelper::map(Airport::find()->asArray()->all(), 'id', 'city', 'country');
        $roles = $this->filtrarRoles(\Yii::$app->authManager->getRoles());

        return $this->render('update', [
            'model' => $model,
            'airports' => $airports,
            'roles' => $roles
        ]);
    }

    public function actionDelete($user_id)
    {
        if (!\Yii::$app->user->can('deleteEmployee'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        if (User::findOne([$user_id])->deleteUser())
            \Yii::$app->session->setFlash('success', "Employee deleted successfully.");
        else
            \Yii::$app->session->setFlash('error', "Employee not deleted successfully.");
        return $this->redirect(['index']);
    }

    public function actionActivate($user_id)
    {
        if (!\Yii::$app->user->can('deleteEmployee'))
            throw new \yii\web\ForbiddenHttpException('Access denied');


        if (User::findOne([$user_id])->activate())
            \Yii::$app->session->setFlash('success', "Employee activated successfully.");
        else
            \Yii::$app->session->setFlash('error', "Employee not activated successfully.");
        return $this->redirect(['index']);
    }

    protected function findModel($user_id)
    {
        if (($model = Employee::findOne(['user_id' => $user_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function filtrarRoles($temp)
    {
        $roles = [];

        // filtrar as roles
        foreach ($temp as $role) {
            if ($role->name != 'client') {
                $roles[$role->name] = $role->name;
            }
        }
        return $roles;
    }
}
