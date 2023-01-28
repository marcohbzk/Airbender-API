<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\BalanceReq $model */

$this->title = 'Update Balance Req: ' . $model->id;
?>
<div class="balance-req-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
