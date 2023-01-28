<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Refund $model */

$this->title = 'Create Refund';
?>
<div class="refund-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
