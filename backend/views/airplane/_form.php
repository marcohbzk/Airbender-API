<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Airplane $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="airplane-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col"><?= $form->field($model, 'luggageCapacity')->textInput()->label('Luggage Capacity (Kg)') ?></div>
    </div>
    <div class="col"><?= $form->field($model, 'minLinha')->hiddenInput(['value' => 1])->label(false) ?></div>
    <div class="row">
        <div class="col"><?= $form->field($model, 'maxLinha')->textInput() ?></div>
    </div>
    <div class="col"><?= $form->field($model, 'minCol')->hiddenInput(['value' => 'A'])->label(false) ?></div>
    <div class="row">
        <div class="col"><?= $form->field($model, 'maxCol')->textInput() ?></div>
    </div>
    <div class="row">
        <div class="col"><?= $form->field($model, 'economicStart')->textInput() ?></div>
        <div class="col"><?= $form->field($model, 'economicStop')->textInput() ?></div>
    </div>
    <div class="row">
        <div class="col"><?= $form->field($model, 'normalStart')->textInput() ?></div>
        <div class="col"><?= $form->field($model, 'normalStop')->textInput() ?></div>
    </div>
    <div class="row">
        <div class="col"><?= $form->field($model, 'luxuryStart')->textInput() ?></div>
        <div class="col"><?= $form->field($model, 'luxuryStop')->textInput() ?></div>
    </div>
    <div class="row">
        <div class="col">
            <?= $form->field($model, 'status')->dropDownList([
                'active' => 'Active',
                'notWorking' => 'Not Working'
            ]) ?>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
