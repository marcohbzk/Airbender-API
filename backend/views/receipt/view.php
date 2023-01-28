<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Receipt $model */

$this->title = 'Receipt #' .$receipt->id . 'Airbender';
\yii\web\YiiAsset::register($this);
?>
<div class="receipt-pay">
    <div class="row mt-5">
        <div class="col m-3 shadow">
            <div class="row h1 m-5">
                <div class="col-6">Receipt #<?= $receipt->id ?></div>
                <div class="col-6 h4 d-flex justify-content-center">Emission Date: <?= $receipt->purchaseDate ?></div>
            </div>
            <div class="row m-5">
                <div class="col-6 m-3">
                    <div class="h1 row">Client</div>
                    <div class="row"><?= $receipt->client->user->userData->fName ?> <?= $receipt->client->user->userData->surname ?></div>
                    <div class="row"><?= $receipt->client->user->email ?></div>
                    <div class="row"><?= $receipt->client->user->userData->phone ?></div>
                    <div class="row"><?= $receipt->client->user->userData->nif ?></div>
                </div>
            </div>
            <div class="row m-5 h1">Tickets</div>
            <?php foreach ($receipt->tickets as $ticket) { ?>
                <div class="row border-top" style="margin-left: 3%; margin-right: 3%;">
                    <div class="col-10 fw-light mt-3 mb-3" style="margin-left: 3%;">
                        <div class="row fs-3"><?= $ticket->fName . ' ' . $ticket->surname ?></div>
                        <div class="row fs-5">
                            <div class="col">Duration: <?= date('g\hi', strtotime($ticket->flight->duration)) ?></div>
                            <div class="col">Date: <?= date('d/m/y', strtotime($ticket->flight->departureDate)) ?></div>
                            <div class="col"><?= $ticket->flight->airportDeparture->city . '->' . $ticket->flight->airportArrival->city ?> </div>
                            <div class="col">Seat: <?= $ticket->seatLinha . '-' . $ticket->seatCol ?></div>
                            <div class="col">Type: <?= $ticket->tariffType ?></div>
                            <div class="col">Status: <?= $ticket->checkedIn == null ? 'Waiting checkin' : 'Checked in' ?></div>
                            <?php if (!is_null($ticket->luggageOne)) { ?><div class="col">Luggage 1: <?= $ticket->luggageOne->description ?></div> <?php } ?>
                            <?php if (!is_null($ticket->luggageTwo)) { ?><div class="col">Luggage 2: <?= $ticket->luggageTwo->description ?></div> <?php } ?>
                        </div>
                    </div>
                    <div class="col d-flex align-items-center justify-content-center">
                        <div class="col">
                            <?= $ticket->getTicketPrice(); ?>€
                        </div>
                    </div>
                    <div class="col d-flex align-items-center justify-content-center">
                        <div class="col">
                            <?= Html::a('<i class="fas fa-eye" aria-hidden="true"></i>', ['ticket/view', 'id' => $ticket->id], ['class' => 'btn btn-sm p-3 btn-primary']) ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <div class="row d-flex mb-5 mt-5 justify-content-center">
                <div class="col-11 border-top">
                    <?php if ($receipt->client->application) { ?>
                        <div class="row" style="margin-left: 3%; margin-right: 3%;">
                            <div class="col-9 d-flex justify-content-end" style="padding-right: 9%;">
                                <div class="row h1 text-info">TOTAL after app discount (5%)</div>
                            </div>
                            <div class="col border-top d-flex justify-content-center">
                                <div class="row h1 text-info"><?= $client->application ? $receipt->total - $receipt->total * 0.05 : $receipt->total ?>€</div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="row" style="margin-left: 3%; margin-right: 3%;">
                            <div class="col-9 d-flex justify-content-end" style="padding-right: 9%;">
                                <div class="row h1">TOTAL</div>
                            </div>
                            <div class="col border-top d-flex justify-content-center">
                                <div class="row h1"><?= $receipt->total ?>€</div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
