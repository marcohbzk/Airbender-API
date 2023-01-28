<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Flight $model */

$this->title = 'Update Flight: ' . $model->id;
?>
<div class="flight-update">

    <?= $this->render('_form', [
        'model' => $model,
        'airports' => $airports,
        'airplanes' => $airplanes,
    ]) ?>

</div>
