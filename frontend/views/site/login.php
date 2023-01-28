<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

$this->title = 'Login';
?>
<div class="gtco-section" id="login-section">
    <div class="gtco-container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center gtco-heading">
                <h2>Login</h2>
                <p>Please fill out the following fields to login:</p>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-5 text-left">
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>


                <div class="my-1 mx-0" style="color:#999;">
                    Don't have an account? Sign up <?= Html::a('here', ['site/signup#signup-section']) ?>
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

</div>
