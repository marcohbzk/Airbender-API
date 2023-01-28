<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Config $model */

$this->title = 'Update Config: ' . $model->id;
?>
<div class="config-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
