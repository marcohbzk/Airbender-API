<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Flight $model */

$this->title = 'Airport #' . $model->id . ' - Airbender';
\yii\web\YiiAsset::register($this);
?>
<div class="airport-view">
    <div class="row m-4">
        <div class="col">
            <div class="row h1">Airport info</div>
            <div class="row">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        'id',
                        'country',
                        'code',
                        'city',
                        'search',
                        'status',
                    ],
                ]) ?>
            </div>
        </div>
    </div>
    <div class="row m-4">
        <div class="col">
            <div class="row h1">Airport Statistics</div>
            <div class="row">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'label' => Html::a('Number of flights', ['flight/index', 'airport_id' => $model->id]),
                            'value' => function ($model) {
                                return count($model->flightsDeparture) + count($model->flightsArrival);
                            }
                        ],
                    ],
                ]) ?>
            </div>
        </div>
    </div>
</div>
