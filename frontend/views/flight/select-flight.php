<?php

use common\models\Flight;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Flights';
?>
<div class="flight-index mt-5">
    <?= Html::a('<i class="fa-solid fa-arrow-left"></i> Go back', ['flight/select-airport']) ?>
    <?php if (count($flights) == 0) { ?>
        <h1>There are no flights available from <?= $airportDeparture->city ?> to <?= $airportArrival->city ?></h1>
    <?php } else { ?>
        <h1>Select one flight!</h1>
        <div class="container px-4">
            <div class="row g-4">
                <div class="col-xs-12 col-md-3 col-lg-3">
                    <div class="shadow h-75" style="overflow-x: scroll; overflow-x: hidden;">
                        <div class="row">
                            <div class="col-8 d-flex justify-content-center">Departure Date</div>
                            <div class="col-4 ">Price</div>
                        </div>
                        <?php foreach ($flights as $flight) { ?>
                            <div class="row p-3" style="cursor: pointer;" id="select<?= $flight->id ?>" onclick="changeActive(<?= $flight->id . ', ' . $flight->activeTariff()->economicPrice . ', ' . $flight->activeTariff()->normalPrice . ', ' . $flight->activeTariff()->luxuryPrice ?>)">
                                <div class="col-8 d-flex justify-content-center">
                                    <?= $flight->departureDate ?>
                                </div>
                                <div class="col-4 ">
                                    <?= $flight->activeTariff('economic') ?>€
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                    <div class="shadow rounded bg-secondary text-white h-100">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-center"><span class="fs-1 text-center">ECONOMIC</span></div>
                            <div class="col-12 m-3">
                                <ul>
                                    <li class="fs-3">Choose Economic seat</li>
                                </ul>
                            </div>
                            <div class="col-12 m-3 d-flex justify-content-center">
                                <?= Html::a('€', ['/ticket/create', 'flight_id' => $flights[0]->id, 'tariffType' => 'economic', 'receipt_id' => $receipt_id], ['id' => 'economicPrice', 'class' => 'btn border w-75 fs-1 text-white']); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                    <div class="shadow rounded bg-info text-white h-100">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-center"><span class="fs-1 text-center">NORMAL</span></div>
                            <div class="col-12 m-3">
                                <ul>
                                    <li class="fs-3">Choose Normal seat</li>
                                    <li class="fs-3">One bag of 10kg included</li>
                                    <li class="fs-3">Refundable</li>
                                </ul>
                            </div>
                            <div class="col-12 m-3 d-flex justify-content-center">
                                <?= Html::a('€', ['/ticket/create', 'flight_id' => $flights[0]->id, 'tariffType' => 'normal', 'receipt_id' => $receipt_id], ['id' => 'normalPrice', 'class' => 'btn border w-75 fs-1 text-white']); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3 col-lg-3">
                    <div class="shadow rounded bg-warning text-white h-100">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-center"><span class="fs-1 text-center">LUXURY</span></div>
                            <div class="col-12 m-3">
                                <ul>
                                    <li class="fs-3">Choose Luxury seat</li>
                                    <li class="fs-3">One bag of 20kg included</li>
                                    <li class="fs-3">Refundable</li>
                                </ul>
                            </div>
                            <div class="col-12 m-3 d-flex justify-content-center">
                                <?= Html::a('€', ['/ticket/create', 'flight_id' => $flights[0]->id, 'tariffType' => 'luxury', 'receipt_id' => $receipt_id], ['id' => 'luxuryPrice', 'class' => 'btn border w-75 fs-1 text-white']); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        </div>


</div>
<script>
    oldSelect = null;


    function changeActive(flight_id, economicPrice, normalPrice, luxuryPrice) {
        $("#economicPrice").text(economicPrice + '€');
        $("#normalPrice").text(normalPrice + '€');
        $("#luxuryPrice").text(luxuryPrice + '€');
        if (oldSelect == flight_id) return;
        if (<?= !is_null($receipt_id) ?> + 0) {
            $("#economicPrice").attr('href', '../ticket/create?flight_id=' + flight_id + '&tariffType=economic&receipt_id=' + <?= $receipt_id ?> + '');
            $("#normalPrice").attr('href', '../ticket/create?flight_id=' + flight_id + '&tariffType=normal&receipt_id=' + <?= $receipt_id ?> + '');
            $("#luxuryPrice").attr('href', '../ticket/create?flight_id=' + flight_id + '&tariffType=luxury&receipt_id=' + <?= $receipt_id ?> + '');
        } else {
            $("#economicPrice").attr('href', '../ticket/create?flight_id=' + flight_id + '&tariffType=economic');
            $("#normalPrice").attr('href', '../ticket/create?flight_id=' + flight_id + '&tariffType=normal');
            $("#luxuryPrice").attr('href', '../ticket/create?flight_id=' + flight_id + '&tariffType=luxury');
        }
        $('#select' + flight_id).addClass('bg-info text-white');
        $('#select' + oldSelect).removeClass('bg-info text-white');
        oldSelect = flight_id;
    }
    window.onload = function() {
        if (<?= !is_null($closestFlight) ?>) {
            changeActive(<?= (!is_null($closestFlight) ? $closestFlight->id : ' ')  . ', ' . (!is_null($closestFlight) ? $closestFlight->activeTariff('economic') : '') . ', ' . (!is_null($closestFlight) ? $closestFlight->activeTariff('normal') : '') . ', ' . (!is_null($closestFlight) ? $closestFlight->activeTariff('luxury') : '')?>);
        }
    }
</script>
