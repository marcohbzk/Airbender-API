<?php

namespace backend\modules\api\controllers;

use HttpResponse;
use yii\rest\ActiveController;
use backend\modules\api\components\CustomAuth;
use common\models\Ticket;
use common\models\Flight;
use common\models\Receipt;
use common\models\Client;
use Exception;
use DateTime;

use PhpMqtt\Client\MqttClient;

class TicketController extends ActiveController
{
    public $modelClass = 'common\models\Ticket';

    public function behaviors()
    {
        \Yii::$app->params['id'] = 0;
        \Yii::$app->params['role'] = null;
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


        unset($actions['index'], $actions['view'], $actions['create'], $actions['delete']);

        return $actions;
    }


    public function actionUpcoming()
    {
        $temp = Ticket::find()->where(['client_id' => \Yii::$app->params['id']])
            ->with('tariff')
            ->with('flight')
            ->with('flight.airplane')
            ->with('flight.airportDeparture')
            ->with('flight.airportArrival')
            ->with('receipt')
            ->all();

        $tickets = [];
        foreach ($temp as $ticket)
            if ($ticket->receipt->status == 'Complete' && (new DateTime($ticket->flight->departureDate)) > date('Y-m-d H:i:s') && is_null($ticket->checkedIn))
                $tickets[] = $ticket;

        $error = ['name' => 'Success', 'message' => 'No tickets were found', 'code' => 204, 'status' => 204];
        return $tickets ? $tickets : json_encode($error);
    }

    public function actionPending()
    {
        $temp = Ticket::find()->where(['client_id' => \Yii::$app->params['id']])
            ->with('tariff')
            ->with('flight')
            ->with('flight.airplane')
            ->with('flight.airportDeparture')
            ->with('flight.airportArrival')
            ->with('receipt')
            ->all();

        $tickets = [];
        foreach ($temp as $ticket)
            if ($ticket->receipt->status == 'Pending')
                $tickets[] = $ticket;

        $error = ['name' => 'Success', 'message' => 'No tickets were found', 'code' => 204, 'status' => 204];
        return $tickets ? $tickets : json_encode($error);
    }

    public function actionPast()
    {
        $temp = Ticket::find()->where(['client_id' => \Yii::$app->params['id']])
            ->with('tariff')
            ->with('flight')
            ->with('flight.airplane')
            ->with('flight.airportDeparture')
            ->with('flight.airportArrival')
            ->with('receipt')
            ->all();

        $tickets = [];
        foreach ($temp as $ticket)
            if ($ticket->receipt->status == 'Complete' && !is_null($ticket->checkedIn))
                $tickets[] = $ticket;

        $error = ['name' => 'Success', 'message' => 'No tickets were found', 'code' => 204, 'status' => 204];
        return $tickets ? $tickets : json_encode($error);
    }

    public function actionView($id)
    {
        $ticket = Ticket::find()->where(['id' => $id])
            ->with('tariff')
            ->with('flight')
            ->with('receipt')
            ->one();

        if ($ticket)
            $this->checkAccess('view', $ticket);

        $error = ['name' => 'Success', 'message' => 'No tickets were found', 'code' => 204, 'status' => 204];
        return $ticket ? $ticket : json_encode($error);
    }

    public function actionDelete($id)
    {
        $model = $this->modelClass::findOne(['id' => $id]);
        if (!$model) {
            $error = ['name' => 'Success', 'message' => 'No tickets were found', 'code' => 204, 'status' => 204];
            return $model ? $model : json_encode($error);
        }

        $this->checkAccess('delete', $model);

        if ($model->receipt->status == 'Complete')
            throw new \yii\web\ForbiddenHttpException(sprintf('Cannot deleted paid tickets'));

        if ($model->checkedIn != NULL)
            throw new \yii\web\ForbiddenHttpException(sprintf('Ticket already checked in!'));

        if ($model->delete())
            return $this->asJson(['name' => 'Success', 'message' => 'Ticket created successfully', 'code' => 200, 'status' => 200]);
        else
            throw new \yii\web\ServerErrorHttpException(sprintf('There was an error while deleting the ticket'));
    }
    public function actionCreate()
    {
        $model = new $this->modelClass;

        $receipt = new Receipt();
        $receipt->purchaseDate = date('Y-m-d H:i:s');
        $receipt->total = 0;
        $receipt->status = 'Pending';
        $receipt->client_id = \Yii::$app->params['id'];

        if (!$receipt->save())
            throw new \yii\web\ServerErrorHttpException(sprintf('There was an unexpected error while saving'));

        $data = \Yii::$app->request->getRawBody();
        $data = json_decode($data);

        $model->receipt_id = $receipt->id;
        $model->client_id = $receipt->client_id;

        // $model->load nao funciona por algum motivo
        $model->fName = isset($data->fName) ? $data->fName : null;
        $model->surname = isset($data->surname) ? $data->surname : null;
        $model->age = isset($data->age) ? $data->age : null;
        $model->gender = isset($data->gender) ? $data->gender : null;
        $model->seatLinha = isset($data->seatLinha) ? $data->seatLinha : null;
        $model->seatCol = isset($data->seatCol) ? $data->seatCol : null;
        $model->flight_id = isset($data->flight_id) ? $data->flight_id : null;
        $model->tariffType = isset($data->tariffType) ? $data->tariffType : null;

        $flight = Flight::findOne([$model->flight_id]);

        if ($flight)
            $model->tariff_id = $flight->activeTariff()->id;


        if ($flight->status != 'Available')
            throw new \yii\web\BadRequestHttpException(sprintf('Flight is not available'));

        if (!$flight->checkIfSeatAvailable($model->seatCol, $model->seatLinha))
            throw new \yii\web\BadRequestHttpException(sprintf('Seats are already taken!'));

        if ($model->save()) {
            return $this->asJson(['name' => 'Success', 'message' => 'Ticket created successfully', 'code' => 200, 'status' => 200]);
        } else {
            throw new \yii\web\BadRequestHttpException(sprintf('Error while saving!'));
        }
    }

    public function actionPay($id)
    {
        $model = $this->modelClass::findOne(['id' => $id]);

        if (!$model)
            throw new \yii\web\NotFoundHttpException(sprintf('Ticket not found'));

        $this->checkAccess('pay', $model);

        $receipt = Receipt::findOne([$model->receipt_id]);
        $client = Client::findOne([\Yii::$app->params['id']]);
        $receipt->refreshTotal();

        // verificar se a fatura ja nao foi paga
        if ($receipt->status == "Complete")
            throw new \yii\web\ForbiddenHttpException(sprintf('Ticket already paid for'));

        if (($client->application ? $receipt->total - $receipt->total * 0.05 : $receipt->total) > $client->balance)
            throw new \yii\web\ForbiddenHttpException(sprintf('Not enough money to pay for the ticket'));


        // descontar da conta do cliente dependendo se tem aplicacao ou nao
        $client->balance -= $client->application ? $receipt->total - $receipt->total * 0.05 : $receipt->total;

        // modificar o status da fatura
        $receipt->status = "Complete";
        $receipt->purchaseDate = date('Y-m-d H:i:s');

        $receipt->updateTicketPrices();

        try {
            $c= new MqttClient('127.0.0.1', 1883, "test-publisher");
            $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)->setUsername('android')->setPassword('a');
            $c->connect($connectionSettings);
            $c->publish($model->client_id, 'ticket', 1);
            $c->disconnect();
        } catch (Exception $ex) {
            throw new \yii\web\ServerErrorHttpException('There was an error while sending the message');
        }

        // avisar o cliente se conseguiu guardar ou nao 
        if (!$client->save() || !$receipt->save())
            throw new \yii\web\ServerErrorHttpException(sprintf('There was an unexpected error while saving'));

        return $this->asJson(['name' => 'Success', 'message' => 'Ticket bought successfully', 'code' => 200, 'status' => 200]);
    }

    public function actionCheckin($id)
    {
        $model = $this->modelClass::findOne(['id' => $id]);

        if (!$model)
            throw new \yii\web\NotFoundHttpException(sprintf('Ticket not found'));

        $this->checkAccess('checkin', $model);

        if ($model->receipt->status != 'Complete')
            throw new \yii\web\ForbiddenHttpException(sprintf('Ticket not payed for yet!'));

        if ($model->checkedIn != NULL)
            throw new \yii\web\ForbiddenHttpException(sprintf('Ticket already checked in!'));

        $model->checkedIn = \Yii::$app->params['id'];

        if (!$model->save())
            throw new \yii\web\ServerErrorHttpException(sprintf('There was an error while trying to checkin!'));

        try {
            $client = new MqttClient('127.0.0.1', 1883, "test-publisher");
            $connectionSettings = (new \PhpMqtt\Client\ConnectionSettings)->setUsername('android')->setPassword('a');
            $client->connect($connectionSettings);
            $client->publish($model->client_id, 'ticket', 1);
            $client->disconnect();
        } catch (Exception $ex) {
            throw new \yii\web\ServerErrorHttpException('There was an error while sending the message');
        }
        return $this->asJson(['name' => 'Success', 'message' => 'Ticket checkedin successfully', 'code' => 200, 'status' => 200]);
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if ($action == 'checkin' && \Yii::$app->params['role'] === 'client')
            throw new \yii\web\ForbiddenHttpException(sprintf('You cannot checkin'));
        if ($action !== 'create' && $action !== 'index' && $model->client_id != \Yii::$app->params['id'] && \Yii::$app->params['role'] === 'client')
            throw new \yii\web\ForbiddenHttpException(sprintf('You only can manage your tickets'));
    }
}
