<?php

use common\models\Flight;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Flights';
?>
<div class="flight-index">

    <?php if (Yii::$app->session->hasFlash('success')) : ?>
        <div class="alert alert-success alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-check"></i>Sucess!</h4>
            <?= Yii::$app->session->getFlash('success') ?>
        </div>
    <?php endif; ?>

    <?php if (Yii::$app->session->hasFlash('error')) : ?>
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <h4><i class="icon fa fa-minus"></i>Error!</h4>
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

    <div class="d-flex m-2 justify-content-end">
        <?= Html::a('+ Create Flight', ['create'], ['class' => 'btn btn-dark']) ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'airplane.id',
            [
                'label' => 'Departure',
                'value' => function ($model) {
                    return $model->airportDeparture->city . ' - ' . '(' . $model->airportDeparture->country . ')';
                }
            ],
            [
                'label' => 'Destination',
                'value' => function ($model) {
                    return $model->airportArrival->city . ' - ' . '(' . $model->airportArrival->country . ')';
                }
            ],
            'departureDate',
            'duration',
            [
                'label' => 'Prices',
                'format' => 'raw',
                'value' => function ($model) {
                    if (is_null($model->activeTariff())) {
                        return 'No active tariffs found!';
                    }
                    return
                        '<h5><span class="badge badge-info">Economic - ' . $model->activeTariff()->economicPrice . '€</span></h5>' .
                        '<h5><span class="badge badge-success">Normal - ' . $model->activeTariff()->normalPrice . '€</span></h5>' .
                        '<h5><span class="badge badge-primary">Luxury - ' . $model->activeTariff()->luxuryPrice . '€</span></h5>';
                }
            ],
            'status',
            [
                'class' => ActionColumn::class,
                'template' => '{history} {view} {update} {delete}',
                'buttons' => [
                    'history' => function ($url, $model) {
                        return Html::a('<i class="fas fa-dollar-sign"></i>', $url, [
                            'title' => Yii::t('app', 'Price History'),
                            'class' => 'btn btn-sm btn-primary',
                        ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<i class="fas fa-eye"></i>', $url, [
                            'title' => Yii::t('app', 'View'),
                            'class' => 'btn btn-sm btn-primary',
                        ]);
                    },
                    'update' => function ($url, $model) {
                        return Html::a('<i class="fas fa-edit"></i>', $url, [
                            'title' => Yii::t('app', 'Update'),
                            'class' => 'btn btn-sm btn-primary',
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        if ($model->status != "Canceled") {
                            return Html::a('<i class="fas fa-trash"></i>', $url, [
                                'title' => Yii::t('app', 'Delete'),
                                'class' => 'btn btn-sm btn-danger',
                                'data' => [
                                    'confirm' => Yii::t('app', 'Are you sure you want to cancel this flight?'),
                                    'method' => 'post',
                                ],
                            ]);
                        }
                    },
                ],
                'urlCreator' => function ($action, $model, $key, $index) {
                    if ($action === 'history') {
                        return Url::to(['tariff/index', 'flight_id' => $model->id]);
                    }
                    if ($action === 'view') {
                        return Url::to(['view', 'id' => $model->id]);
                    }
                    if ($action === 'update') {
                        return Url::to(['update', 'id' => $model->id]);
                    }
                    if ($action === 'delete') {
                        return Url::to(['delete', 'id' => $model->id]);
                    }
                }
            ],
        ],
    ]); ?>


</div>
