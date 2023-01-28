<?php

namespace backend\controllers;

use common\models\LoginForm;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

use common\backend\Employee;
use common\models\User;
use common\models\BalanceReq;
use common\models\Airport;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['index'],
                        'roles' => ['client'],
                        'denyCallback' => function ($rule, $action) {
                            Yii::$app->user->logout();
                            \Yii::$app->response->redirect(['../../frontend/web/site/login']);
                        },
                    ],
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['admin', 'supervisor', 'ticketOperator'],
                    ],
                    [
                        'actions' => ['index'],
                        'allow' => false,
                        'roles' => ['client', '?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
        ];
    }

    public function actionIndex()
    {
        $airports = [
            'count' => Airport::find()->count(),
            'mostSearched' => Airport::find()->orderBy(['search' => SORT_DESC])->one(),
            'leastSearched' => Airport::find()->orderBy(['search' => SORT_ASC])->one(),
        ];

        $clients = [
            'count' => User::find()->where('status=10')
                ->innerJoin('auth_assignment', 'auth_assignment.user_id = user.id')
                ->andWhere('auth_assignment.item_name = "client"')->count(),
        ];

        $balanceReq = [
            'count' => BalanceReq::find()->where(['status' => 'Ongoing'])->count(),
        ];

        return $this->render('index', [
            'airports' => $airports,
            'balanceReq' => $balanceReq,
            'clients' => $clients,
        ]);
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
