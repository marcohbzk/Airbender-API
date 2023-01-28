<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\BalanceReq $model */

$this->title = 'Create Balance Req';
$this->params['breadcrumbs'][] = ['label' => 'Balance Reqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="balance-req-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
