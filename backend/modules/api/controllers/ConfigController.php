<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use backend\modules\api\components\CustomAuth;

class ConfigController extends ActiveController
{
    public $modelClass = 'common\models\Config';

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
        unset($actions['delete'], $actions['create'], $actions['update'], $actions['options']);

        $actions['index']['prepareDataProvider'] = [$this, 'active'];
        return $actions;
    }

    public function active()
    {
        return $this->modelClass::find()->where(['active' => true])->all();
    }
}
