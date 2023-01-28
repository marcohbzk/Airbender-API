<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use common\models\User;
use backend\modules\api\components\CustomAuth;

class UserController extends ActiveController
{
    public $modelClass = 'common\models\User';


    public function behaviors()
    {
        \Yii::$app->params['id'] = 0;
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::class,
            'auth' => [$this, 'authCustom'],
        ];
        return $behaviors;
    }

    public function actions()
    {
        $actions = parent::actions();

        // dar disable de todas as actions desnecessarias
        unset($actions['create'], $actions['update'], $actions['view'], $actions['delete']);

        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'userInfo'];

        return $actions;
    }

    public function userInfo()
    {
        return $this->modelClass::findOne([\Yii::$app->params['id']]);
    }

    public function actionChange()
    {
        $model = \common\models\UserData::find()->where(['user_id' => \Yii::$app->params['id']])->one();

        $data = \Yii::$app->request->getRawBody();
        $data = json_decode($data);

        $model->fName = $data->fName ?? $model->fName;
        $model->surname = $data->surname ?? $model->surname;
        $model->phone = $data->phone ?? $model->phone;
        $model->nif = $data->nif ?? $model->nif;

        if ($model->save())
            return $this->asJson(['name' => 'Success', 'message' => 'User information changed successfully', 'code' => 200, 'status' => 200]);

        throw new \yii\web\ServerErrorHttpException(sprintf('There was an unexpected error while saving'));
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if ($action == 'update' && $model->client_id !== \Yii::$app->params['id'])
            throw new \yii\web\ForbiddenHttpException(sprintf('You only can manage your balance requests!'));
    }
}
