<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Flight $model */

$this->title = 'Flight #' . $model->id . ' - Airbender';
\yii\web\YiiAsset::register($this);
?>
<div class="flight-view">
    <div class="row m-4">
        <div class="col">
            <div class="row">
                <div class="col">
                    <div class="row h1">Flight info</div>
                    <div class="row">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                [
                                    'label' => 'Airplane',
                                    'format' => 'raw',
                                    'value' => Html::a($model->airplane_id, ['airplane/view', 'id' => $model->airplane_id]),
                                ],
                                [
                                    'label' => 'Departure Airport',
                                    'format' => 'raw',
                                    'value' => Html::a($model->airportDeparture->country . ' - ' . $model->airportDeparture->city, ['airport/view', 'id' => $model->airportDeparture_id]),
                                ],
                                [
                                    'label' => 'Arrival Airport',
                                    'format' => 'raw',
                                    'value' => Html::a($model->airportArrival->country . ' - ' . $model->airportArrival->city, ['airport/view', 'id' => $model->airportArrival_id]),
                                ],
                                [
                                    'label' => 'Date',
                                    'value' => function ($model) {
                                        return $model->departureDate;
                                    }
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col">
                    <div class="row">
                        <div class="col h1">Tickets</div>
                        <div class="col d-flex justify-content-end"><?= Html::a('View all', ['ticket/index', 'flight_id' => $model->id]) ?></div>
                    </div>
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            [
                                'label' => 'Total Seats',
                                'value' => function ($model) {
                                    return $model->airplane->countTotalSeats();
                                }
                            ],
                            [
                                'label' => 'Tickets Bought',
                                'value' => function ($model) {
                                    return $model->countBoughtTickets();
                                }
                            ],
                            [
                                'label' => 'Total Available',
                                'value' => function ($model) {
                                    return $model->airplane->countTotalSeats() - $model->countBoughtTickets();
                                }
                            ],
                            [
                                'label' => 'Total Luggage',
                                'value' => function ($model) {
                                    return $model->airplane->luggageCapacity . 'kg';
                                }
                            ],
                            [
                                'label' => 'Luggage Bought',
                                'value' => function ($model) {
                                    return $model->countBoughtLuggage() . 'kg';
                                }
                            ],
                            [
                                'label' => 'Luggage Available',
                                'value' => function ($model) {
                                    return $model->getAvailableLuggage() . 'kg';
                                }
                            ],
                        ],
                    ]) ?>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col">
                    <div class="row">
                        <div class="col h1">Current Price</div>
                        <div class="col d-flex justify-content-end"><?= Html::a('View history', ['tariff/index', 'flight_id' => $model->id]) ?></div>
                    </div>
                    <div class="row">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                [
                                    'label' => 'Economic Price',
                                    'format' => 'raw',
                                    'value' => $model->activeTariff()->economicPrice . '€',
                                ],
                                [
                                    'label' => 'Normal Price',
                                    'format' => 'raw',
                                    'value' => $model->activeTariff()->normalPrice . '€',
                                ],
                                [
                                    'label' => 'Luxury Price',
                                    'format' => 'raw',
                                    'value' => $model->activeTariff()->luxuryPrice . '€',
                                ],
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>

        </div>
        <div class="col-4">
            <div class="row d-flex justify-content-center h1">SEATS</div>
            <?php foreach ($model->getAvailableSeats() as $col => $linha) { ?>
                <div class="row mb-3" style="margin-left: 6%; margin-right: 6%;">
                    <div class="col"><?= $col ?></div>
                    <?php foreach ($linha as $key => $l) {
                        if ($l['status']) {
                            switch ($l['type']) {
                                case 'economic':
                                    $extraClasses = "bg-secondary";
                                    break;
                                case 'normal':
                                    $extraClasses = "bg-info";
                                    break;
                                case 'luxury':
                                    $extraClasses = "bg-warning";
                                    break;
                            }
                        } else {
                            $extraClasses = "bg-danger opacity-50";
                        }
                    ?>
                        <div id="<?= $col . '-' . $key ?>" class="col text-white rounded m-1 text-center <?= $extraClasses ?>"><?= $key ?></div>
                    <?php } ?>
                </div>
            <?php } ?>

            <div class="row text-center m-3">
                <div class="col text-white rounded bg-secondary m-3">Economic</div>
                <div class="col text-white rounded bg-info m-3">Normal</div>
                <div class="col text-white rounded bg-warning m-3">Luxury</div>
                <div class="col text-white rounded bg-danger m-3">Taken</div>
            </div>

        </div>
    </div>
</div>
