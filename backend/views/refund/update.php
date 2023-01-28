<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Refund $model */

$this->title = 'Update Refund: ' . $model->id;
?>
<div class="refund-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
