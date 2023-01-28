<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Ticket $model */

$this->title = 'Ticket #' . $model->id . ' - Airbender';
\yii\web\YiiAsset::register($this);
?>
<div class="ticket-view">
    <div class="row mt-5">
        <div class="col m-3 shadow">
            <div class="row h1 m-5">
                <div class="col-6">Ticket #<?= $model->id ?></div>
            </div>
            <div class="row border-top" style="margin-left: 3%; margin-right: 3%;">
                <div class="col-10 fw-light mt-3 mb-3" style="margin-left: 3%;">
                    <div class="row fs-3"><?= $model->fName . ' ' . $model->surname ?></div>
                    <div class="row fs-5">
                        <div class="col">Duration: <?= date('g\hi', strtotime($model->flight->duration)) ?></div>
                        <div class="col">Date: <?= date('d/m/y', strtotime($model->flight->departureDate)) ?></div>
                        <div class="col"><?= $model->flight->airportDeparture->city . '->' . $model->flight->airportArrival->city ?> </div>
                        <div class="col">Seat: <?= $model->seatLinha . '-' . $model->seatCol ?></div>
                        <div class="col">Type: <?= $model->tariffType ?></div>
                        <div class="col">Status: <?= $model->checkedIn == null ? 'Waiting checkin' : 'Checked in' ?></div>
                    </div>
                </div>
                <div class="col d-flex align-items-center justify-content-center">
                    <div class="col">
                        <?= $model->getTicketPrice(); ?>€
                    </div>
                </div>
            </div>
            <div class="row border-top mb-5" style="margin-left: 3%; margin-right: 3%;">
                <h1>Luggage</h1>
                <div class="col-10 fw-light mt-3 mb-3" style="margin-left: 3%;">
                    <div class="row fs-2">
                        <div class="col"><?php if (!is_null($model->luggageOne)) { ?><div class="col">Luggage 1: <?= $model->luggageOne->description ?></div> <?php } ?></div>
                        <div class="col"><?php if (!is_null($model->luggageTwo)) { ?><div class="col">Luggage 2: <?= $model->luggageTwo->description ?></div> <?php } ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
