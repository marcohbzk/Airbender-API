<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

$this->title = 'My Yii Application';
?>
<div class="gtco-section">
    <div class="gtco-container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-8 text-center gtco-heading">
                <h2>Trip To Your Favourite Destination</h2>
            </div>
        </div>
        <div class="row">
            <h1>Popular destinations</h1>
            <?php foreach ($airports as $airport) { ?>
                <div class="col-lg-4 col-md-4 col-sm-6">
                    <a href="../flight/select-airport?airportArrival_id=<?= $airport->id ?>" class="fh5co-card-item ">
                        <figure>
                            <div class="overlay"><i class="ti-plus"></i></div>
                            <?= Html::img(Yii::getAlias('@web') . '/images/img_2.jpg', ['class' => 'img-responsive', 'alt' => 'Image']); ?>
                        </figure>
                        <div class="fh5co-text">
                            <h3><?= $airport->city . ', ' . $airport->country ?></h3>
                            <p><span class="btn btn-primary">Schedule a Trip</span></p>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
        <div class="row">
            <h1>Upcoming Flights</h1>
            <?php foreach ($flights as $flight) { ?>
                <div class="col-4 col-md-4 col-sm-6 mb-5">
                    <h3><?= $flight->airportDeparture->city . ' -> ' . $flight->airportArrival->city ?></h3>
                    <p><?= date('d M y G.i', strtotime($flight->departureDate)) ?> - Est(<?= date('G.i', strtotime($flight->duration)) ?>h)</p>
                    <a class="btn btn-primary" href="../flight/select-flight?flight_id=<?= $flight->id ?>"><?= $flight->activeTariff()->economicPrice ?>€</a>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
