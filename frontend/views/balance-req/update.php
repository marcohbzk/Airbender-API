<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\BalanceReq $model */

$this->title = 'Update Balance Req: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Balance Reqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="balance-req-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
