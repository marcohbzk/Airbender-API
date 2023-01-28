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

    <?= $form->field($model, 'requestDate')->hiddenInput(['value'=> date('Y-m-d H:i:s')])->label(false); ?>

    <?= $form->field($model, 'client_id')->hiddenInput(['value'=> Yii::$app->user->identity->getId()])->label(false); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
