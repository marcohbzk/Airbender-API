<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Receipt $model */

$this->title = 'Create Receipt';
?>
<div class="receipt-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
