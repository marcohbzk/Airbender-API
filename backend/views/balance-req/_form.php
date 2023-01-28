<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\BalanceReq $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="balance-req-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'amount')->textInput() ?>

    <?= $form->field($model, 'status')->dropDownList([ 'Accepted' => 'Accepted', 'Declined' => 'Declined', 'Ongoing' => 'Ongoing', 'Canceled' => 'Canceled', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'requestDate')->textInput() ?>

    <?= $form->field($model, 'decisionDate')->textInput() ?>

    <?= $form->field($model, 'client_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
