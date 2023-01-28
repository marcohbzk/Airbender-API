<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Airplane $model */

$this->title = 'Create Airplane';
?>
<div class="airplane-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
