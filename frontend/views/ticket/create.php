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
<div class="flight-index">
    <div class="container mt-5">
        <?php $form = ActiveForm::begin(['id' => 'form-buyTicket']); ?>
        <div class="row shadow mb-4">
            <div class="col-8 p-5">
                <div class="row">
                    <div class="h1 col">Ticket</div>
                    <div class="col"><?= $form->field($ticket, 'useAccount')->checkbox(['label' => 'Use account information']); ?></div>
                </div>
                <div class="row">
                    <div class="col"><?= $form->field($ticket, 'fName'); ?></div>
                    <div class="col"><?= $form->field($ticket, 'surname'); ?></div>
                </div>
                <div class="row">
                    <div class="col"><?= $form->field($ticket, 'gender')->dropDownList(['M' => 'Male', 'F' => 'Female']); ?></div>
                    <div class="col"><?= $form->field($ticket, 'age'); ?></div>
                </div>
                <div class="row mb-5">
                    <?php if ($tariffType != "economic") { ?>
                        <?= $form->field($ticket, 'luggage_1')->hiddenInput(['value' => $tariffType == "normal" ? "2" : "1"]) ?>
                        <div class="col shadow rounded btn opacity-50" style="margin-left: 3%; margin-right: 3%;">
                            <div class="row d-flex justify-content-center">None</div>
                            <div class="row d-flex justify-content-center h1"><i class="fa-solid fa-ban"></i></div>
                            <div class="row d-flex justify-content-center h3">Free</div>
                        </div>
                        <?php foreach ($config as $c) { ?>
                            <div class="col shadow rounded btn <?= $tariffType == "normal" && $c->weight == 10 ? "border border-primary opacity-50" : ($tariffType == "luxury" && $c->weight == 20 ? "border border-primary opacity-50" : "opacity-50") ?>" style="margin-left: 3%; margin-right: 3%;">
                                <div class="row d-flex justify-content-center"><?= $c->weight . 'KG' ?></div>
                                <div class="row d-flex justify-content-center h1"><i class="fa-solid fa-suitcase-rolling"></i></div>
                                <div class="row d-flex justify-content-center h3">Free</div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <?= $form->field($ticket, 'luggage_1')->hiddenInput() ?>
                        <div class="col shadow rounded btn border-primary" onclick="switchConfig(0, 1)" id="config0_luggage1" role="button" style="margin-left: 3%; margin-right: 3%;">
                            <div class="row d-flex justify-content-center">None</div>
                            <div class="row d-flex justify-content-center h1"><i class="fa-solid fa-ban"></i></div>
                            <div class="row d-flex justify-content-center h3">Free</div>
                        </div>
                        <?php foreach ($config as $c) { ?>
                            <div class="col shadow rounded btn opacity-50" role="button" onclick="switchConfig(<?= $c->id ?>, 1)" id="config<?= $c->id ?>_luggage1" style="margin-left: 3%; margin-right: 3%;">
                                <div class="row d-flex justify-content-center"><?= $c->weight . 'KG' ?></div>
                                <div class="row d-flex justify-content-center h1"><i class="fa-solid fa-suitcase-rolling"></i></div>
                                <div class="row d-flex justify-content-center h3"><?= $c->price . '€' ?></div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
                <div class="row">
                    <?= $form->field($ticket, 'luggage_2')->hiddenInput() ?>
                    <div class="col shadow rounded btn border-primary" onclick="switchConfig(0, 2)" id="config0_luggage2" role="button" style="margin-left: 3%; margin-right: 3%;">
                        <div class="row d-flex justify-content-center">None</div>
                        <div class="row d-flex justify-content-center h1"><i class="fa-solid fa-ban"></i></div>
                        <div class="row d-flex justify-content-center h3">Free</div>
                    </div>
                    <?php foreach ($config as $c) { ?>
                        <div class="col shadow rounded btn opacity-50" role="button" onclick="switchConfig(<?= $c->id ?>, 2)" id="config<?= $c->id ?>_luggage2" style="margin-left: 3%; margin-right: 3%;">
                            <div class="row d-flex justify-content-center"><?= $c->weight . 'KG' ?></div>
                            <div class="row d-flex justify-content-center h1"><i class="fa-solid fa-suitcase-rolling"></i></div>
                            <div class="row d-flex justify-content-center h3"><?= $c->price . '€' ?></div>
                        </div>
                    <?php } ?>
                </div>
                <?= $form->field($ticket, 'client_id')->hiddenInput(['value' => Yii::$app->user->identity->getId()])->label('') ?>
            </div>
            <div class="col mt-5">
                <span class="h2 d-flex justify-content-center">Choose a seat!</span>
                <?php foreach ($flight->getAvailableSeats() as $col => $linha) { ?>
                    <div class="row mb-3" style="margin-left: 6%; margin-right: 6%;">
                        <div class="col"><?= $col ?></div>
                        <?php foreach ($linha as $key => $l) {
                            if ($l['status']) {
                                switch ($l['type']) {
                                    case 'economic':
                                        $extraClasses = "bg-secondary";
                                        $active = true;
                                        break;
                                    case 'normal':
                                        $extraClasses = $tariffType == "economic" ? "bg-info opacity-50" : "bg-info";
                                        $active = $tariffType != "economic";
                                        break;
                                    case 'luxury':
                                        $extraClasses = $tariffType != "luxury" ? "bg-warning opacity-50" : "bg-warning";
                                        $active = $tariffType == "luxury";
                                        break;
                                    default:
                                        $active = 0;
                                }
                            } else {
                                $extraClasses = "bg-danger opacity-50";
                                $active = 0;
                            }
                        ?>
                            <div id="<?= $col . '-' . $key ?>" class="col text-white rounded m-1 text-center <?= $extraClasses ?>" <?php if ($active) echo 'role="button"' ?> onclick="chooseSeat(<?= "'" . $col . "', " . $key . ', ' . $active ?>)"><?= $key ?></div>
                        <?php } ?>
                    </div>
                <?php } ?>
                <div class="row text-center m-3">
                    <div class="col text-white rounded bg-secondary m-3">Economic</div>
                    <div class="col text-white rounded bg-info m-3">Normal</div>
                    <div class="col text-white rounded bg-warning m-3">Luxury</div>
                    <div class="col text-white rounded bg-danger m-3">Taken</div>
                </div>
                <div class="row text-center m-3">
                    <div class="col"><?= $form->field($ticket, 'seatCol')->textInput(['readonly' => true]) ?></div>
                    <div class="col"><?= $form->field($ticket, 'seatLinha')->textInput(['readonly' => true]) ?></div>
                </div>
            </div>
        </div>
        <div class="form-group row d-flex justify-content-center">
            <?= Html::submitButton('Next', ['class' => 'btn btn-primary mt-5 w-75']) ?>
        </div>

        <?php ActiveForm::end(); ?>


    </div>
</div>

<script>
    currentConfig = [0, 0];
    currentSeat = [null, null];


    window.onload = function() {
        if ($('#ticketbuilder-luggage_1').val())
            switchConfig($('#ticketbuilder-luggage_1').val(), 1);
        if ($('#ticketbuilder-luggage_2').val())
            switchConfig($('#ticketbuilder-luggage_2').val(), 2);
    }


    function switchConfig(config, luggage) {
        if (currentConfig[luggage - 1] != config) {
            $('#config' + config + '_luggage' + luggage).addClass('border-primary');
            $('#config' + config + '_luggage' + luggage).removeClass('opacity-50');
            $('#config' + currentConfig[luggage - 1] + '_luggage' + luggage).addClass('opacity-50');
            $('#config' + currentConfig[luggage - 1] + '_luggage' + luggage).removeClass('border-primary');
            if (config == 0)
                $('#ticketbuilder-luggage_' + luggage).removeAttr("value");
            else
                $('#ticketbuilder-luggage_' + luggage).val("" + config);


            currentConfig[luggage - 1] = config;
        }
    }

    function chooseSeat(col, linha, active = 0) {
        if ((currentSeat[0] != col || currentSeat[1] != linha) && active == 1) {
            $('#' + col + '-' + linha).addClass('border border-primary border-4');
            $('#' + currentSeat[0] + '-' + currentSeat[1]).removeClass('border border-primary border-4');
            $('#ticketbuilder-seatcol').val("" + linha);
            $('#ticketbuilder-seatlinha').val("" + col);

            currentSeat[0] = col;
            currentSeat[1] = linha;
        }
    }
    const checkbox = document.getElementById('ticketbuilder-useaccount')

    checkbox.addEventListener('change', (event) => {
        if (event.currentTarget.checked) {
            $('#ticketbuilder-fname').attr('disabled', 'disabled');
            $('#ticketbuilder-surname').attr('disabled', 'disabled');
            $('#ticketbuilder-age').attr('disabled', 'disabled');
            $('#ticketbuilder-gender').attr('disabled', 'disabled');
        } else {
            $('#ticketbuilder-fname').removeAttr('disabled', 'disabled');
            $('#ticketbuilder-surname').removeAttr('disabled', 'disabled');
            $('#ticketbuilder-age').removeAttr('disabled', 'disabled');
            $('#ticketbuilder-gender').removeAttr('disabled', 'disabled');
        }
    })
</script>
