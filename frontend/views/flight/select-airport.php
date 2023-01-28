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

?>
<div class="flight-index">

    <div class="container">

          <div class="row">


              </div>

    </div>
    <div class="container mt-5 d-flex justify-content-center">
        <?php $form = ActiveForm::begin(['id' => 'form-selectAirport', 'action' => ['flight/select-airport', 'receipt_id' => $receipt_id]]); ?>

        <div class="row d-flex justify-content-center">
            <div class="col col-md-5 mx-auto">
                <?= $form->field($model, 'airportDeparture_id')->dropDownList($airports, ['prompt' => 'From'])->label('') ?>
            </div>
            <div class="col-1 mx-auto d-flex justify-content-center align-items-center">
                <i class="fa-solid fa-plane"></i>
            </div>
            <div class="col col-md-5 mx-auto">
                <?= $form->field($model, 'airportArrival_id')->dropDownList($airports, ['prompt' => 'To'])->label('') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-1 d-flex justify-content-center align-items-center">
                <i class="fa-solid fa-plane-departure"></i>
            </div>
            <div class="col">
                <?=
                $form->field($model, 'departureDate')->widget(
                    DatePicker::classname(),
                    [
                        'dateFormat' => 'php:Y-m-d',
                        'options' => ['class' => 'form-control'],
                        'inline' => false,
                        'clientOptions' => [
                            'changeMonth' => true,
                            'changeYear' => true,
                            'format' => 'yyyy-mm-dd',
                        ],


                    ]
                )

                ?>
            </div>
        </div>
        <div class="form-group row d-flex justify-content-center">
            <?= Html::submitButton('Search', ['class' => 'btn btn-primary mt-5 w-75']) ?>
        </div>

        <?php ActiveForm::end(); ?>


    </div>
</div>

<script>
    window.onload = function() {
        if (!$("#selectairport-airportarrival_id").val()) {
            $("#selectairport-airportarrival_id").attr('disabled', 'disabled');
        }

        $('#selectairport-airportdeparture_id').change(function() {
            departureid = $(this).val();
            $("#selectairport-airportarrival_id").find("option").show();
            $("#selectairport-airportarrival_id").find("optgroup").show();
            option = $("#selectairport-airportarrival_id").find("option[value^=" + departureid + "]");
            option.hide();
            $("#selectairport-airportarrival_id").removeAttr('disabled', 'disabled');
            if (option.parent().children("option[value!=" + departureid + "]").size() == 0)
                option.parent().hide();
        })
        $('#selectairport-airportarrival_id').change(function() {
            arrivalid = $(this).val();
            $("#selectairport-airportdeparture_id").find("option").show();
            $("#selectairport-airportdeparture_id").find("optgroup").show();
            option = $("#selectairport-airportdeparture_id").find("option[value^=" + arrivalid + "]");
            option.hide();
            $("#selectairport-airportdeparture_id").fadeIn();
            if (option.parent().children("option[value!=" + arrivalid + "]").size() == 0)
                option.parent().hide();
        })
    };
</script>