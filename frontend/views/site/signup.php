<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \frontend\models\SignupForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Signup';
?>
<div class="gtco-section" id="signup-section">
    <div class="gtco-container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center gtco-heading">
                <h2>Signup</h2>
                <p>Please fill out the following fields to login:</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-6 text-left">
                <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="my-1 mx-0" style="color:#999;">
                    Already have an account? Login in <?= Html::a('here', ['site/login#login-section']) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

</div>
