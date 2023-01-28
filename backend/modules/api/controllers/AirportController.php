<?php

namespace backend\modules\api\controllers;

use yii\rest\ActiveController;
use common\models\Airport;
use backend\modules\api\components\CustomAuth;

class AirportController extends ActiveController
{
    public $modelClass = 'common\models\Airport';

    public function behaviors()
    {
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

        // customize the data provider preparation with the "prepareDataProvider()" method
        $actions['index']['prepareDataProvider'] = [$this, 'operational'];
        return $actions;
    }

    public function operational()
    {
        return Airport::find()->where(['status' => 'Operational'])->all();
    }
}
