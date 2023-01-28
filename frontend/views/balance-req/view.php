<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\BalanceReq $model */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Balance Reqs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="balance-req-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
        <a href="index">Voltar atrás</a>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'amount',
            'status',
            'requestDate',
            [
                'label' =>'Decision Date',
                'value' => function ($model) {
                    return isset($model->decisionDate) ? $model->decisionDate : "To be decided";
                }
            ],
        ],
    ]) ?>

</div>