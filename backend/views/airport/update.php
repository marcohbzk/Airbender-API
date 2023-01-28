<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Airport $model */

$this->title = 'Update Airport: ' . $model->id;
?>
<div class="airport-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
