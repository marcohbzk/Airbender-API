<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\DatePicker;

/** @var yii\web\View $this */
/** @var backend\models\Employee $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="employee-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col"><?= $form->field($model, 'fName')->textInput()->label('First Name') ?></div>
        <div class="col"><?= $form->field($model, 'surname')->textInput() ?></div>
        <div class="col"><?= $form->field($model, 'email')->textInput() ?></div>
    </div>
    <div class="row">
        <div class="col">
            <?= $form->field($model, 'gender')->dropDownList([
                'M' => 'Male',
                'F' => 'Female'
            ]) ?>
        </div>
        <div class="col">
            <?=
            $form->field($model, 'birthdate')->widget(DatePicker::classname(), [
                'language' => 'en',
                'dateFormat' => 'dd-MM-yyyy',
                'inline' => false,
                'clientOptions' => [
                    'changeMonth' => true,
                    'changeYear' => true,
                    'yearRange' => '1930:' . date('d-m-Y'),
                ],
            ])->textInput() ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'phone')->textInput(['maxlength' => 9]) ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'nif')->textInput(['maxlength' => 9]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col"><?= $form->field($model, 'salary')->textInput() ?></div>
        <div class="col">
            <?= $form->field($model, 'role')->dropDownList($roles)->label('Roles') ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'airport_id')->dropDownList($airports)->label('Airport') ?>
        </div>
    </div>

    <div class="row">
        <div class="col"><?= $form->field($model, 'username')->textInput() ?></div>
        <div class="col"><?= $form->field($model, 'password')->passwordInput() ?></div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
