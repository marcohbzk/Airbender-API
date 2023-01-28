<?php

use common\models\Ticket;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Tickets';
?>
<div class="ticket-history mt-5">

    <?= Html::a('View checked in', ['history'], ['class' => 'btn btn-primary']) ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'fName',
            'surname',
            'gender',
            'age',
            'checkedIn',
            //'client_id',
            [
                'label' => 'Flight',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->flight->airportDeparture->city . ' - ' . $model->flight->airportArrival->city;
                }
            ],
            [
                'label' => 'Date',
                'value' => function ($model) {
                    return $model->flight->departureDate;
                }
            ],
            //'seatLinha',
            //'seatCol',
            //'luggage_1',
            //'luggage_2',
            //'receipt_id',
            [
                'class' => ActionColumn::class,
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fas fa-eye"></i>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class' => 'btn btn-sm btn-primary',
                        ]);
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'view')
                        return Url::to(['view', 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
