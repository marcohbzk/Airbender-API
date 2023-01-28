<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Receipt $model */

$this->title = 'Update Receipt: ' . $model->id;
?>
<div class="receipt-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
