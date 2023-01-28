<?php

namespace backend\modules\api\controllers;

use common\models\User;

class LoginController extends \yii\web\Controller
{
    public $user;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => \yii\filters\auth\HttpBasicAuth::className(),
            //’except' => ['index', 'view'], //Excluir aos GETs
            'auth' => [$this, 'auth']
        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        // dar disable de todas as actions desnecessarias
        unset($actions['delete'], $actions['create'], $actions['view'], $actions['update'], $actions['options']);

        return $actions;
    }

    public function auth($username, $password)
    {
        $user = User::findByUsername($username);
        if ($user && $user->validatePassword($password)) {
            $this->user = $user;
            return $user;
        }
        throw new \yii\web\ForbiddenHttpException('No authentication'); //403
    }

    public function actionIndex()
    {
        if ($this->user->client) {
            if (!$this->user->client->application) {
                $this->user->client->application = true;
                $this->user->client->save();
            }
        }

        $response['id'] = $this->user->id;
        $response['username'] = $this->user->username;
        $response['fName'] = $this->user->userData->fName;
        $response['surname'] = $this->user->userData->surname;
        $response['birthdate'] = $this->user->userData->birthdate;
        $response['gender'] = $this->user->userData->gender;
        $response['nif'] = $this->user->userData->nif;
        $response['phone'] = $this->user->userData->phone;
        $response['balance'] = $this->user->client->balance ?? 0;
        $response['role'] = $this->user->authAssignment->item_name;
        $response['token'] = $this->user->auth_key;

        return json_encode($response);
    }
}
