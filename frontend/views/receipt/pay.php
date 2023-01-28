<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use kartik\date\DatePicker;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

?>
<div class="receipt-pay">
    <div class="row mt-5">
        <div class="col-8 m-3 shadow">
            <div class="row h1 m-5">
                <div class="col-6">Receipt #<?= $receipt->id ?></div>
                <div class="col-6 text-end">Total: <?= $receipt->total ?>€</div>
            </div>
            <?php foreach ($receipt->tickets as $ticket) { ?>
                <div class="row border-top" style="margin-left: 3%; margin-right: 3%;">
                    <div class="col-9 fw-light mt-3 mb-3" style="margin-left: 3%;">
                        <div class="row fs-3"><?= $ticket->fName . ' ' . $ticket->surname ?></div>
                        <div class="row fs-5">
                            <div class="col">Duration: <?= date('g\hi', strtotime($ticket->flight->duration)) ?></div>
                            <div class="col">Date: <?= date('d/m/y', strtotime($ticket->flight->departureDate)) ?></div>
                            <div class="col"><?= $ticket->flight->airportDeparture->city . '->' . $ticket->flight->airportArrival->city ?> </div>
                            <div class="col">Seat: <?= $ticket->seatLinha . '-' . $ticket->seatCol ?></div>
                            <div class="col">Type: <?= $ticket->tariffType ?></div>
                        </div>
                    </div>

                    <div class="col d-flex align-items-center justify-content-center">
                        <div class="row"><?= $ticket->getTicketPrice() ?>€</div>
                    </div>
                    <div class="col d-flex align-items-center justify-content-center">
                        <div class="row"><a class="btn btn-sm btn-danger" href="delete-ticket?id=<?= $ticket->id ?>" title="Delete" data-confirm="Are you sure you want to delete this ticket? This might delete the current receipt" data-method="post"><i class="fas fa-trash" aria-hidden="true"></i></a></div>
                    </div>
                </div>
            <?php } ?>
            <div class="row m-5">
                <?= Html::a('Add +', ['flight/select-airport', 'receipt_id' => $receipt->id], ['class' => 'btn btn-primary d-flex justify-content-center']) ?>
            </div>
        </div>
        <div class="col m-3 shadow">
            <div class="row h4 m-5">
                Payment details
            </div>
            <?php foreach ($receipt->tickets as $ticket) { ?>
                <div class="row border-top" style="margin-left: 3%; margin-right: 3%;">
                    <div class="col-9 fw-light" style="margin-left: 3%;">
                        <div class="row fs-5"><?= $ticket->fName . ' ' . $ticket->surname ?></div>
                    </div>
                    <div class="col d-flex justify-content-center">
                        <div class="row fs-5"><?= $ticket->getTicketPrice() ?>€</div>
                    </div>
                </div>
            <?php } ?>
            <div class="row" style="margin-left: 3%; margin-right: 3%;">
                <div class="col-9 d-flex justify-content-end" style="padding-right: 9%;">
                    <div class="row fs-4">TOTAL</div>
                </div>
                <div class="col border-top d-flex justify-content-center">
                    <div class="row fs-4"><?= $receipt->total ?>€</div>
                </div>
            </div>
            <?php if ($client->application) { ?>
            <div class="row" style="margin-left: 3%; margin-right: 3%;">
                <div class="col-9 d-flex justify-content-end" style="padding-right: 9%;">
                    <div class="row fs-4 text-info">TOTAL after app discount (5%)</div>
                </div>
                <div class="col border-top d-flex justify-content-center">
                    <div class="row fs-4 text-info"><?= $client->application ? $receipt->total - $receipt->total * 0.05 : $receipt->total ?>€</div>
                </div>
            </div>
            <?php } ?>
            <div class="row" style="margin-left: 3%; margin-right: 3%;">
                <div class="col-9 d-flex justify-content-end" style="padding-right: 9%;">
                    <div class="row fs-4">Current Balance</div>
                </div>
                <div class="col d-flex justify-content-center">
                    <div class="row fs-4"><?= $client->balance ?>€</div>
                </div>
            </div>
            <div class="row" style="margin-left: 3%; margin-right: 3%;">
                <div class="col-9 d-flex justify-content-end" style="padding-right: 9%;">
                    <div class="row fs-4">Balance After</div>
                </div>
                <div class="col d-flex justify-content-center">
                    <?php if ($client->balance - $receipt->total < 0) { ?>
                        <div class="row fs-4 text-danger"><?= $client->balance - ($client->application ? $receipt->total - $receipt->total * 0.05 : $receipt->total) ?>€</div>
                    <?php } else { ?>
                        <div class="row fs-4 text-success"><?= $client->balance - ($client->application ? $receipt->total - $receipt->total * 0.05 : $receipt->total) ?>€</div>
                    <?php } ?>
                </div>
            </div>
            <div class="row mt-5" style="margin-left: 6%; margin-right: 6%;">
                <?= Html::a('Ask for balance', ['receipt/ask', 'id' => $receipt->id], ['class' => 'btn btn-info d-flex justify-content-center']) ?>
            </div>
            <div class="row mb-5" style="margin-left: 6%; margin-right: 6%;">
                <?= Html::a('Pay', ['receipt/pay', 'id' => $receipt->id], ['data-method' => 'post', 'class' => 'btn btn-primary d-flex justify-content-center']) ?>
            </div>
        </div>
    </div>
</div>
