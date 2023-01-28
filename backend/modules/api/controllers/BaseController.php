<?php

namespace backend\modules\api\controllers;

class BaseController extends \yii\web\Controller
{
    public function actionError()
    {
        $exception = \Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            $response = [
                'name' => $exception->getName(),
                'message' => $exception->getMessage(),
                'code' => $exception->getCode(),
                'status' => $exception->statusCode,
                'type' => get_class($exception),
            ];
            $json = json_encode($response);

            return $json;
        }
    }
}
