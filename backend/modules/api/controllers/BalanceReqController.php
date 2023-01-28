<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use backend\modules\api\components\CustomAuth;
use Exception;

class BalanceReqController extends ActiveController
{
    public $modelClass = 'common\models\BalanceReq';

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

        unset($actions['update'], $actions['create'], $actions['delete']);

        $actions['index']['prepareDataProvider'] = [$this, 'ownRequests'];

        return $actions;
    }
    public function actionCreate()
    {
        $model = new $this->modelClass;

        $data = \Yii::$app->request->getRawBody();
        $data = json_decode($data);

        $model->amount = $data->amount;
        $model->status = 'Ongoing';
        $model->requestDate = date('Y-m-d H:i:s');
        $model->client_id = \Yii::$app->params['id'];

        if ($model->save())
            return $this->asJson(['name' => 'Success', 'message' => 'Balance Request created successfully', 'code' => 200, 'status' => 200]);
        else
            throw new \yii\web\BadRequestHttpException(sprintf('Bad request'));
    }

    public function actionDelete($id)
    {
        try {
            $model = $this->modelClass::findOne([$id]);
        } catch (Exception $ex) {
            throw new \yii\web\BadRequestHttpException(sprintf('Bad request'));
        }

        $this->checkAccess('delete', $model);

        if ($model->status != 'Ongoing')
            throw new \yii\web\ForbiddenHttpException(sprintf('You cannot delete balance requests that are already decided!'));

        $balanceReqEmployee = \common\models\BalanceReqEmployee::find()->where(['balanceReq_id' => $model->id])->one();

        if ($balanceReqEmployee)
            $balanceReqEmployee->delete();

        if ($model->delete())
            return $this->asJson(['name' => 'Success', 'message' => 'Balance Request deleted successfully', 'code' => 200, 'status' => 200]);
        else
            throw new \yii\web\ServerErrorHttpException(sprintf('There was an unexpected error while trying to delete the balance request!'));
    }

    public function ownRequests()
    {
        return \common\models\Client::findOne(['user_id' => \Yii::$app->params['id']])->requests;
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if ($action == 'delete' && $model->client_id !== \Yii::$app->params['id'])
            throw new \yii\web\ForbiddenHttpException(sprintf('You only can manage your balance requests!'));
    }
}