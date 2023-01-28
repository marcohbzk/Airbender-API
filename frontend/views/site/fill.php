<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Client $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="site-fill">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->hiddenInput(['value' => Yii::$app->user->identity->getId()])->label(false) ?>

    <?= $form->field($model, 'fName')->textInput() ?>

    <?= $form->field($model, 'surname')->textInput() ?>

    <?= $form->field($model, 'birthdate')->textInput() ?>

    <?= $form->field($model, 'nif')->textInput(['maxlength' => 9]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => 9]) ?>

    <?= $form->field($model, 'gender')->dropDownList([
        'M' => 'Male',
        'F' => 'Female'
    ]) ?>

    <?= $form->field($model, 'accCreationDate')->hiddenInput(['value' => date('Y-m-d H:i:s')])->label(false) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>