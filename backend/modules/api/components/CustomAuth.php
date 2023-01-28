<?php

namespace backend\modules\api\components;

use yii\filters\auth\AuthMethod;

class CustomAuth extends AuthMethod
{
    public $auth;

    public function authenticate($user, $request, $response)
    {
        $token = \Yii::$app->request->getQueryParam('access-token');
        if ($this->auth) {
            $user = \common\models\User::findIdentityByAccessToken($token);
            if (!$user) {
                throw new \yii\web\ForbiddenHttpException('No authentication'); //403
            }
            \Yii::$app->params['id'] = $user->id;
            \Yii::$app->params['role'] = $user->authAssignment->item_name;
            return $user;
        }
    }
}
