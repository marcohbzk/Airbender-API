<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\BalanceReq $model */

$this->title = 'Create Balance Req';
?>
<div class="balance-req-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
