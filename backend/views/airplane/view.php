<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Flight $model */

$this->title = 'Airplane #' . $model->id . ' - Airbender';
\yii\web\YiiAsset::register($this);
?>
<div class="airplane-view">
    <div class="row m-4">
        <div class="col">
            <div class="row">
                <div class="col">
                    <div class="row h1">Airplane info</div>
                    <div class="row">
                        <?= DetailView::widget([
                            'model' => $model,
                            'attributes' => [
                                'id',
                                'luggageCapacity',
                                [
                                    'label' => 'Seats',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model->minLinha . $model->minCol . '-' . $model->maxLinha . $model->maxCol;
                                    }
                                ],
                                [
                                    'label' => 'Economic Seats',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model->economicStart . '-' . $model->economicStop;
                                    }
                                ],
                                [
                                    'label' => 'Normal Seats',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model->normalStart . '-' . $model->normalStop;
                                    }
                                ],
                                [
                                    'label' => 'Luxury Seats',
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        return $model->luxuryStart . '-' . $model->luxuryStop;
                                    }
                                ],
                                [
                                    'label' => 'Total Seats',
                                    'value' => function ($model) {
                                        return $model->countTotalSeats();
                                    }
                                ],
                                'status',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="row d-flex justify-content-center h1">SEATS</div>
            <?php foreach ($model->getSeats() as $col => $linha) { ?>
                <div class="row mb-3" style="margin-left: 6%; margin-right: 6%;">
                    <div class="col"><?= $col ?></div>
                    <?php foreach ($linha as $key => $l) {
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
