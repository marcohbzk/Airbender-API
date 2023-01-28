<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Airplane $model */

$this->title = 'Update Airplane: ' . $model->id;
?>
<div class="airplane-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
